<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TournamentController extends Controller
{
    /**
     * Get a paginated list of tournaments based on search and filters.
     */
    public function tournamentsData(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $type = $request->input('type');
        $date = $request->input('date');
        $sortBy = $request->input('sortBy', 'created_at');
        $sortDir = $request->input('sortDir', 'desc');

        $tournaments = Tournament::query()
            ->when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('season', 'like', "%{$search}%");
            })
            ->when($status, fn($q, $status) => $q->where('status', $status))
            ->when($type, function ($query, $type) {
                $typeRanges = [
                    'knockout' => ['operator' => '<=', 'value' => 16],
                    'group_knockout' => ['operator' => 'between', 'value' => [17, 32]],
                    'league' => ['operator' => '>', 'value' => 32],
                ];

                if (isset($typeRanges[$type])) {
                    $condition = $typeRanges[$type];
                    if ($condition['operator'] === 'between') {
                        $query->whereBetween('max_teams', $condition['value']);
                    } else {
                        $query->where('max_teams', $condition['operator'], $condition['value']);
                    }
                }
            })
            ->when($date, function ($q, $date) {
                switch ($date) {
                    case 'this_month':
                        $q->whereMonth('start_date', now()->month)->whereYear('start_date', now()->year);
                        break;
                    case 'next_month':
                        $next = now()->addMonth();
                        $q->whereMonth('start_date', $next->month)->whereYear('start_date', $next->year);
                        break;
                    case 'past':
                        $q->whereDate('end_date', '<', now());
                        break;
                }
            })
            ->withCount('teams')
            ->orderBy($sortBy, $sortDir)
            ->paginate(15);

        return response()->json($tournaments);
    }

    /**
     * Store a new tournament.
     */
    public function storeTournament(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_id' => 'required|exists:users,id',
        ]);

        try {
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('tournaments/logos', 'public');
            }

            Tournament::create($validated);

            return redirect()->route('tournaments.index')->with('success', 'Tournament created successfully.');
        } catch (\Throwable $e) {
            Log::error('Store Tournament Error:', ['message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * Display a single tournament with its details.
     */
    public function showTournament(Tournament $tournament)
    {
        $tournament->load([
            'organizer:id,name',
            'teams:id,name,logo,city,country',
            'matches' => fn($query) => $query->with(['homeTeam:id,name,logo', 'awayTeam:id,name,logo', 'events' => fn($q) => $q->where('type', 'goal')->with(['player:id,name', 'team:id,name'])])->orderBy('scheduled_at', 'desc'),
        ]);

        $tournamentData = $this->getTournamentDetails($tournament);
        $topScorers = $this->getTopScorers($tournament);
        $standings = $this->getStandings($tournament);
        $statistics = $this->getTournamentStatistics($tournament);
        $organizers = User::whereHas('roles', fn($query) => $query->where('name', 'organizer'))->get();

        return view('fuma.tournament_detail', array_merge($tournamentData, [
            'tournament' => $tournament,
            'topScorers' => $topScorers,
            'standings' => $standings,
            'statistics' => $statistics,
            'organizers' => $organizers,
        ]));
    }

    private function getTournamentDetails(Tournament $tournament)
    {
        $totalMatches = $tournament->matches->count();
        $playedMatches = $tournament->matches->where('status', 'completed')->count();
        $recentMatches = $tournament->matches->take(3);

        return [
            'tournamentProgress' => [
                'started' => $tournament->start_date?->format('M d, Y'),
                'percentage' => $totalMatches > 0 ? round(($playedMatches / $totalMatches) * 100, 2) : 0,
                'ended' => $tournament->end_date?->format('M d, Y'),
            ],
            'recentMatches' => $recentMatches,
            'tournamentInformation' => [
                'prize_pool' => $tournament->prize_pool ? number_format($tournament->prize_pool) : 'Not specified',
                'format' => $tournament->type ?? 'Not specified',
                'match_played' => "{$playedMatches}/{$totalMatches}",
                'organizer' => $tournament->organizer?->name ?? 'Unknown',
                'location' => $tournament->venue ?? 'Not specified',
            ],
            'teams' => $tournament->teams->map(fn($team) => [
                'id' => $team->id,
                'name' => $team->name,
                'logo' => $team->logo,
                'city' => $team->city . ', ' . $team->country,
            ]),
            'matches' => $tournament->matches->map(fn($match) => [
                'id' => $match->id,
                'home_team' => $match->homeTeam->name,
                'away_team' => $match->awayTeam->name,
                'scheduled_at' => $match->scheduled_at->format('M d, Y H:i'),
                'status' => $match->status,
                'home_score' => $match->home_score,
                'away_score' => $match->away_score,
            ]),
        ];
    }

    private function getTopScorers(Tournament $tournament)
    {
        $matchIds = $tournament->matches->pluck('id');
        if ($matchIds->isEmpty()) {
            return collect();
        }

        return DB::table('match_events as me')
            ->join('players as p', 'me.player_id', '=', 'p.id')
            ->join('teams as t', 'me.team_id', '=', 't.id')
            ->where('me.type', 'goal')
            ->whereIn('me.match_id', $matchIds)
            ->select('p.id as player_id', 'p.name as player_name', 'p.jersey_number', 'p.avatar', 't.id as team_id', 't.name as team_name', DB::raw('COUNT(*) as goals'))
            ->groupBy('p.id', 'p.name', 'p.jersey_number', 'p.avatar', 't.id', 't.name')
            ->orderByDesc('goals')
            ->limit(3)
            ->get()
            ->map(fn($item) => [
                'player' => ['id' => $item->player_id, 'name' => $item->player_name, 'jersey_number' => $item->jersey_number, 'avatar' => $item->avatar,],
                'team' => ['id' => $item->team_id, 'name' => $item->team_name,],
                'goals' => $item->goals,
            ]);
    }

    private function getStandings(Tournament $tournament)
    {
        return $tournament->teams()
            ->withPivot(['points', 'matches_played', 'wins', 'draws', 'losses', 'goals_for', 'goals_against'])
            ->orderByDesc('points')
            ->orderByDesc(DB::raw('(goals_for - goals_against)'))
            ->orderByDesc('goals_for')
            ->get()
            ->map(fn($team) => [
                'id' => $team->id,
                'name' => $team->name,
                'logo' => $team->logo,
                'points' => $team->pivot->points,
                'matches_played' => $team->pivot->matches_played,
                'wins' => $team->pivot->wins,
                'draws' => $team->pivot->draws,
                'losses' => $team->pivot->losses,
                'goals_for' => $team->pivot->goals_for,
                'goals_against' => $team->pivot->goals_against,
                'goal_difference' => $team->pivot->goals_for - $team->pivot->goals_against,
            ]);
    }

    private function getTournamentStatistics(Tournament $tournament)
    {
        $matchIds = $tournament->matches->pluck('id');
        if ($matchIds->isEmpty()) {
            return ['ball_possession' => collect(), 'total_goals' => ['matches' => 0, 'goals' => 0, 'avg_per_match' => 0], 'disciplines' => ['yellow_cards' => 0, 'red_cards' => 0, 'fouls_committed' => 0]];
        }

        $statsQuery = DB::table('match_events as me')
            ->join('teams as t', 'me.team_id', '=', 't.id')
            ->whereIn('me.match_id', $matchIds)
            ->select('t.id as team_id', 't.name as team_name', DB::raw('COUNT(*) as total_actions'), DB::raw("SUM(CASE WHEN me.type = 'yellow_card' THEN 1 ELSE 0 END) as yellow_cards"), DB::raw("SUM(CASE WHEN me.type = 'red_card' THEN 1 ELSE 0 END) as red_cards"), DB::raw("SUM(CASE WHEN me.type = 'other' AND me.description LIKE '%foul%' THEN 1 ELSE 0 END) as fouls_committed"), DB::raw("SUM(CASE WHEN me.type = 'goal' THEN 1 ELSE 0 END) as goals_scored"))
            ->groupBy('t.id', 't.name')
            ->get();

        $totalActions = $statsQuery->sum('total_actions');
        $ballPossession = $statsQuery->sortByDesc('total_actions')->take(3)->mapWithKeys(fn($team) => [$team->team_name => $totalActions > 0 ? round(($team->total_actions / $totalActions) * 100) : 0]);
        $totalGoals = $statsQuery->sum('goals_scored');
        $totalMatches = $matchIds->count();
        $avgGoals = $totalMatches > 0 ? round($totalGoals / $totalMatches, 2) : 0;
        $disciplines = ['yellow_cards' => $statsQuery->sum('yellow_cards'), 'red_cards' => $statsQuery->sum('red_cards'), 'fouls_committed' => $statsQuery->sum('fouls_committed')];

        return ['ball_possession' => $ballPossession, 'total_goals' => ['matches' => $totalMatches, 'goals' => $totalGoals, 'avg_per_match' => $avgGoals], 'disciplines' => $disciplines];
    }

    /**
     * Update an existing tournament.
     */
    public function updateTournament(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'organizer_id' => 'required|exists:users,id',
            'status' => 'required|in:upcoming,ongoing,completed',
            'prize_pool' => 'nullable|numeric|min:0',
        ]);

        if ($tournament->status === 'completed' && $request->status !== 'completed') {
            return redirect()->back()->withInput()->withErrors(['status' => 'Completed tournament cannot be reverted.']);
        }
        if ($tournament->status === 'ongoing' && $request->status === 'upcoming') {
            return redirect()->back()->withInput()->withErrors(['status' => 'Ongoing tournament cannot go back to upcoming.']);
        }

        try {
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('tournaments/logos', 'public');
            }

            $oldMaxTeams = $tournament->max_teams;
            $oldVenue = $tournament->venue;
            $tournament->update($validated);

            if (in_array($tournament->status, ['upcoming', 'ongoing'])) {
                if ($request->has(['start_date', 'end_date'])) {
                    $this->rescheduleMatches($tournament);
                }
                if ($tournament->status === 'upcoming' && $oldMaxTeams != $tournament->max_teams) {
                    $this->updateTeamStatsForMaxTeamsChange($tournament, $oldMaxTeams);
                }
                if ($oldVenue != $validated['venue']) {
                    $this->updateMatchVenues($tournament, $validated['venue']);
                }
            }

            return redirect()->route('tournaments.show', $tournament)->with('success', 'Tournament updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Update Tournament Error:', ['message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }

    private function rescheduleMatches(Tournament $tournament)
    {
        $matches = $tournament->matches()->where('status', 'scheduled')->orderBy('scheduled_at')->get();
        if ($matches->isEmpty()) return;

        $startDate = Carbon::parse($tournament->start_date);
        foreach ($matches as $match) {
            $oldTime = Carbon::parse($match->scheduled_at)->format('H:i:s');
            $match->scheduled_at = $startDate->format('Y-m-d') . ' ' . $oldTime;
            $match->save();
        }
    }

    private function updateTeamStatsForMaxTeamsChange(Tournament $tournament)
    {
        $stats = ['points' => 0, 'goals_for' => 0, 'goals_against' => 0, 'goal_difference' => 0, 'matches_played' => 0, 'wins' => 0, 'draws' => 0, 'losses' => 0, 'status' => 'active'];
        $tournament->teams()->updateExistingPivot($tournament->teams->pluck('id'), $stats);
    }

    private function updateMatchVenues(Tournament $tournament, $venue)
    {
        $tournament->matches()->where('status', 'scheduled')->update(['venue' => $venue]);
    }

    /**
     * Get a list of available teams to add to a tournament.
     */
    public function availableTeams(Request $request, Tournament $tournament)
    {
        $search = $request->get('search', '');
        $registeredIds = $tournament->teams()->pluck('team_id');

        $teams = Team::whereNotIn('id', $registeredIds)
            ->where('name', 'like', "%{$search}%")
            ->distinct()
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json(['results' => $teams]);
    }

    /**
     * Add a team to the tournament.
     */
    public function addTeamToTournament(Request $request, Tournament $tournament)
    {
        $validated = $request->validate(['team_id' => 'required|exists:teams,id']);
        $teamId = $validated['team_id'];

        if ($tournament->teams()->where('team_id', $teamId)->exists()) {
            return back()->withErrors(['team_id' => 'Team is already registered in this tournament.'])->withInput();
        }

        if ($tournament->teams()->count() >= $tournament->max_teams) {
            return back()->withErrors(['team_id' => 'Tournament is already full.'])->withInput();
        }

        $tournament->teams()->attach($teamId, ['status' => 'registered']);

        return redirect()->route('tournaments.show', $tournament)->with('success', 'Team added to tournament successfully.');
    }

    /**
     * Add a new scheduled match to the tournament.
     */
    public function addScheduleMatch(Request $request, Tournament $tournament)
    {
        $validated = $request->validate($this->getMatchValidationRules($tournament));
        $validated['venue'] = $validated['venue'] ?? $tournament->venue;
        $validated['tournament_id'] = $tournament->id;
        MatchModel::create($validated);
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Match scheduled successfully.');
    }

    /**
     * Update an existing scheduled match.
     */
    public function updateScheduleMatch(Request $request, Tournament $tournament, MatchModel $match)
    {
        $validated = $request->validate($this->getMatchValidationRules($tournament, $match->id));
        $validated['venue'] = $validated['venue'] ?? $tournament->venue;
        $match->update($validated);
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Match updated successfully.');
    }

    private function getMatchValidationRules(Tournament $tournament, $matchId = null)
    {
        $commonRules = [
            'required',
            'exists:teams,id',
            function ($attribute, $value, $fail) use ($tournament, $matchId) {
                if (!request()->scheduled_at) return;
                $scheduledDate = Carbon::parse(request()->scheduled_at)->toDateString();
                $query = $tournament->matches()->whereDate('scheduled_at', $scheduledDate);
                if ($matchId) {
                    $query->where('id', '!=', $matchId);
                }
                if ($query->where(fn($q) => $q->where('home_team_id', $value)->orWhere('away_team_id', $value))->exists()) {
                    $fail("Selected team already has a match scheduled on this date.");
                }
            }
        ];

        return [
            'home_team_id' => $commonRules,
            'away_team_id' => array_merge($commonRules, ['different:home_team_id']),
            'stage' => 'nullable|in:group,round_of_16,quarter_final,semi_final,final',
            'scheduled_at' => "required|date|after_or_equal:{$tournament->start_date}|before_or_equal:{$tournament->end_date}",
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ];
    }

    /**
     * Delete a scheduled match.
     */
    public function deleteScheduleMatch(Tournament $tournament, MatchModel $match)
    {
        if ($match->status !== 'scheduled') {
            return redirect()->route('tournaments.show', $tournament)->withErrors(['match' => 'Only scheduled matches can be deleted.']);
        }

        if ($match->tournament_id !== $tournament->id) {
            return redirect()->route('tournaments.show', $tournament)->withErrors(['match' => 'Match does not belong to this tournament.']);
        }

        $match->delete();
        return redirect()->route('tournaments.show', $tournament)->with('success', 'Match deleted successfully.');
    }

    /**
     * Delete a tournament.
     */
    public function deleteTournament(Tournament $tournament)
    {
        if ($tournament->logo) {
            Storage::disk('public')->delete($tournament->logo);
        }

        $tournament->delete();
        return redirect()->route('tournaments.index')->with('success', 'Tournament deleted successfully.');
    }
}

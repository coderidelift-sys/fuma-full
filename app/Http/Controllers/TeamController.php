<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Team;
use App\Models\Tournament;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    /* -------------------------------------------------------------------------- */
    /* INDEX / LISTING                                                            */
    /* -------------------------------------------------------------------------- */

    public function index(): View
    {
        $teams = $this->getFilteredTeams(request())->paginate(10);
        return view('fuma.teams', compact('teams'));
    }

    public function teamsData(Request $request): JsonResponse
    {
        try {
            $teams = $this->getFilteredTeams($request)->paginate(10);
            return response()->json($teams);
        } catch (Exception $e) {
            Log::error('Error fetching teams data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch teams data'], 500);
        }
    }

    private function getTeamsQuery()
    {
        return Team::withCount(['players', 'tournaments']);
    }

    private function getFilteredTeams(Request $request)
    {
        $query = $this->getTeamsQuery();

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', $term)
                    ->orWhere('city', 'like', $term)
                    ->orWhere('country', 'like', $term)
            );
        }

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('established')) {
            $query->when($request->established === 'before_2000', fn($q) => $q->where('founded_year', '<', 2000))
                ->when($request->established === '2000_2010', fn($q) => $q->whereBetween('founded_year', [2000, 2010]))
                ->when($request->established === 'after_2010', fn($q) => $q->where('founded_year', '>', 2010));
        }

        return $query;
    }

    /* -------------------------------------------------------------------------- */
    /* STORE                                                                      */
    /* -------------------------------------------------------------------------- */

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules());

        try {
            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
            }

            Team::create($data);

            return redirect()->route('teams.index')->with('success', 'Team created successfully!');
        } catch (Exception $e) {
            Log::error('Error creating team: ' . $e->getMessage());
            return back()->with('error', 'Failed to create team.')->withInput();
        }
    }

    /* -------------------------------------------------------------------------- */
    /* SHOW                                                                       */
    /* -------------------------------------------------------------------------- */

    public function show(Team $team): View
    {
        $team->load([
            'players:id,team_id,name,position,birth_date',
            'tournaments:id,name,status,start_date,end_date',
            'tournaments.teams:id,name',
            'tournaments.matches' => fn($q) => $q
                ->where(fn($q2) => $q2->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
                ->orderByDesc('scheduled_at')
        ]);

        $matches = $team->tournaments->flatMap->matches;

        return view('fuma.team-detail', $this->buildTeamData($team, $matches));
    }

    /* -------------------------------------------------------------------------- */
    /* UPDATE                                                                     */
    /* -------------------------------------------------------------------------- */

    public function update(Request $request, Team $team): RedirectResponse
    {
        $data = $request->validate($this->rules(true));

        try {
            if ($request->hasFile('logo')) {
                if ($team->logo) {
                    Storage::disk('public')->delete($team->logo);
                }
                $data['logo'] = $request->file('logo')->store('teams/logos', 'public');
            }

            $team->update($data);

            return redirect()->route('teams.show', $team)->with('success', 'Team updated successfully!');
        } catch (Exception $e) {
            Log::error('Error updating team: ' . $e->getMessage());
            return back()->with('error', 'Failed to update team.')->withInput();
        }
    }

    /* -------------------------------------------------------------------------- */
    /* DESTROY                                                                    */
    /* -------------------------------------------------------------------------- */

    public function destroy(Team $team): JsonResponse
    {
        try {
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }

            $team->delete();

            return response()->json(['success' => true, 'message' => 'Team deleted successfully']);
        } catch (Exception $e) {
            Log::error('Error deleting team: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete team'], 500);
        }
    }

    /* -------------------------------------------------------------------------- */
    /* PRIVATE / HELPER METHODS                                                   */
    /* -------------------------------------------------------------------------- */

    private function rules(bool $isUpdate = false): array
    {
        $required = $isUpdate ? 'nullable' : 'required';

        return [
            'name'                => 'required|string|max:255',
            'short_name'        => 'nullable|string',
            'logo'                => 'nullable|image|max:2048',
            'founded_year'        => 'nullable|integer|min:1800|max:' . date('Y'),
            'country'            => "$required|string|max:255",
            'city'                => "$required|string|max:255",
            'manager_name'        => 'nullable|string|max:255',
            'manager_phone'        => 'nullable|string|max:255',
            'manager_email'        => 'nullable|email|max:255',
            'nickname'            => 'nullable|string|max:255',
            'stadium'            => 'nullable|string|max:255',
            'capacity'            => 'nullable|string|max:255',
            'primary_color'        => 'nullable|string|max:255',
            'secondary_color'    => 'nullable|string|max:255',
            'website'            => 'nullable|url|max:255',
            'status'            => 'nullable|string|max:255',
        ];
    }

    private function buildTeamData(Team $team, Collection $matches): array
    {
        $completed = $matches->where('status', 'completed');
        $grouped = $team->players->groupBy('position')->map->count();

        $wins  = $completed->filter(
            fn($m) => ($m->home_team_id === $team->id && $m->home_score > $m->away_score) ||
                ($m->away_team_id === $team->id && $m->away_score > $m->home_score)
        )->count();

        $draws = $completed->filter(fn($m) => $m->home_score === $m->away_score)->count();
        $losses = $completed->count() - ($wins + $draws);

        $goalsFor = $completed->sum(fn($m) => $m->home_team_id === $team->id ? $m->home_score : $m->away_score);
        $goalsAgainst = $completed->sum(fn($m) => $m->home_team_id === $team->id ? $m->away_score : $m->home_score);

        $avgAge = $team->players
            ->whereNotNull('birth_date')
            ->avg(fn($p) => Carbon::parse($p->birth_date)->age);

        $totalPoints = ($wins * 3) + $draws;
        $maxPoints = $completed->count() * 3;
        $pointsPct = $maxPoints > 0 ? round(($totalPoints / $maxPoints) * 100) : 0;

        $leagueInfo = $this->getLeagueInfo($team);

        return [
            'team'                => $team,
            'players'            => $team->players()->paginate(10),
            'matches'            => $matches,
            'nextMatch'            => $matches->where('status', 'scheduled')->where('scheduled_at', '>', now())->sortBy('scheduled_at')->first(),
            'goalkeepers_count'    => $grouped['GK'] ?? 0,
            'defenders_count'    => $grouped['DEF'] ?? 0,
            'midfielders_count'    => $grouped['MID'] ?? 0,
            'forwards_count'    => $grouped['FWD'] ?? 0,
            'wins_count'        => $wins,
            'draws_count'        => $draws,
            'losses_count'        => $losses,
            'goals_for'            => $goalsFor,
            'goals_against'        => $goalsAgainst,
            'average_age'        => $avgAge ? round($avgAge, 1) : null,
            'league_position'    => $leagueInfo['position'],
            'league_name'        => $leagueInfo['league'],
            'season'            => $leagueInfo['season'],
            'total_points'        => $totalPoints,
            'max_points'        => $maxPoints,
            'points_percentage'    => $pointsPct,
            'history'            => $this->getTeamHistory($team),
        ];
    }

    private function getLeagueInfo(Team $team): array
    {
        $teamData = $this->getTeamDataOptimized($team);

        return [
            'position'    => $teamData['current_position'],
            'league'    => $teamData['current_tournament']->name ?? 'No Active League',
            'season'    => $teamData['current_season'],
        ];
    }

    private function getTeamHistory(Team $team): Collection
    {
        $tournaments = $team->tournaments()
            ->with([
                'matches' => fn($q) => $q
                    ->where(fn($q2) => $q2->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
                    ->where('status', 'completed')
            ])
            ->orderByDesc('start_date')
            ->get();

        return $tournaments->map(function (Tournament $tournament) use ($team) {
            $wins = $draws = $losses = $goalsFor = $goalsAgainst = $points = 0;

            foreach ($tournament->matches as $match) {
                $isHome = $match->home_team_id === $team->id;
                $homeScore = $match->home_score ?? 0;
                $awayScore = $match->away_score ?? 0;

                $goalsFor        += $isHome ? $homeScore : $awayScore;
                $goalsAgainst    += $isHome ? $awayScore : $homeScore;

                if ($homeScore === $awayScore) {
                    $draws++;
                    $points++;
                } elseif (($isHome && $homeScore > $awayScore) || (!$isHome && $awayScore > $homeScore)) {
                    $wins++;
                    $points += 3;
                } else {
                    $losses++;
                }
            }

            $startYear = $tournament->start_date->year;
            $endYear = $tournament->end_date->year;
            $season = $startYear === $endYear ? (string) $startYear : $startYear . '/' . substr((string) $endYear, -2);

            return (object)[
                'season'            => $season,
                'league'            => $tournament->name,
                'position'            => $this->calculateTournamentPosition($tournament, $team, $points),
                'points'            => $points,
                'matches_played'    => $tournament->matches->count(),
                'wins'                => $wins,
                'draws'                => $draws,
                'losses'            => $losses,
                'goals_for'            => $goalsFor,
                'goals_against'        => $goalsAgainst,
                'goal_difference'    => $goalsFor - $goalsAgainst,
                'tournament_type'    => $tournament->type,
                'start_date'        => $tournament->start_date->format('Y-m-d'),
                'end_date'            => $tournament->end_date->format('Y-m-d')
            ];
        });
    }

    private function calculateTournamentPosition(Tournament $tournament, Team $team, int $points): string
    {
        $teams = $tournament->teams()
            ->select('teams.id', 'tournament_teams.points', 'tournament_teams.goal_difference', 'tournament_teams.goals_for')
            ->orderByDesc('tournament_teams.points')
            ->orderByDesc('tournament_teams.goal_difference')
            ->orderByDesc('tournament_teams.goals_for')
            ->get();

        $position = 1;
        foreach ($teams as $t) {
            if ($t->id === $team->id) {
                break;
            }
            $position++;
        }

        $suffix = $this->getPositionSuffix($position);

        return match (true) {
            $position === 1        => "{$position}{$suffix} (Champions)",
            $position <= 3        => "{$position}{$suffix} (Medal)",
            $position <= 8        => "{$position}{$suffix} (Top 8)",
            default                => "{$position}{$suffix}",
        };
    }

    private function getPositionSuffix(int $position): string
    {
        if ($position >= 11 && $position <= 13) {
            return 'th';
        }

        return match ($position % 10) {
            1    => 'st',
            2    => 'nd',
            3    => 'rd',
            default => 'th'
        };
    }

    private function getTeamDataOptimized(Team $team): array
    {
        $tournaments = $team->tournaments()
            ->with([
                'teams' => fn($q) => $q
                    ->orderByDesc('tournament_teams.points')
                    ->orderByDesc('tournament_teams.goal_difference')
                    ->orderByDesc('tournament_teams.goals_for')
            ])
            ->orderByDesc('start_date')
            ->get();

        $currentTournament = null;
        $currentPosition = 0;
        $currentSeason = date('Y');

        foreach ($tournaments as $tournament) {
            if (in_array($tournament->status, ['ongoing', 'completed'], true)) {
                $currentTournament = $tournament;

                $pos = 1;
                foreach ($tournament->teams as $t) {
                    if ($t->id === $team->id) {
                        $currentPosition = $pos;
                        break;
                    }
                    $pos++;
                }

                $startYear = $tournament->start_date->year;
                $endYear = $tournament->end_date->year;
                $currentSeason = $startYear === $endYear ? (string) $startYear : $startYear . '/' . substr((string) $endYear, -2);

                break;
            }
        }

        return [
            'current_tournament'    => $currentTournament,
            'current_position'        => $currentPosition,
            'current_season'        => $currentSeason,
            'all_tournaments'        => $tournaments
        ];
    }
}

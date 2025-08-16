<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Http\Request;

class FumaMatchController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchModel::with(['homeTeam', 'awayTeam', 'tournament']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tournament')) {
            $query->where('tournament_id', $request->tournament);
        }

        if ($request->filled('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        $matches = $query->orderBy('scheduled_at', 'desc')->paginate(10);

        // Get tournaments for filter dropdown
        $tournaments = Tournament::orderBy('name')->get();

        return view('fuma.matches.index', compact('matches', 'tournaments'));
    }

    public function show(MatchModel $match)
    {
        $match->load([
            'homeTeam.players',
            'awayTeam.players',
            'tournament',
            'events.player'
        ]);

        return view('fuma.matches.show', compact('match'));
    }

    public function create()
    {
        $tournaments = Tournament::where('status', '!=', 'completed')->orderBy('name')->get();
        $teams = Team::orderBy('name')->get();

        return view('fuma.matches.create', compact('tournaments', 'teams'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'stage' => 'required|in:group,round_of_16,quarter_final,semi_final,final',
            'scheduled_at' => 'required|date|after:now',
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validated['status'] = 'scheduled';

        $match = MatchModel::create($validated);

        return redirect()->route('fuma.matches.show', $match)
            ->with('success', 'Match scheduled successfully!');
    }

    public function updateScore(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'status' => 'required|in:live,completed',
        ]);

        $match->update($validated);

        // Update team statistics if match is completed
        if ($validated['status'] === 'completed') {
            $this->updateTeamStats($match);
        }

        return redirect()->route('fuma.matches.show', $match)
            ->with('success', 'Match score updated successfully!');
    }

    private function updateTeamStats(MatchModel $match)
    {
        $tournament = $match->tournament;
        $homeTeam = $match->homeTeam;
        $awayTeam = $match->awayTeam;

        // Get pivot records
        $homePivot = $tournament->teams()->where('team_id', $homeTeam->id)->first()->pivot;
        $awayPivot = $tournament->teams()->where('team_id', $awayTeam->id)->first()->pivot;

        // Update match count
        $homePivot->matches_played += 1;
        $awayPivot->matches_played += 1;

        // Update goals
        $homePivot->goals_for += $match->home_score;
        $homePivot->goals_against += $match->away_score;
        $awayPivot->goals_for += $match->away_score;
        $awayPivot->goals_against += $match->home_score;

        // Determine winner and update stats
        if ($match->home_score > $match->away_score) {
            // Home team wins
            $homePivot->wins += 1;
            $homePivot->points += 3;
            $awayPivot->losses += 1;
        } elseif ($match->away_score > $match->home_score) {
            // Away team wins
            $awayPivot->wins += 1;
            $awayPivot->points += 3;
            $homePivot->losses += 1;
        } else {
            // Draw
            $homePivot->draws += 1;
            $homePivot->points += 1;
            $awayPivot->draws += 1;
            $awayPivot->points += 1;
        }

        // Update goal difference
        $homePivot->goal_difference = $homePivot->goals_for - $homePivot->goals_against;
        $awayPivot->goal_difference = $awayPivot->goals_for - $awayPivot->goals_against;

        // Save the pivot records
        $homePivot->save();
        $awayPivot->save();
    }
}
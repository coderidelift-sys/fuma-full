<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;

class FumaTournamentController extends Controller
{
    public function index(Request $request)
    {
        $query = Tournament::with(['teams', 'organizer']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        $tournaments = $query->orderBy('start_date', 'desc')->paginate(10);

        return view('fuma.tournaments.index', compact('tournaments'));
    }

    public function show(Tournament $tournament)
    {
        $tournament->load([
            'teams.players',
            'matches.homeTeam',
            'matches.awayTeam',
            'organizer'
        ]);

        // Get top scorers for this tournament
        $topScorers = Player::whereHas('team.tournaments', function($query) use ($tournament) {
                $query->where('tournament_id', $tournament->id);
            })
            ->orderBy('goals_scored', 'desc')
            ->limit(5)
            ->get();

        $tournament->topScorers = $topScorers;

        return view('fuma.tournaments.show', compact('tournament'));
    }

    public function create()
    {
        return view('fuma.tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('tournaments', 'public');
        }

        $validated['organizer_id'] = auth()->id();
        $validated['status'] = 'upcoming';

        $tournament = Tournament::create($validated);

        return redirect()->route('fuma.tournaments.show', $tournament)
            ->with('success', 'Tournament created successfully!');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();

        return redirect()->route('fuma.tournaments.index')
            ->with('success', 'Tournament deleted successfully!');
    }
}
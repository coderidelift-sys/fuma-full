<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class FumaTeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with(['players']);

        // Apply filters
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'city':
                $query->orderBy('city', 'asc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $teams = $query->paginate(12);

        // Get all cities for filter dropdown
        $cities = Team::distinct()->pluck('city')->filter()->sort()->values();

        return view('fuma.teams.index', compact('teams', 'cities'));
    }

    public function show(Team $team)
    {
        $team->load(['players', 'tournaments', 'manager']);

        // Get team statistics
        $team_stats = [
            'total_matches' => $team->getAllMatchesAttribute()->count(),
            'wins' => $team->tournaments->sum('pivot.wins'),
            'draws' => $team->tournaments->sum('pivot.draws'),
            'losses' => $team->tournaments->sum('pivot.losses'),
            'goals_for' => $team->tournaments->sum('pivot.goals_for'),
            'goals_against' => $team->tournaments->sum('pivot.goals_against'),
        ];

        return view('fuma.teams.show', compact('team', 'team_stats'));
    }

    public function create()
    {
        return view('fuma.teams.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('teams', 'public');
        }

        $validated['rating'] = 0.00;
        $validated['trophies_count'] = 0;

        $team = Team::create($validated);

        return redirect()->route('fuma.teams.show', $team)
            ->with('success', 'Team registered successfully!');
    }
}
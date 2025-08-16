<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class FumaPlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Player::with(['team']);

        // Apply filters
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        if ($request->filled('team')) {
            $query->where('team_id', $request->team);
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
            case 'goals':
                $query->orderBy('goals_scored', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }

        $players = $query->paginate(16);

        // Get teams for filter dropdown
        $teams = Team::orderBy('name')->get();

        return view('fuma.players.index', compact('players', 'teams'));
    }

    public function show(Player $player)
    {
        $player->load(['team', 'matchEvents']);

        // Get player statistics
        $player_stats = [
            'matches_played' => $player->matchEvents->pluck('match_id')->unique()->count(),
            'goals' => $player->goals_scored,
            'assists' => $player->assists,
            'yellow_cards' => $player->yellow_cards,
            'red_cards' => $player->red_cards,
            'clean_sheets' => $player->clean_sheets,
        ];

        return view('fuma.players.show', compact('player', 'player_stats'));
    }

    public function create()
    {
        $teams = Team::orderBy('name')->get();
        $positions = ['Goalkeeper', 'Defender', 'Midfielder', 'Forward'];

        return view('fuma.players.create', compact('teams', 'positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Goalkeeper,Defender,Midfielder,Forward',
            'jersey_number' => 'nullable|string|max:3',
            'birth_date' => 'nullable|date|before:today',
            'nationality' => 'required|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:40|max:150',
            'team_id' => 'nullable|exists:teams,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('players', 'public');
        }

        // Set default values
        $validated['rating'] = 0.00;
        $validated['goals_scored'] = 0;
        $validated['assists'] = 0;
        $validated['clean_sheets'] = 0;
        $validated['yellow_cards'] = 0;
        $validated['red_cards'] = 0;

        $player = Player::create($validated);

        return redirect()->route('fuma.players.show', $player)
            ->with('success', 'Player added successfully!');
    }
}
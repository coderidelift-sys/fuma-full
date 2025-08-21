<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlayerController extends Controller
{
    public function index()
    {
        return view('console.players.index');
    }

    public function data(Request $request)
    {
        $query = Player::query()
            ->select(['id', 'name', 'position', 'jersey_number', 'rating', 'goals_scored', 'assists', 'team_id'])
            ->with(['team:id,name']);

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        return datatables()->eloquent($query)->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:10',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        DB::transaction(function () use ($validated) {
            Player::create($validated);
        });

        return response()->json(['message' => 'Player created'], 201);
    }

    public function show(Player $player)
    {
        $player->loadMissing(['team:id,name']);
        return response()->json($player);
    }

    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:10',
            'jersey_number' => 'nullable|integer|min:1|max:99',
            'team_id' => 'nullable|exists:teams,id',
        ]);

        DB::transaction(function () use ($player, $validated) {
            $player->update($validated);
        });

        return response()->json(['message' => 'Player updated']);
    }

    public function destroy(Player $player)
    {
        DB::transaction(function () use ($player) {
            $player->delete();
        });

        return response()->json(['message' => 'Player deleted']);
    }
}



<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentController extends Controller
{
    public function index()
    {
        return view('console.tournaments.index');
    }

    public function data(Request $request)
    {
        $query = Tournament::query()
            ->select(['id', 'name', 'status', 'start_date', 'end_date', 'max_teams', 'venue'])
            ->withCount(['matches', 'teams']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return datatables()->eloquent($query)->toJson();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_teams' => 'required|integer|min:2',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        DB::transaction(function () use ($validated) {
            Tournament::create($validated);
        });

        return response()->json(['message' => 'Tournament created'], 201);
    }

    public function show(Tournament $tournament)
    {
        return response()->json($tournament);
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'max_teams' => 'required|integer|min:2',
            'venue' => 'nullable|string|max:255',
            'status' => 'required|in:upcoming,ongoing,completed',
        ]);

        DB::transaction(function () use ($tournament, $validated) {
            $tournament->update($validated);
        });

        return response()->json(['message' => 'Tournament updated']);
    }

    public function destroy(Tournament $tournament)
    {
        DB::transaction(function () use ($tournament) {
            $tournament->delete();
        });

        return response()->json(['message' => 'Tournament deleted']);
    }
}



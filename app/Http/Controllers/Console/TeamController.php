<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    public function index()
    {
        return view('console.teams.index');
    }

    public function data(Request $request)
    {
        $query = Team::query()
            ->select(['id', 'name', 'city', 'country', 'rating', 'manager_name', 'status'])
            ->withCount(['players', 'tournaments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            Team::create($validated);
        });

        return response()->json(['message' => 'Team created'], 201);
    }

    public function show(Team $team)
    {
        return response()->json($team);
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:50',
            'manager_email' => 'nullable|email|max:255',
            'status' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($team, $validated) {
            $team->update($validated);
        });

        return response()->json(['message' => 'Team updated']);
    }

    public function destroy(Team $team)
    {
        DB::transaction(function () use ($team) {
            $team->delete();
        });

        return response()->json(['message' => 'Team deleted']);
    }
}



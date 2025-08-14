<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        $query = Tournament::with(['organizer', 'teams']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by organizer
        if ($request->has('organizer_id')) {
            $query->where('organizer_id', $request->organizer_id);
        }

        $tournaments = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $tournaments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['organizer_id'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('tournaments/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $tournament = Tournament::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Tournament created successfully',
            'data' => $tournament->load('organizer')
        ], 201);
    }

    public function show($id)
    {
        $tournament = Tournament::with([
            'organizer',
            'teams',
            'matches.homeTeam',
            'matches.awayTeam',
            'committees.user'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tournament
        ]);
    }

    public function update(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);

        // Check if user is organizer or admin
        if ($tournament->organizer_id !== $request->user()->id && !$this->isAdmin($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'max_teams' => 'sometimes|required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:upcoming,ongoing,completed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($tournament->logo) {
                Storage::disk('public')->delete($tournament->logo);
            }

            $logoPath = $request->file('logo')->store('tournaments/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $tournament->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Tournament updated successfully',
            'data' => $tournament->load('organizer')
        ]);
    }

    public function destroy($id)
    {
        $tournament = Tournament::findOrFail($id);

        if ($tournament->organizer_id !== auth()->id() && !$this->isAdmin(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Delete logo if exists
        if ($tournament->logo) {
            Storage::disk('public')->delete($tournament->logo);
        }

        $tournament->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tournament deleted successfully'
        ]);
    }

    public function addTeam(Request $request, $id)
    {
        $tournament = Tournament::findOrFail($id);
        $team = Team::findOrFail($request->team_id);

        $validator = Validator::make($request->all(), [
            'team_id' => 'required|exists:teams,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if team is already in tournament
        if ($tournament->teams()->where('team_id', $team->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Team is already in this tournament'
            ], 400);
        }

        // Check if tournament is full
        if ($tournament->teams()->count() >= $tournament->max_teams) {
            return response()->json([
                'success' => false,
                'message' => 'Tournament is full'
            ], 400);
        }

        $tournament->teams()->attach($team->id, ['status' => 'registered']);

        return response()->json([
            'success' => true,
            'message' => 'Team added to tournament successfully'
        ]);
    }

    public function standings($id)
    {
        $tournament = Tournament::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $tournament->standings
        ]);
    }

    private function isAdmin($user)
    {
        return $user->roles()->where('name', 'admin')->exists();
    }
}

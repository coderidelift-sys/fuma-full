<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $query = Team::with(['manager', 'players']);

        // Filter by city
        if ($request->has('city')) {
            $query->byCity($request->city);
        }

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        $teams = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $teams
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
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
        $data['manager_id'] = $request->user()->id;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('teams/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $team = Team::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Team created successfully',
            'data' => $team->load('manager')
        ], 201);
    }

    public function show($id)
    {
        $team = Team::with(['manager', 'players', 'tournaments'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $team
        ]);
    }

    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        // Check if user is manager or admin
        if ($team->manager_id !== $request->user()->id && !$this->isAdmin($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'sometimes|required|string|max:255',
            'country' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
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
            if ($team->logo) {
                Storage::disk('public')->delete($team->logo);
            }

            $logoPath = $request->file('logo')->store('teams/logos', 'public');
            $data['logo'] = $logoPath;
        }

        $team->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Team updated successfully',
            'data' => $team->load('manager')
        ]);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        if ($team->manager_id !== auth()->id() && !$this->isAdmin(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Delete logo if exists
        if ($team->logo) {
            Storage::disk('public')->delete($team->logo);
        }

        $team->delete();

        return response()->json([
            'success' => true,
            'message' => 'Team deleted successfully'
        ]);
    }

    public function addPlayer(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        if ($team->manager_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();
        $data['team_id'] = $team->id;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $player = Player::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Player added to team successfully',
            'data' => $player
        ], 201);
    }

    private function isAdmin($user)
    {
        return $user->roles()->where('name', 'admin')->exists();
    }
}

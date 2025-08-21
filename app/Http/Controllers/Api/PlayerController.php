<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        // Optimized query dengan selective field loading
        $query = Player::select([
            'id', 'name', 'position', 'jersey_number', 'avatar',
            'nationality', 'rating', 'goals_scored', 'assists',
            'team_id', 'created_at', 'birth_date'
        ])->with(['team:id,name,short_name,logo']);

        // Filter by position
        if ($request->has('position')) {
            $query->byPosition($request->position);
        }

        // Filter by team
        if ($request->has('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        // Filter by rating
        if ($request->has('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        // Sort by goals, rating, etc.
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'goals':
                    $query->topScorers();
                    break;
                case 'rating':
                    $query->topRated();
                    break;
                case 'assists':
                    $query->orderBy('assists', 'desc');
                    break;
                case 'recent':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

        // Pagination dengan limit yang reasonable
        $perPage = min($request->get('per_page', 10), 50); // Max 50 per page
        $players = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $players
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'team_id' => 'nullable|exists:teams,id',
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

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $player = Player::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Player created successfully',
            'data' => $player->load('team')
        ], 201);
    }

    public function show(Player $player)
    {
        // Load only necessary relationships
        $player->load([
            'team:id,name,short_name,logo,city,country',
            'matchEvents' => function($query) {
                $query->select('id', 'match_id', 'player_id', 'type', 'minute')
                      ->orderBy('minute', 'desc')
                      ->limit(10);
            }
        ]);

        return response()->json([
            'success' => true,
            'data' => $player
        ]);
    }

    public function update(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        // Check if user is team manager or admin
        if ($player->team && $player->team->manager_id !== $request->user()->id && !$this->isAdmin($request->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'team_id' => 'nullable|exists:teams,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->except('avatar');

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($player->avatar) {
                Storage::disk('public')->delete($player->avatar);
            }

            $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $player->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Player updated successfully',
            'data' => $player->load('team')
        ]);
    }

    public function destroy($id)
    {
        $player = Player::findOrFail($id);

        if ($player->team && $player->team->manager_id !== auth()->id() && !$this->isAdmin(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied'
            ], 403);
        }

        // Delete avatar if exists
        if ($player->avatar) {
            Storage::disk('public')->delete($player->avatar);
        }

        $player->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player deleted successfully'
        ]);
    }

    public function updateStats(Request $request, $id)
    {
        $player = Player::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'clean_sheets' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0',
            'red_cards' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $player->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Player stats updated successfully',
            'data' => $player
        ]);
    }

    private function isAdmin($user)
    {
        return $user->roles()->where('name', 'admin')->exists();
    }
}

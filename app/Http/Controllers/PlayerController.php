<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;

class PlayerController extends Controller
{
    /**
     * Display a listing of players
     */
    public function index(): View
    {
        $teams = Team::all();
        return view('fuma.players', compact('teams'));
    }

    /**
     * Get players data for AJAX requests (with filters)
     */
    public function playersData(Request $request): JsonResponse
    {
        try {
            $query = Player::with(['team']);

            // Apply filters
            if ($request->filled('search')) {
                $term = '%' . $request->search . '%';
                $query->where('name', 'like', $term);
            }

            if ($request->filled('position')) {
                $query->where('position', $request->position);
            }

            if ($request->filled('team')) {
                $query->where('team_id', $request->team);
            }

            if ($request->filled('nationality')) {
                $query->where('nationality', $request->nationality);
            }

            $players = $query->orderBy('name')->paginate(10);

            return response()->json($players);
        } catch (Exception $e) {
            Log::error('Error fetching players data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch players data'], 500);
        }
    }

    /**
     * Store a newly created player
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'position' => 'required|string',
                'team_id' => 'nullable|exists:teams,id',
                'jersey_number' => 'nullable|integer|min:1|max:99',
                'birth_date' => 'nullable|date|before:today',
                'nationality' => 'required|string|max:255',
                'height' => 'nullable|numeric|min:100|max:250',
                'weight' => 'nullable|numeric|min:30|max:150',
                'bio' => 'nullable|string',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Combine first and last name
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Set default values
            $data['rating'] = 3.50; // Default rating
            $data['goals_scored'] = 0;
            $data['assists'] = 0;
            $data['clean_sheets'] = 0;
            $data['yellow_cards'] = 0;
            $data['red_cards'] = 0;

            Player::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Player created successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error creating player: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create player'
            ], 500);
        }
    }

    /**
     * Display the specified player
     */
    public function show(Player $player): View
    {
        $player->load(['team']);

        // Get teams for edit form
        $teams = Team::all();

        // Get player's matches
        $matches = MatchModel::where('home_team_id', $player->team_id)
            ->orWhere('away_team_id', $player->team_id)
            ->with(['homeTeam', 'awayTeam', 'tournament'])
            ->orderBy('scheduled_at', 'desc')
            ->limit(10)
            ->get();

        // Calculate statistics
        $matches_count = $matches->count();
        $minutes_played = $matches_count * 90; // Assuming 90 minutes per match

        // Get recent matches with player performance data using service
        $recent_matches = $matches->take(5);

        if (app()->bound('App\Services\MatchEventService')) {
            $service = app('App\Services\MatchEventService');
            $matchIds = $recent_matches->pluck('id');

            // Get all performance data in one query
            $performanceData = $service->getPlayerPerformanceInMatches($player->id, $matchIds);

            $recent_matches = $recent_matches->map(function ($match) use ($performanceData, $player) {
                $matchPerformance = $performanceData->get($match->id, collect());

                $match->player_performance = [
                    'goals' => $matchPerformance->where('type', 'goal')->first()->count ?? 0,
                    'assists' => $matchPerformance->where('type', 'assist')->first()->count ?? 0,
                    'yellow_cards' => $matchPerformance->where('type', 'yellow_card')->first()->count ?? 0,
                    'red_cards' => $matchPerformance->where('type', 'red_card')->first()->count ?? 0,
                    'clean_sheets' => $this->calculateCleanSheet($match, $player),
                    'minutes_played' => 90, // Assuming full match
                ];

                return $match;
            });
        } else {
            // Fallback to old method if service not available
            $recent_matches = $recent_matches->map(function ($match) use ($player) {
                $matchEvents = DB::table('match_events as me')
                    ->where('me.match_id', $match->id)
                    ->where('me.player_id', $player->id)
                    ->get();

                $match->player_performance = [
                    'goals' => $matchEvents->where('type', 'goal')->count(),
                    'assists' => $matchEvents->where('type', 'assist')->count(),
                    'yellow_cards' => $matchEvents->where('type', 'yellow_card')->count(),
                    'red_cards' => $matchEvents->where('type', 'red_card')->count(),
                    'clean_sheets' => $this->calculateCleanSheet($match, $player),
                    'minutes_played' => 90,
                ];

                return $match;
            });
        }

        // Load player with computed attributes
        $player->load(['team']);

        // Ensure career stats are loaded
        $careerStats = $player->career_stats;
        $totalCareerStats = $player->total_career_stats;

        return view('fuma.player-detail', compact(
            'player',
            'teams',
            'matches_count',
            'minutes_played',
            'recent_matches',
            'careerStats',
            'totalCareerStats'
        ));
    }

    /**
     * Calculate clean sheet for a player in a specific match
     */
    private function calculateCleanSheet($match, $player): int
    {
        if (!in_array($player->position, ['GK', 'DEF'])) {
            return 0;
        }

        $opponentTeamId = ($match->home_team_id == $player->team_id) ? $match->away_team_id : $match->home_team_id;
        $opponentGoals = DB::table('match_events')
            ->where('match_id', $match->id)
            ->where('team_id', $opponentTeamId)
            ->where('type', 'goal')
            ->count();

        return $opponentGoals == 0 ? 1 : 0;
    }

    /**
     * Update the specified player
     */
    public function update(Request $request, Player $player): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'position' => 'required|string',
                'team_id' => 'nullable|exists:teams,id',
                'jersey_number' => 'nullable|integer|min:1|max:99',
                'birth_date' => 'nullable|date|before:today',
                'nationality' => 'required|string|max:255',
                'height' => 'nullable|numeric|min:100|max:250',
                'weight' => 'nullable|numeric|min:30|max:150',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($player->avatar) {
                    Storage::disk('public')->delete($player->avatar);
                }

                $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            $player->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Player updated successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error updating player: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update player'
            ], 500);
        }
    }

    /**
     * Remove the specified player
     */
    public function destroy(Player $player): JsonResponse
    {
        try {
            // Delete avatar if exists
            if ($player->avatar) {
                Storage::disk('public')->delete($player->avatar);
            }

            $player->delete();

            return response()->json([
                'success' => true,
                'message' => 'Player deleted successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting player: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete player'
            ], 500);
        }
    }
}

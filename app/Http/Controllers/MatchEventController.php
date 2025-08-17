<?php

namespace App\Http\Controllers;

use App\Models\MatchEvent;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MatchEventController extends Controller
{
    /**
     * Store a newly created match event
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'match_id' => 'required|exists:matches,id',
                'player_id' => 'nullable|exists:players,id',
                'team_id' => 'required|exists:teams,id',
                'type' => 'required|in:goal,yellow_card,red_card,substitution,injury,assist,clean_sheet,foul,other',
                'minute' => 'required|integer|min:1|max:120',
                'description' => 'nullable|string',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate match exists and is live
            $match = MatchModel::findOrFail($request->match_id);
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Events can only be added to live matches'
                ], 422);
            }

            // Validate player belongs to the team
            if ($request->player_id) {
                $player = Player::findOrFail($request->player_id);
                if ($player->team_id != $request->team_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Player does not belong to the specified team'
                    ], 422);
                }
            }

            // Validate minute is within match duration
            if ($request->minute > 90 && $match->stage !== 'final') {
                return response()->json([
                    'success' => false,
                    'message' => 'Minute cannot exceed 90 for regular matches'
                ], 422);
            }

            // Create the event
            $event = MatchEvent::create($request->all());

            // Update match scores if it's a goal
            if ($request->type === 'goal') {
                $this->updateMatchScore($match, $request->team_id);
            }

            // Update player statistics
            if ($request->player_id) {
                $this->updatePlayerStats($request->player_id, $request->type);
            }

            return response()->json([
                'success' => true,
                'message' => 'Match event created successfully!',
                'event' => $event
            ]);
        } catch (Exception $e) {
            Log::error('Error creating match event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create match event'
            ], 500);
        }
    }

    /**
     * Update the specified match event
     */
    public function update(Request $request, MatchEvent $event): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'sometimes|required|in:goal,yellow_card,red_card,substitution,injury,assist,clean_sheet,foul,other',
                'minute' => 'sometimes|required|integer|min:1|max:120',
                'description' => 'nullable|string',
                'metadata' => 'nullable|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if match is still live
            $match = $event->match;
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Events can only be updated in live matches'
                ], 422);
            }

            // Store old values for statistics update
            $oldType = $event->type;
            $oldPlayerId = $event->player_id;

            // Update the event
            $event->update($request->all());

            // Update statistics if type or player changed
            if ($oldType !== $request->type || $oldPlayerId !== $event->player_id) {
                if ($oldPlayerId) {
                    $this->updatePlayerStats($oldPlayerId, $oldType, -1); // Remove old stats
                }
                if ($event->player_id) {
                    $this->updatePlayerStats($event->player_id, $request->type, 1); // Add new stats
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Match event updated successfully!',
                'event' => $event
            ]);
        } catch (Exception $e) {
            Log::error('Error updating match event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update match event'
            ], 500);
        }
    }

    /**
     * Remove the specified match event
     */
    public function destroy(MatchEvent $event): JsonResponse
    {
        try {
            // Check if match is still live
            $match = $event->match;
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Events can only be deleted in live matches'
                ], 422);
            }

            // Update player statistics
            if ($event->player_id) {
                $this->updatePlayerStats($event->player_id, $event->type, -1);
            }

            // Update match scores if it's a goal
            if ($event->type === 'goal') {
                $this->updateMatchScore($match, $event->team_id, -1);
            }

            // Delete the event
            $event->delete();

            return response()->json([
                'success' => true,
                'message' => 'Match event deleted successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting match event: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete match event'
            ], 500);
        }
    }

    /**
     * Get events for a specific match
     */
    public function getMatchEvents(MatchModel $match): JsonResponse
    {
        try {
            $events = $match->events()
                ->with(['player', 'team'])
                ->orderBy('minute', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'events' => $events
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching match events: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch match events'
            ], 500);
        }
    }

    /**
     * Update match score when goal is scored
     */
    private function updateMatchScore(MatchModel $match, int $teamId, int $increment = 1): void
    {
        if ($match->home_team_id === $teamId) {
            $match->increment('home_score', $increment);
        } elseif ($match->away_team_id === $teamId) {
            $match->increment('away_score', $increment);
        }
    }

    /**
     * Update player statistics
     */
    private function updatePlayerStats(int $playerId, string $eventType, int $increment = 1): void
    {
        $player = Player::find($playerId);
        if (!$player) return;

        switch ($eventType) {
            case 'goal':
                $player->increment('goals_scored', $increment);
                break;
            case 'assist':
                $player->increment('assists', $increment);
                break;
            case 'yellow_card':
                $player->increment('yellow_cards', $increment);
                break;
            case 'red_card':
                $player->increment('red_cards', $increment);
                break;
            case 'clean_sheet':
                $player->increment('clean_sheets', $increment);
                break;
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\MatchEvent;
use App\Models\Venue;
use App\Models\MatchLineup;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;
use Carbon\Carbon;
use App\Models\Player; // Added for player stats update

class MatchController extends Controller
{
    /**
     * Display a listing of matches
     */
        public function index(): View
    {
        $tournaments = Tournament::all();
        $teams = Team::all();
        $venues = Venue::active()->get();

        return view('fuma.matches', compact('tournaments', 'teams', 'venues'));
    }

    /**
     * Get matches data for AJAX requests (with filters)
     */
    public function data(Request $request): JsonResponse
    {
        try {
            // Optimized query with specific field selection
            $query = MatchModel::select([
                'id', 'tournament_id', 'home_team_id', 'away_team_id',
                'stage', 'status', 'scheduled_at', 'venue',
                'home_score', 'away_score', 'current_minute'
            ])->with([
                'homeTeam:id,name,short_name,logo',
                'awayTeam:id,name,short_name,logo',
                'tournament:id,name'
            ]);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('tournament')) {
                $query->where('tournament_id', $request->tournament);
            }

            if ($request->filled('date')) {
                $date = Carbon::parse($request->date);
                $query->whereDate('scheduled_at', $date);
            }

            // Apply search if provided
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->whereHas('homeTeam', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('awayTeam', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })->orWhereHas('tournament', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                });
            }

            // Order by scheduled_at (upcoming first)
            $query->orderBy('scheduled_at', 'asc');

            $matches = $query->paginate(10);

            return response()->json($matches);
        } catch (Exception $e) {
            Log::error('Error fetching matches data: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch matches data'], 500);
        }
    }

    /**
     * Store a newly created match
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'home_team_id' => 'required|exists:teams,id',
                'away_team_id' => 'required|exists:teams,id|different:home_team_id',
                'tournament_id' => 'required|exists:tournaments,id',
                'stage' => 'required|in:group,round_of_16,quarter_final,semi_final,final',
                'scheduled_at' => 'required|date|after:now',
                'venue' => 'nullable|string|max:255',
                'referee' => 'nullable|string|max:255',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if teams are already in the same tournament
            $existingMatch = MatchModel::where('tournament_id', $request->tournament_id)
                ->where(function($q) use ($request) {
                    $q->where(function($q) use ($request) {
                        $q->where('home_team_id', $request->home_team_id)
                          ->where('away_team_id', $request->away_team_id);
                    })->orWhere(function($q) use ($request) {
                        $q->where('home_team_id', $request->away_team_id)
                          ->where('away_team_id', $request->home_team_id);
                    });
                })->first();

            if ($existingMatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'These teams already have a match in this tournament'
                ], 422);
            }

            // Check if teams are available at the scheduled time
            $conflictingMatch = MatchModel::where('scheduled_at', $request->scheduled_at)
                ->where(function($q) use ($request) {
                    $q->where('home_team_id', $request->home_team_id)
                      ->orWhere('away_team_id', $request->home_team_id)
                      ->orWhere('home_team_id', $request->away_team_id)
                      ->orWhere('away_team_id', $request->away_team_id);
                })->first();

            if ($conflictingMatch) {
                return response()->json([
                    'success' => false,
                    'message' => 'One or both teams have conflicting matches at this time'
                ], 422);
            }

            $data = $request->all();
            $data['status'] = 'scheduled';

            MatchModel::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Match created successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error creating match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create match'
            ], 500);
        }
    }

    /**
     * Display the specified match
     */
    public function show(MatchModel $match): View
    {
        // Load all necessary relationships
        $match->load([
            'homeTeam',
            'awayTeam',
            'tournament',
            'events' => function($query) {
                $query->orderBy('minute', 'asc');
            },
            'events.player',
            'events.team'
        ]);

        // Get comprehensive match data
        $matchData = $this->getComprehensiveMatchData($match);

        return view('fuma.match-detail', $matchData);
    }

    /**
     * Update the specified match
     */
    public function update(Request $request, MatchModel $match): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'home_team_id' => 'sometimes|required|exists:teams,id',
                'away_team_id' => 'sometimes|required|exists:teams,id|different:home_team_id',
                'tournament_id' => 'sometimes|required|exists:tournaments,id',
                'stage' => 'sometimes|required|in:group,round_of_16,quarter_final,semi_final,final',
                'scheduled_at' => 'sometimes|required|date',
                'venue' => 'nullable|string|max:255',
                'referee' => 'nullable|string|max:255',
                'status' => 'sometimes|required|in:scheduled,live,completed,cancelled',
                'home_score' => 'nullable|integer|min:0',
                'away_score' => 'nullable|integer|min:0',
                'notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // If updating scores, validate that match is live or completed
            if ($request->has('home_score') || $request->has('away_score')) {
                if (!in_array($match->status, ['live', 'completed'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot update scores for a match that is not live or completed'
                    ], 422);
                }
            }

            // If changing status to completed, ensure scores are set
            if ($request->status === 'completed') {
                if (is_null($request->home_score) || is_null($request->away_score)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Scores must be set when completing a match'
                    ], 422);
                }
            }

            $match->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Match updated successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error updating match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update match'
            ], 500);
        }
    }

    /**
     * Remove the specified match
     */
    public function destroy(MatchModel $match): JsonResponse
    {
        try {
            // Check if match can be deleted
            if ($match->status === 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete a live match'
                ], 422);
            }

            // Delete related match events first
            $match->events()->delete();

            // Delete the match
            $match->delete();

            return response()->json([
                'success' => true,
                'message' => 'Match deleted successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete match'
            ], 500);
        }
    }

    /**
     * Start a match (change status to live)
     */
    public function startMatch(MatchModel $match): JsonResponse
    {
        try {
            if ($match->status !== 'scheduled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Match can only be started if it is scheduled'
                ], 422);
            }

            // Check if lineups are set for both teams
            $homeLineup = $match->lineups()->where('team_id', $match->home_team_id)->count();
            $awayLineup = $match->lineups()->where('team_id', $match->away_team_id)->count();

            if ($homeLineup < 11 || $awayLineup < 11) {
                return response()->json([
                    'success' => false,
                    'message' => 'Both teams must have at least 11 players in lineup before starting match'
                ], 422);
            }

            $match->update([
                'status' => 'live',
                'current_minute' => 0,
                'started_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Match started successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            Log::error('Error starting match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start match'
            ], 500);
        }
    }

    /**
     * Stop/Pause a live match
     */
    public function pauseMatch(MatchModel $match): JsonResponse
    {
        try {
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Match can only be paused if it is live'
                ], 422);
            }

            $match->update([
                'status' => 'paused',
                'paused_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Match paused successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            dd($e);
            Log::error('Error pausing match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to pause match'
            ], 500);
        }
    }

    /**
     * Resume a paused match
     */
    public function resumeMatch(MatchModel $match): JsonResponse
    {
        try {
            if ($match->status !== 'paused') {
                return response()->json([
                    'success' => false,
                    'message' => 'Match can only be resumed if it is paused'
                ], 422);
            }

            $match->update([
                'status' => 'live',
                'resumed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Match resumed successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            Log::error('Error resuming match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resume match'
            ], 500);
        }
    }

    /**
     * Complete a match
     */
    public function completeMatch(Request $request, MatchModel $match): JsonResponse
    {
        try {
            if (!in_array($match->status, ['live', 'paused'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Match can only be completed if it is live or paused'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'home_score' => 'required|integer|min:0',
                'away_score' => 'required|integer|min:0',
                'attendance' => 'nullable|integer|min:0',
                'weather' => 'nullable|string|max:255',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $match->update([
                'status' => 'completed',
                'home_score' => $request->home_score,
                'away_score' => $request->away_score,
                'attendance' => $request->attendance,
                'weather' => $request->weather,
                'notes' => $request->notes,
                'completed_at' => now(),
                'current_minute' => 90
            ]);

            // Update tournament standings
            $this->updateTournamentStandings($match);

            // Update player statistics
            $this->updatePlayerStatistics($match);

            return response()->json([
                'success' => true,
                'message' => 'Match completed successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            Log::error('Error completing match: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete match'
            ], 500);
        }
    }

    /**
     * Update match score during live match
     */
    public function updateScore(Request $request, MatchModel $match): JsonResponse
    {
        try {
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Score can only be updated during live matches'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'home_score' => 'required|integer|min:0',
                'away_score' => 'required|integer|min:0',
                'scorer_id' => 'nullable|exists:players,id',
                'assist_id' => 'nullable|exists:players,id',
                'minute' => 'required|integer|min:1|max:120'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $match->update([
                'home_score' => $request->home_score,
                'away_score' => $request->away_score
            ]);

            // Log the goal event if scorer is provided
            if ($request->scorer_id) {
                $teamId = $request->home_score > $request->away_score ? $match->home_team_id : $match->away_team_id;

                MatchEvent::create([
                    'match_id' => $match->id,
                    'player_id' => $request->scorer_id,
                    'team_id' => $teamId,
                    'type' => 'goal',
                    'minute' => $request->minute,
                    'description' => 'Goal scored',
                    'metadata' => [
                        'assist_id' => $request->assist_id,
                        'score_after' => $request->home_score . '-' . $request->away_score
                    ]
                ]);

                // Update player stats
                $this->updatePlayerStats($request->scorer_id, 'goal');
                if ($request->assist_id) {
                    $this->updatePlayerStats($request->assist_id, 'assist');
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Score updated successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            Log::error('Error updating score: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update score'
            ], 500);
        }
    }

    /**
     * Update match minute (for live matches)
     */
    public function updateMinute(Request $request, MatchModel $match): JsonResponse
    {
        try {
            if ($match->status !== 'live') {
                return response()->json([
                    'success' => false,
                    'message' => 'Minute can only be updated during live matches'
                ], 422);
            }

            $validator = Validator::make($request->all(), [
                'current_minute' => 'required|integer|min:0|max:120'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $match->update([
                'current_minute' => $request->current_minute
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Match minute updated successfully!',
                'data' => $match->fresh()
            ]);
        } catch (Exception $e) {
            Log::error('Error updating match minute: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update match minute'
            ], 500);
        }
    }

    /**
     * Get match management data (lineups, events, etc.)
     */
    public function getMatchManagementData(MatchModel $match): JsonResponse
    {
        try {
            $match->load([
                'homeTeam.players',
                'awayTeam.players',
                'lineups.player',
                'events.player',
                'tournament'
            ]);

            $data = [
                'match' => $match,
                'home_team_players' => $match->homeTeam->players,
                'away_team_players' => $match->awayTeam->players,
                'lineups' => $match->lineups->groupBy('team_id'),
                'events' => $match->events->sortBy('minute'),
                'can_start' => $match->status === 'scheduled',
                'can_pause' => $match->status === 'live',
                'can_resume' => $match->status === 'paused',
                'can_complete' => in_array($match->status, ['live', 'paused'])
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Error getting match management data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get match management data'
            ], 500);
        }
    }

    /**
     * Get comprehensive match statistics from database
     */
    private function getMatchStatistics(MatchModel $match): array
    {
        // Cache match statistics for completed matches (they don't change)
        if ($match->status === 'completed') {
            return cache()->remember("match_stats_{$match->id}", 3600, function () use ($match) {
                return $this->calculateMatchStatistics($match);
            });
        }

        // For live matches, cache for shorter time
        if ($match->status === 'live') {
            return cache()->remember("match_stats_{$match->id}", 30, function () use ($match) {
                return $this->calculateMatchStatistics($match);
            });
        }

        return $this->calculateMatchStatistics($match);
    }

    private function calculateMatchStatistics(MatchModel $match): array
    {
        $homeTeamId = $match->home_team_id;
        $awayTeamId = $match->away_team_id;

        // Get all match events for this match
        $matchEvents = $match->events()->with(['team', 'player'])->get();

        // Initialize statistics arrays
        $stats = [
            'home' => $this->initializeTeamStats(),
            'away' => $this->initializeTeamStats()
        ];

        // Calculate statistics from match events
        foreach ($matchEvents as $event) {
            $teamId = $event->team_id;
            $isHomeTeam = ($teamId === $homeTeamId);

            if ($isHomeTeam) {
                $this->processMatchEvent($event, $stats['home']);
            } else {
                $this->processMatchEvent($event, $stats['away']);
            }
        }

        // Calculate derived statistics
        $this->calculateDerivedStats($stats);

        // Calculate possession (based on events distribution)
        $totalEvents = $matchEvents->count();
        $homeEvents = $matchEvents->where('team_id', $homeTeamId)->count();
        $awayEvents = $matchEvents->where('team_id', $awayTeamId)->count();

        $stats['possession'] = [
            'home' => $totalEvents > 0 ? round(($homeEvents / $totalEvents) * 100) : 50,
            'away' => $totalEvents > 0 ? round(($awayEvents / $totalEvents) * 100) : 50
        ];

        // Ensure away possession complements home possession
        $stats['possession']['away'] = 100 - $stats['possession']['home'];

        // Transform to match frontend expectations
        return [
            'possession' => $stats['possession'],
            'shots' => [
                'home' => $stats['home']['shots'],
                'away' => $stats['away']['shots']
            ],
            'shots_on_target' => [
                'home' => $stats['home']['shots_on_target'],
                'away' => $stats['away']['shots_on_target']
            ],
            'shots_off_target' => [
                'home' => $stats['home']['shots_off_target'],
                'away' => $stats['away']['shots_off_target']
            ],
            'shots_blocked' => [
                'home' => $stats['home']['shots_blocked'],
                'away' => $stats['away']['shots_blocked']
            ],
            'corners' => [
                'home' => $stats['home']['corners'],
                'away' => $stats['away']['corners']
            ],
            'fouls' => [
                'home' => $stats['home']['fouls'],
                'away' => $stats['away']['fouls']
            ],
            'yellow_cards' => [
                'home' => $stats['home']['yellow_cards'],
                'away' => $stats['away']['yellow_cards']
            ],
            'red_cards' => [
                'home' => $stats['home']['red_cards'],
                'away' => $stats['away']['red_cards']
            ],
            'offsides' => [
                'home' => $stats['home']['offsides'],
                'away' => $stats['away']['offsides']
            ],
            'total_shots' => [
                'home' => $stats['home']['shots'],
                'away' => $stats['away']['shots']
            ],
            'total_passes' => [
                'home' => $stats['home']['passes'],
                'away' => $stats['away']['passes']
            ],
            'pass_accuracy' => [
                'home' => $stats['home']['pass_accuracy'],
                'away' => $stats['away']['pass_accuracy']
            ],
            'key_passes' => [
                'home' => $stats['home']['key_passes'],
                'away' => $stats['away']['key_passes']
            ],
            'tackles' => [
                'home' => $stats['home']['tackles'],
                'away' => $stats['away']['tackles']
            ],
            'interceptions' => [
                'home' => $stats['home']['interceptions'],
                'away' => $stats['away']['interceptions']
            ],
            'saves' => [
                'home' => $stats['home']['saves'],
                'away' => $stats['away']['saves']
            ],
            'clearances' => [
                'home' => $stats['home']['clearances'],
                'away' => $stats['away']['clearances']
            ],
            'blocks' => [
                'home' => $stats['home']['blocks'],
                'away' => $stats['away']['blocks']
            ]
        ];
    }

    /**
     * Initialize team statistics structure
     */
    private function initializeTeamStats(): array
    {
        return [
            'goals' => 0,
            'shots' => 0,
            'shots_on_target' => 0,
            'shots_off_target' => 0,
            'shots_blocked' => 0,
            'corners' => 0,
            'fouls' => 0,
            'yellow_cards' => 0,
            'red_cards' => 0,
            'offsides' => 0,
            'passes' => 0,
            'pass_accuracy' => 0,
            'key_passes' => 0,
            'tackles' => 0,
            'interceptions' => 0,
            'saves' => 0,
            'clearances' => 0,
            'blocks' => 0
        ];
    }

    /**
     * Process individual match event and update statistics
     */
    private function processMatchEvent($event, &$teamStats)
    {
        switch ($event->type) {
            case 'goal':
                $teamStats['goals']++;
                $teamStats['shots_on_target']++;
                $teamStats['shots']++;
                break;

            case 'yellow_card':
                $teamStats['yellow_cards']++;
                break;

            case 'red_card':
                $teamStats['red_cards']++;
                break;

            case 'foul':
                $teamStats['fouls']++;
                break;

            case 'corner':
                $teamStats['corners']++;
                break;

            case 'offside':
                $teamStats['offsides']++;
                break;

            case 'shot':
                $teamStats['shots']++;
                break;

            case 'shot_on_target':
                $teamStats['shots_on_target']++;
                $teamStats['shots']++;
                break;

            case 'shot_off_target':
                $teamStats['shots_off_target']++;
                $teamStats['shots']++;
                break;

            case 'shot_blocked':
                $teamStats['shots_blocked']++;
                $teamStats['shots']++;
                break;

            case 'pass':
                $teamStats['passes']++;
                break;

            case 'key_pass':
                $teamStats['key_passes']++;
                break;

            case 'tackle':
                $teamStats['tackles']++;
                break;

            case 'interception':
                $teamStats['interceptions']++;
                break;

            case 'save':
                $teamStats['saves']++;
                break;

            case 'clearance':
                $teamStats['clearances']++;
                break;

            case 'block':
                $teamStats['blocks']++;
                break;
        }
    }

    /**
     * Calculate derived statistics that depend on other stats
     */
    private function calculateDerivedStats(&$stats): void
    {
        foreach (['home', 'away'] as $team) {
            // Calculate shots off target if not explicitly recorded
            if ($stats[$team]['shots_off_target'] === 0 && $stats[$team]['shots'] > 0) {
                $stats[$team]['shots_off_target'] = max(0, $stats[$team]['shots'] - $stats[$team]['shots_on_target'] - $stats[$team]['shots_blocked']);
            }

            // Calculate pass accuracy (simplified calculation)
            if ($stats[$team]['passes'] > 0) {
                $successfulPasses = $stats[$team]['passes'] - max(0, $stats[$team]['passes'] * 0.15); // Assume 85% accuracy
                $stats[$team]['pass_accuracy'] = round(($successfulPasses / $stats[$team]['passes']) * 100);
            } else {
                $stats[$team]['pass_accuracy'] = 0;
            }

            // Ensure all numeric values are integers
            foreach ($stats[$team] as $key => $value) {
                if (is_numeric($value)) {
                    $stats[$team][$key] = (int) $value;
                }
            }
        }
    }

        /**
     * Get team lineup from database
     */
    private function getTeamLineup(int $matchId, int $teamId): array
    {
        $lineups = MatchLineup::where('match_id', $matchId)
            ->where('team_id', $teamId)
            ->with(['player:id,name,avatar,position,jersey_number'])
            ->orderBy('type')
            ->orderBy('jersey_number')
            ->get();

        $result = [
            'starting_xi' => [],
            'substitutes' => [],
            'bench' => []
        ];

        foreach ($lineups as $lineup) {
            $playerData = [
                'jersey_number' => $lineup->jersey_number,
                'name' => $lineup->player->name,
                'position' => $lineup->position,
                'avatar' => $lineup->player->avatar ?? 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740',
                'is_captain' => $lineup->is_captain,
                'substitution_time' => $lineup->substitution_minute
            ];

            $result[$lineup->type][] = $playerData;
        }

        return $result;
    }

    /**
     * Get comprehensive match data for frontend
     */
    private function getComprehensiveMatchData(MatchModel $match): array
    {
        // Get match statistics
        $matchStats = $this->getMatchStatistics($match);

        // Get lineups
        $homeLineup = $this->getTeamLineup($match->id, $match->home_team_id);
        $awayLineup = $this->getTeamLineup($match->id, $match->away_team_id);

        // Get match events for timeline
        $events = $match->events()
            ->with(['player', 'team'])
            ->orderBy('minute', 'asc')
            ->get();

        // Transform events for frontend
        $transformedEvents = $events->map(function ($event) {
            return [
                'type' => $event->type,
                'minute' => $event->minute,
                'description' => $event->description,
                'player_name' => $event->player ? $event->player->name : null,
                'team_name' => $event->team ? $event->team->name : null,
                'metadata' => $event->metadata
            ];
        });

        return [
            'match' => $match,
            'matchStats' => $matchStats,
            'homeLineup' => $homeLineup,
            'awayLineup' => $awayLineup,
            'events' => $transformedEvents,
            'homeTeam' => $match->homeTeam,
            'awayTeam' => $match->awayTeam,
            'tournament' => $match->tournament
        ];
    }

    /**
     * Update tournament standings after match completion
     */
    private function updateTournamentStandings(MatchModel $match): void
    {
        // This would update the tournament_teams pivot table
        // Implementation depends on your tournament structure
    }

    /**
     * Update player statistics after match completion
     */
    private function updatePlayerStatistics(MatchModel $match): void
    {
        // This would update player stats based on match events
        // Implementation depends on your player stats structure
    }

    /**
     * Update individual player stats
     */
    private function updatePlayerStats(int $playerId, string $statType): void
    {
        $player = Player::find($playerId);
        if (!$player) return;

        switch ($statType) {
            case 'goal':
                $player->increment('goals_scored');
                break;
            case 'assist':
                $player->increment('assists');
                break;
            case 'yellow_card':
                $player->increment('yellow_cards');
                break;
            case 'red_card':
                $player->increment('red_cards');
                break;
        }
    }
}

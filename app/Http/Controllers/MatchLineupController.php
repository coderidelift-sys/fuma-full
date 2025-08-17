<?php

namespace App\Http\Controllers;

use App\Models\MatchModel;
use App\Models\MatchLineup;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class MatchLineupController extends Controller
{
    /**
     * Get lineup for a specific match and team
     */
    public function getLineup(Request $request, MatchModel $match): JsonResponse
    {
        try {
            $teamId = $request->team_id ?? $match->home_team_id;

            $lineups = MatchLineup::where('match_id', $match->id)
                ->where('team_id', $teamId)
                ->with(['player:id,name,position,jersey_number,avatar'])
                ->orderBy('type')
                ->orderBy('jersey_number')
                ->get();

            $data = [
                'starting_xi' => $lineups->where('type', 'starting_xi')->values(),
                'substitutes' => $lineups->where('type', 'substitute')->values(),
                'bench' => $lineups->where('type', 'bench')->values(),
                'formation' => $this->getFormation($lineups->where('type', 'starting_xi'))
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Error getting lineup: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get lineup'
            ], 500);
        }
    }

    /**
     * Set lineup for a team in a match
     */
    public function setLineup(Request $request, MatchModel $match): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|exists:teams,id',
                'lineup' => 'required|array',
                'lineup.starting_xi' => 'required|array|min:11|max:11',
                'lineup.substitutes' => 'required|array|max:12',
                'lineup.bench' => 'nullable|array',
                'formation' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validate that team is participating in the match
            if (!in_array($request->team_id, [$match->home_team_id, $match->away_team_id])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Team is not participating in this match'
                ], 422);
            }

            // Validate that match hasn't started
            if ($match->status !== 'scheduled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Lineup can only be set for scheduled matches'
                ], 422);
            }

            // Validate all players belong to the team
            $teamPlayers = Player::where('team_id', $request->team_id)->pluck('id')->toArray();
            $lineupPlayerIds = collect($request->lineup)
                ->flatten(1)
                ->pluck('player_id')
                ->toArray();

            $invalidPlayers = array_diff($lineupPlayerIds, $teamPlayers);
            if (!empty($invalidPlayers)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Some players do not belong to the team'
                ], 422);
            }

            // Delete existing lineup for this team
            MatchLineup::where('match_id', $match->id)
                ->where('team_id', $request->team_id)
                ->delete();

            // Create new lineup
            $lineupData = [];
            foreach ($request->lineup as $type => $players) {
                foreach ($players as $player) {
                    $lineupData[] = [
                        'match_id' => $match->id,
                        'team_id' => $request->team_id,
                        'player_id' => $player['player_id'],
                        'type' => $type === 'starting_xi' ? 'starting_xi' : ($type === 'substitutes' ? 'substitute' : 'bench'),
                        'jersey_number' => $player['jersey_number'],
                        'position' => $player['position'],
                        'is_captain' => $player['is_captain'] ?? false,
                        'metadata' => json_encode([
                            'formation_position' => $player['formation_position'] ?? null,
                            'formation' => $request->formation ?? '4-4-2'
                        ])
                    ];
                }
            }

            MatchLineup::insert($lineupData);

            return response()->json([
                'success' => true,
                'message' => 'Lineup set successfully!'
            ]);
        } catch (Exception $e) {
            Log::error('Error setting lineup: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to set lineup'
            ], 500);
        }
    }

    /**
     * Update lineup during match (substitutions, etc.)
     */
    public function updateLineup(Request $request, MatchModel $match)
    {
        // Validasi input, otomatis redirect back dengan errors jika gagal
        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'player_out_id' => 'required|exists:players,id',
            'player_in_id' => 'required|exists:players,id',
            'minute' => 'required|integer|min:1|max:120',
            'position' => 'required|string|max:50',
        ]);

        try {
            // Pastikan match live
            if ($match->status !== 'live') {
                return back()->withErrors(['match' => 'Lineup can only be updated during live matches']);
            }

            // Pastikan pemain termasuk di tim
            $teamPlayers = Player::where('team_id', $request->team_id)->pluck('id')->toArray();
            if (!in_array($request->player_out_id, $teamPlayers) || !in_array($request->player_in_id, $teamPlayers)) {
                return back()->withErrors(['players' => 'Players do not belong to the team']);
            }

            // Player out
            $playerOut = MatchLineup::where([
                'match_id' => $match->id,
                'team_id' => $request->team_id,
                'player_id' => $request->player_out_id,
                'type' => 'starting_xi'
            ])->first();

            if (!$playerOut) {
                return back()->withErrors(['player_out' => 'Player out is not in starting XI']);
            }

            $playerOut->update([
                'substitution_minute' => $request->minute,
                'substitution_type' => 'out'
            ]);

            // Player in
            $playerIn = MatchLineup::where([
                'match_id' => $match->id,
                'team_id' => $request->team_id,
                'player_id' => $request->player_in_id,
                'type' => 'substitute'
            ])->first();

            if (!$playerIn) {
                return back()->withErrors(['player_in' => 'Player in is not in substitutes']);
            }

            $playerIn->update([
                'type' => 'starting_xi',
                'substitution_minute' => $request->minute,
                'substitution_type' => 'in',
                'position' => $request->position
            ]);

            // Log substitution
            $this->logSubstitutionEvent($match, $request->team_id, $request->player_out_id, $request->player_in_id, $request->minute);

            return back()->with('success', 'Substitution made successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating lineup: ' . $e->getMessage());
            return back()->withErrors(['exception' => 'Failed to update lineup: ' . $e->getMessage()]);
        }
    }

    /**
     * Get available players for lineup selection
     */
    public function getAvailablePlayers(MatchModel $match, int $teamId): JsonResponse
    {
        try {
            $players = Player::where('team_id', $teamId)
                ->with(['team:id,name'])
                ->orderBy('position')
                ->orderBy('name')
                ->get();

            $data = [
                'players' => $players,
                'positions' => ['GK', 'DEF', 'MID', 'FWD'],
                'formation_suggestions' => [
                    '4-4-2' => ['GK' => 1, 'DEF' => 4, 'MID' => 4, 'FWD' => 2],
                    '4-3-3' => ['GK' => 1, 'DEF' => 4, 'MID' => 3, 'FWD' => 3],
                    '3-5-2' => ['GK' => 1, 'DEF' => 3, 'MID' => 5, 'FWD' => 2],
                    '5-3-2' => ['GK' => 1, 'DEF' => 5, 'MID' => 3, 'FWD' => 2]
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (Exception $e) {
            Log::error('Error getting available players: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available players'
            ], 500);
        }
    }

    /**
     * Get formation from lineup
     */
    private function getFormation($startingXI): string
    {
        // Hitung jumlah pemain per posisi
        $positions = $startingXI->pluck('position')->countBy();
        $def = $positions['DEF'] ?? 0;
        $mid = $positions['MID'] ?? 0;
        $fwd = $positions['FWD'] ?? 0;

        // Formasi dropdown yang tersedia
        $availableFormations = [
            '4-4-2',
            '4-3-3',
            '3-5-2',
            '5-3-2'
        ];

        // Fungsi untuk hitung jarak “perbedaan” dari jumlah pemain
        $calculateDifference = function ($formation) use ($def, $mid, $fwd) {
            [$fDef, $fMid, $fFwd] = array_map('intval', explode('-', $formation));
            return abs($def - $fDef) + abs($mid - $fMid) + abs($fwd - $fFwd);
        };

        // Cari formasi dengan jarak terkecil
        $closest = collect($availableFormations)
            ->sortBy(fn($f) => $calculateDifference($f))
            ->first();

        return $closest;
    }

    /**
     * Log substitution event
     */
    private function logSubstitutionEvent(MatchModel $match, int $teamId, int $playerOutId, int $playerInId, int $minute): void
    {
        // Create substitution event
        \App\Models\MatchEvent::create([
            'match_id' => $match->id,
            'team_id' => $teamId,
            'type' => 'substitution',
            'minute' => $minute,
            'description' => 'Substitution made',
            'metadata' => [
                'player_out_id' => $playerOutId,
                'player_in_id' => $playerInId
            ]
        ]);
    }
}

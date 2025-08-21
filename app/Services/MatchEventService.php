<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use Carbon\Carbon;

class MatchEventService
{
    /**
     * Get player performance in specific matches
     */
    public function getPlayerPerformanceInMatches(int $playerId, Collection $matchIds): Collection
    {
        if ($matchIds->isEmpty()) {
            return collect();
        }

        return DB::table('match_events')
            ->whereIn('match_id', $matchIds)
            ->where('player_id', $playerId)
            ->select('match_id', 'type', DB::raw('COUNT(*) as count'))
            ->groupBy('match_id', 'type')
            ->get()
            ->groupBy('match_id');
    }

    /**
     * Get top scorers in a tournament
     */
    public function getTopScorersInTournament(int $tournamentId, int $limit = 3): Collection
    {
        return DB::table('match_events as me')
            ->join('players as p', 'me.player_id', '=', 'p.id')
            ->join('teams as t', 'me.team_id', '=', 't.id')
            ->where('me.type', 'goal')
            ->whereIn('me.match_id', function($query) use ($tournamentId) {
                $query->select('id')
                      ->from('matches')
                      ->where('tournament_id', $tournamentId);
            })
            ->select('p.id as player_id', 'p.name as player_name', 'p.jersey_number', 'p.avatar',
                     't.id as team_id', 't.name as team_name', DB::raw('COUNT(*) as goals'))
            ->groupBy('p.id', 'p.name', 'p.jersey_number', 'p.avatar', 't.id', 't.name')
            ->orderByDesc('goals')
            ->limit($limit)
            ->get()
            ->map(fn($item) => [
                'player' => [
                    'id' => $item->player_id,
                    'name' => $item->player_name,
                    'jersey_number' => $item->jersey_number,
                    'avatar' => $item->avatar
                ],
                'team' => [
                    'id' => $item->team_id,
                    'name' => $item->team_name
                ],
                'goals' => $item->goals,
            ]);
    }

    /**
     * Get player tournament statistics
     */
    public function getPlayerTournamentStats(int $playerId, Collection $matchIds): array
    {
        if ($matchIds->isEmpty()) {
            return [
                'goals' => 0,
                'assists' => 0,
                'yellow_cards' => 0,
                'red_cards' => 0,
                'clean_sheets' => 0,
                'matches' => 0
            ];
        }

        $stats = DB::table('match_events')
            ->whereIn('match_id', $matchIds)
            ->where('player_id', $playerId)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Get player info to determine position
        $player = DB::table('players')->where('id', $playerId)->first();
        $cleanSheets = 0;

        // Calculate clean sheets for goalkeepers and defenders
        if ($player && in_array($player->position, ['GK', 'DEF'])) {
            $cleanSheets = $this->calculatePlayerCleanSheets($playerId, $matchIds);
        }

        return [
            'goals' => $stats['goal'] ?? 0,
            'assists' => $stats['assist'] ?? 0,
            'yellow_cards' => $stats['yellow_card'] ?? 0,
            'red_cards' => $stats['red_card'] ?? 0,
            'clean_sheets' => $cleanSheets,
            'matches' => $matchIds->count()
        ];
    }

    /**
     * Get team performance statistics in matches
     */
    public function getTeamPerformanceInMatches(int $teamId, Collection $matchIds): array
    {
        if ($matchIds->isEmpty()) {
            return [
                'goals_scored' => 0,
                'goals_conceded' => 0,
                'yellow_cards' => 0,
                'red_cards' => 0,
                'clean_sheets' => 0
            ];
        }

        $teamStats = DB::table('match_events')
            ->whereIn('match_id', $matchIds)
            ->where('team_id', $teamId)
            ->select('type', DB::raw('COUNT(*) as count'))
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        // Calculate goals conceded (opponent goals)
        $opponentGoals = DB::table('match_events as me')
            ->join('matches as m', 'me.match_id', '=', 'm.id')
            ->whereIn('me.match_id', $matchIds)
            ->where('me.type', 'goal')
            ->where(function($query) use ($teamId) {
                $query->where(function($q) use ($teamId) {
                    $q->where('m.home_team_id', $teamId)
                      ->whereRaw('me.team_id != ?', [$teamId]);
                })->orWhere(function($q) use ($teamId) {
                    $q->where('m.away_team_id', $teamId)
                      ->whereRaw('me.team_id != ?', [$teamId]);
                });
            })
            ->count();

        return [
            'goals_scored' => $teamStats['goal'] ?? 0,
            'goals_conceded' => $opponentGoals,
            'yellow_cards' => $teamStats['yellow_card'] ?? 0,
            'red_cards' => $teamStats['red_card'] ?? 0,
            'clean_sheets' => $this->calculateTeamCleanSheets($teamId, $matchIds)
        ];
    }

    /**
     * Calculate clean sheets for a team in specific matches
     */
    private function calculateTeamCleanSheets(int $teamId, Collection $matchIds): int
    {
        $cleanSheets = 0;

        foreach ($matchIds as $matchId) {
            $match = DB::table('matches')->where('id', $matchId)->first();
            if (!$match) continue;

            $opponentTeamId = ($match->home_team_id == $teamId) ? $match->away_team_id : $match->home_team_id;

            $opponentGoals = DB::table('match_events')
                ->where('match_id', $matchId)
                ->where('team_id', $opponentTeamId)
                ->where('type', 'goal')
                ->count();

            if ($opponentGoals == 0) {
                $cleanSheets++;
            }
        }

        return $cleanSheets;
    }

    /**
     * Calculate clean sheets for a player in specific matches
     */
    private function calculatePlayerCleanSheets(int $playerId, Collection $matchIds): int
    {
        $cleanSheets = 0;

        foreach ($matchIds as $matchId) {
            $match = DB::table('matches')->where('id', $matchId)->first();
            if (!$match) continue;

            // Get player's team ID from the match
            $playerTeamId = null;
            if ($match->home_team_id == $playerId || $match->away_team_id == $playerId) {
                // This is a direct team match, get player's actual team
                $player = DB::table('players')->where('id', $playerId)->first();
                $playerTeamId = $player->team_id;
            } else {
                // Get player's team from lineup or other source
                $lineup = DB::table('match_lineups')
                    ->where('match_id', $matchId)
                    ->where('player_id', $playerId)
                    ->first();
                $playerTeamId = $lineup ? $lineup->team_id : null;
            }

            if (!$playerTeamId) continue;

            $opponentTeamId = ($match->home_team_id == $playerTeamId) ? $match->away_team_id : $match->home_team_id;

            $opponentGoals = DB::table('match_events')
                ->where('match_id', $matchId)
                ->where('team_id', $opponentTeamId)
                ->where('type', 'goal')
                ->count();

            if ($opponentGoals == 0) {
                $cleanSheets++;
            }
        }

        return $cleanSheets;
    }

    /**
     * Get tournament statistics
     */
    public function getTournamentStatistics(int $tournamentId): array
    {
        $matchIds = DB::table('matches')
            ->where('tournament_id', $tournamentId)
            ->pluck('id');

        if ($matchIds->isEmpty()) {
            return [
                'ball_possession' => collect(),
                'total_goals' => ['matches' => 0, 'goals' => 0, 'avg_per_match' => 0],
                'disciplines' => ['yellow_cards' => 0, 'red_cards' => 0, 'fouls_committed' => 0]
            ];
        }

        $statsQuery = DB::table('match_events as me')
            ->join('teams as t', 'me.team_id', '=', 't.id')
            ->whereIn('me.match_id', $matchIds)
            ->select(
                't.id as team_id',
                't.name as team_name',
                DB::raw('COUNT(*) as total_actions'),
                DB::raw("SUM(CASE WHEN me.type = 'yellow_card' THEN 1 ELSE 0 END) as yellow_cards"),
                DB::raw("SUM(CASE WHEN me.type = 'red_card' THEN 1 ELSE 0 END) as red_cards"),
                DB::raw("SUM(CASE WHEN me.type = 'other' AND me.description LIKE '%foul%' THEN 1 ELSE 0 END) as fouls_committed"),
                DB::raw("SUM(CASE WHEN me.type = 'goal' THEN 1 ELSE 0 END) as goals_scored")
            )
            ->groupBy('t.id', 't.name')
            ->get();

        $totalActions = $statsQuery->sum('total_actions');
        $ballPossession = $statsQuery->sortByDesc('total_actions')
            ->take(3)
            ->mapWithKeys(fn($team) => [
                $team->team_name => $totalActions > 0 ? round(($team->total_actions / $totalActions) * 100) : 0
            ]);

        $totalGoals = $statsQuery->sum('goals_scored');
        $totalMatches = $matchIds->count();
        $avgGoals = $totalMatches > 0 ? round($totalGoals / $totalMatches, 2) : 0;

        $disciplines = [
            'yellow_cards' => $statsQuery->sum('yellow_cards'),
            'red_cards' => $statsQuery->sum('red_cards'),
            'fouls_committed' => $statsQuery->sum('fouls_committed')
        ];

        return [
            'ball_possession' => $ballPossession,
            'total_goals' => [
                'matches' => $totalMatches,
                'goals' => $totalGoals,
                'avg_per_match' => $avgGoals
            ],
            'disciplines' => $disciplines
        ];
    }

    /**
     * Get player career statistics by season - OPTIMIZED VERSION
     */
    public function getPlayerCareerStats(int $playerId, Collection $tournaments): Collection
    {
        if ($tournaments->isEmpty()) {
            return collect();
        }

        // Single optimized query untuk semua tournament stats
        $tournamentIds = $tournaments->pluck('id');

        $stats = DB::table('tournaments as t')
            ->leftJoin('matches as m', 't.id', '=', 'm.tournament_id')
            ->leftJoin('match_events as me', function($join) use ($playerId) {
                $join->on('m.id', '=', 'me.match_id')
                     ->where('me.player_id', '=', $playerId);
            })
            ->whereIn('t.id', $tournamentIds)
            ->select([
                't.id as tournament_id',
                't.name as tournament_name',
                't.start_date',
                DB::raw('COUNT(DISTINCT m.id) as matches'),
                DB::raw("SUM(CASE WHEN me.type = 'goal' THEN 1 ELSE 0 END) as goals"),
                DB::raw("SUM(CASE WHEN me.type = 'assist' THEN 1 ELSE 0 END) as assists"),
                DB::raw("SUM(CASE WHEN me.type = 'yellow_card' THEN 1 ELSE 0 END) as yellow_cards"),
                DB::raw("SUM(CASE WHEN me.type = 'red_card' THEN 1 ELSE 0 END) as red_cards")
            ])
            ->groupBy('t.id', 't.name', 't.start_date')
            ->get();

        $careerStats = collect();

        foreach ($stats as $stat) {
            // Calculate clean sheets for goalkeepers and defenders
            $cleanSheets = 0;
            if ($stat->matches > 0) {
                $cleanSheets = $this->calculatePlayerCleanSheetsForTournament($playerId, $stat->tournament_id);
            }

            $careerStats->push([
                'season' => $this->getSeasonFromDate(Carbon::parse($stat->start_date)),
                'team' => $tournaments->firstWhere('id', $stat->tournament_id)->pivot->team_name ?? 'Unknown Team',
                'tournament' => $stat->tournament_name,
                'matches' => $stat->matches,
                'goals' => $stat->goals,
                'assists' => $stat->assists,
                'clean_sheets' => $cleanSheets,
                'yellow_cards' => $stat->yellow_cards,
                'red_cards' => $stat->red_cards,
                'minutes' => $stat->matches * 90
            ]);
        }

        return $careerStats;
    }

    /**
     * Calculate clean sheets for a player in a specific tournament
     */
    private function calculatePlayerCleanSheetsForTournament(int $playerId, int $tournamentId): int
    {
        // Get player info to determine position
        $player = DB::table('players')->where('id', $playerId)->first();
        if (!$player || !in_array($player->position, ['GK', 'DEF'])) {
            return 0;
        }

        // Get matches where player participated
        $matchIds = DB::table('match_lineups')
            ->where('player_id', $playerId)
            ->join('matches', 'match_lineups.match_id', '=', 'matches.id')
            ->where('matches.tournament_id', $tournamentId)
            ->pluck('matches.id');

        if ($matchIds->isEmpty()) {
            return 0;
        }

        $cleanSheets = 0;

        foreach ($matchIds as $matchId) {
            $match = DB::table('matches')->where('id', $matchId)->first();
            if (!$match) continue;

            // Get player's team from lineup
            $lineup = DB::table('match_lineups')
                ->where('match_id', $matchId)
                ->where('player_id', $playerId)
                ->first();

            if (!$lineup) continue;

            $playerTeamId = $lineup->team_id;
            $opponentTeamId = ($match->home_team_id == $playerTeamId) ? $match->away_team_id : $match->home_team_id;

            $opponentGoals = DB::table('match_events')
                ->where('match_id', $matchId)
                ->where('team_id', $opponentTeamId)
                ->where('type', 'goal')
                ->count();

            if ($opponentGoals == 0) {
                $cleanSheets++;
            }
        }

        return $cleanSheets;
    }

    /**
     * Get season from date (e.g., 2023-09-01 → 2023-24)
     */
    private function getSeasonFromDate($date): string
    {
        $year = $date->year;
        $month = $date->month;

        // Season runs from August to July
        if ($month >= 8) {
            return $year . '-' . substr($year + 1, -2);
        } else {
            return ($year - 1) . '-' . substr($year, -2);
        }
    }
}

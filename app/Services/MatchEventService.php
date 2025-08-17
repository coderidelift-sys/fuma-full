<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;

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
     * Get player statistics for a specific tournament/season
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

        return [
            'goals' => $stats['goal'] ?? 0,
            'assists' => $stats['assist'] ?? 0,
            'yellow_cards' => $stats['yellow_card'] ?? 0,
            'red_cards' => $stats['red_card'] ?? 0,
            'clean_sheets' => $stats['clean_sheet'] ?? 0,
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
     * Get player career statistics by season
     */
    public function getPlayerCareerStats(int $playerId, Collection $tournaments): Collection
    {
        $careerStats = collect();

        foreach ($tournaments as $tournament) {
            $matchIds = DB::table('matches')
                ->where('tournament_id', $tournament->id)
                ->pluck('id');

            if ($matchIds->isEmpty()) continue;

            $stats = $this->getPlayerTournamentStats($playerId, $matchIds);

            $careerStats->push([
                'season' => $this->getSeasonFromDate($tournament->start_date),
                'team' => $tournament->pivot->team_name ?? 'Unknown Team',
                'tournament' => $tournament->name,
                'matches' => $stats['matches'],
                'goals' => $stats['goals'],
                'assists' => $stats['assists'],
                'clean_sheets' => $stats['clean_sheets'],
                'yellow_cards' => $stats['yellow_cards'],
                'red_cards' => $stats['red_cards'],
                'minutes' => $stats['matches'] * 90
            ]);
        }

        return $careerStats;
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

<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\MatchEvent;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsController extends Controller
{
    public function data()
    {
        $cacheKey = 'console_dashboard_analytics_' . auth()->id();
        $data = Cache::remember($cacheKey, 60, function () {
            $usersCount = User::count();

            $tournamentsTotal = Tournament::count();
            $tournamentsUpcoming = Tournament::upcoming()->count();
            $tournamentsOngoing = Tournament::active()->count();
            $tournamentsCompleted = Tournament::completed()->count();

            $teamsCount = Team::count();
            $playersCount = Player::count();

            $matchesTotal = MatchModel::count();
            $matchesLive = MatchModel::live()->count();
            $matchesUpcoming = MatchModel::upcoming()->count();
            $matchesCompleted = MatchModel::completed()->count();

            $topScorers = MatchEvent::select('player_id', DB::raw('COUNT(*) as goals'))
                ->where('type', 'goal')
                ->groupBy('player_id')
                ->orderByDesc('goals')
                ->with(['player:id,name'])
                ->limit(5)
                ->get()
                ->map(function ($row) {
                    return [
                        'name' => optional($row->player)->name ?? 'Unknown',
                        'goals' => (int) $row->goals,
                    ];
                });

            $topTeamsWins = DB::table('tournament_teams as tt')
                ->select('tt.team_id', DB::raw('SUM(tt.wins) as wins'))
                ->groupBy('tt.team_id')
                ->orderByDesc('wins')
                ->limit(5)
                ->get();

            $teamNames = Team::whereIn('id', $topTeamsWins->pluck('team_id')->all())
                ->pluck('name', 'id');

            $topTeams = $topTeamsWins->map(function ($row) use ($teamNames) {
                return [
                    'name' => $teamNames[$row->team_id] ?? 'Unknown',
                    'wins' => (int) $row->wins,
                ];
            });

            $labels = [];
            $usersSeries = [];
            $teamsSeries = [];
            $playersSeries = [];
            $matchesSeries = [];
            for ($i = 13; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $labels[] = now()->subDays($i)->format('d M');
                $usersSeries[] = DB::table('users')->whereDate('created_at', $date)->count();
                $teamsSeries[] = DB::table('teams')->whereDate('created_at', $date)->count();
                $playersSeries[] = DB::table('players')->whereDate('created_at', $date)->count();
                $matchesSeries[] = DB::table('matches')->whereDate('created_at', $date)->count();
            }

            $usersByRole = DB::table('roles')
                ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
                ->select('roles.name', DB::raw('COUNT(user_roles.user_id) as total'))
                ->groupBy('roles.id', 'roles.name')
                ->orderBy('roles.name')
                ->get();

            $recentMatches = MatchModel::with(['homeTeam:id,name', 'awayTeam:id,name'])
                ->orderByDesc('scheduled_at')
                ->limit(10)
                ->get(['id', 'home_team_id', 'away_team_id', 'scheduled_at', 'status', 'home_score', 'away_score']);

            return [
                'summary' => [
                    'users' => $usersCount,
                    'tournaments' => [
                        'total' => $tournamentsTotal,
                        'upcoming' => $tournamentsUpcoming,
                        'ongoing' => $tournamentsOngoing,
                        'completed' => $tournamentsCompleted,
                    ],
                    'teams' => $teamsCount,
                    'players' => $playersCount,
                    'matches' => [
                        'total' => $matchesTotal,
                        'live' => $matchesLive,
                        'upcoming' => $matchesUpcoming,
                        'completed' => $matchesCompleted,
                    ],
                ],
                'charts' => [
                    'tournamentsStatus' => [
                        'labels' => ['Upcoming', 'Ongoing', 'Completed'],
                        'series' => [
                            $tournamentsUpcoming,
                            $tournamentsOngoing,
                            $tournamentsCompleted,
                        ],
                    ],
                    'matchesStatus' => [
                        'labels' => ['Upcoming', 'Live', 'Completed'],
                        'series' => [
                            $matchesUpcoming,
                            $matchesLive,
                            $matchesCompleted,
                        ],
                    ],
                    'topScorers' => [
                        'categories' => $topScorers->pluck('name'),
                        'series' => $topScorers->pluck('goals'),
                    ],
                    'topTeams' => [
                        'categories' => $topTeams->pluck('name'),
                        'series' => $topTeams->pluck('wins'),
                    ],
                    'activity' => [
                        'labels' => $labels,
                        'series' => [
                            ['name' => 'Users', 'data' => $usersSeries],
                            ['name' => 'Teams', 'data' => $teamsSeries],
                            ['name' => 'Players', 'data' => $playersSeries],
                            ['name' => 'Matches', 'data' => $matchesSeries],
                        ],
                    ],
                    'usersByRole' => [
                        'labels' => $usersByRole->pluck('name'),
                        'series' => $usersByRole->pluck('total'),
                    ],
                ],
                'recent' => [
                    'matches' => $recentMatches->map(function ($m) {
                        return [
                            'id' => $m->id,
                            'home_team' => optional($m->homeTeam)->name,
                            'away_team' => optional($m->awayTeam)->name,
                            'scheduled_at' => optional($m->scheduled_at)->toDateTimeString(),
                            'status' => $m->status,
                            'score' => is_null($m->home_score) || is_null($m->away_score)
                                ? null
                                : ($m->home_score . ' - ' . $m->away_score),
                        ];
                    }),
                ],
            ];
        });

        return response()->json($data);
    }
}



<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StatisticsController extends Controller
{
    public function index()
    {
        try {
            // Get tournaments statistics
            $tournamentsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments');

            $tournaments = [];
            if ($tournamentsResponse->successful()) {
                $tournaments = $tournamentsResponse->json()['data']['data'] ?? [];
            }

            // Get teams statistics
            $teamsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/teams');

            $teams = [];
            if ($teamsResponse->successful()) {
                $teams = $teamsResponse->json()['data']['data'] ?? [];
            }

            // Get players statistics
            $playersResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/players');

            $players = [];
            if ($playersResponse->successful()) {
                $players = $playersResponse->json()['data']['data'] ?? [];
            }

            // Get matches statistics
            $matchesResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/matches');

            $matches = [];
            if ($matchesResponse->successful()) {
                $matches = $matchesResponse->json()['data']['data'] ?? [];
            }

            $statistics = [
                'tournaments' => [
                    'total' => count($tournaments),
                    'by_status' => [
                        'upcoming' => collect($tournaments)->where('status', 'upcoming')->count(),
                        'ongoing' => collect($tournaments)->where('status', 'ongoing')->count(),
                        'completed' => collect($tournaments)->where('status', 'completed')->count(),
                    ]
                ],
                'teams' => [
                    'total' => count($teams),
                    'by_city' => collect($teams)->groupBy('city')->map->count(),
                    'top_rated' => collect($teams)->sortByDesc('rating')->take(10)->values(),
                    'by_trophies' => collect($teams)->sortByDesc('trophies_count')->take(10)->values(),
                ],
                'players' => [
                    'total' => count($players),
                    'by_position' => [
                        'Forward' => collect($players)->where('position', 'Forward')->count(),
                        'Midfielder' => collect($players)->where('position', 'Midfielder')->count(),
                        'Defender' => collect($players)->where('position', 'Defender')->count(),
                        'Goalkeeper' => collect($players)->where('position', 'Goalkeeper')->count(),
                    ],
                    'top_scorers' => collect($players)->sortByDesc('goals_scored')->take(10)->values(),
                    'top_assists' => collect($players)->sortByDesc('assists')->take(10)->values(),
                    'top_rated' => collect($players)->sortByDesc('rating')->take(10)->values(),
                ],
                'matches' => [
                    'total' => count($matches),
                    'by_status' => [
                        'scheduled' => collect($matches)->where('status', 'scheduled')->count(),
                        'live' => collect($matches)->where('status', 'live')->count(),
                        'completed' => collect($matches)->where('status', 'completed')->count(),
                        'cancelled' => collect($matches)->where('status', 'cancelled')->count(),
                    ],
                    'by_stage' => [
                        'group' => collect($matches)->where('stage', 'group')->count(),
                        'round_of_16' => collect($matches)->where('stage', 'round_of_16')->count(),
                        'quarter_final' => collect($matches)->where('stage', 'quarter_final')->count(),
                        'semi_final' => collect($matches)->where('stage', 'semi_final')->count(),
                        'final' => collect($matches)->where('stage', 'final')->count(),
                    ]
                ]
            ];

            return view('fuma.statistics.index', compact('statistics'));
        } catch (\Exception $e) {
            return view('fuma.statistics.index', [
                'statistics' => [
                    'tournaments' => ['total' => 0, 'by_status' => ['upcoming' => 0, 'ongoing' => 0, 'completed' => 0]],
                    'teams' => ['total' => 0, 'by_city' => [], 'top_rated' => [], 'by_trophies' => []],
                    'players' => ['total' => 0, 'by_position' => ['Forward' => 0, 'Midfielder' => 0, 'Defender' => 0, 'Goalkeeper' => 0], 'top_scorers' => [], 'top_assists' => [], 'top_rated' => []],
                    'matches' => ['total' => 0, 'by_status' => ['scheduled' => 0, 'live' => 0, 'completed' => 0, 'cancelled' => 0], 'by_stage' => ['group' => 0, 'round_of_16' => 0, 'quarter_final' => 0, 'semi_final' => 0, 'final' => 0]]
                ]
            ]);
        }
    }
}

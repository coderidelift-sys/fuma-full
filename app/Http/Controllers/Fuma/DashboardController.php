<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Get statistics from API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments');

            $tournaments = [];
            if ($response->successful()) {
                $tournaments = $response->json()['data']['data'] ?? [];
            }

            // Get teams count
            $teamsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/teams');

            $teams = [];
            if ($teamsResponse->successful()) {
                $teams = $teamsResponse->json()['data']['data'] ?? [];
            }

            // Get players count
            $playersResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/players');

            $players = [];
            if ($playersResponse->successful()) {
                $players = $playersResponse->json()['data']['data'] ?? [];
            }

            // Get matches count
            $matchesResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/matches');

            $matches = [];
            if ($matchesResponse->successful()) {
                $matches = $matchesResponse->json()['data']['data'] ?? [];
            }

            $stats = [
                'tournaments' => [
                    'total' => count($tournaments),
                    'ongoing' => collect($tournaments)->where('status', 'ongoing')->count(),
                    'upcoming' => collect($tournaments)->where('status', 'upcoming')->count(),
                    'completed' => collect($tournaments)->where('status', 'completed')->count(),
                ],
                'teams' => [
                    'total' => count($teams),
                    'top_rated' => collect($teams)->sortByDesc('rating')->take(5)->values(),
                ],
                'players' => [
                    'total' => count($players),
                    'top_scorers' => collect($players)->sortByDesc('goals_scored')->take(5)->values(),
                ],
                'matches' => [
                    'total' => count($matches),
                    'upcoming' => collect($matches)->where('status', 'scheduled')->count(),
                    'live' => collect($matches)->where('status', 'live')->count(),
                    'completed' => collect($matches)->where('status', 'completed')->count(),
                ]
            ];

            return view('fuma.dashboard', compact('stats'));
        } catch (\Exception $e) {
            return view('fuma.dashboard', [
                'stats' => [
                    'tournaments' => ['total' => 0, 'ongoing' => 0, 'upcoming' => 0, 'completed' => 0],
                    'teams' => ['total' => 0, 'top_rated' => []],
                    'players' => ['total' => 0, 'top_scorers' => []],
                    'matches' => ['total' => 0, 'upcoming' => 0, 'live' => 0, 'completed' => 0],
                ]
            ]);
        }
    }
}

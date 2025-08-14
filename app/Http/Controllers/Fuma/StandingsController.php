<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StandingsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $tournamentId = $request->get('tournament_id');

            if ($tournamentId) {
                // Get specific tournament standings
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . session('fuma_token'),
                    'Accept' => 'application/json'
                ])->get(config('app.url') . '/api/tournaments/' . $tournamentId . '/standings');

                if ($response->successful()) {
                    $standings = $response->json()['data'];
                    $tournament = $standings['tournament'] ?? null;
                    $teams = $standings['teams'] ?? [];

                    return view('fuma.standings.show', compact('standings', 'tournament', 'teams'));
                }
            }

            // Get all tournaments for selection
            $tournamentsResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments');

            $tournaments = [];
            if ($tournamentsResponse->successful()) {
                $tournaments = $tournamentsResponse->json()['data']['data'] ?? [];
            }

            return view('fuma.standings.index', compact('tournaments'));
        } catch (\Exception $e) {
            return view('fuma.standings.index', ['tournaments' => []]);
        }
    }
}

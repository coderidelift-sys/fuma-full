<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/matches', [
                'page' => $request->get('page', 1),
                'tournament_id' => $request->get('tournament_id'),
                'status' => $request->get('status'),
                'stage' => $request->get('stage')
            ]);

            if ($response->successful()) {
                $matches = $response->json()['data'];
                return view('fuma.matches.index', compact('matches'));
            }

            return view('fuma.matches.index', ['matches' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.matches.index', ['matches' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.matches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'stage' => 'required|in:group,round_of_16,quarter_final,semi_final,final',
            'scheduled_at' => 'required|date|after:now',
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/matches', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.matches.index')
                    ->with('success', 'Match created successfully!');
            }

            return back()->with('error', 'Failed to create match: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating match: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/matches/' . $id);

            if ($response->successful()) {
                $match = $response->json()['data'];
                return view('fuma.matches.show', compact('match'));
            }

            return redirect()->route('fuma.matches.index')
                ->with('error', 'Match not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.matches.index')
                ->with('error', 'Error loading match: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/matches/' . $id);

            if ($response->successful()) {
                $match = $response->json()['data'];
                return view('fuma.matches.edit', compact('match'));
            }

            return redirect()->route('fuma.matches.index')
                ->with('error', 'Match not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.matches.index')
                ->with('error', 'Error loading match: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tournament_id' => 'sometimes|required|exists:tournaments,id',
            'home_team_id' => 'sometimes|required|exists:teams,id',
            'away_team_id' => 'sometimes|required|exists:teams,id|different:home_team_id',
            'stage' => 'sometimes|required|in:group,round_of_16,quarter_final,semi_final,final',
            'status' => 'sometimes|required|in:scheduled,live,completed,cancelled',
            'scheduled_at' => 'sometimes|required|date',
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/matches/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.matches.index')
                    ->with('success', 'Match updated successfully!');
            }

            return back()->with('error', 'Failed to update match: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating match: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/matches/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.matches.index')
                    ->with('success', 'Match deleted successfully!');
            }

            return back()->with('error', 'Failed to delete match: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting match: ' . $e->getMessage());
        }
    }

    public function addEvent(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:goal,yellow_card,red_card,substitution,injury,other',
            'minute' => 'required|integer|min:1|max:120',
            'player_id' => 'nullable|exists:players,id',
            'description' => 'nullable|string',
            'metadata' => 'nullable|json'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/matches/' . $id . '/events', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.matches.show', $id)
                    ->with('success', 'Match event added successfully!');
            }

            return back()->with('error', 'Failed to add event: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding event: ' . $e->getMessage());
        }
    }

    public function updateScore(Request $request, $id)
    {
        $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/matches/' . $id . '/score', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.matches.show', $id)
                    ->with('success', 'Match score updated successfully!');
            }

            return back()->with('error', 'Failed to update score: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating score: ' . $e->getMessage());
        }
    }
}

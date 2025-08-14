<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/players', [
                'page' => $request->get('page', 1),
                'position' => $request->get('position'),
                'team_id' => $request->get('team_id'),
                'min_rating' => $request->get('min_rating')
            ]);

            if ($response->successful()) {
                $players = $response->json()['data'];
                return view('fuma.players.index', compact('players'));
            }

            return view('fuma.players.index', ['players' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.players.index', ['players' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.players.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'team_id' => 'nullable|exists:teams,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/players', $data);

            if ($response->successful()) {
                return redirect()->route('fuma.players.index')
                    ->with('success', 'Player created successfully!');
            }

            return back()->with('error', 'Failed to create player: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating player: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/players/' . $id);

            if ($response->successful()) {
                $player = $response->json()['data'];
                return view('fuma.players.show', compact('player'));
            }

            return redirect()->route('fuma.players.index')
                ->with('error', 'Player not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.players.index')
                ->with('error', 'Error loading player: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/players/' . $id);

            if ($response->successful()) {
                $player = $response->json()['data'];
                return view('fuma.players.edit', compact('player'));
            }

            return redirect()->route('fuma.players.index')
                ->with('error', 'Player not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.players.index')
                ->with('error', 'Error loading player: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'team_id' => 'nullable|exists:teams,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->except('avatar');

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/players/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('fuma.players.index')
                    ->with('success', 'Player updated successfully!');
            }

            return back()->with('error', 'Failed to update player: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating player: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/players/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.players.index')
                    ->with('success', 'Player deleted successfully!');
            }

            return back()->with('error', 'Failed to delete player: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting player: ' . $e->getMessage());
        }
    }

    public function updateStats(Request $request, $id)
    {
        $request->validate([
            'goals_scored' => 'nullable|integer|min:0',
            'assists' => 'nullable|integer|min:0',
            'clean_sheets' => 'nullable|integer|min:0',
            'yellow_cards' => 'nullable|integer|min:0',
            'red_cards' => 'nullable|integer|min:0',
            'rating' => 'nullable|numeric|min:0|max:5'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/players/' . $id . '/stats', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.players.show', $id)
                    ->with('success', 'Player statistics updated successfully!');
            }

            return back()->with('error', 'Failed to update statistics: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating statistics: ' . $e->getMessage());
        }
    }
}

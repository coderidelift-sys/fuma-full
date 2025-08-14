<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TournamentController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments', [
                'page' => $request->get('page', 1),
                'status' => $request->get('status'),
                'organizer_id' => $request->get('organizer_id')
            ]);

            if ($response->successful()) {
                $tournaments = $response->json()['data'];
                return view('fuma.tournaments.index', compact('tournaments'));
            }

            return view('fuma.tournaments.index', ['tournaments' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.tournaments.index', ['tournaments' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.tournaments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'max_teams' => 'required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();

            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/tournaments', $data);

            if ($response->successful()) {
                return redirect()->route('fuma.tournaments.index')
                    ->with('success', 'Tournament created successfully!');
            }

            return back()->with('error', 'Failed to create tournament: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating tournament: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments/' . $id);

            if ($response->successful()) {
                $tournament = $response->json()['data'];
                return view('fuma.tournaments.show', compact('tournament'));
            }

            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Tournament not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Error loading tournament: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments/' . $id);

            if ($response->successful()) {
                $tournament = $response->json()['data'];
                return view('fuma.tournaments.edit', compact('tournament'));
            }

            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Tournament not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Error loading tournament: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'max_teams' => 'sometimes|required|integer|min:2|max:64',
            'venue' => 'nullable|string|max:255',
            'status' => 'sometimes|required|in:upcoming,ongoing,completed',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->except('logo');

            if ($request->hasFile('logo')) {
                $data['logo'] = $request->file('logo');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/tournaments/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('fuma.tournaments.index')
                    ->with('success', 'Tournament updated successfully!');
            }

            return back()->with('error', 'Failed to update tournament: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating tournament: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/tournaments/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.tournaments.index')
                    ->with('success', 'Tournament deleted successfully!');
            }

            return back()->with('error', 'Failed to delete tournament: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting tournament: ' . $e->getMessage());
        }
    }

    public function standings($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/tournaments/' . $id . '/standings');

            if ($response->successful()) {
                $standings = $response->json()['data'];
                return view('fuma.tournaments.standings', compact('standings', 'id'));
            }

            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Failed to load standings');
        } catch (\Exception $e) {
            return redirect()->route('fuma.tournaments.index')
                ->with('error', 'Error loading standings: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/teams', [
                'page' => $request->get('page', 1),
                'city' => $request->get('city'),
                'min_rating' => $request->get('min_rating')
            ]);

            if ($response->successful()) {
                $teams = $response->json()['data'];
                return view('fuma.teams.index', compact('teams'));
            }

            return view('fuma.teams.index', ['teams' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.teams.index', ['teams' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
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
            ])->post(config('app.url') . '/api/teams', $data);

            if ($response->successful()) {
                return redirect()->route('fuma.teams.index')
                    ->with('success', 'Team created successfully!');
            }

            return back()->with('error', 'Failed to create team: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating team: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/teams/' . $id);

            if ($response->successful()) {
                $team = $response->json()['data'];
                return view('fuma.teams.show', compact('team'));
            }

            return redirect()->route('fuma.teams.index')
                ->with('error', 'Team not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.teams.index')
                ->with('error', 'Error loading team: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/teams/' . $id);

            if ($response->successful()) {
                $team = $response->json()['data'];
                return view('fuma.teams.edit', compact('team'));
            }

            return redirect()->route('fuma.teams.index')
                ->with('error', 'Team not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.teams.index')
                ->with('error', 'Error loading team: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'city' => 'sometimes|required|string|max:255',
            'country' => 'nullable|string|max:255',
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'manager_email' => 'nullable|email|max:255',
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
            ])->put(config('app.url') . '/api/teams/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('fuma.teams.index')
                    ->with('success', 'Team updated successfully!');
            }

            return back()->with('error', 'Failed to update team: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating team: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/teams/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.teams.index')
                    ->with('success', 'Team deleted successfully!');
            }

            return back()->with('error', 'Failed to delete team: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting team: ' . $e->getMessage());
        }
    }

    public function addPlayer(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|in:Forward,Midfielder,Defender,Goalkeeper',
            'jersey_number' => 'nullable|string|max:10',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'height' => 'nullable|numeric|min:100|max:250',
            'weight' => 'nullable|numeric|min:30|max:150',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();
            $data['team_id'] = $id;

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/teams/' . $id . '/players', $data);

            if ($response->successful()) {
                return redirect()->route('fuma.teams.show', $id)
                    ->with('success', 'Player added to team successfully!');
            }

            return back()->with('error', 'Failed to add player: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding player: ' . $e->getMessage());
        }
    }
}

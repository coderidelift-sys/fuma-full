<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/committees', [
                'page' => $request->get('page', 1),
                'tournament_id' => $request->get('tournament_id'),
                'position' => $request->get('position'),
                'status' => $request->get('status')
            ]);

            if ($response->successful()) {
                $committees = $response->json()['data'];
                return view('fuma.committees.index', compact('committees'));
            }

            return view('fuma.committees.index', ['committees' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.committees.index', ['committees' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.committees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required|exists:tournaments,id',
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/committees', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.committees.index')
                    ->with('success', 'Committee member added successfully!');
            }

            return back()->with('error', 'Failed to add committee member: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error adding committee member: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/committees/' . $id);

            if ($response->successful()) {
                $committee = $response->json()['data'];
                return view('fuma.committees.show', compact('committee'));
            }

            return redirect()->route('fuma.committees.index')
                ->with('error', 'Committee member not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.committees.index')
                ->with('error', 'Error loading committee member: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/committees/' . $id);

            if ($response->successful()) {
                $committee = $response->json()['data'];
                return view('fuma.committees.edit', compact('committee'));
            }

            return redirect()->route('fuma.committees.index')
                ->with('error', 'Committee member not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.committees.index')
                ->with('error', 'Error loading committee member: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tournament_id' => 'sometimes|required|exists:tournaments,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'position' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,inactive'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/committees/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.committees.index')
                    ->with('success', 'Committee member updated successfully!');
            }

            return back()->with('error', 'Failed to update committee member: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating committee member: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/committees/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.committees.index')
                    ->with('success', 'Committee member removed successfully!');
            }

            return back()->with('error', 'Failed to remove committee member: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing committee member: ' . $e->getMessage());
        }
    }
}

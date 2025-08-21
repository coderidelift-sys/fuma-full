<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Events\MatchStatusUpdated;
use App\Events\MatchScoreUpdated;
use App\Events\MatchMinuteUpdated;

class MatchController extends Controller
{
    public function index()
    {
        return view('console.matches.index');
    }

    public function data(Request $request)
    {
        $query = MatchModel::query()
            ->select(['id', 'tournament_id', 'home_team_id', 'away_team_id', 'scheduled_at', 'status', 'home_score', 'away_score'])
            ->with([
                'homeTeam:id,name',
                'awayTeam:id,name',
                'tournament:id,name',
            ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tournament_id')) {
            $query->where('tournament_id', $request->tournament_id);
        }

        return datatables()->eloquent($query)->toJson();
    }

    public function update(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'scheduled_at' => 'nullable|date',
            'status' => 'nullable|in:scheduled,live,paused,completed',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($match, $validated) {
            $match->update($validated);
            // Invalidate cached stats snapshot if any
            Cache::forget("match_stats_{$match->id}");
            // Broadcast score change when relevant
            if (array_key_exists('home_score', $validated) || array_key_exists('away_score', $validated)) {
                $fresh = $match->fresh(['homeTeam:id,name', 'awayTeam:id,name']);
                MatchScoreUpdated::dispatch($fresh->id, (int) ($fresh->home_score ?? 0), (int) ($fresh->away_score ?? 0));
            }
            // Broadcast status change when relevant
            if (array_key_exists('status', $validated)) {
                MatchStatusUpdated::dispatch($match->id, (string) $validated['status']);
            }
        });

        return response()->json(['message' => 'Match updated']);
    }

    public function updateStatus(Request $request, MatchModel $match)
    {
        $validated = $request->validate([
            'action' => 'required|in:start,pause,resume,complete',
        ]);

        DB::transaction(function () use ($match, $validated) {
            $now = now();
            switch ($validated['action']) {
                case 'start':
                    $match->update(['status' => 'live', 'started_at' => $now]);
                    MatchStatusUpdated::dispatch($match->id, 'live');
                    break;
                case 'pause':
                    $match->update(['status' => 'paused', 'paused_at' => $now]);
                    MatchStatusUpdated::dispatch($match->id, 'paused');
                    break;
                case 'resume':
                    $match->update(['status' => 'live', 'resumed_at' => $now]);
                    MatchStatusUpdated::dispatch($match->id, 'live');
                    break;
                case 'complete':
                    $match->update(['status' => 'completed', 'completed_at' => $now]);
                    MatchStatusUpdated::dispatch($match->id, 'completed');
                    break;
            }
            Cache::forget("match_stats_{$match->id}");
        });

        return response()->json(['message' => 'Match status updated']);
    }

    public function destroy(MatchModel $match)
    {
        DB::transaction(function () use ($match) {
            $match->delete();
        });

        return response()->json(['message' => 'Match deleted']);
    }
}



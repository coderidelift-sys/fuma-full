<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\MatchEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatchController extends Controller
{
    public function index(Request $request)
    {
        $query = MatchModel::select([
            'id','tournament_id','home_team_id','away_team_id','stage','status','scheduled_at','venue','home_score','away_score','current_minute'
        ])->with([
            'tournament:id,name',
            'homeTeam:id,name,short_name,logo',
            'awayTeam:id,name,short_name,logo'
        ]);

        // Filter by tournament
        if ($request->has('tournament_id')) {
            $query->where('tournament_id', $request->tournament_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by stage
        if ($request->has('stage')) {
            $query->where('stage', $request->stage);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('scheduled_at', $request->date);
        }

        // Filter upcoming matches
        if ($request->has('upcoming') && $request->upcoming) {
            $query->where('status', 'scheduled')->where('scheduled_at', '>', now());
        }

        // Filter live matches
        if ($request->has('live') && $request->live) {
            $query->where('status', 'live');
        }

        $matches = $query->orderBy('scheduled_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $matches
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tournament_id' => 'required|exists:tournaments,id',
            'home_team_id' => 'required|exists:teams,id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'stage' => 'required|in:group,round_of_16,quarter_final,semi_final,final',
            'scheduled_at' => 'required|date',
            'venue' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $match = MatchModel::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'MatchModel created successfully',
            'data' => $match->load(['tournament', 'homeTeam', 'awayTeam'])
        ], 201);
    }

    public function show($id)
    {
        $match = MatchModel::with([
            'tournament:id,name',
            'homeTeam:id,name,short_name,logo',
            'awayTeam:id,name,short_name,logo',
            'events' => fn($q) => $q->select(['id','match_id','player_id','team_id','type','minute','description'])
                ->with(['player:id,name'])
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $match
        ]);
    }

    public function update(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'stage' => 'sometimes|required|in:group,round_of_16,quarter_final,semi_final,final',
            'status' => 'sometimes|required|in:scheduled,live,completed,cancelled',
            'scheduled_at' => 'sometimes|required|date',
            'venue' => 'nullable|string|max:255',
            'home_score' => 'nullable|integer|min:0',
            'away_score' => 'nullable|integer|min:0',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $match->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'MatchModel updated successfully',
            'data' => $match->load([
                'tournament:id,name',
                'homeTeam:id,name,short_name,logo',
                'awayTeam:id,name,short_name,logo'
            ])
        ]);
    }

    public function destroy($id)
    {
        $match = MatchModel::findOrFail($id);
        $match->delete();

        return response()->json([
            'success' => true,
            'message' => 'MatchModel deleted successfully'
        ]);
    }

    public function addEvent(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'player_id' => 'nullable|exists:players,id',
            'type' => 'required|in:goal,yellow_card,red_card,substitution,injury,other',
            'minute' => 'required|integer|min:1|max:120',
            'description' => 'nullable|string',
            'metadata' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $event = MatchEvent::create([
            'match_id' => $match->id,
            'player_id' => $request->player_id,
            'type' => $request->type,
            'minute' => $request->minute,
            'description' => $request->description,
            'metadata' => $request->metadata
        ]);

        return response()->json([
            'success' => true,
            'message' => 'MatchModel event added successfully',
            'data' => $event->load('player')
        ], 201);
    }

    public function updateScore(Request $request, $id)
    {
        $match = MatchModel::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $match->update([
            'home_score' => $request->home_score,
            'away_score' => $request->away_score,
            'status' => 'completed'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'MatchModel score updated successfully',
            'data' => $match->load([
                'tournament:id,name',
                'homeTeam:id,name,short_name,logo',
                'awayTeam:id,name,short_name,logo'
            ])
        ]);
    }
}

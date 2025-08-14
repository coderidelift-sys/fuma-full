<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Committee;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommitteeController extends Controller
{
    public function index(Request $request)
    {
        $query = Committee::with(['tournament', 'user']);

        if ($request->has('tournament_id')) {
            $query->where('tournament_id', $request->tournament_id);
        }

        if ($request->has('position')) {
            $query->where('position', $request->position);
        }

        $committees = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $committees
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tournament_id' => 'required|exists:tournaments,id',
            'user_id' => 'required|exists:users,id',
            'position' => 'required|string|max:255',
            'status' => 'nullable|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user is already in committee for this tournament
        if (Committee::where('tournament_id', $request->tournament_id)
                    ->where('user_id', $request->user_id)
                    ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is already in committee for this tournament'
            ], 400);
        }

        $committee = Committee::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Committee member added successfully',
            'data' => $committee->load(['tournament', 'user'])
        ], 201);
    }

    public function show($id)
    {
        $committee = Committee::with(['tournament', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $committee
        ]);
    }

    public function update(Request $request, $id)
    {
        $committee = Committee::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'position' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $committee->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Committee member updated successfully',
            'data' => $committee->load(['tournament', 'user'])
        ]);
    }

    public function destroy($id)
    {
        $committee = Committee::findOrFail($id);
        $committee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Committee member removed successfully'
        ]);
    }
}

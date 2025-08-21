<?php

namespace App\Http\Controllers;

use App\Models\MatchCommentary;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MatchCommentaryController extends Controller
{
    /**
     * Get commentary for a specific match
     */
    public function getMatchCommentary(MatchModel $matchModel): JsonResponse
    {
        $commentary = MatchCommentary::byMatch($matchModel->id)
            ->with(['user:id,name,avatar'])
            ->orderedByMinute()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentary
        ]);
    }

    /**
     * Add new commentary
     */
    public function store(Request $request, MatchModel $match): JsonResponse
    {
        if (!$match) {
            return response()->json([
                'success' => false,
                'message' => 'Match not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'minute' => 'required|integer|min:0|max:120',
            'commentary_type' => 'required|in:general,tactical,incident,highlight,warning',
            'description' => 'required|string|max:1000',
            'is_important' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user has permission to add commentary
        $user = Auth::user();
        $userRole = $this->getUserRole($user, $match);

        // if (!$userRole) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You do not have permission to add commentary for this match'
        //     ], 403);
        // }

        $commentary = MatchCommentary::create([
            'match_id' => $match->id,
            'user_id' => $user->id ?? 1,
            'user_role' => $userRole ?? 'commentator',
            'minute' => $request->minute,
            'commentary_type' => $request->commentary_type,
            'description' => $request->description,
            'is_important' => $request->boolean('is_important', false)
        ]);

        $commentary->load(['user:id,name,avatar']);

        return response()->json([
            'success' => true,
            'message' => 'Commentary added successfully',
            'data' => $commentary
        ], 201);
    }

    /**
     * Update existing commentary
     */
    public function update(Request $request, MatchModel $matchModel, MatchCommentary $commentary): JsonResponse
    {
        // Check if user can edit this commentary
        $user = Auth::user();
        if ($commentary->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own commentary'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'minute' => 'sometimes|integer|min:0|max:120',
            'commentary_type' => 'sometimes|in:general,tactical,incident,highlight,warning',
            'description' => 'sometimes|string|max:1000',
            'is_important' => 'sometimes|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $commentary->update($request->only(['minute', 'commentary_type', 'description', 'is_important']));
        $commentary->load(['user:id,name,avatar']);

        return response()->json([
            'success' => true,
            'message' => 'Commentary updated successfully',
            'data' => $commentary
        ]);
    }

    /**
     * Delete commentary
     */
    public function destroy(MatchModel $matchModel, MatchCommentary $commentary): JsonResponse
    {
        // Check if user can delete this commentary
        $user = Auth::user();
        if ($commentary->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own commentary'
            ], 403);
        }

        $commentary->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentary deleted successfully'
        ]);
    }

    /**
     * Get commentary by type
     */
    public function getByType(MatchModel $matchModel, string $type): JsonResponse
    {
        $commentary = MatchCommentary::byMatch($matchModel->id)
            ->byType($type)
            ->with(['user:id,name,avatar'])
            ->orderedByMinute()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentary
        ]);
    }

    /**
     * Get important commentary
     */
    public function getImportant(MatchModel $matchModel): JsonResponse
    {
        $commentary = MatchCommentary::byMatch($matchModel->id)
            ->important()
            ->with(['user:id,name,avatar'])
            ->orderedByMinute()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentary
        ]);
    }

    /**
     * Get commentary by minute range
     */
    public function getByMinuteRange(MatchModel $matchModel, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'start_minute' => 'required|integer|min:0',
            'end_minute' => 'required|integer|min:0|gte:start_minute'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $commentary = MatchCommentary::byMatch($matchModel->id)
            ->whereBetween('minute', [$request->start_minute, $request->end_minute])
            ->with(['user:id,name,avatar'])
            ->orderedByMinute()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $commentary
        ]);
    }

    /**
     * Determine user role for commentary
     */
    private function getUserRole($user, $matchModel): ?string
    {
        if (!$user) {
            return null;
        }

        // Admin can add commentary as any role
        if ($user->hasRole('admin')) {
            return 'admin';
        }

        // Referee can add referee commentary
        if ($user->hasRole('referee')) {
            return 'referee';
        }

        // Match officials can add official commentary
        if ($user->hasRole('match_official') || $user->hasRole('organizer')) {
            return 'match_official';
        }

        // Commentators can add commentary
        if ($user->hasRole('commentator')) {
            return 'commentator';
        }

        // Check if user is assigned to this match as referee
        if ($matchModel->referee && $matchModel->referee === $user->name) {
            return 'referee';
        }

        return null;
    }
}

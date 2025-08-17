<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Store a newly created player
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'position' => 'required|string|in:Goalkeeper,Defender,Midfielder,Forward',
            'jersey_number' => 'nullable',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'team_id' => 'required|exists:teams,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('players/avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        // Set default nationality if not provided
        if (empty($data['nationality'])) {
            $data['nationality'] = 'Indonesia';
        }

        Player::create($data);

        return response()->json(['success' => true, 'message' => 'Player added successfully']);
    }
}

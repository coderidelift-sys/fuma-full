<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    public function edit()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/me');

            if ($response->successful()) {
                $user = $response->json()['data'];
                return view('fuma.profile.edit', compact('user'));
            }

            return view('fuma.profile.edit', ['user' => null]);
        } catch (\Exception $e) {
            return view('fuma.profile.edit', ['user' => null]);
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email',
            'whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            $data = $request->except(['current_password', 'new_password_confirmation', 'avatar']);

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            if ($request->filled('new_password')) {
                $data['password'] = $request->new_password;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/profile', $data);

            if ($response->successful()) {
                // Update session data
                $userData = $response->json()['data'];
                session(['fuma_user' => $userData]);

                return redirect()->route('fuma.profile')
                    ->with('success', 'Profile updated successfully!');
            }

            return back()->with('error', 'Failed to update profile: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating profile: ' . $e->getMessage());
        }
    }
}

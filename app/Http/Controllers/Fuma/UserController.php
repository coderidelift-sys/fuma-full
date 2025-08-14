<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/users', [
                'page' => $request->get('page', 1),
                'role' => $request->get('role'),
                'search' => $request->get('search')
            ]);

            if ($response->successful()) {
                $users = $response->json()['data'];
                return view('fuma.users.index', compact('users'));
            }

            return view('fuma.users.index', ['users' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        } catch (\Exception $e) {
            return view('fuma.users.index', ['users' => ['data' => [], 'current_page' => 1, 'last_page' => 1]]);
        }
    }

    public function create()
    {
        return view('fuma.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            $data = $request->except(['password_confirmation', 'avatar']);

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/users', $data);

            if ($response->successful()) {
                return redirect()->route('fuma.users.index')
                    ->with('success', 'User created successfully!');
            }

            return back()->with('error', 'Failed to create user: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/users/' . $id);

            if ($response->successful()) {
                $user = $response->json()['data'];
                return view('fuma.users.show', compact('user'));
            }

            return redirect()->route('fuma.users.index')
                ->with('error', 'User not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.users.index')
                ->with('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/users/' . $id);

            if ($response->successful()) {
                $user = $response->json()['data'];
                return view('fuma.users.edit', compact('user'));
            }

            return redirect()->route('fuma.users.index')
                ->with('error', 'User not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.users.index')
                ->with('error', 'Error loading user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'whatsapp' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'roles' => 'sometimes|required|array|min:1',
            'roles.*' => 'exists:roles,id'
        ]);

        try {
            $data = $request->except(['password_confirmation', 'avatar']);

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $request->file('avatar');
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/users/' . $id, $data);

            if ($response->successful()) {
                return redirect()->route('fuma.users.index')
                    ->with('success', 'User updated successfully!');
            }

            return back()->with('error', 'Failed to update user: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/users/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.users.index')
                    ->with('success', 'User deleted successfully!');
            }

            return back()->with('error', 'Failed to delete user: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}

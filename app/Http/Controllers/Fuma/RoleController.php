<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RoleController extends Controller
{
    public function index()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/roles');

            if ($response->successful()) {
                $roles = $response->json()['data'];
                return view('fuma.roles.index', compact('roles'));
            }

            return view('fuma.roles.index', ['roles' => []]);
        } catch (\Exception $e) {
            return view('fuma.roles.index', ['roles' => []]);
        }
    }

    public function create()
    {
        return view('fuma.roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->post(config('app.url') . '/api/roles', $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.roles.index')
                    ->with('success', 'Role created successfully!');
            }

            return back()->with('error', 'Failed to create role: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating role: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/roles/' . $id);

            if ($response->successful()) {
                $role = $response->json()['data'];
                return view('fuma.roles.show', compact('role'));
            }

            return redirect()->route('fuma.roles.index')
                ->with('error', 'Role not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.roles.index')
                ->with('error', 'Error loading role: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->get(config('app.url') . '/api/roles/' . $id);

            if ($response->successful()) {
                $role = $response->json()['data'];
                return view('fuma.roles.edit', compact('role'));
            }

            return redirect()->route('fuma.roles.index')
                ->with('error', 'Role not found');
        } catch (\Exception $e) {
            return redirect()->route('fuma.roles.index')
                ->with('error', 'Error loading role: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'display_name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->put(config('app.url') . '/api/roles/' . $id, $request->all());

            if ($response->successful()) {
                return redirect()->route('fuma.roles.index')
                    ->with('success', 'Role updated successfully!');
            }

            return back()->with('error', 'Failed to update role: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating role: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . session('fuma_token'),
                'Accept' => 'application/json'
            ])->delete(config('app.url') . '/api/roles/' . $id);

            if ($response->successful()) {
                return redirect()->route('fuma.roles.index')
                    ->with('success', 'Role deleted successfully!');
            }

            return back()->with('error', 'Failed to delete role: ' . ($response->json()['message'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting role: ' . $e->getMessage());
        }
    }
}

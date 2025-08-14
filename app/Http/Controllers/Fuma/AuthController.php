<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('fuma_token')) {
            return redirect()->route('fuma.dashboard');
        }
        return view('fuma.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        try {
            $response = Http::post(config('app.url') . '/api/login', [
                'email' => $request->email,
                'password' => $request->password
            ]);

            if ($response->successful()) {
                $data = $response->json()['data'];
                session(['fuma_token' => $data['token']]);
                session(['fuma_user' => $data['user']]);

                return redirect()->route('fuma.dashboard')
                    ->with('success', 'Welcome to FUMA Backoffice!');
            }

            return back()->with('error', 'Invalid credentials');
        } catch (\Exception $e) {
            return back()->with('error', 'Login failed: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        try {
            if (session('fuma_token')) {
                Http::withHeaders([
                    'Authorization' => 'Bearer ' . session('fuma_token'),
                    'Accept' => 'application/json'
                ])->post(config('app.url') . '/api/logout');
            }
        } catch (\Exception $e) {
            // Ignore logout API errors
        }

        session()->forget(['fuma_token', 'fuma_user']);
        return redirect()->route('fuma.login')
            ->with('success', 'Logged out successfully');
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FumaAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user has FUMA token
        if (!session('fuma_token')) {
            return redirect()->route('fuma.login')
                ->with('error', 'Please login to access FUMA Backoffice');
        }

        // Check if user is authenticated in Laravel
        if (!Auth::check()) {
            session()->forget(['fuma_token', 'fuma_user']);
            return redirect()->route('fuma.login')
                ->with('error', 'Session expired. Please login again');
        }

        return $next($request);
    }
}

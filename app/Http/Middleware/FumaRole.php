<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FumaRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('fuma.login')
                ->with('error', 'Please login to access this page');
        }

        $user = Auth::user();

        if (empty($roles)) {
            return $next($request);
        }

        // Check if user has any of the required roles
        $hasRole = $user->roles()->whereIn('name', $roles)->exists();

        if (!$hasRole) {
            return redirect()->route('fuma.dashboard')
                ->with('error', 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}

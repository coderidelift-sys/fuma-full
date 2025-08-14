<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\School;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $compact = [];
        $view = 'dashboard';

        return view($view, $compact);
    }
}

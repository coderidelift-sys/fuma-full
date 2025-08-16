<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel;
use Illuminate\Http\Request;

class FumaController extends Controller
{
    public function index()
    {
        // Get statistics for the dashboard
        $stats = [
            'active_tournaments' => Tournament::where('status', 'ongoing')->count(),
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => MatchModel::where('status', 'completed')->count(),
        ];

        // Get featured content
        $featured_tournaments = Tournament::with(['teams', 'organizer'])
            ->where('status', 'ongoing')
            ->orWhere('status', 'upcoming')
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        $top_teams = Team::with(['players'])
            ->orderBy('rating', 'desc')
            ->limit(8)
            ->get();

        $upcoming_matches = MatchModel::with(['homeTeam', 'awayTeam', 'tournament'])
            ->where('status', 'scheduled')
            ->where('scheduled_at', '>', now())
            ->orderBy('scheduled_at', 'asc')
            ->limit(6)
            ->get();

        $top_players = Player::with(['team'])
            ->orderBy('rating', 'desc')
            ->limit(8)
            ->get();

        return view('fuma.index', compact(
            'stats',
            'featured_tournaments',
            'top_teams',
            'upcoming_matches',
            'top_players'
        ));
    }
}
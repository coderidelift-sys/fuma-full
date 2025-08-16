<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('fuma.index');
    }

    public function homePageData()
    {
        $quickStats = DB::table('tournaments')
            ->selectRaw("
                (SELECT COUNT(*) FROM tournaments WHERE status = 'ongoing') AS activeTournaments,
                (SELECT COUNT(*) FROM teams) AS registeredTeams,
                (SELECT COUNT(*) FROM players) AS players,
                (SELECT COUNT(*) FROM matches WHERE status = 'completed') AS matchesPlayed
            ")
            ->first();

        $featuredTournaments = Tournament::where('status', 'ongoing')
            ->take(3)
            ->get();

        $topTeams = Team::orderBy('rating', 'desc')
            ->orderBy('trophies_count', 'desc')
            ->take(4)
            ->get();

        $topPlayers = Player::with('team:id,name')
            ->orderBy('rating', 'desc')
            ->orderBy('goals_scored', 'desc')
            ->take(4)
            ->get();

        $upcomingMatches = MatchModel::with(['homeTeam:id,name,logo', 'awayTeam:id,name,logo'])
            ->where('status', 'scheduled')
            ->whereDate('scheduled_at', '>=', Carbon::now()) // hanya tanggal sekarang ke depan
            ->orderBy('scheduled_at', 'asc')                // urut dari yang paling dekat
            ->take(2)
            ->get();

        return response()->json([
            'quickStats' => $quickStats,
            'featuredTournaments' => $featuredTournaments,
            'topTeams' => $topTeams,
            'topPlayers' => $topPlayers,
            'upcomingMatches' => $upcomingMatches
        ]);
    }

    public function tournaments()
    {
        $organizers = User::whereHas('roles', function ($query) {
            $query->where('name', 'organizer');
        })->get();

        return view('fuma.tournaments', compact('organizers'));
    }
}

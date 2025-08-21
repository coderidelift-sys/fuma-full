<?php

namespace App\Http\Controllers\Fuma;

use App\Http\Controllers\Controller;
use App\Models\MatchModel;
use App\Models\Player;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use App\Models\Venue;
use App\Services\CacheService;
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
        // Cache quick stats dengan duration yang sesuai
        $quickStats = CacheService::remember(
            CacheService::key('home', 'quick_stats'),
            'stats',
            fn () => DB::table('tournaments')
                ->selectRaw("
                    (SELECT COUNT(*) FROM tournaments WHERE status = 'ongoing') AS activeTournaments,
                    (SELECT COUNT(*) FROM teams) AS registeredTeams,
                    (SELECT COUNT(*) FROM players) AS players,
                    (SELECT COUNT(*) FROM matches WHERE status = 'completed') AS matchesPlayed
                ")
                ->first()
        );

        // Cache featured tournaments
        $featuredTournaments = CacheService::remember(
            CacheService::key('home', 'featured_tournaments'),
            'ui',
            fn () => Tournament::where('status', 'ongoing')
                ->select('id', 'name', 'logo', 'start_date', 'end_date')
                ->take(3)
                ->get()
        );

        // Cache top teams dengan eager loading
        $topTeams = CacheService::remember(
            CacheService::key('home', 'top_teams'),
            'stats',
            fn () => Team::select('id', 'name', 'logo', 'rating', 'trophies_count')
                ->orderBy('rating', 'desc')
                ->orderBy('trophies_count', 'desc')
                ->take(4)
                ->get()
        );

        // Cache top players dengan eager loading
        $topPlayers = CacheService::remember(
            CacheService::key('home', 'top_players'),
            'stats',
            fn () => Player::select('id', 'name', 'avatar', 'position', 'rating', 'goals_scored', 'team_id')
                ->with(['team:id,name'])
                ->orderBy('rating', 'desc')
                ->orderBy('goals_scored', 'desc')
                ->take(4)
                ->get()
        );

        // Cache upcoming matches
        $upcomingMatches = CacheService::remember(
            CacheService::key('home', 'upcoming_matches'),
            'match_data',
            fn () => MatchModel::select('id', 'home_team_id', 'away_team_id', 'scheduled_at')
                ->with(['homeTeam:id,name,logo', 'awayTeam:id,name,logo'])
                ->where('status', 'scheduled')
                ->whereDate('scheduled_at', '>=', Carbon::now())
                ->orderBy('scheduled_at', 'asc')
                ->take(2)
                ->get()
        );

        // Cache venues
        $venues = CacheService::remember(
            CacheService::key('home', 'top_venues'),
            'ui',
            fn () => Venue::active()
                ->select('id', 'name', 'city', 'capacity')
                ->orderBy('capacity', 'desc')
                ->take(3)
                ->get()
        );

        return response()->json([
            'quickStats' => $quickStats,
            'featuredTournaments' => $featuredTournaments,
            'topTeams' => $topTeams,
            'topPlayers' => $topPlayers,
            'upcomingMatches' => $upcomingMatches,
            'venues' => $venues
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

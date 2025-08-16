<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use App\Models\Team;
use App\Models\Player;
use App\Models\MatchModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FumaController extends Controller
{
    public function index()
    {
        // Get statistics for the homepage
        $stats = [
            'active_tournaments' => Tournament::where('status', 'ongoing')->count(),
            'total_teams' => Team::count(),
            'total_players' => Player::count(),
            'total_matches' => MatchModel::count()
        ];

        // Get recent tournaments
        $recentTournaments = Tournament::with(['organizer', 'teams'])
            ->latest()
            ->take(6)
            ->get();

        // Get top teams
        $topTeams = Team::topRated()
            ->with(['manager'])
            ->take(8)
            ->get();

        // Get recent matches
        $recentMatches = MatchModel::with(['homeTeam', 'awayTeam', 'tournament'])
            ->latest('scheduled_at')
            ->take(6)
            ->get();

        // Get top players
        $topPlayers = Player::topRated()
            ->with(['team'])
            ->take(8)
            ->get();

        return view('fuma.index', compact('stats', 'recentTournaments', 'topTeams', 'recentMatches', 'topPlayers'));
    }

    public function tournaments(Request $request)
    {
        $query = Tournament::with(['organizer', 'teams']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('venue', 'like', '%' . $request->search . '%');
            });
        }

        $tournaments = $query->latest()->paginate(12);

        return view('fuma.tournaments', compact('tournaments'));
    }

    public function tournamentDetail($id)
    {
        $tournament = Tournament::with([
            'organizer',
            'teams',
            'matches.homeTeam',
            'matches.awayTeam',
            'committees.user'
        ])->findOrFail($id);

        $standings = $tournament->standings;

        return view('fuma.tournament-detail', compact('tournament', 'standings'));
    }

    public function teams(Request $request)
    {
        $query = Team::with(['manager', 'players']);

        // Filter by city
        if ($request->has('city') && $request->city !== 'all') {
            $query->byCity($request->city);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('city', 'like', '%' . $request->search . '%')
                  ->orWhere('country', 'like', '%' . $request->search . '%');
            });
        }

        $teams = $query->latest()->paginate(12);

        // Get unique cities for filter
        $cities = Team::distinct()->pluck('city')->filter();

        return view('fuma.teams', compact('teams', 'cities'));
    }

    public function teamDetail($id)
    {
        $team = Team::with(['manager', 'players', 'tournaments', 'homeMatches.awayTeam', 'awayMatches.homeTeam'])
            ->findOrFail($id);

        return view('fuma.team-detail', compact('team'));
    }

    public function players(Request $request)
    {
        $query = Player::with(['team']);

        // Filter by position
        if ($request->has('position') && $request->position !== 'all') {
            $query->byPosition($request->position);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('nationality', 'like', '%' . $request->search . '%')
                  ->orWhereHas('team', function($teamQuery) use ($request) {
                      $teamQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $players = $query->latest()->paginate(12);

        // Get unique positions for filter
        $positions = ['Forward', 'Midfielder', 'Defender', 'Goalkeeper'];

        return view('fuma.players', compact('players', 'positions'));
    }

    public function playerDetail($id)
    {
        $player = Player::with(['team', 'matchEvents.match'])
            ->findOrFail($id);

        return view('fuma.player-detail', compact('player'));
    }

    public function matches(Request $request)
    {
        $query = MatchModel::with(['homeTeam', 'awayTeam', 'tournament']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by tournament
        if ($request->has('tournament_id') && $request->tournament_id !== 'all') {
            $query->where('tournament_id', $request->tournament_id);
        }

        $matches = $query->latest('scheduled_at')->paginate(12);

        // Get tournaments for filter
        $tournaments = Tournament::select('id', 'name')->get();

        return view('fuma.matches', compact('matches', 'tournaments'));
    }

    public function matchDetail($id)
    {
        $match = MatchModel::with([
            'homeTeam.players',
            'awayTeam.players',
            'tournament',
            'events.player'
        ])->findOrFail($id);

        return view('fuma.match-detail', compact('match'));
    }
}
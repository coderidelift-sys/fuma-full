@extends('layouts.fuma')

@section('title', $team->name . ' - Team Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="me-3" width="80" height="80" style="object-fit: contain;">
                        @else
                            <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $team->name }}" class="me-3" width="80" height="80" style="object-fit: contain;">
                        @endif
                        <div>
                            <h1 class="fw-bold mb-1">{{ $team->name }}</h1>
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt me-2"></i>{{ $team->city }}, {{ $team->country }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        @if(auth()->check())
                            <button class="btn btn-light">
                                <i class="fas fa-edit me-2"></i> Edit Team
                            </button>
                        @endif
                        <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Teams
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="row">
            <!-- Team Information -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Information</h5>
                    </div>
                    <div class="card-body">
                        @if($team->description)
                            <p class="mb-3">{{ $team->description }}</p>
                        @endif
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>City</span>
                                <span class="fw-bold">{{ $team->city }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-flag me-2"></i>Country</span>
                                <span class="fw-bold">{{ $team->country }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-users me-2"></i>Players</span>
                                <span class="fw-bold">{{ $team->players->count() }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-star me-2"></i>Rating</span>
                                <span class="fw-bold">{{ number_format($team->rating, 1) }}/5.0</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-trophy me-2"></i>Trophies</span>
                                <span class="fw-bold">{{ $team->trophies_count }}</span>
                            </li>
                            @if($team->manager_name)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-user-tie me-2"></i>Manager</span>
                                <span class="fw-bold">{{ $team->manager_name }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Team Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Team Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $team_stats['total_matches'] }}</div>
                                <div class="stats-label">Matches</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $team_stats['wins'] }}</div>
                                <div class="stats-label">Wins</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $team_stats['draws'] }}</div>
                                <div class="stats-label">Draws</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $team_stats['losses'] }}</div>
                                <div class="stats-label">Losses</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number text-primary">{{ $team_stats['goals_for'] }}</div>
                                <div class="stats-label">Goals For</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number text-primary">{{ $team_stats['goals_against'] }}</div>
                                <div class="stats-label">Goals Against</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Players Roster -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Players Roster</h5>
                        @if(auth()->check())
                            <a href="{{ route('fuma.players.create', ['team_id' => $team->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus me-2"></i> Add Player
                            </a>
                        @endif
                    </div>
                    <div class="card-body">
                        @if($team->players->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Player</th>
                                            <th>Position</th>
                                            <th>Age</th>
                                            <th>Goals</th>
                                            <th>Assists</th>
                                            <th>Rating</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($team->players->sortBy('jersey_number') as $player)
                                        <tr>
                                            <td>
                                                @if($player->jersey_number)
                                                    <span class="badge bg-primary">#{{ $player->jersey_number }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($player->avatar)
                                                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                                    @else
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=32&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32">
                                                    @endif
                                                    <span>{{ $player->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $player->position }}</td>
                                            <td>{{ $player->age ?: '-' }}</td>
                                            <td>{{ $player->goals_scored }}</td>
                                            <td>{{ $player->assists }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-star text-warning me-1"></i>{{ number_format($player->rating, 1) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('fuma.players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-circle fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No players registered for this team yet.</p>
                                @if(auth()->check())
                                    <a href="{{ route('fuma.players.create', ['team_id' => $team->id]) }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i> Add First Player
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Matches -->
                @if($team->getAllMatchesAttribute()->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Matches</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Tournament</th>
                                        <th>Opponent</th>
                                        <th>Score</th>
                                        <th>Result</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($team->getAllMatchesAttribute()->sortByDesc('scheduled_at')->take(5) as $match)
                                    <tr>
                                        <td>{{ $match->scheduled_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('fuma.tournaments.show', $match->tournament) }}" class="text-decoration-none">
                                                {{ $match->tournament->name }}
                                            </a>
                                        </td>
                                        <td>
                                            @php
                                                $opponent = $match->home_team_id === $team->id ? $match->awayTeam : $match->homeTeam;
                                                $isHome = $match->home_team_id === $team->id;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                @if($opponent->logo)
                                                    <img src="{{ asset('storage/' . $opponent->logo) }}" alt="{{ $opponent->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $opponent->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $opponent->name }} {{ $isHome ? '(A)' : '(H)' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($match->status === 'completed')
                                                @php
                                                    $teamScore = $isHome ? $match->home_score : $match->away_score;
                                                    $opponentScore = $isHome ? $match->away_score : $match->home_score;
                                                @endphp
                                                <span class="match-score">{{ $teamScore }} - {{ $opponentScore }}</span>
                                            @else
                                                <span class="text-muted">{{ ucfirst($match->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($match->status === 'completed')
                                                @php
                                                    $teamScore = $isHome ? $match->home_score : $match->away_score;
                                                    $opponentScore = $isHome ? $match->away_score : $match->home_score;
                                                @endphp
                                                @if($teamScore > $opponentScore)
                                                    <span class="badge bg-success">W</span>
                                                @elseif($teamScore < $opponentScore)
                                                    <span class="badge bg-danger">L</span>
                                                @else
                                                    <span class="badge bg-warning">D</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.matches.show', $match) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
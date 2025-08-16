@extends('layouts.fuma')

@section('title', $tournament->name)

@section('content')
    <!-- Tournament Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    @if($tournament->logo)
                        <img src="{{ asset('storage/' . $tournament->logo) }}" alt="Tournament Logo" class="tournament-logo mb-3 mb-md-0" style="width: 80px; height: 80px; object-fit: contain;">
                    @else
                        <div class="tournament-logo mb-3 mb-md-0 bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-trophy text-primary fa-2x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <h1 class="h3 fw-bold mb-1">{{ $tournament->name }}</h1>
                    <p class="mb-2 opacity-75">{{ $tournament->description }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        @if($tournament->status === 'ongoing')
                            <span class="badge bg-success">
                                <i class="fas fa-circle me-1 small"></i> Ongoing
                            </span>
                        @elseif($tournament->status === 'upcoming')
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-clock me-1 small"></i> Upcoming
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-check me-1 small"></i> Completed
                            </span>
                        @endif
                        <span class="text-white opacity-75">
                            <i class="fas fa-calendar-alt me-1"></i> {{ $tournament->start_date->format('M j') }} - {{ $tournament->end_date->format('M j, Y') }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-users me-1"></i> {{ $tournament->teams->count() }}/{{ $tournament->max_teams }} Teams
                        </span>
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'organizer']) && (auth()->user()->id === $tournament->organizer_id || auth()->user()->isAdmin()))
                            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editTournamentModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Manage
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                            <i class="fas fa-plus me-2"></i>Add Team
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleMatchModal">
                                            <i class="fas fa-calendar-plus me-2"></i>Schedule Match
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mb-5">
        <!-- Tournament Navigation -->
        <ul class="nav nav-tabs mb-4" id="tournamentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                    <i class="fas fa-home me-1"></i> Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="standings-tab" data-bs-toggle="tab" data-bs-target="#standings" type="button">
                    <i class="fas fa-list-ol me-1"></i> Standings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches" type="button">
                    <i class="fas fa-futbol me-1"></i> Matches
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button">
                    <i class="fas fa-users me-1"></i> Teams
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="tournamentTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-4">
                    <!-- Tournament Info -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Tournament Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <strong>Organizer:</strong>
                                        <p class="mb-0">{{ $tournament->organizer->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Venue:</strong>
                                        <p class="mb-0">{{ $tournament->venue ?? 'Multiple Venues' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Start Date:</strong>
                                        <p class="mb-0">{{ $tournament->start_date->format('F j, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>End Date:</strong>
                                        <p class="mb-0">{{ $tournament->end_date->format('F j, Y') }}</p>
                                    </div>
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <p class="mb-0">{{ $tournament->description ?: 'No description available.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Matches -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Matches</h5>
                            </div>
                            <div class="card-body">
                                @forelse($tournament->matches->take(5) as $match)
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($match->homeTeam->logo)
                                                    <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="Home Team" class="team-logo">
                                                @else
                                                    <div class="team-logo bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-shield-alt text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</div>
                                                <small class="text-muted">{{ $match->scheduled_at->format('M j, Y H:i') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            @if($match->status === 'completed')
                                                <div class="fw-bold">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</div>
                                            @elseif($match->status === 'ongoing')
                                                <span class="badge bg-danger">Live</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $match->scheduled_at->format('H:i') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted text-center py-3">No matches scheduled yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Stats -->
                    <div class="col-lg-4">
                        <div class="card stats-card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Tournament Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Teams Registered</span>
                                        <span class="fw-bold">{{ $tournament->teams->count() }}/{{ $tournament->max_teams }}</span>
                                    </div>
                                    <div class="progress mt-1">
                                        <div class="progress-bar" style="width: {{ ($tournament->teams->count() / $tournament->max_teams) * 100 }}%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Matches Played</span>
                                        <span class="fw-bold">{{ $tournament->matches->where('status', 'completed')->count() }}</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Total Goals</span>
                                        <span class="fw-bold">{{ $tournament->matches->sum('home_score') + $tournament->matches->sum('away_score') }}</span>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span>Remaining Matches</span>
                                        <span class="fw-bold">{{ $tournament->matches->whereIn('status', ['upcoming', 'ongoing'])->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standings Tab -->
            <div class="tab-pane fade" id="standings" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-trophy me-2"></i>Tournament Standings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Pos</th>
                                        <th>Team</th>
                                        <th>Played</th>
                                        <th>Won</th>
                                        <th>Draw</th>
                                        <th>Lost</th>
                                        <th>GF</th>
                                        <th>GA</th>
                                        <th>GD</th>
                                        <th>Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($standings as $index => $team)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $index + 1 }}</span>
                                                @if($index < 3)
                                                    <i class="fas fa-medal text-warning ms-1"></i>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($team->logo)
                                                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo me-2">
                                                    @else
                                                        <div class="team-logo me-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                            <i class="fas fa-shield-alt text-white" style="font-size: 12px;"></i>
                                                        </div>
                                                    @endif
                                                    <span>{{ $team->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $team->pivot->matches_played ?? 0 }}</td>
                                            <td>{{ $team->pivot->wins ?? 0 }}</td>
                                            <td>{{ $team->pivot->draws ?? 0 }}</td>
                                            <td>{{ $team->pivot->losses ?? 0 }}</td>
                                            <td>{{ $team->pivot->goals_for ?? 0 }}</td>
                                            <td>{{ $team->pivot->goals_against ?? 0 }}</td>
                                            <td>{{ $team->pivot->goal_difference ?? 0 }}</td>
                                            <td><span class="fw-bold text-primary">{{ $team->pivot->points ?? 0 }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matches Tab -->
            <div class="tab-pane fade" id="matches" role="tabpanel">
                <div class="row g-4">
                    @forelse($tournament->matches as $match)
                        <div class="col-lg-6">
                            <div class="card match-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        @if($match->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($match->status === 'ongoing')
                                            <span class="badge bg-danger live-badge">Live</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Upcoming</span>
                                        @endif
                                        <small class="text-muted">{{ $match->scheduled_at->format('M j, Y H:i') }}</small>
                                    </div>
                                    
                                    <div class="match-teams mb-3">
                                        <div class="team-info">
                                            @if($match->homeTeam->logo)
                                                <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="Home Team" class="team-logo mb-2">
                                            @else
                                                <div class="team-logo mb-2 bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                                    <i class="fas fa-shield-alt text-white"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-0">{{ $match->homeTeam->name }}</h6>
                                            <small class="text-muted">Home</small>
                                        </div>
                                        
                                        <div class="vs-text">
                                            @if($match->status === 'completed')
                                                <div class="score-display bg-primary text-white px-3 py-2 rounded">
                                                    {{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}
                                                </div>
                                            @else
                                                <div class="vs-indicator bg-light px-3 py-2 rounded">
                                                    VS
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="team-info">
                                            @if($match->awayTeam->logo)
                                                <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="Away Team" class="team-logo mb-2">
                                            @else
                                                <div class="team-logo mb-2 bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                                    <i class="fas fa-shield-alt text-white"></i>
                                                </div>
                                            @endif
                                            <h6 class="mb-0">{{ $match->awayTeam->name }}</h6>
                                            <small class="text-muted">Away</small>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        @if($match->venue)
                                            <small class="text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>{{ $match->venue }}
                                            </small>
                                        @endif
                                        <a href="{{ route('fuma.match-detail', $match->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-futbol fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">No matches scheduled</h4>
                                <p class="text-muted">Matches will appear here once they are scheduled.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Teams Tab -->
            <div class="tab-pane fade" id="teams" role="tabpanel">
                <div class="row g-4">
                    @foreach($tournament->teams as $team)
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    @if($team->logo)
                                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo mb-3">
                                    @else
                                        <div class="team-logo mb-3 bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                            <i class="fas fa-shield-alt text-white fa-2x"></i>
                                        </div>
                                    @endif
                                    
                                    <h5 class="card-title">{{ $team->name }}</h5>
                                    <p class="text-muted mb-3">{{ $team->city }}, {{ $team->country }}</p>
                                    
                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="small text-muted">Players</div>
                                            <div class="fw-bold text-primary">{{ $team->players->count() }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small text-muted">Points</div>
                                            <div class="fw-bold text-success">{{ $team->pivot->points ?? 0 }}</div>
                                        </div>
                                        <div class="col-4">
                                            <div class="small text-muted">Rating</div>
                                            <div class="fw-bold text-warning">{{ number_format($team->rating ?? 0, 1) }}</div>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('fuma.team-detail', $team->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View Team
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .tournament-header {
        background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        color: white;
        padding: 3rem 0;
    }
    
    .tournament-logo {
        border: 3px solid rgba(255,255,255,0.2);
        border-radius: 15px;
        padding: 10px;
        background: rgba(255,255,255,0.1);
    }
    
    .badge-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.8rem;
    }
    
    .stats-card {
        border-left: 3px solid var(--primary-color);
        transition: all 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .live-badge {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .team-logo {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }
</style>
@endpush
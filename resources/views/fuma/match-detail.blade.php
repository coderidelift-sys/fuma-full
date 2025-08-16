@extends('layouts.fuma')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name)

@section('content')
    <!-- Match Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-12 text-center">
                    <div class="mb-3">
                        <span class="badge bg-primary fs-6">{{ $match->tournament->name }}</span>
                    </div>
                    
                    <div class="match-teams-header mb-3">
                        <div class="row align-items-center">
                            <div class="col-4 text-end">
                                @if($match->homeTeam->logo)
                                    <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="Home Team" class="team-logo-large mb-2">
                                @else
                                    <div class="team-logo-large mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                        <i class="fas fa-shield-alt text-primary fa-3x"></i>
                                    </div>
                                @endif
                                <h3 class="fw-bold text-white">{{ $match->homeTeam->name }}</h3>
                                <small class="text-white opacity-75">Home Team</small>
                            </div>
                            
                            <div class="col-4 text-center">
                                @if($match->status === 'completed')
                                    <div class="score-display-large bg-white text-primary p-3 rounded">
                                        <span class="display-4 fw-bold">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</span>
                                    </div>
                                @elseif($match->status === 'ongoing')
                                    <div class="live-display-large bg-danger text-white p-3 rounded">
                                        <i class="fas fa-circle me-2"></i>
                                        <span class="fs-3 fw-bold">LIVE</span>
                                    </div>
                                @else
                                    <div class="vs-display-large bg-white text-primary p-3 rounded">
                                        <span class="display-4 fw-bold">VS</span>
                                    </div>
                                @endif
                                
                                <div class="mt-3 text-white">
                                    <div class="fw-bold">{{ $match->scheduled_at->format('F j, Y') }}</div>
                                    <div>{{ $match->scheduled_at->format('H:i') }}</div>
                                </div>
                            </div>
                            
                            <div class="col-4 text-start">
                                @if($match->awayTeam->logo)
                                    <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="Away Team" class="team-logo-large mb-2">
                                @else
                                    <div class="team-logo-large mb-2 bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                        <i class="fas fa-shield-alt text-primary fa-3x"></i>
                                    </div>
                                @endif
                                <h3 class="fw-bold text-white">{{ $match->awayTeam->name }}</h3>
                                <small class="text-white opacity-75">Away Team</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($match->venue)
                        <div class="text-white opacity-75">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $match->venue }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mb-5">
        <!-- Match Navigation -->
        <ul class="nav nav-tabs mb-4" id="matchTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                    <i class="fas fa-home me-1"></i> Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lineups-tab" data-bs-toggle="tab" data-bs-target="#lineups" type="button">
                    <i class="fas fa-users me-1"></i> Lineups
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="events-tab" data-bs-toggle="tab" data-bs-target="#events" type="button">
                    <i class="fas fa-list me-1"></i> Match Events
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="matchTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-4">
                    <!-- Match Info -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Match Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <strong>Tournament:</strong>
                                        <p class="mb-0">
                                            <a href="{{ route('fuma.tournament-detail', $match->tournament->id) }}" class="text-decoration-none">
                                                {{ $match->tournament->name }}
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Date & Time:</strong>
                                        <p class="mb-0">{{ $match->scheduled_at->format('F j, Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Venue:</strong>
                                        <p class="mb-0">{{ $match->venue ?? 'TBD' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <p class="mb-0">
                                            @if($match->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($match->status === 'ongoing')
                                                <span class="badge bg-danger">Live</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Upcoming</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if($match->referee)
                                        <div class="col-12">
                                            <strong>Referee:</strong>
                                            <p class="mb-0">{{ $match->referee }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Stats -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Match Statistics</h5>
                            </div>
                            <div class="card-body">
                                @if($match->status === 'completed')
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="fw-bold text-primary">{{ $match->home_score ?? 0 }}</div>
                                            <small class="text-muted">Goals</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-secondary">VS</div>
                                            <small class="text-muted">Final Score</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-primary">{{ $match->away_score ?? 0 }}</div>
                                            <small class="text-muted">Goals</small>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Total Events</span>
                                            <span class="fw-bold">{{ $match->events->count() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Goals Scored</span>
                                            <span class="fw-bold">{{ $match->events->where('event_type', 'goal')->count() }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Cards Issued</span>
                                            <span class="fw-bold">{{ $match->events->whereIn('event_type', ['yellow_card', 'red_card'])->count() }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">Match not completed yet</h6>
                                        <p class="text-muted">Statistics will be available after the match.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lineups Tab -->
            <div class="tab-pane fade" id="lineups" role="tabpanel">
                <div class="row g-4">
                    <!-- Home Team Lineup -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    @if($match->homeTeam->logo)
                                        <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="Home Team" class="me-2" style="width: 24px; height: 24px;">
                                    @endif
                                    {{ $match->homeTeam->name }} (Home)
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($match->homeTeam->players as $player)
                                    <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                                        @if($player->avatar)
                                            <img src="{{ asset('storage/' . $player->avatar) }}" alt="Player" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle me-3 bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $player->name }}</div>
                                            <small class="text-muted">{{ $player->position }}</small>
                                        </div>
                                        
                                        @if($player->jersey_number)
                                            <span class="badge bg-primary">#{{ $player->jersey_number }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Away Team Lineup -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    @if($match->awayTeam->logo)
                                        <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="Away Team" class="me-2" style="width: 24px; height: 24px;">
                                    @endif
                                    {{ $match->awayTeam->name }} (Away)
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($match->awayTeam->players as $player)
                                    <div class="d-flex align-items-center mb-2 p-2 bg-light rounded">
                                        @if($player->avatar)
                                            <img src="{{ asset('storage/' . $player->avatar) }}" alt="Player" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle me-3 bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $player->name }}</div>
                                            <small class="text-muted">{{ $player->position }}</small>
                                        </div>
                                        
                                        @if($player->jersey_number)
                                            <span class="badge bg-primary">#{{ $player->jersey_number }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Events Tab -->
            <div class="tab-pane fade" id="events" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Match Events</h5>
                    </div>
                    <div class="card-body">
                        @forelse($match->events->sortBy('minute') as $event)
                            <div class="timeline-item d-flex align-items-center mb-3 p-3 bg-light rounded">
                                <div class="timeline-marker me-3">
                                    @if($event->event_type === 'goal')
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-futbol"></i>
                                        </div>
                                    @elseif($event->event_type === 'yellow_card')
                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-square"></i>
                                        </div>
                                    @elseif($event->event_type === 'red_card')
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-square"></i>
                                        </div>
                                    @else
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-info"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $event->player->name }}</div>
                                    <div class="text-muted">
                                        @if($event->event_type === 'goal')
                                            <i class="fas fa-futbol me-1 text-success"></i> Goal scored
                                        @elseif($event->event_type === 'yellow_card')
                                            <i class="fas fa-square me-1 text-warning"></i> Yellow card
                                        @elseif($event->event_type === 'red_card')
                                            <i class="fas fa-square me-1 text-danger"></i> Red card
                                        @else
                                            {{ ucfirst(str_replace('_', ' ', $event->event_type)) }}
                                        @endif
                                        @if($event->description)
                                            - {{ $event->description }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ $event->minute }}'</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-list fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No events recorded</h5>
                                <p class="text-muted">Match events will appear here during the game.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .team-logo-large {
        width: 100px;
        height: 100px;
        object-fit: contain;
    }
    
    .match-teams-header {
        margin: 2rem 0;
    }
    
    .score-display-large,
    .live-display-large,
    .vs-display-large {
        border-radius: 15px;
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .live-display-large {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }
    
    .timeline-item {
        transition: all 0.3s ease;
    }
    
    .timeline-item:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
</style>
@endpush
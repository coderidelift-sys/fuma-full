@extends('layouts.fuma')

@section('title', $team->name)

@section('content')
    <!-- Team Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    @if($team->logo)
                        <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo mb-3 mb-md-0" style="width: 80px; height: 80px; object-fit: contain;">
                    @else
                        <div class="team-logo mb-3 mb-md-0 bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-shield-alt text-primary fa-2x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <h1 class="h3 fw-bold mb-1">{{ $team->name }}</h1>
                    <p class="mb-2 opacity-75">{{ $team->description ?: 'Professional football team' }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <span class="text-white opacity-75">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $team->city }}, {{ $team->country }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-users me-1"></i> {{ $team->players->count() }} Players
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-star me-1"></i> {{ number_format($team->rating ?? 0, 1) }} Rating
                        </span>
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager']) && (auth()->user()->id === $team->manager_id || auth()->user()->isAdmin()))
                            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editTeamModal">
                                <i class="fas fa-edit"></i>
                            </button>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Manage
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                                            <i class="fas fa-user-plus me-2"></i>Add Player
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
        <div class="row g-4">
            <!-- Team Info -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Team Information</h5>
                    </div>
                    <div class="card-body">
                        @if($team->manager_name)
                            <div class="mb-3">
                                <strong>Manager:</strong>
                                <p class="mb-0">{{ $team->manager_name }}</p>
                                @if($team->manager_email)
                                    <small class="text-muted">{{ $team->manager_email }}</small>
                                @endif
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Location:</strong>
                            <p class="mb-0">{{ $team->city }}, {{ $team->country }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Total Players:</strong>
                            <p class="mb-0">{{ $team->players->count() }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Team Rating:</strong>
                            <p class="mb-0">
                                <span class="badge bg-success">{{ number_format($team->rating ?? 0, 1) }}/5.0</span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Trophies:</strong>
                            <p class="mb-0">
                                <i class="fas fa-trophy text-warning me-1"></i> {{ $team->trophies_count ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Team Stats -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Team Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Total Matches</span>
                                <span class="fw-bold">{{ $team->getAllMatchesAttribute()->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Tournaments</span>
                                <span class="fw-bold">{{ $team->tournaments->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Goals Scored</span>
                                <span class="fw-bold text-success">{{ $team->players->sum('goals_scored') }}</span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Clean Sheets</span>
                                <span class="fw-bold text-primary">{{ $team->players->where('position', 'Goalkeeper')->sum('clean_sheets') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Players List -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Team Players</h5>
                        @auth
                            @if(auth()->user()->hasAnyRole(['admin', 'manager']) && (auth()->user()->id === $team->manager_id || auth()->user()->isAdmin()))
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                                    <i class="fas fa-user-plus me-1"></i> Add Player
                                </button>
                            @endif
                        @endauth
                    </div>
                    <div class="card-body">
                        @if($team->players->count() > 0)
                            <div class="row g-3">
                                @foreach($team->players as $player)
                                    <div class="col-md-6">
                                        <div class="card border">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    @if($player->avatar)
                                                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="Player" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle me-3 bg-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                    
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $player->name }}</h6>
                                                        <div class="d-flex justify-content-between">
                                                            <small class="text-muted">{{ $player->position }}</small>
                                                            @if($player->jersey_number)
                                                                <span class="badge bg-primary">#{{ $player->jersey_number }}</span>
                                                            @endif
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-1">
                                                            <small class="text-muted">
                                                                @if($player->position === 'Goalkeeper')
                                                                    <i class="fas fa-shield-alt me-1"></i>{{ $player->clean_sheets ?? 0 }} CS
                                                                @else
                                                                    <i class="fas fa-futbol me-1"></i>{{ $player->goals_scored ?? 0 }} Goals
                                                                @endif
                                                            </small>
                                                            <small class="text-warning">
                                                                <i class="fas fa-star me-1"></i>{{ number_format($player->rating ?? 0, 1) }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No players registered</h5>
                                <p class="text-muted">Add players to build your team roster.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Matches -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-futbol me-2"></i>Recent Matches</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $recentMatches = $team->getAllMatchesAttribute()->take(5);
                        @endphp
                        
                        @forelse($recentMatches as $match)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-bold">
                                        {{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}
                                    </div>
                                    <small class="text-muted">
                                        {{ $match->tournament->name }} • {{ $match->scheduled_at->format('M j, Y') }}
                                    </small>
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
                            <p class="text-muted text-center py-3">No matches played yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .team-logo {
        border: 3px solid rgba(255,255,255,0.2);
        border-radius: 15px;
        padding: 10px;
        background: rgba(255,255,255,0.1);
    }
</style>
@endpush
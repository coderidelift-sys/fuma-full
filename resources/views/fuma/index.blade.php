@extends('layouts.fuma')

@section('title', 'Football Tournament Management')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section animate__animated animate__fadeIn">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Football Tournament Management System</h1>
                    <p class="lead mb-4">Manage your football tournaments with ease. Track teams, players, matches, and statistics all in one place.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-accent btn-lg px-4">
                            <i class="fas fa-trophy me-2"></i> View Tournaments
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="fas fa-lock me-2"></i> Admin Login
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://media.istockphoto.com/id/974754900/id/vektor/ilustrasi-vektor-banner-turnamen-sepak-bola-bola-di-latar-belakang-lapangan-sepak-bola.jpg?s=170667a&w=0&k=20&c=-a5zDG5lJGTK0r_N8LotOEZOuDurWzwN0fY4LVl09hQ=" alt="Football Tournament" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.1s;">
                        <div class="stats-number text-primary">{{ $stats['active_tournaments'] }}</div>
                        <div class="stats-label">Active Tournaments</div>
                        <i class="fas fa-trophy mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.2s;">
                        <div class="stats-number text-primary">{{ $stats['total_teams'] }}</div>
                        <div class="stats-label">Registered Teams</div>
                        <i class="fas fa-users mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.3s;">
                        <div class="stats-number text-primary">{{ $stats['total_players'] }}</div>
                        <div class="stats-label">Players</div>
                        <i class="fas fa-user-circle mt-3 text-primary"></i>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card bg-white slide-up" style="animation-delay: 0.4s;">
                        <div class="stats-number text-primary">{{ $stats['total_matches'] }}</div>
                        <div class="stats-label">Matches Played</div>
                        <i class="fas fa-futbol mt-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tournaments -->
    <section id="tournaments" class="py-5">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Featured Tournaments</h2>
                <p class="text-muted">Join the excitement of our premier football tournaments</p>
            </div>
            
            <div class="row g-4">
                @forelse($featured_tournaments as $tournament)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                        @if($tournament->logo)
                            <img src="{{ asset('storage/' . $tournament->logo) }}" class="card-img-top" alt="{{ $tournament->name }}">
                        @else
                            <img src="https://tse1.mm.bing.net/th/id/OIP.MaIk4N5rw51_K6gHkokGUgHaGl?pid=Api" class="card-img-top" alt="{{ $tournament->name }}">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $tournament->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($tournament->description, 100) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-{{ $tournament->status === 'ongoing' ? 'success' : ($tournament->status === 'upcoming' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($tournament->status) }}
                                </span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    {{ $tournament->start_date->format('M d, Y') }}
                                </small>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    {{ $tournament->teams->count() }}/{{ $tournament->max_teams }} teams
                                </span>
                                <a href="{{ route('fuma.tournaments.show', $tournament) }}" class="btn btn-sm btn-outline-primary">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No tournaments available at the moment.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-trophy me-2"></i> View All Tournaments
                </a>
            </div>
        </div>
    </section>

    <!-- Top Teams -->
    <section id="teams" class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Top Teams</h2>
                <p class="text-muted">Meet the highest-rated teams in our tournaments</p>
            </div>
            
            <div class="row g-3">
                @forelse($top_teams as $team)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card team-card h-100 text-center">
                        <div class="card-body p-3">
                            @if($team->logo)
                                <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="team-logo-sm mb-2">
                            @else
                                <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $team->name }}" class="team-logo-sm mb-2">
                            @endif
                            <h6 class="card-title mb-1">{{ $team->name }}</h6>
                            <div class="d-flex justify-content-center small mb-2">
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $team->city }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-center small mb-2">
                                <span class="badge bg-light text-dark me-1">
                                    <i class="fas fa-user me-1"></i>{{ $team->players->count() }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-star text-warning me-1"></i>{{ number_format($team->rating, 1) }}
                                </span>
                            </div>
                            <a href="{{ route('fuma.teams.show', $team) }}" class="btn btn-sm btn-outline-primary w-100">
                                View Team
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No teams available at the moment.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.teams.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Teams
                </a>
            </div>
        </div>
    </section>

    <!-- Upcoming Matches -->
    <section id="matches" class="py-5">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Upcoming Matches</h2>
                <p class="text-muted">Don't miss these exciting matches coming soon</p>
            </div>
            
            <div class="row g-3">
                @forelse($upcoming_matches as $match)
                <div class="col-md-6">
                    <div class="card match-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $match->stage)) }}</span>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i> 
                                    {{ $match->scheduled_at->format('M d, H:i') }}
                                </small>
                            </div>
                            
                            <div class="d-flex align-items-center mb-2">
                                <div class="d-flex align-items-center flex-grow-1">
                                    @if($match->homeTeam->logo)
                                        <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                    @else
                                        <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                    @endif
                                    <span class="text-truncate">{{ $match->homeTeam->name }}</span>
                                </div>
                                
                                <div class="px-2 text-center flex-shrink-0">
                                    <div class="vs-badge bg-light rounded-pill px-2 py-0 d-inline-block">
                                        <small class="fw-bold">VS</small>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                                    <span class="text-truncate text-end">{{ $match->awayTeam->name }}</span>
                                    @if($match->awayTeam->logo)
                                        <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="team-logo-sm ms-2">
                                    @else
                                        <img src="https://tse2.mm.bing.net/th/id/OIP.lpgOZ4hPNpsQjk22cDyIegHaFf?pid=Api&P=0&h=180" alt="{{ $match->awayTeam->name }}" class="team-logo-sm ms-2">
                                    @endif
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-dark small">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $match->venue ?: 'TBD' }}
                                </span>
                                <div>
                                    <a href="{{ route('fuma.matches.show', $match) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No upcoming matches scheduled.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.matches.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-list me-2"></i> View All Matches
                </a>
            </div>
        </div>
    </section>

    <!-- Top Players -->
    <section id="players" class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Top Players</h2>
                <p class="text-muted">Outstanding players making their mark in the tournaments</p>
            </div>
            
            <div class="row g-4">
                @forelse($top_players as $player)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            @if($player->avatar)
                                <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=80&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle mb-3" width="80" height="80">
                            @endif
                            <h6 class="card-title">{{ $player->name }}</h6>
                            <p class="text-muted small mb-2">{{ $player->position }}</p>
                            @if($player->team)
                                <p class="text-muted small mb-2">{{ $player->team->name }}</p>
                            @endif
                            <div class="d-flex justify-content-center gap-2 mb-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-futbol me-1"></i>{{ $player->goals_scored }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-star text-warning me-1"></i>{{ number_format($player->rating, 1) }}
                                </span>
                            </div>
                            <a href="{{ route('fuma.players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                                View Profile
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No players available at the moment.</p>
                </div>
                @endforelse
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.players.index') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Players
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .team-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .team-card:hover .team-logo-sm {
        transform: scale(1.1);
    }
    
    .vs-badge {
        min-width: 40px;
    }
    
    .text-truncate {
        max-width: 100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush
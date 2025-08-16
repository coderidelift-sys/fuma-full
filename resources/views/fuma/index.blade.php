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
                        <a href="{{ route('fuma.tournaments') }}" class="btn btn-accent btn-lg px-4">
                            <i class="fas fa-trophy me-2"></i> View Tournaments
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-lock me-2"></i> Admin Login
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-lg px-4">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        @endguest
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
                        <div class="stats-number text-primary">{{ number_format($stats['total_players']) }}</div>
                        <div class="stats-label">Players</div>
                        <i class="fas fa-user mt-3 text-primary"></i>
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
    <section id="tournaments" class="py-4">
        <div class="container">
            <div class="section-header mb-4 text-center">
                <h2 class="fw-bold h4">Featured Tournaments</h2>
                <p class="text-muted small">Browse active competitions</p>
            </div>
            
            <div class="row g-3">
                @foreach($recentTournaments as $index => $tournament)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 shadow-sm animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.1 }}s;">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    @if($tournament->status === 'ongoing')
                                        <span class="badge bg-primary small">Ongoing</span>
                                    @elseif($tournament->status === 'upcoming')
                                        <span class="badge bg-warning text-dark small">Upcoming</span>
                                    @else
                                        <span class="badge bg-secondary small">Completed</span>
                                    @endif
                                    <span class="text-muted small">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $tournament->start_date->format('M j') }}-{{ $tournament->end_date->format('M j') }}
                                    </span>
                                </div>
                                <h5 class="card-title mb-2">{{ $tournament->name }}</h5>
                                <p class="card-text small text-muted mb-3">{{ Str::limit($tournament->description, 50) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">
                                        <i class="far fa-users me-1"></i>{{ $tournament->teams->count() }} Teams
                                    </span>
                                    <a href="{{ route('fuma.tournament-detail', $tournament->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.tournaments') }}" class="btn btn-sm btn-primary px-3">
                    <i class="fas fa-list me-1"></i> All Tournaments
                </a>
            </div>
        </div>
    </section>

    <!-- Top Teams -->
    <section id="teams" class="py-5 bg-light">
        <div class="container">
            <div class="section-header mb-4 text-center">
                <h2 class="fw-bold">Top Teams</h2>
                <p class="text-muted mb-0">The most successful teams in our tournaments</p>
            </div>
            
            <div class="row g-3">
                @foreach($topTeams as $index => $team)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="card team-card h-100">
                            <div class="card-body p-3 text-center">
                                @if($team->logo)
                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo-sm mb-2">
                                @else
                                    <div class="team-logo-sm mb-2 bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                        <i class="fas fa-shield-alt text-white"></i>
                                    </div>
                                @endif
                                <h6 class="card-title mb-1">{{ $team->name }}</h6>
                                <div class="d-flex justify-content-center small mb-2">
                                    <span class="text-muted me-2">
                                        <i class="fas fa-trophy text-warning me-1"></i>{{ $team->trophies_count ?? 0 }}
                                    </span>
                                    <span class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $team->city }}
                                    </span>
                                </div>
                                <div class="d-flex justify-content-center small mb-2">
                                    <span class="badge bg-light text-dark me-1">
                                        <i class="fas fa-user me-1"></i>{{ $team->players->count() }}
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-star text-warning me-1"></i>{{ number_format($team->rating ?? 0, 1) }}
                                    </span>
                                </div>
                                <a href="{{ route('fuma.team-detail', $team->id) }}" class="btn btn-sm btn-outline-primary w-100">
                                    View Team
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.teams') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Teams
                </a>
            </div>
        </div>
    </section>

    <!-- Recent Matches -->
    <section id="matches" class="py-5">
        <div class="container">
            <div class="section-header mb-4 text-center">
                <h2 class="fw-bold">Recent Matches</h2>
                <p class="text-muted">Latest match results and upcoming fixtures</p>
            </div>
            
            <div class="row g-3">
                @foreach($recentMatches->take(6) as $index => $match)
                    <div class="col-md-6">
                        <div class="card match-card h-100">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary">{{ $match->tournament->name }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> 
                                        {{ $match->scheduled_at->format('M j, H:i') }}
                                    </small>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        @if($match->homeTeam->logo)
                                            <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="Team Logo" class="team-logo-sm me-2">
                                        @else
                                            <div class="team-logo-sm me-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="fas fa-shield-alt text-white" style="font-size: 12px;"></i>
                                            </div>
                                        @endif
                                        <span class="text-truncate">{{ $match->homeTeam->name }}</span>
                                    </div>
                                    
                                    <div class="px-2 text-center flex-shrink-0">
                                        @if($match->status === 'completed')
                                            <div class="score-display">
                                                <span class="fw-bold">{{ $match->home_score ?? 0 }} - {{ $match->away_score ?? 0 }}</span>
                                            </div>
                                        @else
                                            <div class="vs-badge bg-light rounded-pill px-2 py-0 d-inline-block">
                                                <small class="fw-bold">VS</small>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex align-items-center flex-grow-1 justify-content-end">
                                        <span class="text-truncate text-end">{{ $match->awayTeam->name }}</span>
                                        @if($match->awayTeam->logo)
                                            <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="Team Logo" class="team-logo-sm ms-2">
                                        @else
                                            <div class="team-logo-sm ms-2 bg-primary rounded-circle d-flex align-items-center justify-content-center">
                                                <i class="fas fa-shield-alt text-white" style="font-size: 12px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark small">
                                        <i class="fas fa-map-marker-alt me-1"></i> {{ $match->venue ?? 'TBD' }}
                                    </span>
                                    <div>
                                        <a href="{{ route('fuma.match-detail', $match->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-4">
                <a href="{{ route('fuma.matches') }}" class="btn btn-primary px-4">
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
                <p class="text-muted">The best performing players in current tournaments</p>
            </div>
            
            <div class="row g-4">
                @foreach($topPlayers->take(4) as $index => $player)
                    <div class="col-lg-3 col-md-6">
                        <div class="player-card animate__animated animate__fadeInUp" style="animation-delay: {{ $index * 0.2 }}s;">
                            @if($player->avatar)
                                <img src="{{ asset('storage/' . $player->avatar) }}" alt="Player" class="player-img">
                            @else
                                <img src="https://images.hdqwalls.com/wallpapers/cristiano-ronaldo-fifa-world-cup-qatar-4k-dx.jpg" alt="Player" class="player-img">
                            @endif
                            <div class="player-overlay">
                                <h5 class="mb-1">{{ $player->name }}</h5>
                                <p class="mb-2">{{ $player->position }} | {{ $player->team->name }}</p>
                                <div class="d-flex justify-content-between">
                                    @if($player->position === 'Goalkeeper')
                                        <span><i class="fas fa-shield-alt me-1"></i> {{ $player->clean_sheets ?? 0 }} Clean Sheets</span>
                                    @else
                                        <span><i class="fas fa-futbol me-1"></i> {{ $player->goals_scored ?? 0 }} Goals</span>
                                    @endif
                                    <span><i class="fas fa-star me-1 text-warning"></i> {{ number_format($player->rating ?? 0, 1) }}</span>
                                </div>
                            </div>
                            <div class="player-number">{{ $player->jersey_number ?? '?' }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('fuma.players') }}" class="btn btn-primary px-4">
                    <i class="fas fa-users me-2"></i> View All Players
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Stadiums -->
    <section class="py-5">
        <div class="container">
            <div class="section-header mb-5 text-center">
                <h2 class="fw-bold">Featured Stadiums</h2>
                <p class="text-muted">Iconic venues hosting our tournaments</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="stadium-card animate__animated animate__fadeIn">
                        <img src="https://images.squarespace-cdn.com/content/v1/6541b7a7b9fd1140888a8017/20e885ad-786d-499b-8316-531d56d1741e/stadion+terbesar+di+eropa+-+Signal+Iduna+Park+Stadium-Spun+Global+-+Arne+Museler+Wikimedia.jpg" alt="Stadium" class="stadium-img">
                        <div class="stadium-overlay">
                            <h4>National Stadium</h4>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> Jakarta, Indonesia</p>
                            <p class="mb-0"><i class="fas fa-people-arrows me-1"></i> Capacity: 50,000</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="stadium-card animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                        <img src="https://images.squarespace-cdn.com/content/v1/6541b7a7b9fd1140888a8017/20e885ad-786d-499b-8316-531d56d1741e/stadion+terbesar+di+eropa+-+Signal+Iduna+Park+Stadium-Spun+Global+-+Arne+Museler+Wikimedia.jpg" alt="Stadium" class="stadium-img">
                        <div class="stadium-overlay">
                            <h4>City Arena</h4>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> Surabaya, Indonesia</p>
                            <p class="mb-0"><i class="fas fa-people-arrows me-1"></i> Capacity: 35,000</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="stadium-card animate__animated animate__fadeIn" style="animation-delay: 0.4s;">
                        <img src="https://images.squarespace-cdn.com/content/v1/6541b7a7b9fd1140888a8017/20e885ad-786d-499b-8316-531d56d1741e/stadion+terbesar+di+eropa+-+Signal+Iduna+Park+Stadium-Spun+Global+-+Arne+Museler+Wikimedia.jpg" alt="Stadium" class="stadium-img">
                        <div class="stadium-overlay">
                            <h4>Sports Complex</h4>
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-1"></i> Bandung, Indonesia</p>
                            <p class="mb-0"><i class="fas fa-people-arrows me-1"></i> Capacity: 25,000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    .team-logo-sm {
        width: 30px;
        height: 30px;
        object-fit: contain;
    }
    
    .match-card {
        transition: all 0.3s ease;
        border-left: 3px solid var(--primary-color);
    }
    
    .match-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

    .stadium-card {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        height: 250px;
    }
    
    .stadium-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .stadium-card:hover .stadium-img {
        transform: scale(1.1);
    }
    
    .stadium-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 20px;
        color: white;
    }

    .score-display {
        background: var(--primary-color);
        color: white;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
    }
</style>
@endpush
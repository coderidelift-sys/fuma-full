@extends('layouts.fuma')

@section('title', $player->name)

@section('content')
    <!-- Player Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    @if($player->avatar)
                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="Player Photo" class="player-avatar mb-3 mb-md-0" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                    @else
                        <div class="player-avatar mb-3 mb-md-0 bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                            <i class="fas fa-user text-primary fa-2x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <h1 class="h3 fw-bold mb-1">{{ $player->name }}</h1>
                    <p class="mb-2 opacity-75">{{ $player->position }} • {{ $player->team->name }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        @if($player->jersey_number)
                            <span class="text-white opacity-75">
                                <i class="fas fa-hashtag me-1"></i> {{ $player->jersey_number }}
                            </span>
                        @endif
                        @if($player->nationality)
                            <span class="text-white opacity-75">
                                <i class="fas fa-flag me-1"></i> {{ $player->nationality }}
                            </span>
                        @endif
                        @if($player->age)
                            <span class="text-white opacity-75">
                                <i class="fas fa-birthday-cake me-1"></i> {{ $player->age }} years
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager', 'organizer']))
                            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editPlayerModal">
                                <i class="fas fa-edit"></i>
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mb-5">
        <div class="row g-4">
            <!-- Player Info -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Player Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Full Name:</strong>
                            <p class="mb-0">{{ $player->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Position:</strong>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $player->position }}</span>
                            </p>
                        </div>
                        
                        @if($player->jersey_number)
                            <div class="mb-3">
                                <strong>Jersey Number:</strong>
                                <p class="mb-0">#{{ $player->jersey_number }}</p>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <strong>Team:</strong>
                            <p class="mb-0">
                                <a href="{{ route('fuma.team-detail', $player->team->id) }}" class="text-decoration-none">
                                    {{ $player->team->name }}
                                </a>
                            </p>
                        </div>
                        
                        @if($player->birth_date)
                            <div class="mb-3">
                                <strong>Birth Date:</strong>
                                <p class="mb-0">{{ $player->birth_date->format('F j, Y') }} ({{ $player->age }} years)</p>
                            </div>
                        @endif
                        
                        @if($player->nationality)
                            <div class="mb-3">
                                <strong>Nationality:</strong>
                                <p class="mb-0">{{ $player->nationality }}</p>
                            </div>
                        @endif
                        
                        @if($player->height)
                            <div class="mb-3">
                                <strong>Height:</strong>
                                <p class="mb-0">{{ $player->height }} cm</p>
                            </div>
                        @endif
                        
                        @if($player->weight)
                            <div class="mb-3">
                                <strong>Weight:</strong>
                                <p class="mb-0">{{ $player->weight }} kg</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Player Statistics -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Player Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-3 text-center">
                                <div class="stats-card bg-light p-3 rounded">
                                    <div class="stats-number text-primary">{{ number_format($player->rating ?? 0, 1) }}</div>
                                    <div class="stats-label">Overall Rating</div>
                                </div>
                            </div>
                            
                            @if($player->position !== 'Goalkeeper')
                                <div class="col-md-3 text-center">
                                    <div class="stats-card bg-light p-3 rounded">
                                        <div class="stats-number text-success">{{ $player->goals_scored ?? 0 }}</div>
                                        <div class="stats-label">Goals Scored</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 text-center">
                                    <div class="stats-card bg-light p-3 rounded">
                                        <div class="stats-number text-info">{{ $player->assists ?? 0 }}</div>
                                        <div class="stats-label">Assists</div>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-3 text-center">
                                    <div class="stats-card bg-light p-3 rounded">
                                        <div class="stats-number text-success">{{ $player->clean_sheets ?? 0 }}</div>
                                        <div class="stats-label">Clean Sheets</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="col-md-3 text-center">
                                <div class="stats-card bg-light p-3 rounded">
                                    <div class="stats-number text-warning">{{ ($player->yellow_cards ?? 0) + ($player->red_cards ?? 0) }}</div>
                                    <div class="stats-label">Total Cards</div>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Stats -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Performance Stats</h6>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Goals Scored</span>
                                        <span class="fw-bold">{{ $player->goals_scored ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span>Assists</span>
                                        <span class="fw-bold">{{ $player->assists ?? 0 }}</span>
                                    </div>
                                </div>
                                @if($player->position === 'Goalkeeper')
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span>Clean Sheets</span>
                                            <span class="fw-bold">{{ $player->clean_sheets ?? 0 }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3">Disciplinary Record</h6>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-square text-warning me-1"></i> Yellow Cards</span>
                                        <span class="fw-bold">{{ $player->yellow_cards ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="d-flex justify-content-between">
                                        <span><i class="fas fa-square text-danger me-1"></i> Red Cards</span>
                                        <span class="fw-bold">{{ $player->red_cards ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Match Events -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Match Events</h5>
                    </div>
                    <div class="card-body">
                        @forelse($player->matchEvents->take(10) as $event)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <div>
                                    <div class="fw-bold">{{ $event->match->homeTeam->name }} vs {{ $event->match->awayTeam->name }}</div>
                                    <small class="text-muted">{{ $event->match->tournament->name }} • {{ $event->match->scheduled_at->format('M j, Y') }}</small>
                                </div>
                                <div class="text-end">
                                    @if($event->event_type === 'goal')
                                        <span class="badge bg-success"><i class="fas fa-futbol me-1"></i>Goal</span>
                                    @elseif($event->event_type === 'assist')
                                        <span class="badge bg-info"><i class="fas fa-hands-helping me-1"></i>Assist</span>
                                    @elseif($event->event_type === 'yellow_card')
                                        <span class="badge bg-warning"><i class="fas fa-square me-1"></i>Yellow Card</span>
                                    @elseif($event->event_type === 'red_card')
                                        <span class="badge bg-danger"><i class="fas fa-square me-1"></i>Red Card</span>
                                    @endif
                                    @if($event->minute)
                                        <small class="text-muted d-block">{{ $event->minute }}'</small>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No match events recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('styles')
<style>
    .player-avatar {
        border: 3px solid rgba(255,255,255,0.2);
        padding: 5px;
        background: rgba(255,255,255,0.1);
    }
    
    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        color: #666;
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>
@endpush
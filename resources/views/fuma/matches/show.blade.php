@extends('layouts.fuma')

@section('title', $match->homeTeam->name . ' vs ' . $match->awayTeam->name . ' - Match Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="mb-2">
                        <a href="{{ route('fuma.tournaments.show', $match->tournament) }}" class="text-light text-decoration-none">
                            <i class="fas fa-trophy me-2"></i>{{ $match->tournament->name }}
                        </a>
                    </div>
                    <h1 class="fw-bold mb-1">{{ $match->homeTeam->name }} vs {{ $match->awayTeam->name }}</h1>
                    <p class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>{{ $match->scheduled_at->format('F d, Y \a\t H:i') }}
                        @if($match->venue)
                            <i class="fas fa-map-marker-alt me-2 ms-3"></i>{{ $match->venue }}
                        @endif
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        @if(auth()->check() && $match->status !== 'completed')
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#updateScoreModal">
                                <i class="fas fa-edit me-2"></i> Update Score
                            </button>
                        @endif
                        <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Matches
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Match Score Section -->
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body py-4">
                        <div class="row align-items-center text-center">
                            <!-- Home Team -->
                            <div class="col-4">
                                <div class="team-section">
                                    @if($match->homeTeam->logo)
                                        <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="team-logo-lg mb-3">
                                    @else
                                        <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $match->homeTeam->name }}" class="team-logo-lg mb-3">
                                    @endif
                                    <h4 class="fw-bold">{{ $match->homeTeam->name }}</h4>
                                    <small class="text-muted">Home</small>
                                </div>
                            </div>

                            <!-- Score -->
                            <div class="col-4">
                                <div class="score-section">
                                    @if($match->status === 'completed')
                                        <div class="score-display">
                                            <span class="score-number">{{ $match->home_score }}</span>
                                            <span class="score-separator">-</span>
                                            <span class="score-number">{{ $match->away_score }}</span>
                                        </div>
                                        <div class="match-status">
                                            <span class="badge bg-success">Full Time</span>
                                        </div>
                                    @elseif($match->status === 'live')
                                        <div class="score-display">
                                            <span class="score-number">{{ $match->home_score ?? 0 }}</span>
                                            <span class="score-separator">-</span>
                                            <span class="score-number">{{ $match->away_score ?? 0 }}</span>
                                        </div>
                                        <div class="match-status">
                                            <span class="badge bg-danger live-badge">LIVE</span>
                                        </div>
                                    @else
                                        <div class="match-time">
                                            <h3 class="fw-bold">{{ $match->scheduled_at->format('H:i') }}</h3>
                                            <span class="badge bg-warning">{{ ucfirst($match->status) }}</span>
                                        </div>
                                    @endif
                                    <div class="match-info mt-2">
                                        <small class="text-muted">{{ ucwords(str_replace('_', ' ', $match->stage)) }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Away Team -->
                            <div class="col-4">
                                <div class="team-section">
                                    @if($match->awayTeam->logo)
                                        <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="team-logo-lg mb-3">
                                    @else
                                        <img src="https://tse2.mm.bing.net/th/id/OIP.lpgOZ4hPNpsQjk22cDyIegHaFf?pid=Api&P=0&h=180" alt="{{ $match->awayTeam->name }}" class="team-logo-lg mb-3">
                                    @endif
                                    <h4 class="fw-bold">{{ $match->awayTeam->name }}</h4>
                                    <small class="text-muted">Away</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Match Events -->
        @if($match->events->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Match Events</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($match->events->sortBy('minute') as $event)
                            <div class="timeline-item">
                                <div class="timeline-marker bg-{{ $event->type === 'goal' ? 'success' : ($event->type === 'yellow_card' ? 'warning' : 'danger') }}">
                                    <i class="fas fa-{{ $event->type === 'goal' ? 'futbol' : ($event->type === 'yellow_card' ? 'square' : 'square') }}"></i>
                                </div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $event->player->name ?? 'Unknown Player' }}</strong>
                                            <span class="text-muted">- {{ ucwords(str_replace('_', ' ', $event->type)) }}</span>
                                        </div>
                                        <span class="badge bg-light text-dark">{{ $event->minute }}'</span>
                                    </div>
                                    @if($event->description)
                                        <small class="text-muted">{{ $event->description }}</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Team Lineups -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $match->homeTeam->name }} Lineup</h5>
                    </div>
                    <div class="card-body">
                        @if($match->homeTeam->players->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($match->homeTeam->players->take(11) as $player)
                                <div class="list-group-item d-flex align-items-center px-0">
                                    @if($player->jersey_number)
                                        <span class="badge bg-primary me-3">#{{ $player->jersey_number }}</span>
                                    @else
                                        <span class="badge bg-secondary me-3">-</span>
                                    @endif
                                    @if($player->avatar)
                                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=32&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32">
                                    @endif
                                    <div>
                                        <strong>{{ $player->name }}</strong><br>
                                        <small class="text-muted">{{ $player->position }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No players available</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ $match->awayTeam->name }} Lineup</h5>
                    </div>
                    <div class="card-body">
                        @if($match->awayTeam->players->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($match->awayTeam->players->take(11) as $player)
                                <div class="list-group-item d-flex align-items-center px-0">
                                    @if($player->jersey_number)
                                        <span class="badge bg-primary me-3">#{{ $player->jersey_number }}</span>
                                    @else
                                        <span class="badge bg-secondary me-3">-</span>
                                    @endif
                                    @if($player->avatar)
                                        <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32" style="object-fit: cover;">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=32&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle me-2" width="32" height="32">
                                    @endif
                                    <div>
                                        <strong>{{ $player->name }}</strong><br>
                                        <small class="text-muted">{{ $player->position }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center py-3">No players available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Score Modal -->
    @if(auth()->check() && $match->status !== 'completed')
    <div class="modal fade" id="updateScoreModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Match Score</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('fuma.matches.updateScore', $match) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label for="home_score" class="form-label">{{ $match->homeTeam->name }} Score</label>
                                <input type="number" class="form-control" id="home_score" name="home_score" 
                                       value="{{ $match->home_score ?? 0 }}" min="0" required>
                            </div>
                            <div class="col-6">
                                <label for="away_score" class="form-label">{{ $match->awayTeam->name }} Score</label>
                                <input type="number" class="form-control" id="away_score" name="away_score" 
                                       value="{{ $match->away_score ?? 0 }}" min="0" required>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="status" class="form-label">Match Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="live" {{ $match->status === 'live' ? 'selected' : '' }}>Live</option>
                                <option value="completed" {{ $match->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Score</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('styles')
<style>
.team-logo-lg {
    width: 80px;
    height: 80px;
    object-fit: contain;
}

.score-display {
    font-size: 3rem;
    font-weight: bold;
    line-height: 1;
}

.score-number {
    color: var(--primary-color);
}

.score-separator {
    color: #6c757d;
    margin: 0 15px;
}

.match-time h3 {
    color: var(--primary-color);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -19px;
    top: 30px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 12px;
}

.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid var(--primary-color);
}
</style>
@endpush
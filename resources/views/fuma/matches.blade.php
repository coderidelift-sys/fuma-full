@extends('layouts.fuma')

@section('title', 'All Matches')

@section('content')
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Matches</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'organizer', 'committee']))
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createMatchModal">
                                <i class="fas fa-plus me-2"></i> Schedule Match
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('fuma.matches') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="all">All Status</option>
                                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Live</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tournament_id" class="form-label">Tournament</label>
                            <select name="tournament_id" id="tournament_id" class="form-select">
                                <option value="all">All Tournaments</option>
                                @foreach($tournaments as $tournament)
                                    <option value="{{ $tournament->id }}" {{ request('tournament_id') == $tournament->id ? 'selected' : '' }}>{{ $tournament->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Matches List -->
        <div class="row g-4">
            @forelse($matches as $match)
                <div class="col-lg-6">
                    <div class="card match-card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-primary">{{ $match->tournament->name }}</span>
                                <div class="text-end">
                                    <div class="small text-muted">{{ $match->scheduled_at->format('M j, Y') }}</div>
                                    <div class="small text-muted">{{ $match->scheduled_at->format('H:i') }}</div>
                                </div>
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
                                    @elseif($match->status === 'ongoing')
                                        <div class="live-indicator bg-danger text-white px-3 py-2 rounded">
                                            <i class="fas fa-circle me-1"></i> LIVE
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
                                <div>
                                    @if($match->status === 'completed')
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($match->status === 'ongoing')
                                        <span class="badge bg-danger">Live</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Upcoming</span>
                                    @endif
                                    
                                    @if($match->venue)
                                        <small class="text-muted ms-2">
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $match->venue }}
                                        </small>
                                    @endif
                                </div>
                                
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
                        <h4 class="text-muted">No matches found</h4>
                        <p class="text-muted">Try adjusting your filter criteria.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($matches->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $matches->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Create Match Modal -->
    @auth
        @if(auth()->user()->hasAnyRole(['admin', 'organizer', 'committee']))
            <div class="modal fade" id="createMatchModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Schedule New Match</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('api.matches.store') }}" method="POST" id="createMatchForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="tournament_id" class="form-label">Tournament</label>
                                        <select class="form-select" id="tournament_id" name="tournament_id" required>
                                            <option value="">Select Tournament</option>
                                            @foreach($tournaments as $tournament)
                                                <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="scheduled_at" class="form-label">Match Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="home_team_id" class="form-label">Home Team</label>
                                        <select class="form-select" id="home_team_id" name="home_team_id" required>
                                            <option value="">Select Home Team</option>
                                            @foreach(\App\Models\Team::all() as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="away_team_id" class="form-label">Away Team</label>
                                        <select class="form-select" id="away_team_id" name="away_team_id" required>
                                            <option value="">Select Away Team</option>
                                            @foreach(\App\Models\Team::all() as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="venue" class="form-label">Venue</label>
                                        <input type="text" class="form-control" id="venue" name="venue">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="referee" class="form-label">Referee</label>
                                        <input type="text" class="form-control" id="referee" name="referee">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Schedule Match</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@push('styles')
<style>
    .filter-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .live-indicator {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .score-display {
        font-weight: bold;
        font-size: 1.1rem;
    }

    .vs-indicator {
        font-weight: bold;
        color: var(--secondary-color);
    }
</style>
@endpush

@push('scripts')
<script>
    // Handle match creation form
    document.getElementById('createMatchForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("api.matches.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('createMatchModal')).hide();
                location.reload();
            } else {
                alert('Error scheduling match: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error scheduling match');
        });
    });

    // Prevent selecting same team for home and away
    document.getElementById('home_team_id')?.addEventListener('change', function() {
        const awaySelect = document.getElementById('away_team_id');
        const homeTeamId = this.value;
        
        Array.from(awaySelect.options).forEach(option => {
            if (option.value === homeTeamId) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });

    document.getElementById('away_team_id')?.addEventListener('change', function() {
        const homeSelect = document.getElementById('home_team_id');
        const awayTeamId = this.value;
        
        Array.from(homeSelect.options).forEach(option => {
            if (option.value === awayTeamId) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
</script>
@endpush
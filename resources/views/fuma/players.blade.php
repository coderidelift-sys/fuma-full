@extends('layouts.fuma')

@section('title', 'All Players')

@section('content')
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Players</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager', 'organizer']))
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPlayerModal">
                                <i class="fas fa-plus me-2"></i> Add Player
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
                <form method="GET" action="{{ route('fuma.players') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="position" class="form-label">Position</label>
                            <select name="position" id="position" class="form-select">
                                <option value="all">All Positions</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position }}" {{ request('position') === $position ? 'selected' : '' }}>{{ $position }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search players..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Players Grid -->
        <div class="row g-4">
            @forelse($players as $player)
                <div class="col-lg-3 col-md-6">
                    <div class="player-card">
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
                            <div class="mt-2">
                                <a href="{{ route('fuma.player-detail', $player->id) }}" class="btn btn-sm btn-light">
                                    <i class="fas fa-eye me-1"></i> View Profile
                                </a>
                            </div>
                        </div>
                        @if($player->jersey_number)
                            <div class="player-number">{{ $player->jersey_number }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-user fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No players found</h4>
                        <p class="text-muted">Try adjusting your search criteria.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $players->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Create Player Modal -->
    @auth
        @if(auth()->user()->hasAnyRole(['admin', 'manager', 'organizer']))
            <div class="modal fade" id="createPlayerModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Player</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('api.players.store') }}" method="POST" enctype="multipart/form-data" id="createPlayerForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="player_name" class="form-label">Player Name</label>
                                        <input type="text" class="form-control" id="player_name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="position" class="form-label">Position</label>
                                        <select class="form-select" id="position" name="position" required>
                                            <option value="">Select Position</option>
                                            <option value="Forward">Forward</option>
                                            <option value="Midfielder">Midfielder</option>
                                            <option value="Defender">Defender</option>
                                            <option value="Goalkeeper">Goalkeeper</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="jersey_number" class="form-label">Jersey Number</label>
                                        <input type="text" class="form-control" id="jersey_number" name="jersey_number">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">Birth Date</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <input type="text" class="form-control" id="nationality" name="nationality">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="team_id" class="form-label">Team</label>
                                        <select class="form-select" id="team_id" name="team_id" required>
                                            <option value="">Select Team</option>
                                            @foreach(\App\Models\Team::all() as $team)
                                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="height" class="form-label">Height (cm)</label>
                                        <input type="number" class="form-control" id="height" name="height" min="150" max="220">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" min="50" max="120">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="avatar" class="form-label">Player Photo</label>
                                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add Player</button>
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
</style>
@endpush

@push('scripts')
<script>
    // Handle player creation form
    document.getElementById('createPlayerForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("api.players.store") }}', {
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
                bootstrap.Modal.getInstance(document.getElementById('createPlayerModal')).hide();
                location.reload();
            } else {
                alert('Error adding player: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding player');
        });
    });
</script>
@endpush
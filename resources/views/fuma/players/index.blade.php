@extends('layouts.fuma')

@section('title', 'All Players - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">All Players</h1>
                    <p class="mb-0">Browse and manage football players</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('fuma.players.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Add Player
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card filter-card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('fuma.players.index') }}">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label for="position" class="form-label">Position</label>
                                    <select class="form-select" id="position" name="position">
                                        <option value="">All Positions</option>
                                        <option value="Goalkeeper" {{ request('position') === 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                        <option value="Defender" {{ request('position') === 'Defender' ? 'selected' : '' }}>Defender</option>
                                        <option value="Midfielder" {{ request('position') === 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                        <option value="Forward" {{ request('position') === 'Forward' ? 'selected' : '' }}>Forward</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="team" class="form-label">Team</label>
                                    <select class="form-select" id="team" name="team">
                                        <option value="">All Teams</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ request('team') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Player name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                                        <option value="goals" {{ request('sort') === 'goals' ? 'selected' : '' }}>Goals</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('fuma.players.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Players Grid -->
        <div class="row g-4">
            @forelse($players as $player)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        @if($player->avatar)
                            <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle mb-3" width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=80&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle mb-3" width="80" height="80">
                        @endif
                        <h6 class="card-title">{{ $player->name }}</h6>
                        @if($player->jersey_number)
                            <div class="badge bg-primary mb-2">#{{ $player->jersey_number }}</div>
                        @endif
                        <p class="text-muted small mb-2">{{ $player->position }}</p>
                        @if($player->team)
                            <p class="text-muted small mb-2">{{ $player->team->name }}</p>
                        @endif
                        <div class="d-flex justify-content-center gap-1 mb-3 flex-wrap">
                            <span class="badge bg-light text-dark small">
                                <i class="fas fa-futbol me-1"></i>{{ $player->goals_scored }}
                            </span>
                            <span class="badge bg-light text-dark small">
                                <i class="fas fa-hands-helping me-1"></i>{{ $player->assists }}
                            </span>
                            <span class="badge bg-light text-dark small">
                                <i class="fas fa-star text-warning me-1"></i>{{ number_format($player->rating, 1) }}
                            </span>
                        </div>
                        @if($player->nationality)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-flag me-1"></i> {{ $player->nationality }}
                            </p>
                        @endif
                        <a href="{{ route('fuma.players.show', $player) }}" class="btn btn-sm btn-outline-primary">
                            View Profile
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-user-circle fa-3x mb-3"></i>
                    <p>No players found matching your criteria.</p>
                    <a href="{{ route('fuma.players.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Add First Player
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($players->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $players->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
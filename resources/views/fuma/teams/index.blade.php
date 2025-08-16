@extends('layouts.fuma')

@section('title', 'All Teams - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">All Teams</h1>
                    <p class="mb-0">Browse and manage football teams</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('fuma.teams.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Register Team
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
                        <form method="GET" action="{{ route('fuma.teams.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="city" class="form-label">City</label>
                                    <select class="form-select" id="city" name="city">
                                        <option value="">All Cities</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Team name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                                        <option value="city" {{ request('sort') === 'city' ? 'selected' : '' }}>City</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Grid -->
        <div class="row g-4">
            @forelse($teams as $team)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100">
                    <div class="card-body text-center">
                        @if($team->logo)
                            <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="mb-3" width="80" height="80" style="object-fit: contain;">
                        @else
                            <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $team->name }}" class="mb-3" width="80" height="80" style="object-fit: contain;">
                        @endif
                        <h5 class="card-title">{{ $team->name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $team->city }}
                        </p>
                        @if($team->description)
                            <p class="card-text small text-muted">{{ Str::limit($team->description, 80) }}</p>
                        @endif
                        <div class="d-flex justify-content-center gap-2 mb-3">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-users me-1"></i> {{ $team->players->count() }} players
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-star text-warning me-1"></i> {{ number_format($team->rating, 1) }}
                            </span>
                            @if($team->trophies_count > 0)
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-trophy text-warning me-1"></i> {{ $team->trophies_count }}
                                </span>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('fuma.teams.show', $team) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                <i class="fas fa-eye me-1"></i> View Team
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <p>No teams found matching your criteria.</p>
                    <a href="{{ route('fuma.teams.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Register First Team
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($teams->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $teams->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
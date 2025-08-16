@extends('layouts.fuma')

@section('title', 'All Tournaments - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">All Tournaments</h1>
                    <p class="mb-0">Manage and view all football tournaments</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('fuma.tournaments.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Create Tournament
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
                        <form method="GET" action="{{ route('fuma.tournaments.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Active</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           placeholder="Tournament name..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="date_from" class="form-label">Start Date From</label>
                                    <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tournaments Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Teams</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tournaments as $tournament)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($tournament->logo)
                                                    <img src="{{ asset('storage/' . $tournament->logo) }}" alt="{{ $tournament->name }}" class="rounded-circle me-3" width="40" height="40">
                                                @else
                                                    <img src="https://tse1.mm.bing.net/th/id/OIP.MaIk4N5rw51_K6gHkokGUgHaGl?pid=Api" alt="{{ $tournament->name }}" class="rounded-circle me-3" width="40" height="40">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $tournament->name }}</h6>
                                                    <small class="text-muted">{{ Str::limit($tournament->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $tournament->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $tournament->end_date->format('Y-m-d') }}</td>
                                        <td>{{ $tournament->teams->count() }}/{{ $tournament->max_teams }}</td>
                                        <td>{{ $tournament->venue ?: 'TBD' }}</td>
                                        <td>
                                            <span class="status-badge badge-{{ $tournament->status === 'ongoing' ? 'active' : $tournament->status }}">
                                                {{ ucfirst($tournament->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.tournaments.show', $tournament) }}" class="btn btn-sm btn-outline-primary action-btn">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-trophy fa-3x mb-3"></i>
                                                <p>No tournaments found matching your criteria.</p>
                                                <a href="{{ route('fuma.tournaments.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i> Create First Tournament
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        @if($tournaments->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $tournaments->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
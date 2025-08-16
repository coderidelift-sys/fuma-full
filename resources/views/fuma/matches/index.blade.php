@extends('layouts.fuma')

@section('title', 'All Matches - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">All Matches</h1>
                    <p class="mb-0">View and manage football matches</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        <a href="{{ route('fuma.matches.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i> Schedule Match
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
                        <form method="GET" action="{{ route('fuma.matches.index') }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="live" {{ request('status') === 'live' ? 'selected' : '' }}>Live</option>
                                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="tournament" class="form-label">Tournament</label>
                                    <select class="form-select" id="tournament" name="tournament">
                                        <option value="">All Tournaments</option>
                                        @foreach($tournaments as $tournament)
                                            <option value="{{ $tournament->id }}" {{ request('tournament') == $tournament->id ? 'selected' : '' }}>
                                                {{ $tournament->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fas fa-search me-1"></i> Filter
                                    </button>
                                    <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matches Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Tournament</th>
                                        <th>Stage</th>
                                        <th>Home Team</th>
                                        <th>Score</th>
                                        <th>Away Team</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($matches as $match)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $match->scheduled_at->format('M d, Y') }}</strong><br>
                                                <small class="text-muted">{{ $match->scheduled_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.tournaments.show', $match->tournament) }}" class="text-decoration-none">
                                                {{ $match->tournament->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $match->stage)) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($match->homeTeam->logo)
                                                    <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $match->homeTeam->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($match->status === 'completed')
                                                <span class="match-score">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                            @elseif($match->status === 'live')
                                                <span class="badge bg-danger live-badge">LIVE</span>
                                            @else
                                                <span class="text-muted">vs</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($match->awayTeam->logo)
                                                    <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse2.mm.bing.net/th/id/OIP.lpgOZ4hPNpsQjk22cDyIegHaFf?pid=Api&P=0&h=180" alt="{{ $match->awayTeam->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $match->awayTeam->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $match->venue ?: 'TBD' }}</td>
                                        <td>
                                            @if($match->status === 'live')
                                                <span class="badge bg-danger live-badge">Live</span>
                                            @elseif($match->status === 'completed')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($match->status === 'cancelled')
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @else
                                                <span class="badge bg-warning">Scheduled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.matches.show', $match) }}" class="btn btn-sm btn-outline-primary action-btn">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-futbol fa-3x mb-3"></i>
                                                <p>No matches found matching your criteria.</p>
                                                <a href="{{ route('fuma.matches.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus me-2"></i> Schedule First Match
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
        @if($matches->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $matches->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection
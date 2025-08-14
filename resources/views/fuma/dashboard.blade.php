@extends('layouts.fuma')

@section('title', 'Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-8 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Welcome {{ auth()->user()->name }}! 🎉</h5>
                            <p class="mb-4">You have <span class="fw-bold">{{ $stats['tournaments']['total'] }}</span> tournaments, <span class="fw-bold">{{ $stats['teams']['total'] }}</span> teams, and <span class="fw-bold">{{ $stats['players']['total'] }}</span> players in your system.</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <i class="ri-football-line ri-8x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="ri-trophy-line ri-2x text-warning"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Tournaments</span>
                            <h3 class="card-title mb-2">{{ $stats['tournaments']['total'] }}</h3>
                            <small class="text-success fw-semibold">
                                <i class="ri-arrow-up-line"></i> {{ $stats['tournaments']['ongoing'] }} ongoing
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="ri-team-line ri-2x text-info"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Teams</span>
                            <h3 class="card-title mb-2">{{ $stats['teams']['total'] }}</h3>
                            <small class="text-success fw-semibold">
                                <i class="ri-arrow-up-line"></i> {{ count($stats['teams']['top_rated']) }} top rated
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="ri-user-line ri-2x text-success"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Players</span>
                            <h3 class="card-title mb-2">{{ $stats['players']['total'] }}</h3>
                            <small class="text-success fw-semibold">
                                <i class="ri-arrow-up-line"></i> {{ count($stats['players']['top_scorers']) }} top scorers
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="ri-football-line ri-2x text-danger"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Matches</span>
                            <h3 class="card-title mb-2">{{ $stats['matches']['total'] }}</h3>
                            <small class="text-success fw-semibold">
                                <i class="ri-arrow-up-line"></i> {{ $stats['matches']['live'] }} live
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-4 order-2">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tournament Status Overview</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ri-time-line"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $stats['tournaments']['upcoming'] }}</h6>
                                    <small>Upcoming</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ri-play-circle-line"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $stats['tournaments']['ongoing'] }}</h6>
                                    <small>Ongoing</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-md me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="ri-check-line"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $stats['tournaments']['completed'] }}</h6>
                                    <small>Completed</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Top Rated Teams</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="topTeams" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topTeams">
                            <a class="dropdown-item" href="{{ route('fuma.teams.index') }}">View All Teams</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($stats['teams']['top_rated']) > 0)
                        @foreach($stats['teams']['top_rated']->take(5) as $team)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    @if($team['logo'])
                                        <img src="{{ asset('storage/' . $team['logo']) }}" alt="{{ $team['name'] }}" class="rounded-circle">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="ri-team-line"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $team['name'] }}</h6>
                                    <small class="text-muted">{{ $team['city'] }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-primary me-2">{{ number_format($team['rating'], 1) }}</span>
                                <a href="{{ route('fuma.teams.show', $team['id']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No teams available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Top Scorers</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="topScorers" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topScorers">
                            <a class="dropdown-item" href="{{ route('fuma.players.index') }}">View All Players</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(count($stats['players']['top_scorers']) > 0)
                        @foreach($stats['players']['top_scorers']->take(5) as $player)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    @if($player['avatar'])
                                        <img src="{{ asset('storage/' . $player['avatar']) }}" alt="{{ $player['name'] }}" class="rounded-circle">
                                    @else
                                        <span class="avatar-initial rounded bg-label-secondary">
                                            <i class="ri-user-line"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="mb-0">{{ $player['name'] }}</h6>
                                    <small class="text-muted">{{ $player['position'] }}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-label-success me-2">{{ $player['goals_scored'] }} goals</span>
                                <a href="{{ route('fuma.players.show', $player['id']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No players available</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Recent Matches</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="recentMatches" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="recentMatches">
                            <a class="dropdown-item" href="{{ route('fuma.matches.index') }}">View All Matches</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ri-football-line"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">Match Status</h6>
                                <small class="text-muted">Live: {{ $stats['matches']['live'] }}, Scheduled: {{ $stats['matches']['upcoming'] }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="ri-time-line"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">Upcoming</h6>
                                <small class="text-muted">{{ $stats['matches']['upcoming'] }} matches</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ri-check-line"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="mb-0">Completed</h6>
                                <small class="text-muted">{{ $stats['matches']['completed'] }} matches</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer'))
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fuma.tournaments.create') }}" class="btn btn-primary w-100">
                                <i class="ri-add-line me-2"></i>Create Tournament
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fuma.teams.create') }}" class="btn btn-info w-100">
                                <i class="ri-add-line me-2"></i>Create Team
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('organizer'))
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fuma.players.create') }}" class="btn btn-success w-100">
                                <i class="ri-add-line me-2"></i>Add Player
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('fuma.matches.create') }}" class="btn btn-warning w-100">
                                <i class="ri-add-line me-2"></i>Schedule Match
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

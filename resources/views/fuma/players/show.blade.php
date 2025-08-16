@extends('layouts.fuma')

@section('title', $player->name . ' - Player Profile')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if($player->avatar)
                            <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle me-3" width="80" height="80" style="object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=80&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle me-3" width="80" height="80">
                        @endif
                        <div>
                            <h1 class="fw-bold mb-1">{{ $player->name }}</h1>
                            <p class="mb-0">
                                {{ $player->position }}
                                @if($player->jersey_number)
                                    <span class="badge bg-primary ms-2">#{{ $player->jersey_number }}</span>
                                @endif
                            </p>
                            @if($player->team)
                                <p class="mb-0 text-light">
                                    <i class="fas fa-users me-2"></i>{{ $player->team->name }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        @if(auth()->check())
                            <button class="btn btn-light">
                                <i class="fas fa-edit me-2"></i> Edit Player
                            </button>
                        @endif
                        <a href="{{ route('fuma.players.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Players
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="row">
            <!-- Player Information -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Player Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-futbol me-2"></i>Position</span>
                                <span class="fw-bold">{{ $player->position }}</span>
                            </li>
                            @if($player->jersey_number)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-tshirt me-2"></i>Jersey Number</span>
                                <span class="fw-bold">#{{ $player->jersey_number }}</span>
                            </li>
                            @endif
                            @if($player->age)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-birthday-cake me-2"></i>Age</span>
                                <span class="fw-bold">{{ $player->age }} years</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-flag me-2"></i>Nationality</span>
                                <span class="fw-bold">{{ $player->nationality }}</span>
                            </li>
                            @if($player->height)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-ruler-vertical me-2"></i>Height</span>
                                <span class="fw-bold">{{ $player->height }} cm</span>
                            </li>
                            @endif
                            @if($player->weight)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-weight me-2"></i>Weight</span>
                                <span class="fw-bold">{{ $player->weight }} kg</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span class="text-muted"><i class="fas fa-star me-2"></i>Rating</span>
                                <span class="fw-bold">{{ number_format($player->rating, 1) }}/5.0</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Player Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Career Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $player_stats['matches_played'] }}</div>
                                <div class="stats-label">Matches</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $player_stats['goals'] }}</div>
                                <div class="stats-label">Goals</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $player_stats['assists'] }}</div>
                                <div class="stats-label">Assists</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="stats-number text-primary">{{ $player_stats['clean_sheets'] }}</div>
                                <div class="stats-label">Clean Sheets</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number text-warning">{{ $player_stats['yellow_cards'] }}</div>
                                <div class="stats-label">Yellow Cards</div>
                            </div>
                            <div class="col-6">
                                <div class="stats-number text-danger">{{ $player_stats['red_cards'] }}</div>
                                <div class="stats-label">Red Cards</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Player Details -->
            <div class="col-lg-8">
                <!-- Team Information -->
                @if($player->team)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Current Team</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($player->team->logo)
                                <img src="{{ asset('storage/' . $player->team->logo) }}" alt="{{ $player->team->name }}" class="me-3" width="60" height="60" style="object-fit: contain;">
                            @else
                                <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $player->team->name }}" class="me-3" width="60" height="60" style="object-fit: contain;">
                            @endif
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $player->team->name }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $player->team->city }}, {{ $player->team->country }}
                                </p>
                            </div>
                            <a href="{{ route('fuma.teams.show', $player->team) }}" class="btn btn-outline-primary">
                                <i class="fas fa-eye me-2"></i> View Team
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Match Events -->
                @if($player->matchEvents->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Match Events</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Match</th>
                                        <th>Event</th>
                                        <th>Minute</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($player->matchEvents->sortByDesc('created_at')->take(10) as $event)
                                    <tr>
                                        <td>{{ $event->match->scheduled_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('fuma.matches.show', $event->match) }}" class="text-decoration-none">
                                                {{ $event->match->homeTeam->name }} vs {{ $event->match->awayTeam->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $event->type === 'goal' ? 'success' : ($event->type === 'yellow_card' ? 'warning' : 'danger') }}">
                                                {{ ucwords(str_replace('_', ' ', $event->type)) }}
                                            </span>
                                        </td>
                                        <td>{{ $event->minute }}'</td>
                                        <td>
                                            <a href="{{ route('fuma.matches.show', $event->match) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @else
                <div class="card">
                    <div class="card-body text-center py-4">
                        <i class="fas fa-futbol fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No match events recorded yet.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
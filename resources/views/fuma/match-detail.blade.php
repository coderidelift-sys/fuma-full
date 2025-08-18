<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $match->homeTeam->name ?? 'Home Team' }} vs {{ $match->awayTeam->name ?? 'Away Team' }} - Match Details
    </title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @vite('resources/css/match_detail.css')
</head>

<body>
    <!-- Navigation with Gradient -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.html">
                <i class="fas fa-futbol me-2"></i> FUMA
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tournaments.index') }}">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('matches.index') }}">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('players.index') }}">Players</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Match Header -->
    <header class="match-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Home Team -->
                <div class="col-md-5 text-center text-md-end">
                    <div class="d-flex flex-column align-items-center align-items-md-end">
                        <img src="{{ $match->homeTeam->logo ?? 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                            alt="Team Logo" class="team-logo-lg mb-2">
                        <h3 class="fw-bold mb-0">{{ $match->homeTeam->name }}</h3>
                        <small
                            class="opacity-75">({{ $match->homeTeam->short_name ?? substr($match->homeTeam->name, 0, 3) }})</small>
                    </div>
                </div>

                <!-- Match Score -->
                <div class="col-md-2 text-center my-4 my-md-0">
                    <div class="d-flex flex-column align-items-center">
                        <div class="match-score-lg bg-white text-dark px-4 py-2 rounded">
                            @if (in_array($match->status, ['completed', 'live']) && $match->home_score !== null && $match->away_score !== null)
                                {{ $match->home_score }} - {{ $match->away_score }}
                            @else
                                VS
                            @endif
                        </div>
                        <div class="mt-2">
                            @if ($match->status === 'live')
                                <span class="badge bg-danger live-badge">Live</span>
                                <div class="text-white small mt-1">{{ $match->current_minute ?? '0' }}'</div>
                            @elseif($match->status === 'completed')
                                <span class="badge bg-secondary">Completed</span>
                            @else
                                <span class="badge bg-primary">Scheduled</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Away Team -->
                <div class="col-md-5 text-center text-md-start">
                    <div class="d-flex flex-column align-items-center align-items-md-start">
                        <img src="{{ $match->awayTeam->logo ?? 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                            alt="Team Logo" class="team-logo-lg mb-2">
                        <h3 class="fw-bold mb-0">{{ $match->awayTeam->name }}</h3>
                        <small
                            class="opacity-75">({{ $match->awayTeam->short_name ?? substr($match->awayTeam->name, 0, 3) }})</small>
                    </div>
                </div>
            </div>

            <!-- Match Info -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <span class="text-white opacity-75">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $match->scheduled_at->format('F j, Y | H:i') }}
                            {{ $match->scheduled_at->format('T') }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $match->venue ?? 'TBD' }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-trophy me-1"></i> {{ $match->tournament->name }} -
                            {{ ucfirst(str_replace('_', ' ', $match->stage)) }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-user me-1"></i> Referee: {{ $match->referee ?? 'TBD' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mb-5">
        <!-- Match Navigation -->
        <ul class="nav nav-tabs mb-4" id="matchTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="summary-tab" data-bs-toggle="tab" data-bs-target="#summary"
                    type="button">
                    <i class="fas fa-list-alt me-1"></i> Summary
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="lineups-tab" data-bs-toggle="tab" data-bs-target="#lineups"
                    type="button">
                    <i class="fas fa-users me-1"></i> Lineups
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats"
                    type="button">
                    <i class="fas fa-chart-bar me-1"></i> Statistics
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="commentary-tab" data-bs-toggle="tab" data-bs-target="#commentary"
                    type="button">
                    <i class="fas fa-comment-alt me-1"></i> Commentary
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management"
                    type="button">
                    <i class="fas fa-cogs me-1"></i> Match Management
                </button>
            </li>
        </ul>

        <div class="tab-content" id="matchTabContent">
            <!-- Summary Tab -->
            <div class="tab-pane fade show active" id="summary" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Match Timeline -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Match Events</h5>

                                <!-- First Half Header -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-uppercase text-muted">First Half</h6>
                                    </div>
                                    <div class="badge bg-light text-dark">
                                        {{ $match->events->where('minute', '<=', 45)->where('type', 'goal')->where('team_id', $match->home_team_id)->count() }}-{{ $match->events->where('minute', '<=', 45)->where('type', 'goal')->where('team_id', $match->away_team_id)->count() }}
                                    </div>
                                </div>

                                <!-- First Half Events -->
                                @forelse($match->events->where('minute', '<=', 45) as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="event-icon {{ $event->type === 'goal' ? 'goal-icon' : ($event->type === 'yellow_card' ? 'yellow-card-icon' : ($event->type === 'red_card' ? 'red-card-icon' : 'substitution-icon')) }} me-2">
                                                    <i class="fas fa-{{ $event->type === 'goal' ? 'futbol' : ($event->type === 'yellow_card' || $event->type === 'red_card' ? 'card' : 'exchange-alt') }} fa-xs"></i>
                                                </span>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $event->type)) }}</strong>
                                                @if ($event->player)
                                                    {{ $event->player->name }}
                                                    @if ($event->team)
                                                        ({{ $event->team->name }})
                                                    @endif
                                                @elseif($event->team)
                                                    {{ $event->team->name }}
                                                @endif
                                                @if ($event->description)
                                                    - {{ $event->description }}
                                                @endif
                                            </div>
                                            <span class="text-muted">{{ $event->minute }}'</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No events recorded in first half
                                    </div>
                                @endforelse

                                <!-- Second Half Header -->
                                <div class="d-flex align-items-center mb-3 mt-4">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0 text-uppercase text-muted">Second Half</h6>
                                    </div>
                                    <div class="badge bg-light text-dark">
                                        {{ $match->events->where('minute', '>', 45)->where('type', 'goal')->where('team_id', $match->home_team_id)->count() }}-{{ $match->events->where('minute', '>', 45)->where('type', 'goal')->where('team_id', $match->away_team_id)->count() }}
                                    </div>
                                </div>

                                <!-- Second Half Events -->
                                @forelse($match->events->where('minute', '>', 45) as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-dot"></div>
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <span class="event-icon {{ $event->type === 'goal' ? 'goal-icon' : ($event->type === 'yellow_card' ? 'yellow-card-icon' : ($event->type === 'red_card' ? 'red-card-icon' : 'substitution-icon')) }} me-2">
                                                    <i class="fas fa-{{ $event->type === 'goal' ? 'futbol' : ($event->type === 'yellow_card' || $event->type === 'red_card' ? 'card' : 'exchange-alt') }} fa-xs"></i>
                                                </span>
                                                <strong>{{ ucfirst(str_replace('_', ' ', $event->type)) }}</strong>
                                                @if ($event->player)
                                                    {{ $event->player->name }}
                                                    @if ($event->team)
                                                        ({{ $event->team->name }})
                                                    @endif
                                                @elseif($event->team)
                                                    {{ $event->team->name }}
                                                @endif
                                                @if ($event->description)
                                                    - {{ $event->description }}
                                                @endif
                                            </div>
                                            <span class="text-muted">{{ $event->minute }}'</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No events recorded in second half
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                    <!-- Match Statistics -->
                    <div class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Match Statistics</h5>
                            </div>
                            <div class="card-body">
                                <!-- Possession -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['possession']['home'] }}%</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['possession']['home'] }}%;"></div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['possession']['away'] }}%</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Possession</div>

                                <!-- Shots -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['shots']['home'] }}</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['shots']['home'] > 0 ? ($matchStats['shots']['home'] / max($matchStats['shots']['home'], $matchStats['shots']['away'])) * 100 : 0 }}%;">
                                        </div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['shots']['away'] }}</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Shots</div>

                                <!-- Shots on Target -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['shots_on_target']['home'] }}</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['shots_on_target']['home'] > 0 ? ($matchStats['shots_on_target']['home'] / max($matchStats['shots_on_target']['home'], $matchStats['shots_on_target']['away'])) * 100 : 0 }}%;">
                                        </div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['shots_on_target']['away'] }}</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Shots on Target</div>

                                <!-- Corners -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['corners']['home'] }}</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['corners']['home'] > 0 ? ($matchStats['corners']['home'] / max($matchStats['corners']['home'], $matchStats['corners']['away'])) * 100 : 0 }}%;">
                                        </div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['corners']['away'] }}</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Corners</div>

                                <!-- Fouls -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['fouls']['home'] }}</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['fouls']['home'] > 0 ? ($matchStats['fouls']['home'] / max($matchStats['fouls']['home'], $matchStats['fouls']['away'])) * 100 : 0 }}%;">
                                        </div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['fouls']['away'] }}</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Fouls</div>

                                <!-- Offsides -->
                                <div class="stats-comparison">
                                    <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                    <div class="stats-value">{{ $matchStats['offsides']['home'] }}</div>
                                    <div class="stats-bar-container">
                                        <div class="stats-bar home"
                                            style="width: {{ $matchStats['offsides']['home'] > 0 ? ($matchStats['offsides']['home'] / max($matchStats['offsides']['home'], $matchStats['offsides']['away'])) * 100 : 0 }}%;">
                                        </div>
                                    </div>
                                    <div class="stats-value">{{ $matchStats['offsides']['away'] }}</div>
                                    <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                </div>
                                <div class="text-center text-muted small mb-3">Offsides</div>
                            </div>
                        </div>


                        <!-- Match Officials -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Match Officials</h5>
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted">Referee</span>
                                        <span class="fw-bold">{{ $match->referee ?? 'TBD' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lineups Tab -->
            <div class="tab-pane fade" id="lineups" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Home Team Lineup -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <h5 class="fw-bold mb-3">{{ $match->homeTeam->name }} Lineup</h5>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Starting XI</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="lineup-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Player</th>
                                                    <th>Position</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($homeLineup['starting_xi'] ?? [] as $player)
                                                    <tr>
                                                        <td>{{ $player['jersey_number'] }}</td>
                                                        <td class="player-cell">
                                                            <img src="{{ $player['avatar'] }}" alt="Player"
                                                                class="player-photo me-2">
                                                            {{ $player['name'] }}
                                                            @if ($player['is_captain'])
                                                                <span class="badge bg-warning ms-1">C</span>
                                                            @endif
                                                        </td>
                                                        <td><span
                                                                class="badge bg-{{ $player['position'] === 'GK' ? 'primary' : ($player['position'] === 'DEF' ? 'success' : ($player['position'] === 'MID' ? 'warning text-dark' : 'danger')) }}">{{ $player['position'] }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No
                                                            lineup data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Substitutes</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="lineup-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Player</th>
                                                    <th>Position</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($homeLineup['substitute'] ?? [] as $player)
                                                    <tr>
                                                        <td>{{ $player['jersey_number'] }}</td>
                                                        <td class="player-cell">
                                                            <img src="{{ $player['avatar'] ?? 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740' }}"
                                                                alt="Player" class="player-photo me-2">
                                                            {{ $player['name'] }}
                                                        </td>
                                                        <td><span
                                                                class="badge bg-{{ $player['position'] === 'GK' ? 'primary' : ($player['position'] === 'DEF' ? 'success' : ($player['position'] === 'MID' ? 'warning text-dark' : 'danger')) }}">{{ $player['position'] }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($player['substitution_time'])
                                                                <span class="badge bg-info">ON
                                                                    {{ $player['substitution_time'] }}'</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No
                                                            substitutes available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Away Team Lineup -->
                            <div class="col-md-6">
                                <h5 class="fw-bold mb-3">{{ $match->awayTeam->name }} Lineup</h5>

                                <div class="card mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Starting XI</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="lineup-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Player</th>
                                                    <th>Position</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($awayLineup['starting_xi'] ?? [] as $player)
                                                    <tr>
                                                        <td>{{ $player['jersey_number'] }}</td>
                                                        <td class="player-cell">
                                                            <img src="{{ $player['avatar'] ?? 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740' }}"
                                                                alt="Player" class="player-photo me-2">
                                                            {{ $player['name'] }}
                                                            @if ($player['is_captain'])
                                                                <span class="badge bg-warning ms-1">C</span>
                                                            @endif
                                                        </td>
                                                        <td><span
                                                                class="badge bg-{{ $player['position'] === 'GK' ? 'primary' : ($player['position'] === 'DEF' ? 'success' : ($player['position'] === 'MID' ? 'warning text-dark' : 'danger')) }}">{{ $player['position'] }}</span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No
                                                            lineup data available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Substitutes</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="lineup-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Player</th>
                                                    <th>Position</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($awayLineup['substitute'] ?? [] as $player)
                                                    <tr>
                                                        <td>{{ $player['jersey_number'] }}</td>
                                                        <td class="player-cell">
                                                            <img src="{{ $player['avatar'] ?? 'https://img.freepik.com/free-vector/blue-circle-with-white-user_78370-4707.jpg?semt=ais_hybrid&w=740' }}"
                                                                alt="Player" class="player-photo me-2">
                                                            {{ $player['name'] }}
                                                        </td>
                                                        <td><span
                                                                class="badge bg-{{ $player['position'] === 'GK' ? 'primary' : ($player['position'] === 'DEF' ? 'success' : ($player['position'] === 'MID' ? 'warning text-dark' : 'danger')) }}">{{ $player['position'] }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($player['substitution_time'])
                                                                <span class="badge bg-info">ON
                                                                    {{ $player['substitution_time'] }}'</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No
                                                            substitutes available</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div class="tab-pane fade" id="stats" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Advanced Statistics</h5>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Attack Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['total_shots']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['total_shots']['home'] > 0 ? ($matchStats['total_shots']['home'] / max($matchStats['total_shots']['home'], $matchStats['total_shots']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['total_shots']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Total Shots</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_on_target']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['shots_on_target']['home'] > 0 ? ($matchStats['shots_on_target']['home'] / max($matchStats['shots_on_target']['home'], $matchStats['shots_on_target']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_on_target']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Shots on Target</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_off_target']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['shots_off_target']['home'] > 0 ? ($matchStats['shots_off_target']['home'] / max($matchStats['shots_off_target']['home'], $matchStats['shots_off_target']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_off_target']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Shots off Target</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_blocked']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['shots_blocked']['home'] > 0 ? ($matchStats['shots_blocked']['home'] / max($matchStats['shots_blocked']['home'], $matchStats['shots_blocked']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['shots_blocked']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Shots Blocked</div>
                                    </div>
                                </div>

                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Passing Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['total_passes']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['total_passes']['home'] > 0 ? ($matchStats['total_passes']['home'] / max($matchStats['total_passes']['home'], $matchStats['total_passes']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['total_passes']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Total Passes</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['pass_accuracy']['home'] ?? 0 }}%</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['pass_accuracy']['home'] > 0 ? ($matchStats['pass_accuracy']['home'] / max($matchStats['pass_accuracy']['home'], $matchStats['pass_accuracy']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['pass_accuracy']['away'] ?? 0 }}%</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Pass Accuracy</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['key_passes']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['key_passes']['home'] > 0 ? ($matchStats['key_passes']['home'] / max($matchStats['key_passes']['home'], $matchStats['key_passes']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['key_passes']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Key Passes</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Defensive Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['fouls']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['fouls']['home'] > 0 ? ($matchStats['fouls']['home'] / max($matchStats['fouls']['home'], $matchStats['fouls']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['fouls']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Fouls Committed</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['yellow_cards']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['yellow_cards']['home'] > 0 ? ($matchStats['yellow_cards']['home'] / max($matchStats['yellow_cards']['home'], $matchStats['yellow_cards']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['yellow_cards']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Yellow Cards</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['red_cards']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['red_cards']['home'] > 0 ? ($matchStats['red_cards']['home'] / max($matchStats['red_cards']['home'], $matchStats['red_cards']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['red_cards']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Red Cards</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['tackles']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['tackles']['home'] > 0 ? ($matchStats['tackles']['home'] / max($matchStats['tackles']['home'], $matchStats['tackles']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['tackles']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Tackles</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">
                                                {{ $matchStats['interceptions']['home'] ?? 0 }}</div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['interceptions']['home'] > 0 ? ($matchStats['interceptions']['home'] / max($matchStats['interceptions']['home'], $matchStats['interceptions']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">
                                                {{ $matchStats['interceptions']['away'] ?? 0 }}</div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Interceptions</div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Other Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['corners']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['corners']['home'] > 0 ? ($matchStats['corners']['home'] / max($matchStats['corners']['home'], $matchStats['corners']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['corners']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Corners</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['offsides']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['offsides']['home'] > 0 ? ($matchStats['offsides']['home'] / max($matchStats['offsides']['home'], $matchStats['offsides']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['offsides']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Offsides</div>

                                        <div class="stats-comparison">
                                            <div class="stats-team text-end">{{ $match->homeTeam->name }}</div>
                                            <div class="stats-value">{{ $matchStats['saves']['home'] ?? 0 }}
                                            </div>
                                            <div class="stats-bar-container">
                                                <div class="stats-bar home"
                                                    style="width: {{ $matchStats['saves']['home'] > 0 ? ($matchStats['saves']['home'] / max($matchStats['saves']['home'], $matchStats['saves']['away'])) * 100 : 0 }}%;">
                                                </div>
                                            </div>
                                            <div class="stats-value">{{ $matchStats['saves']['away'] ?? 0 }}
                                            </div>
                                            <div class="stats-team text-start">{{ $match->awayTeam->name }}</div>
                                        </div>
                                        <div class="text-center text-muted small mb-3">Saves</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Commentary Tab -->
            <div class="tab-pane fade" id="commentary" role="tabpanel">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title fw-bold mb-0">Live Commentary</h5>
                        @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('referee') || auth()->user()->hasRole('commentator') || auth()->user()->hasRole('organizer')))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCommentaryModal">
                                <i class="fas fa-plus me-2"></i>Add Commentary
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if ($match->commentary && $match->commentary->count() > 0)
                            @foreach ($match->commentary->sortBy('minute') as $comment)
                                <div class="timeline-item {{ $comment->is_important ? 'border-start border-warning border-3' : '' }}">
                                    <div class="timeline-dot {{ $comment->is_important ? 'bg-warning' : '' }}"></div>
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <span class="badge bg-{{ $comment->commentary_type === 'general' ? 'secondary' : ($comment->commentary_type === 'tactical' ? 'info' : ($comment->commentary_type === 'incident' ? 'warning' : ($comment->commentary_type === 'highlight' ? 'success' : 'danger'))) }} me-2">
                                                    <i class="{{ $comment->commentary_icon }} me-1"></i>
                                                    {{ $comment->commentary_type_label }}
                                                </span>
                                                @if($comment->is_important)
                                                    <span class="badge bg-warning text-dark me-2">
                                                        <i class="fas fa-star me-1"></i>Important
                                                    </span>
                                                @endif
                                                <small class="text-muted">{{ $comment->user_role_label }}</small>
                                            </div>
                                            <div class="commentary-content">
                                                <strong>{{ $comment->formatted_minute }}</strong> - {{ $comment->description }}
                                            </div>
                                            <small class="text-muted">
                                                By {{ $comment->user->name ?? 'Unknown' }} • {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="ms-3">
                                            @if(auth()->check() && (auth()->user()->id === $comment->user_id || auth()->user()->hasRole('admin')))
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCommentary({{ $comment->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="fas fa-comment-slash fa-3x mb-3"></i>
                                <h6>No commentary available yet</h6>
                                <p class="mb-0">Be the first to add commentary for this match!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Match Management Tab -->
            <div class="tab-pane fade" id="management" role="tabpanel">
                <div class="row">
                    <!-- Match Control Panel -->
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-gamepad me-2"></i>Match Control</h5>
                            </div>
                            <div class="card-body">
                                <!-- Match Status Display -->
                                <div class="text-center mb-4">
                                    <div
                                        class="badge bg-{{ $match->status === 'scheduled' ? 'warning' : ($match->status === 'live' ? 'success' : ($match->status === 'completed' ? 'secondary' : 'info')) }} fs-6 p-3">
                                        <i
                                            class="fas fa-{{ $match->status === 'scheduled' ? 'clock' : ($match->status === 'live' ? 'play' : ($match->status === 'completed' ? 'check' : 'pause')) }} me-2"></i>
                                        {{ ucfirst($match->status) }}
                                    </div>
                                </div>

                                <!-- Match Timer (for live matches) -->
                                @if ($match->status === 'live')
                                    <div class="text-center mb-4">
                                        <div class="display-6 fw-bold text-primary" id="matchTimer">
                                            {{ $match->current_minute ?? 0 }}'
                                        </div>
                                        <small class="text-muted">Match Time</small>
                                    </div>
                                @endif

                                <!-- Control Buttons -->
                                <div class="d-grid gap-2">
                                    @if ($match->status === 'scheduled')
                                        <button class="btn btn-success" id="startMatchBtn"
                                            onclick="startMatch()">
                                            <i class="fas fa-play me-2"></i>Start Match
                                        </button>
                                    @elseif($match->status === 'live')
                                        <button class="btn btn-warning" id="pauseMatchBtn"
                                            onclick="pauseMatch()">
                                            <i class="fas fa-pause me-2"></i>Pause Match
                                        </button>
                                        <button class="btn btn-danger" id="completeMatchBtn"
                                            onclick="showCompleteMatchModal()">
                                            <i class="fas fa-flag-checkered me-2"></i>Complete Match
                                        </button>
                                    @elseif($match->status === 'paused')
                                        <button class="btn btn-success" id="resumeMatchBtn"
                                            onclick="resumeMatch()">
                                            <i class="fas fa-play me-2"></i>Resume Match
                                        </button>
                                    @endif
                                </div>

                                <!-- Quick Actions -->
                                @if ($match->status === 'live')
                                    <hr class="my-3">
                                    <h6 class="text-muted mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary btn-sm"
                                            onclick="showScoreUpdateModal()">
                                            <i class="fas fa-futbol me-2"></i>Update Score
                                        </button>
                                        <button class="btn btn-outline-warning btn-sm" onclick="showEventModal()">
                                            <i class="fas fa-plus me-2"></i>Add Event
                                        </button>
                                        <button class="btn btn-outline-info btn-sm"
                                            onclick="showSubstitutionModal()">
                                            <i class="fas fa-exchange-alt me-2"></i>Substitution
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Simple Lineup Setup -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Quick Lineup Setup</h5>
                            </div>
                            <div class="card-body">
                                <!-- Team Selection -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Select Team</label>
                                        <select class="form-select form-select-lg" id="lineupTeamSelect">
                                            <option value="{{ $match->home_team_id }}">
                                                {{ $match->homeTeam->name }}</option>
                                            <option value="{{ $match->away_team_id }}">
                                                {{ $match->awayTeam->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Formation</label>
                                        <select class="form-select form-select-lg" id="formationSelect">
                                            <option value="4-4-2">4-4-2 (Balanced)</option>
                                            <option value="4-3-3">4-3-3 (Attacking)</option>
                                            <option value="3-5-2">3-5-2 (Midfield Control)</option>
                                            <option value="5-3-2">5-3-2 (Defensive)</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Simple Player Selection -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Starting XI
                                                    (11 Players)</h6>
                                            </div>
                                            <div class="card-body p-3" id="startingXIContainer"
                                                style="min-height: 250px;">
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-users fa-2x mb-2 text-primary"></i>
                                                    <p class="mb-1">Select 11 players for Starting XI</p>
                                                    <small class="text-muted">Click players below to add
                                                        them</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-success">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0"><i
                                                        class="fas fa-user-plus me-2"></i>Substitutes (Max 7)</h6>
                                            </div>
                                            <div class="card-body p-3" id="substitutesContainer"
                                                style="min-height: 250px;">
                                                <div class="text-center text-muted py-4">
                                                    <i class="fas fa-user-plus fa-2x mb-2 text-success"></i>
                                                    <p class="mb-1">Select substitute players</p>
                                                    <small class="text-muted">Optional but recommended</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($match->status == 'scheduled')
                                    <!-- Available Players - Simple Grid -->
                                    <div class="mt-4">
                                        <h6 class="fw-bold mb-3">Available Players</h6>
                                        <div class="row" id="availablePlayersContainer">
                                            <div class="col-12 text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                                <p class="mt-2 text-muted">Loading players...</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="text-center mt-4">
                                        <button class="btn btn-lg btn-primary me-3" id="saveLineupBtn"
                                            onclick="saveLineup()" disabled>
                                            <i class="fas fa-save me-2"></i>Save Lineup
                                        </button>
                                        <button class="btn btn-lg btn-outline-secondary" onclick="resetLineup()">
                                            <i class="fas fa-undo me-2"></i>Reset
                                        </button>
                                    </div>
                                @endif

                                <!-- Status Bar -->
                                <div class="mt-3 p-3 bg-light rounded">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="h5 mb-1 text-primary" id="startingXICount">0</div>
                                            <small class="text-muted">Starting XI</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="h5 mb-1 text-success" id="substitutesCount">0</div>
                                            <small class="text-muted">Substitutes</small>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="h5 mb-1 text-info" id="formationDisplay">4-4-2</div>
                                            <small class="text-muted">Formation</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('_partials._modals.match_detail')

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center text-muted small">
            <p class="mb-0">© 2023 Football Tournament Management System</p>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const isScheduled = "{{$match->status == 'scheduled'}}";
        const urlFetchAvailablePlayers = `/match-lineups/{{ $match->id }}/available-players`;
        const globalHomeName = '{{ $match->homeTeam->name }}';
        const globalAwayName = '{{ $match->awayTeam->name }}';
        const urlCurrentLineUp = `/match-lineups/{{ $match->id }}`;
        const homeTeamId = {{ $match->home_team_id }};
        const awayTeamId = {{ $match->away_team_id }};
        const urlStartMatch = '/matches/{{ $match->id }}/start';
        const urlPauseMatch = '/matches/{{ $match->id }}/pause';
        const urlResumeMatch = '/matches/{{ $match->id }}/resume';
        const urlCompleteMatch = '/matches/{{ $match->id }}/complete';
        const urlScoreMatch = '/matches/{{ $match->id }}/score';
        const urlMatchEvents = '/match-events';
        const urlMatchComments = '/matches/{{ $match->id }}/commentary';
        const urlDeteleComment = '/matches/{{ $match->id }}/commentary';
        const globalHomeScore = {{ $match->home_score ?? 0 }};
        const globalAwayScore = {{ $match->away_score ?? 0 }};
    </script>

    @vite('resources/js/match_detail_script.js')
</body>

</html>

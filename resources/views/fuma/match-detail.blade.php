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
    <style>
        :root {
            --primary: #2563eb;
            --secondary: #1e40af;
            --success: #16a34a;
            --warning: #ea580c;
            --danger: #dc3545;
            --info: #0ea5e9;
            --blue-gradient: linear-gradient(135deg, #1e3a8a, #1e40af);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
        }

        /* Updated Navbar with Gradient */
        .navbar {
            background: var(--blue-gradient);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
        }

        .match-header {
            background: var(--blue-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .team-logo-lg {
            width: 100px;
            height: 100px;
            object-fit: contain;
            background-color: white;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .team-logo-lg:hover {
            transform: scale(1.05);
        }

        .match-score-lg {
            font-size: 2.5rem;
            font-weight: bold;
            min-width: 120px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .live-badge {
            animation: pulse 1.5s infinite;
            background-color: var(--danger);
        }

        @keyframes pulse {
            0% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            background: transparent;
        }

        .stat-card {
            border-left: 3px solid var(--primary);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .player-photo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            transition: transform 0.3s ease;
        }

        .player-photo:hover {
            transform: scale(1.1);
        }

        .event-icon {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: transform 0.2s;
        }

        .event-icon:hover {
            transform: scale(1.2);
        }

        .goal-icon {
            background-color: rgba(22, 163, 74, 0.1);
            color: var(--success);
        }

        .yellow-card-icon {
            background-color: rgba(234, 179, 8, 0.1);
            color: #ca8a04;
        }

        .red-card-icon {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger);
        }

        .substitution-icon {
            background-color: rgba(14, 165, 233, 0.1);
            color: var(--info);
        }

        .timeline-item {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            background-color: rgba(241, 245, 249, 0.5);
            border-radius: 5px;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: 10px;
            top: 5px;
            width: 2px;
            height: calc(100% - 10px);
            background-color: #e2e8f0;
        }

        .timeline-dot {
            position: absolute;
            left: 5px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--primary);
            z-index: 1;
        }

        /* Table styling for lineups */
        .lineup-table {
            width: 100%;
            border-collapse: collapse;
        }

        .lineup-table th {
            background-color: #f1f5f9;
            padding: 8px 12px;
            text-align: left;
            font-weight: 600;
        }

        .lineup-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .lineup-table tr:last-child td {
            border-bottom: none;
        }

        .lineup-table .player-cell {
            display: flex;
            align-items: center;
        }

        /* Stats comparison styling */
        .stats-comparison {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stats-team {
            width: 30%;
            text-align: center;
            font-weight: 600;
        }

        .stats-value {
            width: 20%;
            text-align: center;
            font-weight: 700;
        }

        .stats-bar-container {
            width: 40%;
            height: 8px;
            background-color: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .stats-bar {
            height: 100%;
            background: var(--blue-gradient);
        }

        .stats-bar.home {
            float: left;
        }

        .stats-bar.away {
            float: right;
        }

        /* Simple Match Management Styles */
        .player-card-simple {
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 80px;
            border: 2px solid transparent !important;
        }

        .player-card-simple:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-color: var(--primary) !important;
        }

        .player-card-simple:active {
            transform: translateY(0);
        }

        #startingXIContainer,
        #substitutesContainer {
            background-color: #f8f9fa;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        #startingXIContainer {
            border-color: var(--primary) !important;
        }

        #substitutesContainer {
            border-color: var(--success) !important;
        }

        .form-select-lg {
            font-size: 1.1rem;
            padding: 0.75rem 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
        }

        /* Status bar styling */
        .status-bar .h5 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        /* Player position badges */
        .badge {
            font-size: 0.7rem;
            padding: 0.35rem 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .player-card-simple {
                min-width: 70px;
                font-size: 0.8rem;
            }

            #startingXIContainer,
            #substitutesContainer {
                min-height: 200px;
            }

            .btn-lg {
                padding: 0.5rem 1rem;
                font-size: 1rem;
            }
        }

        /* Substitution Modal Styles */
        .substitution-player-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            border-radius: 8px;
            padding: 10px;
            margin: 5px;
            text-align: center;
            background: white;
        }

        .substitution-player-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .substitution-player-card.selected {
            border-color: #28a745;
            background-color: #d4edda;
            color: #155724;
        }

        .substitution-player-card.selected-out {
            border-color: #dc3545;
            background-color: #f8d7da;
            color: #721c24;
        }

        .position-btn-group .btn {
            border-radius: 0;
        }

        .position-btn-group .btn:first-child {
            border-top-left-radius: 0.375rem;
            border-bottom-left-radius: 0.375rem;
        }

        .position-btn-group .btn:last-child {
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }
    </style>
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
                        <a class="nav-link" href="{{ route('dashboard') }}">Home</a>
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


    @if (isset($match))
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
                                        {{-- <li
                                            class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span class="text-muted">Assistant Referee 1</span>
                                            <span class="fw-bold">{{ $match->assistant_referee_1 ?? 'TBD' }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span class="text-muted">Assistant Referee 2</span>
                                            <span class="fw-bold">{{ $match->assistant_referee_2 ?? 'TBD' }}</span>
                                        </li>
                                        <li
                                            class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span class="text-muted">Fourth Official</span>
                                            <span class="fw-bold">{{ $match->fourth_official ?? 'TBD' }}</span>
                                        </li> --}}
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

                                    @if(!$match->status == 'live')
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
            </div>
        </main>
    @else
        <div class="container mt-5">
            <div class="alert alert-warning">
                <h4>Match Not Found</h4>
                <p>The requested match could not be found.</p>
                <a href="{{ route('matches.index') }}" class="btn btn-primary">Back to Matches</a>
            </div>
        </div>
    @endif

    <!-- Complete Match Modal -->
    <div class="modal fade" id="completeMatchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-flag-checkered me-2"></i>Complete Match</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="completeMatchForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Home Team Score</label>
                                <input type="number" class="form-control" id="finalHomeScore" name="home_score"
                                    min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Away Team Score</label>
                                <input type="number" class="form-control" id="finalAwayScore" name="away_score"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Attendance</label>
                                <input type="number" class="form-control" id="attendance" name="attendance"
                                    min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Weather</label>
                                <select class="form-select" id="weather" name="weather">
                                    <option value="">Select weather</option>
                                    <option value="Sunny">Sunny</option>
                                    <option value="Cloudy">Cloudy</option>
                                    <option value="Rainy">Rainy</option>
                                    <option value="Windy">Windy</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Match Notes</label>
                            <textarea class="form-control" id="matchNotes" name="notes" rows="3"
                                placeholder="Additional notes about the match..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="completeMatchForm" class="btn btn-danger">Complete Match</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Score Update Modal -->
    <div class="modal fade" id="scoreUpdateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-futbol me-2"></i>Update Score</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="scoreUpdateForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Home Team Score</label>
                                <input type="number" class="form-control" id="liveHomeScore" name="home_score"
                                    min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Away Team Score</label>
                                <input type="number" class="form-control" id="liveAwayScore" name="away_score"
                                    min="0" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Scorer</label>
                                <select class="form-select" id="scorerSelect" name="scorer_id">
                                    <option value="">Select scorer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Assist</label>
                                <select class="form-select" id="assistSelect" name="assist_id">
                                    <option value="">Select assist</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Minute</label>
                            <input type="number" class="form-control" id="goalMinute" name="minute"
                                min="1" max="120" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="scoreUpdateForm" id="scoreUpdateBtn" class="btn btn-primary">Update
                        Score</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Match Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addEventForm">
                        <input type="hidden" name="match_id" value="{{ $match->id }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Event Type</label>
                                <select class="form-select" id="eventType" name="type" required>
                                    <option value="">Select event type</option>
                                    <option value="yellow_card">Yellow Card</option>
                                    <option value="red_card">Red Card</option>
                                    <option value="foul">Foul</option>
                                    <option value="injury">Injury</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Team</label>
                                <select class="form-select" id="eventTeam" name="team_id" required>
                                    <option value="">Select team</option>
                                    <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}
                                    </option>
                                    <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Player</label>
                                <select class="form-select" id="eventPlayer" name="player_id">
                                    <option value="">Select player (optional)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minute</label>
                                <input type="number" class="form-control" id="eventMinute" name="minute"
                                    min="1" max="120" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="eventDescription" name="description" rows="3"
                                placeholder="Describe the event..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addEventForm" id="addEventFormBtn" class="btn btn-warning">Add
                        Event</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Substitution Modal -->
    <div class="modal fade" id="substitutionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Player Substitution</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="substitutionForm" action="{{ route('match-lineups.update', $match->id) }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Team</label>
                                <select class="form-select" id="subTeam" name="team_id" required>
                                    <option value="">Select team</option>
                                    <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}
                                    </option>
                                    <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Minute</label>
                                <input type="number" class="form-control" id="subMinute" name="minute"
                                    min="1" max="120" required>
                            </div>
                        </div>

                        <!-- Player Selection Interface -->
                        <div class="row">
                            <!-- Players Out (Starting XI) -->
                            <div class="col-md-6">
                                <h6 class="text-danger mb-3"><i class="fas fa-sign-out-alt me-2"></i>Player Out
                                    (Starting XI)</h6>
                                <div id="playersOutContainer" class="border rounded p-3"
                                    style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p class="mb-0">No players in Starting XI</p>
                                    </div>
                                </div>
                                <input type="hidden" id="playerOutId" name="player_out_id" required>
                            </div>

                            <!-- Players In (Substitutes) -->
                            <div class="col-md-6">
                                <h6 class="text-success mb-3"><i class="fas fa-sign-in-alt me-2"></i>Player In
                                    (Substitutes)</h6>
                                <div id="playersInContainer" class="border rounded p-3"
                                    style="min-height: 200px; background-color: #f8f9fa;">
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                                        <p class="mb-0">No substitute players available</p>
                                    </div>
                                </div>
                                <input type="hidden" id="playerInId" name="player_in_id" required>
                            </div>
                        </div>

                        <!-- Position Selection -->
                        <div class="mt-3">
                            <label class="form-label">Position for Player In</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="position" id="posGK"
                                    value="GK" required>
                                <label class="btn btn-outline-primary" for="posGK">GK</label>

                                <input type="radio" class="btn-check" name="position" id="posDEF"
                                    value="DEF" required>
                                <label class="btn btn-outline-success" for="posDEF">DEF</label>

                                <input type="radio" class="btn-check" name="position" id="posMID"
                                    value="MID" required>
                                <label class="btn btn-outline-warning" for="posMID">MID</label>

                                <input type="radio" class="btn-check" name="position" id="posFWD"
                                    value="FWD" required>
                                <label class="btn btn-outline-danger" for="posFWD">FWD</label>
                            </div>
                        </div>

                        <!-- Selected Players Display -->
                        <div class="mt-3 p-3 bg-light rounded" id="selectedPlayersDisplay" style="display: none;">
                            <h6 class="mb-2">Selected Substitution:</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="badge bg-danger">OUT:</span>
                                    <span id="selectedPlayerOut">None selected</span>
                                </div>
                                <div class="col-md-6">
                                    <span class="badge bg-success">IN:</span>
                                    <span id="selectedPlayerIn">None selected</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="substitutionForm" class="btn btn-info" id="makeSubstitutionBtn">
                        <i class="fas fa-exchange-alt me-2"></i>Make Substitution
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center text-muted small">
            <p class="mb-0">© 2023 Football Tournament Management System</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Match Management JavaScript -->
    <script>
        // Global variables
        let currentMatchData = null;
        let currentLineup = {
            starting_xi: [],
            substitutes: [],
            bench: []
        };
        let availablePlayers = [];
        let selectedTeamId = null;

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            initializeMatchManagement();
            setupEventListeners();
        });

        // Toastr function to replace alerts
        function showToastr(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                // Fallback to alert if toastr not available
                alert(message);
            }
        }

        // Replace all alerts with showToastr
        function replaceAlerts() {
            // Lineup management alerts
            if (typeof addPlayerToLineup === 'function') {
                // This will be handled by the actual function calls
            }
        }

        // Initialize match management
        function initializeMatchManagement() {
            // // console.log('Initializing match management...');
            selectedTeamId = document.getElementById('lineupTeamSelect').value;
            // // console.log('Selected team ID:', selectedTeamId);

            loadAvailablePlayers(selectedTeamId);
            loadCurrentLineup();

            // Set current scores in modals
            document.getElementById('finalHomeScore').value = {{ $match->home_score ?? 0 }};
            document.getElementById('finalAwayScore').value = {{ $match->away_score ?? 0 }};
            document.getElementById('liveHomeScore').value = {{ $match->home_score ?? 0 }};
            document.getElementById('liveAwayScore').value = {{ $match->away_score ?? 0 }};

            // Load existing lineup data if available
            loadExistingLineupData();

            // Populate player selects in modals
            setTimeout(() => {
                // console.log('Delayed populatePlayerSelects called');
                populatePlayerSelects();
            }, 1000); // Delay to ensure players are loaded

            // Auto-populate team selections in modals
            autoPopulateTeamSelections();

            // console.log('Match management initialized');
        }

        // Setup event listeners
        function setupEventListeners() {
            // Team selection change
            document.getElementById('lineupTeamSelect').addEventListener('change', function() {
                selectedTeamId = this.value;
                loadAvailablePlayers(selectedTeamId);
                loadCurrentLineup();
            });

            // Formation change
            document.getElementById('formationSelect').addEventListener('change', function() {
                updateFormationDisplay();
            });

            // Team selection change in modals
            document.getElementById('eventTeam')?.addEventListener('change', function() {
                populatePlayerSelects(); // Refresh player dropdowns
            });

            document.getElementById('subTeam')?.addEventListener('change', function() {
                populatePlayerSelects(); // Refresh player dropdowns
            });

            // Form submissions
            document.getElementById('completeMatchForm').addEventListener('submit', completeMatch);
            document.getElementById('scoreUpdateForm').addEventListener('submit', updateScore);
            document.getElementById('addEventForm').addEventListener('submit', addEvent);
            document.getElementById('substitutionForm').addEventListener('submit', makeSubstitution);
        }

        // Load available players for a team
        function loadAvailablePlayers(teamId) {
            const isLive = "{{$match->status == 'live'}}";
            // console.log('Loading available players for team:', teamId);
            fetch(`/match-lineups/{{ $match->id }}/available-players/${teamId}`)
                .then(response => response.json())
                .then(data => {
                    // console.log('Available players response:', data);
                    if (data.success) {
                        // Handle both data structures
                        if (data.data && data.data.players) {
                            availablePlayers = data.data.players;
                        } else if (data.players) {
                            availablePlayers = data.players;
                        } else {
                            availablePlayers = [];
                        }

                        // console.log('Available players loaded:', availablePlayers);
                        if(!isLive){
                            renderAvailablePlayers();
                        }
                        populatePlayerSelects();
                    } else {
                        console.error('Failed to load players:', data.message);
                        // Load test data if backend fails
                        loadTestPlayerData(teamId);
                    }
                })
                .catch(error => {
                    console.error('Error loading players:', error);
                    // Load test data if backend fails
                    loadTestPlayerData(teamId);
                });
        }

        // Load test player data for development
        function loadTestPlayerData(teamId) {
            // console.log('Loading test player data for team:', teamId);

            // Generate test players based on team
            const teamName = teamId == {{ $match->home_team_id }} ? '{{ $match->homeTeam->name }}' :
                '{{ $match->awayTeam->name }}';

            // Generate unique IDs based on team to avoid conflicts
            const baseId = teamId == {{ $match->home_team_id }} ? 1000 : 2000;

            availablePlayers = [{
                    id: baseId + 1,
                    name: 'John Doe',
                    position: 'GK',
                    jersey_number: 1,
                    team_id: teamId
                },
                {
                    id: baseId + 2,
                    name: 'Mike Smith',
                    position: 'DEF',
                    jersey_number: 2,
                    team_id: teamId
                },
                {
                    id: baseId + 3,
                    name: 'David Johnson',
                    position: 'DEF',
                    jersey_number: 3,
                    team_id: teamId
                },
                {
                    id: baseId + 4,
                    name: 'Tom Wilson',
                    position: 'DEF',
                    jersey_number: 4,
                    team_id: teamId
                },
                {
                    id: baseId + 5,
                    name: 'Chris Brown',
                    position: 'DEF',
                    jersey_number: 5,
                    team_id: teamId
                },
                {
                    id: baseId + 6,
                    name: 'Alex Davis',
                    position: 'MID',
                    jersey_number: 6,
                    team_id: teamId
                },
                {
                    id: baseId + 7,
                    name: 'Sam Miller',
                    position: 'MID',
                    jersey_number: 7,
                    team_id: teamId
                },
                {
                    id: baseId + 8,
                    name: 'Ryan Garcia',
                    position: 'MID',
                    jersey_number: 8,
                    team_id: teamId
                },
                {
                    id: baseId + 9,
                    name: 'Kevin Lee',
                    position: 'MID',
                    jersey_number: 9,
                    team_id: teamId
                },
                {
                    id: baseId + 10,
                    name: 'James Taylor',
                    position: 'FWD',
                    jersey_number: 10,
                    team_id: teamId
                },
                {
                    id: baseId + 11,
                    name: 'Robert Anderson',
                    position: 'FWD',
                    jersey_number: 11,
                    team_id: teamId
                },
                {
                    id: baseId + 12,
                    name: 'Daniel Martinez',
                    position: 'GK',
                    jersey_number: 12,
                    team_id: teamId
                },
                {
                    id: baseId + 13,
                    name: 'Paul Rodriguez',
                    position: 'DEF',
                    jersey_number: 13,
                    team_id: teamId
                },
                {
                    id: baseId + 14,
                    name: 'Mark Thompson',
                    position: 'MID',
                    jersey_number: 14,
                    team_id: teamId
                },
                {
                    id: baseId + 15,
                    name: 'Steve White',
                    position: 'FWD',
                    jersey_number: 15,
                    team_id: teamId
                }
            ];

            // console.log('Test players loaded:', availablePlayers);
            renderAvailablePlayers();
            populatePlayerSelects();
        }

        // Load current lineup
        function loadCurrentLineup() {
            // console.log('Loading current lineup for team:', selectedTeamId);
            fetch(`/match-lineups/{{ $match->id }}?team_id=${selectedTeamId}`)
                .then(response => response.json())
                .then(data => {
                    // console.log('Lineup response:', data);
                    if (data.success && data.data) {
                        // Map backend data structure to frontend
                        currentLineup = {
                            starting_xi: data.data.starting_xi || [],
                            substitutes: data.data.substitutes ||
                        [], // Map from backend 'substitute' to frontend 'substitutes'
                            bench: data.data.bench || []
                        };
                        // console.log('Current lineup loaded:', currentLineup);
                        renderLineup();
                        updateFormationDisplay();
                    } else {
                        // console.log('No existing lineup, initializing empty');
                        // Initialize empty lineup if none exists
                        currentLineup = {
                            starting_xi: [],
                            substitutes: [],
                            bench: []
                        };
                        renderLineup();
                    }
                })
                .catch(error => {
                    console.error('Error loading lineup:', error);
                    // Initialize empty lineup on error
                    currentLineup = {
                        starting_xi: [],
                        substitutes: [],
                        bench: []
                    };
                    renderLineup();
                });
        }

        // Load existing lineup data from database
        function loadExistingLineupData() {
            // Try to load lineup for both teams
            const homeTeamId = {{ $match->home_team_id }};
            const awayTeamId = {{ $match->away_team_id }};

            // Load home team lineup
            fetch(`/match-lineups/{{ $match->id }}?team_id=${homeTeamId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.starting_xi && data.data.starting_xi.length > 0) {
                        // console.log('Home team lineup loaded:', data.data.formation);
                        // Update formation selector if lineup exists
                        if (data.data.formation) {
                            document.getElementById('formationSelect').value = data.data.formation;
                        }
                    }
                })
                .catch(error => console.error('Error loading home team lineup:', error));

            // Load away team lineup
            fetch(`/match-lineups/{{ $match->id }}?team_id=${awayTeamId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.starting_xi && data.data.starting_xi.length > 0) {
                        // console.log('Away team lineup loaded:', data.data);
                    }
                })
                .catch(error => console.error('Error loading away team lineup:', error));
        }

        // Render available players - Simple click interface
        function renderAvailablePlayers() {
            const container = document.getElementById('availablePlayersContainer');
            if (!availablePlayers.length) {
                container.innerHTML = '<div class="col-12 text-center text-muted py-3">No players available</div>';
                return;
            }

            // Group players by position
            const playersByPosition = {
                'GK': availablePlayers.filter(p => p.position === 'GK'),
                'DEF': availablePlayers.filter(p => p.position === 'DEF'),
                'MID': availablePlayers.filter(p => p.position === 'MID'),
                'FWD': availablePlayers.filter(p => p.position === 'FWD')
            };

            let playersHtml = '';

            Object.entries(playersByPosition).forEach(([position, players]) => {
                if (players.length > 0) {
                    playersHtml += `
                        <div class="col-12 mb-3">
                            <h6 class="text-muted mb-2">${position} (${players.length})</h6>
                            <div class="d-flex flex-wrap gap-2">
                                ${players.map(player => `
                                                                <div class="player-card-simple p-2 border rounded bg-light"
                                                                     onclick="addPlayerToLineup('${getBestLineupType(player)}', ${JSON.stringify(player).replace(/"/g, '&quot;')})"
                                                                     style="cursor: pointer; min-width: 80px;">
                                                                    <div class="text-center">
                                                                        <div class="fw-bold small">${player.name}</div>
                                                                        <div class="badge bg-${getPositionColor(player.position)}">${player.position}</div>
                                                                        <div class="small text-muted">#${player.jersey_number || '?'}</div>
                                                                    </div>
                                                                </div>
                                                            `).join('')}
                            </div>
                        </div>
                    `;
                }
            });

            container.innerHTML = playersHtml;
        }

        // Determine best lineup type for a player
        function getBestLineupType(player) {
            if (currentLineup.starting_xi.length < 11) {
                return 'starting_xi';
            } else if (currentLineup.substitutes.length < 7) {
                return 'substitutes';
            }
            return 'substitutes'; // Default to substitutes if both are full
        }

        // Render current lineup
        function renderLineup() {
            renderStartingXI();
            renderSubstitutes();
            updateSaveButton();
        }

        // Render Starting XI
        function renderStartingXI() {
            const isLive = "{{$match->status == 'live'}}";
            const container = document.getElementById('startingXIContainer');
            if (!currentLineup.starting_xi || !currentLineup.starting_xi.length) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p>Drag players here to set Starting XI</p>
                        <small class="text-muted">Need exactly 11 players</small>
                    </div>
                `;
                return;
            }

            const playersHtml = currentLineup.starting_xi.map(player => {
                // Handle different data structures
                const playerName = player.player ? player.player.name : player.name;
                const playerId = player.player_id || player.id;
                const position = player.position || 'N/A';
                const jerseyNumber = player.jersey_number || '?';
                const isCaptain = player.is_captain || false;

                return `
                    <div class="player-card d-inline-block m-1 p-2 border rounded bg-light"
                         data-player-id="${playerId}">
                        <div class="text-center">
                            <div class="fw-bold small">${playerName}</div>
                            <div class="badge bg-${getPositionColor(position)}">${position}</div>
                            <div class="small text-muted">#${jerseyNumber}</div>
                            ${isCaptain ? '<div class="badge bg-warning">C</div>' : ''}
                            ${!isLive ? `<button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromLineup('starting_xi', ${playerId})">
                                <i class="fas fa-times"></i>
                            </button>` : ''}
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = playersHtml;
        }

        // Render Substitutes
        function renderSubstitutes() {
            const isLive = "{{$match->status == 'live'}}";
            const container = document.getElementById('substitutesContainer');

            if (!currentLineup.substitutes || !currentLineup.substitutes.length) {
                container.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                        <p>Drag players here for substitutes</p>
                        <small class="text-muted">Optional - max 12 players</small>
                    </div>
                `;
                return;
            }

            const playersHtml = currentLineup.substitutes.map(player => {
                // Handle different data structures
                const playerName = player.player ? player.player.name : player.name;
                const playerId = player.player_id || player.id;
                const position = player.position || 'N/A';
                const jerseyNumber = player.jersey_number || '?';

                return `
                    <div class="player-card d-inline-block m-1 p-2 border rounded bg-light"
                         data-player-id="${playerId}">
                        <div class="text-center">
                            <div class="fw-bold small">${playerName}</div>
                            <div class="badge bg-${getPositionColor(position)}">${position}</div>
                            <div class="small text-muted">#${jerseyNumber}</div>
                            ${!isLive ? `<button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromLineup('substitutes', ${playerId})">
                                <i class="fas fa-times"></i>
                            </button>` : ''}
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = playersHtml;
        }

        // Simple click-based player addition
        function addPlayerToLineup(type, player) {
            // Check if player is already in lineup
            const isAlreadyInLineup = [...currentLineup.starting_xi, ...currentLineup.substitutes]
                .some(p => p.player_id === player.id);

            if (isAlreadyInLineup) {
                showToastr('warning', `${player.name} is already in the lineup!`);
                return;
            }

            // Auto-switch to substitutes if Starting XI is full
            if (type === 'starting_xi' && currentLineup.starting_xi.length >= 11) {
                if (currentLineup.substitutes.length >= 7) {
                    showToastr('warning', 'Both Starting XI and Substitutes are full!');
                    return;
                }
                type = 'substitute'; // Use singular form to match backend
            }

            if (type === 'substitute' && currentLineup.substitutes.length >= 7) {
                showToastr('warning', 'Substitutes are full!');
                return;
            }

            const lineupPlayer = {
                player_id: parseInt(player.id),
                player: player,
                position: player.position,
                jersey_number: parseInt(player.jersey_number || generateJerseyNumber(type)),
                is_captain: false
            };

            // Map frontend type to backend type
            const backendType = type === 'substitute' ? 'substitutes' : type;

            currentLineup[backendType].push(lineupPlayer);

            renderLineup();
            updateSaveButton();
            updateStatusBar();
        }

        // Remove player from lineup
        function removeFromLineup(type, playerId) {
            currentLineup[type] = currentLineup[type].filter(p => parseInt(p.player_id) !== parseInt(playerId));
            renderLineup();
            updateSaveButton();
        }

        // Generate jersey number
        function generateJerseyNumber(type) {
            const usedNumbers = [...currentLineup.starting_xi, ...currentLineup.substitutes]
                .map(p => p.jersey_number)
                .filter(n => n);

            for (let i = 1; i <= 99; i++) {
                if (!usedNumbers.includes(i)) {
                    return i;
                }
            }
            return 1;
        }

        // Update formation display
        function updateFormationDisplay() {
            const formation = document.getElementById('formationSelect').value;
            // This could show a visual formation diagram
            // console.log('Formation changed to:', formation);
        }

        // Update save button state
        function updateSaveButton() {
            const saveBtn = document.getElementById('saveLineupBtn');
            const canSave = currentLineup.starting_xi.length === 11 && currentLineup.substitutes.length > 0;
            if(saveBtn){
                saveBtn.disabled = !canSave;
            }

            // Update status bar
            updateStatusBar();
        }

        // Update status bar
        function updateStatusBar() {
            const startingXICount = currentLineup.starting_xi ? currentLineup.starting_xi.length : 0;
            const substitutesCount = currentLineup.substitutes ? currentLineup.substitutes.length : 0;
            const formation = document.getElementById('formationSelect').value;

            document.getElementById('startingXICount').textContent = startingXICount;
            document.getElementById('substitutesCount').textContent = substitutesCount;
            document.getElementById('formationDisplay').textContent = formation;
        }

        // Reset lineup
        function resetLineup() {
            if (confirm('Are you sure you want to reset the lineup? This will clear all selected players.')) {
                currentLineup = {
                    starting_xi: [],
                    substitutes: [],
                    bench: []
                };
                renderLineup();
                updateSaveButton();
                updateStatusBar();
            }
        }

        // Save lineup
        function saveLineup() {
            const formation = document.getElementById('formationSelect').value;

            // Validate lineup before saving
            if (currentLineup.starting_xi.length !== 11) {
                showToastr('error', 'Starting XI must have exactly 11 players');
                return;
            }

            if (currentLineup.substitutes.length === 0) {
                showToastr('error', 'At least one substitute is required');
                return;
            }

            // Validate that all players have required data
            const allPlayers = [...currentLineup.starting_xi, ...currentLineup.substitutes];
            const invalidPlayers = allPlayers.filter(player =>
                !player.player_id || !player.position || !player.jersey_number
            );

            if (invalidPlayers.length > 0) {
                showToastr('error', 'Some players are missing required information');
                console.error('Invalid players:', invalidPlayers);
                return;
            }

            // Validate that all players belong to the selected team
            const selectedTeamIdInt = parseInt(selectedTeamId);
            const teamPlayers = availablePlayers.filter(p => p.team_id === selectedTeamIdInt);
            const teamPlayerIds = teamPlayers.map(p => p.id);

            const invalidTeamPlayers = allPlayers.filter(player =>
                !teamPlayerIds.includes(parseInt(player.player_id))
            );

            if (invalidTeamPlayers.length > 0) {
                showToastr('error', 'Some players do not belong to the selected team');
                console.error('Invalid team players:', invalidTeamPlayers);
                console.error('Team player IDs:', teamPlayerIds);
                console.error('Selected team ID:', selectedTeamIdInt);
                return;
            }

            // Transform data to match backend expectations
            const lineupData = {
                team_id: parseInt(selectedTeamId),
                formation: formation,
                lineup: {
                    starting_xi: currentLineup.starting_xi.map(player => ({
                        player_id: parseInt(player.player_id || player.id),
                        jersey_number: parseInt(player.jersey_number),
                        position: player.position,
                        is_captain: player.is_captain || false
                    })),
                    substitutes: currentLineup.substitutes.map(player => ({ // Use singular form for backend
                        player_id: parseInt(player.player_id || player.id),
                        jersey_number: parseInt(player.jersey_number),
                        position: player.position,
                        is_captain: false
                    }))
                }
            };

            // console.log('Sending lineup data:', lineupData);
            // console.log('Current lineup state:', currentLineup);
            // console.log('Selected team ID:', selectedTeamId);
            // console.log('Formation:', formation);

            fetch('/match-lineups/{{ $match->id }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(lineupData)
                })
                .then(response => response.json())
                .then(data => {
                    // console.log('Save lineup response:', data);
                    if (data.success) {
                        showToastr('success', 'Lineup saved successfully!');
                        loadCurrentLineup();
                    } else {
                        showToastr('error', 'Error saving lineup: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error saving lineup:', error);
                    showToastr('error', 'Error saving lineup');
                });
        }

        // Auto-populate team selections in modals
        function autoPopulateTeamSelections() {
            // Set default team selections for modals
            const homeTeamId = {{ $match->home_team_id }};
            const awayTeamId = {{ $match->away_team_id }};

            // Event modal - default to home team
            const eventTeamSelect = document.getElementById('eventTeam');
            if (eventTeamSelect) {
                eventTeamSelect.value = homeTeamId;
            }

            // Substitution modal - default to home team
            const subTeamSelect = document.getElementById('subTeam');
            if (subTeamSelect) {
                subTeamSelect.value = homeTeamId;
            }
        }

        // Player selection functions for substitution modal
        let selectedPlayerOut = null;
        let selectedPlayerIn = null;

        function selectPlayerOut(playerId, playerName, position) {
            // Clear previous selection
            document.querySelectorAll('#playersOutContainer .substitution-player-card').forEach(card => {
                card.classList.remove('selected-out');
            });

            // Select new player
            const playerCard = document.querySelector(`#playersOutContainer [data-player-id="${playerId}"]`);
            if (playerCard) {
                playerCard.classList.add('selected-out');
            }

            selectedPlayerOut = {
                id: playerId,
                name: playerName,
                position: position
            };
            document.getElementById('playerOutId').value = playerId;

            updateSubstitutionDisplay();
            // console.log('Player OUT selected:', selectedPlayerOut);
        }

        function selectPlayerIn(playerId, playerName, position) {
            // Clear previous selection
            document.querySelectorAll('#playersInContainer .substitution-player-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Select new player
            const playerCard = document.querySelector(`#playersInContainer [data-player-id="${playerId}"]`);
            if (playerCard) {
                playerCard.classList.add('selected');
            }

            selectedPlayerIn = {
                id: playerId,
                name: playerName,
                position: position
            };
            document.getElementById('playerInId').value = playerId;

            updateSubstitutionDisplay();
            // console.log('Player IN selected:', selectedPlayerIn);
        }

        function updateSubstitutionDisplay() {
            const display = document.getElementById('selectedPlayersDisplay');
            const playerOutSpan = document.getElementById('selectedPlayerOut');
            const playerInSpan = document.getElementById('selectedPlayerIn');

            if (selectedPlayerOut && selectedPlayerIn) {
                playerOutSpan.textContent = `${selectedPlayerOut.name} (${selectedPlayerOut.position})`;
                playerInSpan.textContent = `${selectedPlayerIn.name} (${selectedPlayerIn.position})`;
                display.style.display = 'block';
            } else {
                display.style.display = 'none';
            }
        }

        // Populate player selects in modals
        function populatePlayerSelects() {
            // console.log('Populating player selects...');
            // console.log('Available players:', availablePlayers);
            // console.log('Current lineup:', currentLineup);

            const homeTeamId = {{ $match->home_team_id }};
            const awayTeamId = {{ $match->away_team_id }};

            // Populate scorer and assist selects
            const scorerSelect = document.getElementById('scorerSelect');
            const assistSelect = document.getElementById('assistSelect');
            const eventPlayerSelect = document.getElementById('eventPlayer');

            if (scorerSelect && assistSelect && availablePlayers.length > 0) {
                // Clear existing options
                scorerSelect.innerHTML = '<option value="">Select scorer</option>';
                assistSelect.innerHTML = '<option value="">Select assist</option>';

                // Add players from both teams
                availablePlayers.forEach(player => {
                    const teamName = player.team_id === homeTeamId ? '{{ $match->homeTeam->name }}' :
                        '{{ $match->awayTeam->name }}';
                    const option =
                        `<option value="${player.id}">${player.name} (${player.position}) - ${teamName}</option>`;
                    scorerSelect.innerHTML += option;
                    assistSelect.innerHTML += option;
                });
                // console.log('Scorer and assist selects populated');
            }

            if (eventPlayerSelect && availablePlayers.length > 0) {
                eventPlayerSelect.innerHTML = '<option value="">Select player (optional)</option>';
                availablePlayers.forEach(player => {
                    const teamName = player.team_id === homeTeamId ? '{{ $match->homeTeam->name }}' :
                        '{{ $match->awayTeam->name }}';
                    const option =
                        `<option value="${player.id}">${player.name} (${player.position}) - ${teamName}</option>`;
                    eventPlayerSelect.innerHTML += option;
                });
                // console.log('Event player select populated');
            }

            // Populate substitution containers
            populateSubstitutionContainers();
        }

        // Populate substitution containers with clickable player cards
        function populateSubstitutionContainers() {
            // console.log('Populating substitution containers...');

            const playersOutContainer = document.getElementById('playersOutContainer');
            const playersInContainer = document.getElementById('playersInContainer');

            if (!playersOutContainer || !playersInContainer) return;

            // Clear containers
            playersOutContainer.innerHTML = '';
            playersInContainer.innerHTML = '';

            // Populate Players Out (Starting XI)
            if (currentLineup.starting_xi && currentLineup.starting_xi.length > 0) {
                currentLineup.starting_xi.forEach(player => {
                    const playerName = player.player ? player.player.name : player.name;
                    const playerId = player.player_id || player.id;
                    const position = player.position || 'N/A';
                    const jerseyNumber = player.jersey_number || '?';

                    const playerCard = `
                        <div class="substitution-player-card"
                             onclick="selectPlayerOut(${playerId}, '${playerName}', '${position}')"
                             data-player-id="${playerId}">
                            <div class="fw-bold">${playerName}</div>
                            <div class="badge bg-${getPositionColor(position)}">${position}</div>
                            <div class="small text-muted">#${jerseyNumber}</div>
                        </div>
                    `;
                    playersOutContainer.innerHTML += playerCard;
                });
                // console.log('Players out container populated with', currentLineup.starting_xi.length, 'players');
            } else {
                playersOutContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-users fa-2x mb-2"></i>
                        <p class="mb-0">No players in Starting XI</p>
                    </div>
                `;
            }

            // Populate Players In (Substitutes)
            if (currentLineup.substitutes && currentLineup.substitutes.length > 0) {
                currentLineup.substitutes.forEach(player => {
                    const playerName = player.player ? player.player.name : player.name;
                    const playerId = player.player_id || player.id;
                    const position = player.position || 'N/A';
                    const jerseyNumber = player.jersey_number || '?';

                    const playerCard = `
                        <div class="substitution-player-card"
                             onclick="selectPlayerIn(${playerId}, '${playerName}', '${position}')"
                             data-player-id="${playerId}">
                            <div class="fw-bold">${playerName}</div>
                            <div class="badge bg-${getPositionColor(position)}">${position}</div>
                            <div class="small text-muted">#${jerseyNumber}</div>
                        </div>
                    `;
                    playersInContainer.innerHTML += playerCard;
                });
                // console.log('Players in container populated with', currentLineup.substitutes.length, 'players');
            } else {
                playersInContainer.innerHTML = `
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-user-plus fa-2x mb-2"></i>
                        <p class="mb-0">No substitute players available</p>
                    </div>
                `;
            }
        }

        // Get position color for badges
        function getPositionColor(position) {
            const colors = {
                'GK': 'primary',
                'DEF': 'success',
                'MID': 'warning',
                'FWD': 'danger'
            };
            return colors[position] || 'secondary';
        }

        // Match Control Functions
        function startMatch() {
            if (confirm('Are you sure you want to start this match?')) {
                fetch('/matches/{{ $match->id }}/start', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error starting match: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error starting match:', error);
                        alert('Error starting match');
                    });
            }
        }

        function pauseMatch() {
            if (confirm('Are you sure you want to pause this match?')) {
                fetch('/matches/{{ $match->id }}/pause', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error pausing match: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error pausing match:', error);
                        alert('Error pausing match');
                    });
            }
        }

        function resumeMatch() {
            if (confirm('Are you sure you want to resume this match?')) {
                fetch('/matches/{{ $match->id }}/resume', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error resuming match: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error resuming match:', error);
                        alert('Error resuming match');
                    });
            }
        }

        // Modal show functions
        function showCompleteMatchModal() {
            new bootstrap.Modal(document.getElementById('completeMatchModal')).show();
        }

        function showScoreUpdateModal() {
            new bootstrap.Modal(document.getElementById('scoreUpdateModal')).show();
        }

        function showEventModal() {
            new bootstrap.Modal(document.getElementById('addEventModal')).show();
        }

        function showSubstitutionModal() {
            new bootstrap.Modal(document.getElementById('substitutionModal')).show();
        }

        // Form submission functions
        function completeMatch(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch('/matches/{{ $match->id }}/complete', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message before reload
                        alert('Match completed successfully!');
                        location.reload();
                    } else {
                        // Show detailed error message
                        let errorMsg = data.message || 'Unknown error occurred';
                        if (data.errors) {
                            errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                        }
                        alert('Error completing match:\n' + errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error completing match:', error);
                    alert('Network error: ' + error.message);
                })
                .finally(() => {

                });
        }

        function updateScore(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            formData.append('_method', 'PUT');

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]') || document.querySelector('#scoreUpdateBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

            fetch('/matches/{{ $match->id }}/score', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Score updated successfully!');
                        location.reload();
                    } else {
                        let errorMsg = data.message || 'Unknown error occurred';
                        if (data.errors) {
                            errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                        }
                        alert('Error updating score:\n' + errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error updating score:', error);
                    alert('Network error: ' + error.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        }

        function addEvent(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            // Validate required fields
            const eventType = formData.get('type');
            const teamId = formData.get('team_id');
            const minute = formData.get('minute');

            if (!eventType || !teamId || !minute) {
                alert('Please fill in all required fields: Event Type, Team, and Minute');
                return;
            }

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]') || document.querySelector('#addEventFormBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

            fetch('/match-events', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Event added successfully!');
                        location.reload();
                    } else {
                        let errorMsg = data.message || 'Unknown error occurred';
                        if (data.errors) {
                            errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                        }
                        alert('Error adding event:\n' + errorMsg);
                    }
                })
                .catch(error => {
                    console.error('Error adding event:', error);
                    alert('Network error: ' + error.message);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        }

        function makeSubstitution(e) {
            e.preventDefault();
            const formData = new FormData(e.target);

            // Validate required fields
            const teamId = formData.get('team_id');
            const minute = formData.get('minute');
            const playerOutId = formData.get('player_out_id');
            const playerInId = formData.get('player_in_id');
            const position = formData.get('position');

            if (!teamId || !minute || !playerOutId || !playerInId || !position) {
                showToastr('error',
                    'Please fill in all required fields: Team, Minute, Player Out, Player In, and Position');
                return;
            }

            // Validate that players are different
            if (playerOutId === playerInId) {
                showToastr('error', 'Player Out and Player In must be different players');
                return;
            }

            // Validate that players are selected
            if (!selectedPlayerOut || !selectedPlayerIn) {
                showToastr('error', 'Please select both Player Out and Player In');
                return;
            }

            // Show loading state
            const submitBtn = document.querySelector('#makeSubstitutionBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Substituting...';

            // SUBMIT FORM LANGSUNG
            document.getElementById('substitutionForm').submit();

            // fetch('/match-lineups/{{ $match->id }}/update-line-up', {
            //         method: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         body: JSON.stringify({
            //             team_id: teamId,
            //             player_out_id: playerOutId,
            //             player_in_id: playerInId,
            //             minute,
            //             position
            //         }),
            //     })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             showToastr('success', 'Substitution made successfully!');
            //             // Reset modal
            //             resetSubstitutionModal();
            //             // Close modal
            //             const modal = bootstrap.Modal.getInstance(document.getElementById('substitutionModal'));
            //             if (modal) modal.hide();
            //             // Reload lineup
            //             loadCurrentLineup();
            //         } else {
            //             let errorMsg = data.message || 'Unknown error occurred';
            //             if (data.errors) {
            //                 errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
            //             }
            //             showToastr('error', 'Error making substitution:\n' + errorMsg);
            //         }
            //     })
            //     .catch(error => {
            //         console.error('Error making substitution:', error);
            //         showToastr('error', 'Network error: ' + error.message);
            //     })
            //     .finally(() => {
            //         submitBtn.disabled = false;
            //         submitBtn.innerHTML = originalText;
            //     });
        }

        // Reset substitution modal
        function resetSubstitutionModal() {
            selectedPlayerOut = null;
            selectedPlayerIn = null;

            // Clear selections
            document.querySelectorAll('#playersOutContainer .substitution-player-card').forEach(card => {
                card.classList.remove('selected-out');
            });
            document.querySelectorAll('#playersInContainer .substitution-player-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Clear hidden inputs
            document.getElementById('playerOutId').value = '';
            document.getElementById('playerInId').value = '';

            // Hide display
            document.getElementById('selectedPlayersDisplay').style.display = 'none';

            // Reset form
            document.getElementById('substitutionForm').reset();
        }

        // Commentary Management Functions
        function addCommentary() {
            const minute = document.getElementById('commentaryMinute').value;
            const type = document.getElementById('commentaryType').value;
            const description = document.getElementById('commentaryDescription').value;
            const isImportant = document.getElementById('commentaryImportant').checked;

            if (!minute || !type || !description) {
                showToastr('error', 'Please fill in all required fields');
                return;
            }

            const formData = new FormData();
            formData.append('minute', minute);
            formData.append('commentary_type', type);
            formData.append('description', description);
            formData.append('is_important', isImportant);

            fetch('/matches/{{ $match->id }}/commentary', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToastr('success', 'Commentary added successfully!');
                    document.getElementById('addCommentaryForm').reset();
                    // Reload commentary
                    location.reload();
                } else {
                    showToastr('error', data.message || 'Error adding commentary');
                }
            })
            .catch(error => {
                console.error('Error adding commentary:', error);
                showToastr('error', 'Network error: ' + error.message);
            });
        }

        function deleteCommentary(commentaryId) {
            if (confirm('Are you sure you want to delete this commentary?')) {
                fetch(`/matches/{{ $match->id }}/commentary/${commentaryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastr('success', 'Commentary deleted successfully!');
                        location.reload();
                    } else {
                        showToastr('error', data.message || 'Error deleting commentary');
                    }
                })
                .catch(error => {
                    console.error('Error deleting commentary:', error);
                    showToastr('error', 'Network error: ' + error.message);
                });
            }
        }
    </script>

    <!-- Add Commentary Modal -->
    <div class="modal fade" id="addCommentaryModal" tabindex="-1" aria-labelledby="addCommentaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCommentaryModalLabel">
                        <i class="fas fa-comment-plus me-2"></i>Add Live Commentary
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCommentaryForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="commentaryMinute" class="form-label">Match Minute *</label>
                                    <input type="number" class="form-control" id="commentaryMinute" name="minute" min="0" max="120" required>
                                    <div class="form-text">Enter the minute when this event occurred</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="commentaryType" class="form-label">Commentary Type *</label>
                                    <select class="form-select" id="commentaryType" name="commentary_type" required>
                                        <option value="">Select type</option>
                                        <option value="general">General</option>
                                        <option value="tactical">Tactical Analysis</option>
                                        <option value="incident">Incident</option>
                                        <option value="highlight">Highlight</option>
                                        <option value="warning">Warning</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="commentaryDescription" class="form-label">Description *</label>
                            <textarea class="form-control" id="commentaryDescription" name="description" rows="4" maxlength="1000" required placeholder="Describe what happened at this moment..."></textarea>
                            <div class="form-text">Maximum 1000 characters</div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="commentaryImportant" name="is_important">
                                <label class="form-check-label" for="commentaryImportant">
                                    Mark as Important
                                </label>
                                <div class="form-text">Important commentary will be highlighted</div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="addCommentary()">
                        <i class="fas fa-plus me-2"></i>Add Commentary
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

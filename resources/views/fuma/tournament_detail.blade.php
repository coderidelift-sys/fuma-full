<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premier League 2023 - Tournament Details</title>
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
            --info: #0ea5e9;
            --blue-gradient: linear-gradient(135deg, #1e40af, #2563eb);
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

        .tournament-header {
            background: var(--blue-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .tournament-logo {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.25rem;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            background: transparent;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .badge-status {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            border-radius: 10px;
        }

        .badge-active {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary);
        }

        .team-logo {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .team-logo:hover {
            transform: scale(1.1);
        }

        .match-card {
            transition: transform 0.2s;
        }

        .match-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .progress {
            height: 8px;
            border-radius: 4px;
        }

        .progress-bar {
            background-color: var(--primary);
        }

        .stats-card {
            border-left: 3px solid var(--primary);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .live-badge {
            animation: pulse 1.5s infinite;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>

<body>
    <!-- Navigation with Gradient -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-futbol me-2"></i>FootyHub
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
                        <a class="nav-link active" href="{{ route('tournaments.index') }}">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="matches.html">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('players.index') }}">Players</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#login" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Tournament Header -->
    <header class="tournament-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    <img src="{{ $tournament->logo ? Storage::url($tournament->logo) : 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                        alt="Tournament Logo" class="tournament-logo mb-3 mb-md-0">
                </div>
                <div class="col-md-7 text-center text-md-start">
                    <h1 class="h3 fw-bold mb-1">{{ $tournament->name }}</h1>
                    <p class="mb-2 opacity-75">{{ $tournament->description }}</p>
                    <div class="d-flex justify-content-center justify-content-md-start gap-3">
                        <span
                            class="badge {{ $tournament->status === 'ongoing' ? 'badge-active' : ($tournament->status === 'upcoming' ? 'badge-upcoming' : 'badge-completed') }}">
                            <i class="fas fa-circle me-1 small"></i> {{ ucfirst($tournament->status) }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $tournament->start_date->format('M d') }} -
                            {{ $tournament->end_date->format('M d, Y') }}
                        </span>
                        <span class="text-white opacity-75">
                            <i class="fas fa-users me-1"></i> {{ $tournament->max_teams }} Teams
                        </span>
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                    @php
                        $isCompleted = $tournament->status === 'completed';
                    @endphp

                    <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editTournamentModal"
                        {{ $isCompleted ? 'disabled' : '' }}
                        title="{{ $isCompleted ? 'Tournament completed, cannot edit' : '' }}">
                        <i class="fas fa-edit"></i>
                    </button>

                    <button class="btn btn-light" {{ $isCompleted ? 'disabled' : '' }}
                        title="{{ $isCompleted ? 'Tournament completed, cannot share' : '' }}">
                        <i class="fas fa-share-alt"></i>
                    </button>

                    <div class="dropdown d-inline-block ms-2">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            {{ $isCompleted ? 'disabled' : '' }}
                            title="{{ $isCompleted ? 'Tournament completed, cannot manage' : '' }}">
                            Manage
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item {{ $isCompleted ? 'disabled text-muted' : '' }}" href="#"
                                    data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                    <i class="fas fa-plus me-2"></i>Add Team
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item {{ $isCompleted ? 'disabled text-muted' : '' }}" href="#"
                                    data-bs-toggle="modal" data-bs-target="#scheduleMatchModal">
                                    <i class="fas fa-calendar-plus me-2"></i>Schedule Match
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form id="deleteTournamentForm"
                                    action="{{ route('tournaments.delete', $tournament->id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <a class="dropdown-item text-danger {{ $isCompleted ? 'disabled text-muted' : '' }}"
                                    href="#delete"
                                    onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this tournament? This action cannot be undone.')) { document.getElementById('deleteTournamentForm').submit(); }">
                                    <i class="fas fa-trash me-2"></i>Delete Tournament
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mb-5">
        <!-- Tournament Navigation -->
        <ul class="nav nav-tabs mb-4" id="tournamentTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                    type="button">
                    <i class="fas fa-home me-1"></i> Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="standings-tab" data-bs-toggle="tab" data-bs-target="#standings"
                    type="button">
                    <i class="fas fa-list-ol me-1"></i> Standings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches"
                    type="button">
                    <i class="fas fa-calendar-alt me-1"></i> Matches
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams"
                    type="button">
                    <i class="fas fa-users me-1"></i> Teams
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats"
                    type="button">
                    <i class="fas fa-chart-bar me-1"></i> Statistics
                </button>
            </li>
        </ul>

        <div class="tab-content" id="tournamentTabContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>There were some errors in your submission:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Tournament Progress -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Tournament Progress</h5>
                                <div class="progress mt-3 mb-2">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $tournamentProgress['percentage'] }}%;"
                                        aria-valuenow="{{ $tournamentProgress['percentage'] }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Started: {{ $tournamentProgress['started'] }}</span>
                                    <span>{{ $tournamentProgress['percentage'] }}% Complete</span>
                                    <span>Ends: {{ $tournamentProgress['ended'] }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Matches -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title fw-bold mb-0">Recent Matches</h5>
                                    <button id="viewAllMatchesBtn" type="button"
                                        class="btn btn-sm btn-outline-primary">
                                        View All
                                    </button>
                                </div>

                                @forelse ($recentMatches as $recentMatch)
                                    <div class="card match-card mb-3">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-5 text-end">
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <span
                                                            class="fw-bold me-2">{{ $recentMatch->homeTeam->name }}</span>
                                                        <img src="{{ $recentMatch->homeTeam->logo ?? 'http://placehold.co/40' }}"
                                                            alt="Team Logo" class="team-logo">
                                                    </div>
                                                </div>
                                                <div class="col-2 text-center">
                                                    <span
                                                        class="badge bg-light text-dark p-2">{{ $recentMatch->home_score ?? 0 }}
                                                        - {{ $recentMatch->away_score ?? 0 }}</span>
                                                </div>
                                                <div class="col-5 text-start">
                                                    <div class="d-flex align-items-center">
                                                        <img src="http://placehold.co/40" alt="Team Logo"
                                                            class="team-logo">
                                                        <span
                                                            class="fw-bold ms-2">{{ $recentMatch->awayTeam->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center mt-2 small text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $recentMatch->scheduled_at?->format('d M, Y') }} | <i
                                                    class="fas fa-map-marker-alt me-1"></i> {{ $recentMatch->venue }}
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        No recent matches available.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Tournament Info -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Tournament Information</h5>
                                <ul class="list-group list-group-flush">
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted"><i class="fas fa-trophy me-2"></i>Prize Pool</span>
                                        <span class="fw-bold">{{ $tournamentInformation['prize_pool'] }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted"><i class="fas fa-flag me-2"></i>Format</span>
                                        <span class="fw-bold">{{ $tournamentInformation['format'] }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted"><i class="fas fa-gamepad me-2"></i>Matches
                                            Played</span>
                                        <span class="fw-bold">{{ $tournamentInformation['match_played'] }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted"><i class="fas fa-user-tie me-2"></i>Organizer</span>
                                        <span class="fw-bold">{{ $tournamentInformation['organizer'] }}</span>
                                    </li>
                                    <li
                                        class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="text-muted"><i class="fas fa-globe me-2"></i>Location</span>
                                        <span class="fw-bold">{{ $tournamentInformation['location'] }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Top Scorers -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-3">Top Scorers</h5>
                                <div class="list-group list-group-flush">
                                    @forelse ($topScorers as $topScorer)
                                        <div class="list-group-item d-flex align-items-center px-0 py-2">
                                            <span class="badge bg-primary rounded-circle me-3">
                                                {{ $topScorer['player']['jersey_number'] }}
                                            </span>
                                            <img src="{{ $topScorer['player']['avatar'] ?? 'https://placehold.co/40' }}"
                                                alt="Player" class="rounded-circle me-3" width="32">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $topScorer['player']['name'] }}</h6>
                                                <small
                                                    class="text-muted">{{ $topScorer['team']['name'] ?? 'No Team' }}</small>
                                            </div>
                                            <span class="badge bg-light text-dark">{{ $topScorer['goals'] }}
                                                Goals</span>
                                        </div>
                                    @empty
                                        <p class="text-muted">No top scorers yet.</p>
                                    @endforelse
                                </div>
                                <button id="viewAllStatsBtn" type="button"
                                    class="btn btn-sm btn-outline-primary w-100 mt-3">View Full
                                    Stats</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standings Tab (content would be loaded dynamically) -->
            <div class="tab-pane fade" id="standings" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">League Standings</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Team</th>
                                        <th>P</th>
                                        <th>W</th>
                                        <th>D</th>
                                        <th>L</th>
                                        <th>GF</th>
                                        <th>GA</th>
                                        <th>GD</th>
                                        <th>Pts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($standings as $index => $team)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $team['logo'] ?? 'https://placehold.co/30' }}"
                                                        alt="Team Logo" class="team-logo me-2">
                                                    <span>{{ $team['name'] }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $team['matches_played'] }}</td>
                                            <td>{{ $team['wins'] }}</td>
                                            <td>{{ $team['draws'] }}</td>
                                            <td>{{ $team['losses'] }}</td>
                                            <td>{{ $team['goals_for'] }}</td>
                                            <td>{{ $team['goals_against'] }}</td>
                                            <td>{{ $team['goal_difference'] > 0 ? '+' . $team['goal_difference'] : $team['goal_difference'] }}
                                            </td>
                                            <td class="fw-bold">{{ $team['points'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matches Tab (content would be loaded dynamically) -->
            <div class="tab-pane fade" id="matches" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">All Matches</h5>
                        <!-- Match fixtures would be listed here -->
                        <!-- Match List -->
                        <div class="list-group">
                            @forelse ($matches as $match)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        {{-- Home Team --}}
                                        <div class="col-md-4 text-end">
                                            <strong>{{ $match['home_team'] }}</strong>
                                            <img src="{{ $match['home_logo'] ?? 'https://placehold.co/30' }}"
                                                class="ms-2 team-logo" alt="Logo">
                                        </div>

                                        {{-- Match Status & Score --}}
                                        <div class="col-md-4 text-center">
                                            @php
                                                $scheduled = \Carbon\Carbon::parse($match['scheduled_at']);
                                            @endphp

                                            @if ($match['status'] === 'scheduled')
                                                <span class="badge bg-primary text-white">Upcoming</span><br>
                                                <small>{{ $scheduled->format('F d, Y - H:i') }}</small>
                                            @elseif($match['status'] === 'completed')
                                                <span class="badge bg-secondary text-light">Completed</span><br>
                                                <small>{{ $scheduled->format('F d, Y') }} - Final Score</small><br>
                                                <strong>{{ $match['home_score'] }} -
                                                    {{ $match['away_score'] }}</strong>
                                            @elseif($match['status'] === 'live')
                                                <span class="badge bg-danger live-badge">Live</span><br>
                                                <small class="live-minute" data-start="{{ $scheduled->timestamp }}">
                                                    0:00
                                                </small>
                                                <small class="extra-time text-dark ms-1" style="display:none;">Extra
                                                    Time</small>
                                                <br>
                                                <strong>{{ $match['home_score'] }} -
                                                    {{ $match['away_score'] }}</strong>
                                            @endif
                                        </div>

                                        {{-- Away Team --}}
                                        <div class="col-md-4 text-start">
                                            <img src="{{ $match['away_logo'] ?? 'https://placehold.co/30' }}"
                                                class="me-2 team-logo" alt="Logo">
                                            <strong>{{ $match['away_team'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="list-group-item text-center text-muted">
                                    <i class="fas fa-futbol fa-2x mb-2"></i>
                                    <p class="mb-0">No matches scheduled yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Tab (content in table format) -->
            <div class="tab-pane fade" id="teams" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Participating Teams</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Logo</th>
                                        <th>Team Name</th>
                                        <th>City</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $index => $team)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{ $team['logo'] ?? 'https://placehold.co/40' }}"
                                                    class="team-logo" alt="Logo {{ $team['name'] }}">
                                            </td>
                                            <td>{{ $team['name'] }}</td>
                                            <td>{{ $team['city'] ?? 'Unknown' }}</td>
                                            <td>
                                                <a href="{{ route('teams.show', $team['id']) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    View Team
                                                </a>
                                                {{-- <a href="{{ route('team.detail', ['id' => $team['id']]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    View Team
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Statistics Tab (content would be loaded dynamically) -->
            <div class="tab-pane fade" id="stats" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Tournament Statistics</h5>
                        <div class="row">
                            <!-- Possession -->
                            <div class="col-md-4">
                                <div class="card border-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Average Ball Possession</h6>
                                        @forelse ($statistics['ball_possession'] as $team => $possession)
                                            <p class="mb-1"><strong>{{ $team }}:</strong>
                                                {{ $possession }}%</p>
                                        @empty
                                            <p class="mb-1 text-muted">No ball possession data available.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- Goal Stats -->
                            <div class="col-md-4">
                                <div class="card border-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Total Goals</h6>
                                        <p class="mb-1"><strong>Total Matches:</strong>
                                            {{ $statistics['total_goals']['matches'] }}</p>
                                        <p class="mb-1"><strong>Total Goals:</strong>
                                            {{ $statistics['total_goals']['goals'] }}</p>
                                        <p class="mb-1"><strong>Avg Goals per Match:</strong>
                                            {{ $statistics['total_goals']['avg_per_match'] }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Discipline -->
                            <div class="col-md-4">
                                <div class="card border-light mb-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold">Disciplinary Stats</h6>
                                        @foreach ($statistics['disciplines'] as $type => $count)
                                            <p class="mb-1">
                                                <strong>{{ ucwords(str_replace('_', ' ', $type)) }}:</strong>
                                                {{ $count }}
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 d-none">
                            <a href="#" class="btn btn-sm btn-outline-primary">Download Full Statistics</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Edit Tournament Modal -->
    <div class="modal fade" id="editTournamentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" id="editTournamentForm" enctype="multipart/form-data" method="POST"
                action="{{ route('tournaments.update', $tournament->id) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Tournament</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="row">
                            <!-- Logo -->
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img id="tournamentLogoPreview"
                                            src="{{ $tournament->logo ? Storage::url($tournament->logo) : 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                                            alt="Tournament Logo" class="img-fluid mb-3" style="max-height: 150px;">
                                        <input type="file" id="tournamentLogoInput" accept="image/*"
                                            class="form-control mb-2" name="logo">
                                        <button type="button" class="btn btn-sm btn-outline-primary w-100"
                                            onclick="document.getElementById('tournamentLogoInput').click()">Change
                                            Logo</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Tournament Details -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Tournament Name</label>
                                    <input type="text" class="form-control" name="name" id="tournamentName"
                                        value="{{ $tournament->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="tournamentDescription" rows="3" required>{{ $tournament->description }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" id="startDate"
                                            value="{{ \Carbon\Carbon::parse($tournament->start_date)->format('Y-m-d') }}"
                                            required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" id="endDate"
                                            value="{{ \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d') }}"
                                            required>
                                        <div id="dateError" class="text-danger mt-1" style="font-size: 0.875rem;">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status</label>
                                        @php
                                            $currentStatus = $tournament->status;
                                        @endphp

                                        <select class="form-select" name="status" id="tournamentStatus" required>
                                            @if ($currentStatus === 'upcoming')
                                                <option value="upcoming" selected>Upcoming</option>
                                                <option value="ongoing">Ongoing</option>
                                                <option value="completed">Completed</option>
                                            @elseif($currentStatus === 'ongoing')
                                                <option value="ongoing" selected>Ongoing</option>
                                                <option value="completed">Completed</option>
                                            @elseif($currentStatus === 'completed')
                                                <option value="completed" selected>Completed</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="teamsNumber" class="form-label">Number of Teams</label>
                                        <select id="teamsNumber" name="max_teams" class="form-select" required>
                                            <option value="" disabled>Select teams</option>
                                            @foreach ([2, 4, 8, 16, 32] as $teamCount)
                                                <option value="{{ $teamCount }}"
                                                    {{ $tournament->max_teams == $teamCount ? 'selected' : '' }}>
                                                    {{ $teamCount }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Prize Pool</label>
                                        <input type="text" class="form-control" name="prize_pool" id="prize_pool"
                                            value="{{ $tournament->prize_pool }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Location</label>
                                        <input type="text" class="form-control" name="venue" id="location"
                                            value="{{ $tournament->venue }}" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Organizer</label>
                                        <select class="form-select" name="organizer_id" id="organizerSelect"
                                            required>
                                            <option value="" disabled>Select organizer</option>
                                            @foreach ($organizers as $org)
                                                <option value="{{ $org->id }}"
                                                    {{ $tournament->organizer_id == $org->id ? 'selected' : '' }}>
                                                    {{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveTournamentBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Team Modal -->
    <div class="modal fade" id="addTeamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('tournaments.addTeam', $tournament->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Team to Tournament</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="teamSearch" class="form-label">Search Team</label>
                        <input type="text" id="teamSearch" class="form-control mb-2"
                            placeholder="Type to search...">

                        <select class="form-select" id="teamSelect" name="team_id" required>
                            <option selected disabled>Choose a team</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Team</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Schedule Match -->
    <div class="modal fade" id="scheduleMatchModal" tabindex="-1" aria-labelledby="scheduleMatchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="scheduleMatchModalLabel">
                        <i class="fas fa-calendar-plus me-2"></i> Schedule Match
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    {{-- DAFTAR MATCH EXISTING --}}
                    <h6 class="fw-bold mb-3">Scheduled Matches</h6>
                    <table class="table table-bordered table-striped mb-4">
                        <thead>
                            <tr>
                                <th>Home</th>
                                <th>Away</th>
                                <th>Date & Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tournament->matches()->orderBy('scheduled_at')->get() as $match)
                                <tr>
                                    <td>{{ $match->homeTeam->name }}</td>
                                    <td>{{ $match->awayTeam->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($match->scheduled_at)->format('d M, Y | H:i') }}</td>
                                    <td>
                                        {{-- Edit button --}}
                                        <button type="button" class="btn btn-sm btn-warning me-1 edit-match-btn"
                                            data-id="{{ $match->id }}" data-home="{{ $match->home_team_id }}"
                                            data-away="{{ $match->away_team_id }}"
                                            data-date="{{ $match->scheduled_at }}"
                                            data-venue="{{ $match->venue }}" data-note="{{ $match->note }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Delete button --}}
                                        <form
                                            action="{{ route('tournaments.deleteScheduleMatch', ['tournament' => $tournament->id, 'match' => $match->id]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure to delete this match?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- FORM SCHEDULE MATCH --}}
                    <form id="formScheduleMatch" method="POST"
                        action="{{ route('tournaments.addScheduleMatch', $tournament->id) }}">
                        @csrf
                        <input type="hidden" id="matchId" name="match_id">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="team1" class="form-label">Home</label>
                                <select class="form-select" id="team1" name="home_team_id" required>
                                    <option selected disabled>Select Team</option>
                                    @foreach ($tournament->teams()->wherePivot('status', 'registered')->get() as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="team2" class="form-label">Away</label>
                                <select class="form-select" id="team2" name="away_team_id" required>
                                    <option selected disabled>Select Team</option>
                                    @foreach ($tournament->teams()->wherePivot('status', 'registered')->get() as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="matchDate" class="form-label">Match Date & Time</label>
                            <input type="datetime-local" class="form-control" id="matchDate" name="scheduled_at"
                                min="{{ \Carbon\Carbon::parse($tournament->start_date)->format('Y-m-d\TH:i') }}"
                                max="{{ \Carbon\Carbon::parse($tournament->end_date)->format('Y-m-d\TH:i') }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="matchNote" class="form-label">Additional Notes</label>
                            <textarea class="form-control" id="matchNote" name="note" rows="3" placeholder="Optional notes..."></textarea>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" form="formScheduleMatch" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Save Schedule
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4 mt-5">
        <div class="container text-center text-muted small">
            <p class="mb-0">© 2023 FootyHub - Football Tournament Management System</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // =================== LOGO PREVIEW ===================
            const logoInput = document.getElementById('tournamentLogoInput');
            const logoPreview = document.getElementById('tournamentLogoPreview');

            logoInput?.addEventListener('change', e => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => logoPreview.src = ev.target.result;
                    reader.readAsDataURL(file);
                }
            });

            // =================== DATE VALIDATION ===================
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            const dateError = document.getElementById('dateError');

            const validateDates = () => {
                if (startInput?.value) endInput.min = startInput.value;
                if (startInput?.value && endInput?.value && new Date(startInput.value) > new Date(endInput
                        .value)) {
                    dateError.textContent = 'End date must be after start date';
                } else {
                    dateError.textContent = '';
                }
            };

            startInput?.addEventListener('input', validateDates);
            endInput?.addEventListener('input', validateDates);

            const editForm = document.getElementById('editTournamentForm');
            editForm?.addEventListener('submit', e => {
                validateDates();
                if (dateError.textContent) e.preventDefault();
            });

            // =================== MATCH FORM ===================
            const form = document.getElementById('formScheduleMatch');
            const matchIdInput = document.getElementById('matchId');
            const team1Input = document.getElementById('team1');
            const team2Input = document.getElementById('team2');
            const matchDateInput = document.getElementById('matchDate');
            const matchNoteInput = document.getElementById('matchNote');

            const switchTeamsOptions = () => {
                [team1Input, team2Input].forEach(input => {
                    const other = input === team1Input ? team2Input : team1Input;
                    Array.from(other.options).forEach(opt => opt.disabled = opt.value === input.value);
                });
            };

            [team1Input, team2Input].forEach(input => input?.addEventListener('change', switchTeamsOptions));

            const resetMatchForm = () => {
                matchIdInput.value = '';
                team1Input.value = '';
                team2Input.value = '';
                matchDateInput.value = '';
                matchNoteInput.value = '';
                form.action = "{{ route('tournaments.addScheduleMatch', $tournament->id) }}";
                form.method = 'POST';
                const methodInput = form.querySelector('input[name="_method"]');
                if (methodInput) methodInput.remove();
            };

            const editMatch = (id, homeId, awayId, scheduledAt, venue, note) => {
                matchIdInput.value = id;
                team1Input.value = homeId;
                team2Input.value = awayId;
                matchDateInput.value = scheduledAt.substring(0, 16);
                matchNoteInput.value = note ?? '';
                form.action = "{{ url('tournaments/' . $tournament->id . '/updateMatch') }}/" + id;
                form.method = 'POST';
                if (!form.querySelector('input[name="_method"]')) {
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    form.appendChild(methodInput);
                }
            };

            // event delegation example
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-match-btn')) {
                    const btn = e.target.closest('.edit-match-btn');
                    const id = btn.dataset.id;
                    const home = btn.dataset.home;
                    const away = btn.dataset.away;
                    const date = btn.dataset.date;
                    const venue = btn.dataset.venue;
                    const note = btn.dataset.note;

                    editMatch(id, home, away, date, venue, note);
                }
            });


            form?.addEventListener('submit', e => {
                let valid = true;
                const minDate = new Date(matchDateInput.min);
                const maxDate = new Date(matchDateInput.max);
                const selectedDate = new Date(matchDateInput.value);

                if (selectedDate < minDate || selectedDate > maxDate) {
                    alert('Match date must be between tournament start and end date.');
                    valid = false;
                }
                if (team1Input.value === team2Input.value) {
                    alert('Home and Away teams must be different.');
                    valid = false;
                }
                if (!valid) e.preventDefault();
            });

            // =================== TOASTR ===================
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right'
            };
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            // =================== SWITCH BOOTSTRAP TABS ===================
            const switchTab = selector => {
                const tabTrigger = document.querySelector(selector);
                if (tabTrigger) new bootstrap.Tab(tabTrigger).show();
            };

            [{
                    btnId: 'viewAllMatchesBtn',
                    target: '[data-bs-target="#matches"]'
                },
                {
                    btnId: 'viewAllStatsBtn',
                    target: '[data-bs-target="#stats"]'
                }
            ].forEach(item => {
                const btn = document.getElementById(item.btnId);
                btn?.addEventListener('click', e => {
                    e.preventDefault();
                    switchTab(item.target);
                });
            });

            // =================== LIVE MATCH TIMER ===================
            const updateLiveMinutes = () => {
                const now = Math.floor(Date.now() / 1000);
                document.querySelectorAll('.live-minute').forEach(el => {
                    const start = parseInt(el.dataset.start) || now;
                    let totalSeconds = Math.max(0, now - start);
                    const minutes = Math.floor(totalSeconds / 60);
                    const seconds = totalSeconds % 60;
                    const extraBadge = el.nextElementSibling;
                    let display;
                    if (minutes < 90) {
                        display = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                        if (extraBadge) extraBadge.style.display = 'none';
                    } else {
                        display = `90+${minutes - 90}:${seconds.toString().padStart(2, '0')}`;
                        if (extraBadge) extraBadge.style.display = 'inline-block';
                    }
                    el.textContent = display;
                });
                requestAnimationFrame(updateLiveMinutes);
            };
            updateLiveMinutes();

            // =================== FETCH TEAMS ===================
            const $select = $('#teamSelect');
            const $search = $('#teamSearch');

            const fetchTeams = (query = '') => {
                $.ajax({
                    url: '{{ route('tournaments.availableTeams', $tournament->id) }}',
                    type: 'GET',
                    data: {
                        search: query
                    },
                    dataType: 'json',
                    success: data => {
                        $select.empty().append('<option selected disabled>Choose a team</option>');
                        data.results.forEach(team => $select.append('<option value="' + team.id +
                            '">' + team.name + '</option>'));
                    },
                    error: xhr => console.error('Error fetching teams:', xhr)
                });
            };

            fetchTeams();
            $search?.on('input', () => fetchTeams($search.val()));

        });
    </script>
</body>

</html>

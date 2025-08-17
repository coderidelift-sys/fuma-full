<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $team->name ?? 'Team Details' }} - Football Tournament Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --accent-color: #e74c3c;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --blue-gradient: linear-gradient(135deg, #1e40af, #2563eb);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--light-color);
        }

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
            transition: all 0.3s;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: white !important;
            transform: translateY(-2px);
        }

        .team-header {
            background: var(--blue-gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .team-logo-lg {
            width: 120px;
            height: 120px;
            object-fit: contain;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
        }

        .nav-tabs .nav-link {
            color: #64748b;
            font-weight: 500;
            border: none;
            padding: 0.75rem 1.25rem;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            background: transparent;
        }

        .stat-card {
            border-left: 3px solid var(--primary-color);
        }

        /* Table styling */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table th {
            font-weight: 600;
        }

        .player-photo-sm {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
        }

        /* Modal styling */
        .modal-header {
            background: var(--blue-gradient);
            color: white;
        }

        .achievements-table th {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
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
                        <a class="nav-link active" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('matches.index') }}">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('players.index') }}">Players</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if (isset($team))
        <!-- Team Header -->
        <header class="team-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center text-md-start">
                        <img src="{{ $team->logo ? Storage::url($team->logo) : 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                            alt="Team Logo" class="team-logo-lg mb-3 mb-md-0">
                    </div>
                    <div class="col-md-7 text-center text-md-start">
                        <h1 class="h3 fw-bold mb-1">{{ $team->name }}
                            @if ($team->short_name)
                                <small class="opacity-75">({{ $team->short_name }})</small>
                            @endif
                        </h1>
                        <p class="mb-2 opacity-75">{{ $team->description ?? 'Premier football club' }}</p>
                        <div class="d-flex justify-content-center justify-content-md-start gap-3">
                            <span class="badge bg-white text-dark badge-status">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ $team->city }}, {{ $team->country }}
                            </span>
                            @if ($team->founded_year)
                                <span class="text-white opacity-75">
                                    <i class="fas fa-calendar-day me-1"></i> Founded: {{ $team->founded_year }}
                                </span>
                            @endif
                            <span class="text-white opacity-75">
                                <i class="fas fa-users me-1"></i> {{ $team->players_count ?? 0 }} Players
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3 text-center text-md-end mt-4 mt-md-0">
                        <!-- Edit Team Button with Modal Trigger -->
                        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editTeamModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-light">
                            <i class="fas fa-share-alt"></i>
                        </button>
                        <div class="dropdown d-inline-block ms-2">
                            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Manage Team
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#addPlayerModal"><i class="fas fa-user-plus me-2"></i>Add
                                        Player</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#editDetailsModal"><i class="fas fa-edit me-2"></i>Edit
                                        Details</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-danger" href="#"
                                        onclick="deleteTeam({{ $team->id }})"><i
                                            class="fas fa-trash me-2"></i>Delete Team</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="container mb-5">
            <!-- Team Navigation -->
            <ul class="nav nav-tabs mb-4" id="teamTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview"
                        type="button">
                        <i class="fas fa-home me-1"></i> Overview
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="players-tab" data-bs-toggle="tab" data-bs-target="#players"
                        type="button">
                        <i class="fas fa-users me-1"></i> Players
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches"
                        type="button">
                        <i class="fas fa-calendar-alt me-1"></i> Matches
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats"
                        type="button">
                        <i class="fas fa-chart-bar me-1"></i> Statistics
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history"
                        type="button">
                        <i class="fas fa-history me-1"></i> History
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="teamTabContent">
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

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Oops!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Team Information -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Team Information</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i
                                                            class="fas fa-trophy me-2"></i>Nickname</span>
                                                    <span class="fw-bold">{{ $team->nickname ?? 'Not set' }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i
                                                            class="fas fa-user-tie me-2"></i>Manager</span>
                                                    <span class="fw-bold">{{ $team->manager_name }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i
                                                            class="fas fa-map-marker-alt me-2"></i>Stadium</span>
                                                    @if ($team->stadium)
                                                        <span class="fw-bold">{{ $team->stadium }}
                                                            <small>({{ $team->capacity }})</small></span>
                                                    @else
                                                        <span class="fw-bold text-muted">Not set</span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i
                                                            class="fas fa-tshirt me-2"></i>Colors</span>
                                                    <span>
                                                        @if ($team->primary_color)
                                                            <span
                                                                class="badge bg-primary me-1">{{ $team->primary_color }}</span>
                                                        @else
                                                            <span class="badge bg-secondary me-1">N/A</span>
                                                        @endif

                                                        @if ($team->secondary_color)
                                                            <span
                                                                class="badge bg-white text-dark border">{{ $team->secondary_color }}</span>
                                                        @else
                                                            <span class="badge bg-secondary border">N/A</span>
                                                        @endif
                                                    </span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i
                                                            class="fas fa-star me-2"></i>Status</span>
                                                    <span class="badge bg-success badge-pill">Active</span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <span class="text-muted"><i class="fas fa-trophy me-2"></i>Active
                                                        Tournaments</span>
                                                    <span class="fw-bold">{{ $team->tournaments_count ?? 0 }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Achievements Table -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Recent Achievements</h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover achievements-table">
                                            <thead>
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Achievement</th>
                                                    <th>Competition</th>
                                                    <th>Details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($achievements ?? [] as $achievement)
                                                    <tr>
                                                        <td>{{ $achievement->year }}</td>
                                                        <td>{{ $achievement->title }}</td>
                                                        <td>{{ $achievement->competition }}</td>
                                                        <td>{{ $achievement->description }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center text-muted">No
                                                            achievements yet</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Squad Overview -->
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Squad Overview</h5>
                                    <div class="mb-4">
                                        <h6 class="small text-muted mb-2">PLAYERS BY POSITION</h6>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>Goalkeepers</span>
                                            <span
                                                class="badge bg-light text-dark">{{ $goalkeepers_count ?? 0 }}</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ (($goalkeepers_count ?? 0) / max($team->players_count ?? 1, 1)) * 100 }}%">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>Defenders</span>
                                            <span class="badge bg-light text-dark">{{ $defenders_count ?? 0 }}</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ (($defenders_count ?? 0) / max($team->players_count ?? 1, 1)) * 100 }}%">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>Midfielders</span>
                                            <span
                                                class="badge bg-light text-dark">{{ $midfielders_count ?? 0 }}</span>
                                        </div>
                                        <div class="progress mb-3" style="height: 6px;">
                                            <div class="progress-bar bg-info"
                                                style="width: {{ (($midfielders_count ?? 0) / max($team->players_count ?? 1, 1)) * 100 }}%">
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span>Forwards</span>
                                            <span class="badge bg-light text-dark">{{ $forwards_count ?? 0 }}</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-warning"
                                                style="width: {{ (($forwards_count ?? 0) / max($team->players_count ?? 1, 1)) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <h6 class="small text-muted mb-2">AVERAGE AGE</h6>
                                        <h3 class="fw-bold">{{ $average_age ?? '26.4' }} <small
                                                class="text-muted">years</small></h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Upcoming Match -->
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3">Next Match</h5>
                                    <div class="text-center py-3">
                                        @if (isset($nextMatch))
                                            <div class="d-flex justify-content-center align-items-center mb-3">
                                                <div class="text-end" style="width: 120px;">
                                                    <h6 class="mb-0">{{ $nextMatch->homeTeam->name ?? 'TBD' }}</h6>
                                                    <small class="text-muted">Home</small>
                                                </div>
                                                <div class="mx-3">
                                                    <span class="badge bg-light text-dark px-3 py-2">VS</span>
                                                </div>
                                                <div class="text-start" style="width: 120px;">
                                                    <h6 class="mb-0">{{ $nextMatch->awayTeam->name ?? 'TBD' }}</h6>
                                                    <small class="text-muted">Away</small>
                                                </div>
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ $nextMatch->scheduled_at ? \Carbon\Carbon::parse($nextMatch->scheduled_at)->format('D, M j, Y') : 'TBD' }}<br>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $nextMatch->scheduled_at ? \Carbon\Carbon::parse($nextMatch->scheduled_at)->format('H:i') : 'TBD' }}<br>
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $nextMatch->venue ?? 'TBD' }}
                                            </div>
                                            <a href="{{ route('matches.show', $nextMatch->id) }}" class="btn btn-sm btn-primary mt-3">View Details</a>
                                        @else
                                            <p class="text-muted">No upcoming matches scheduled</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Players Tab -->
                <div class="tab-pane fade" id="players" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title fw-bold mb-0">Team Players</h5>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addPlayerModal">
                                    <i class="fas fa-plus me-1"></i> Add Player
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Player</th>
                                            <th>Position</th>
                                            <th>Age</th>
                                            <th>Height</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($players ?? [] as $player)
                                            <tr>
                                                <td>{{ $player->jersey_number ?? 'N/A' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $player->avatar ? Storage::url($player->avatar) : 'https://placehold.co/40' }}"
                                                            alt="Player" class="player-photo-sm me-2">
                                                        <span>{{ $player->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $player->position }}</td>
                                                <td>{{ $player->birth_date ? \Carbon\Carbon::parse($player->birth_date)->age : 'N/A' }}
                                                </td>
                                                <td>
                                                    {{ $player->height ? number_format($player->height / 100, 2) . 'm' : 'N/A' }}
                                                </td>
                                                <td>
                                                    @if ($player->is_captain)
                                                        <span class="badge bg-primary">Captain</span>
                                                    @else
                                                        <span class="badge bg-success">Starter</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-outline-primary"
                                                        href="{{ route('players.show', $player->id) }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No players found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                {{ $players->withQueryString()->fragment('players')->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Matches Tab -->
                <div class="tab-pane fade" id="matches" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Recent Matches</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Match</th>
                                            <th>Competition</th>
                                            <th>Result</th>
                                            <th>Venue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($matches ?? [] as $match)
                                            <tr>
                                                <td>{{ $match->scheduled_at ? \Carbon\Carbon::parse($match->scheduled_at)->format('M d, Y') : 'TBD' }}
                                                </td>
                                                <td>{{ $match->homeTeam->name ?? '-' }} vs
                                                    {{ $match->awayTeam->name ?? '-' }}</td>
                                                <td>{{ $match->tournament->name ?? '-' }}</td>
                                                <td><span
                                                        class="badge bg-light text-dark">{{ $match->home_score ?? 0 }}
                                                        - {{ $match->away_score ?? 0 }}</span></td>
                                                <td>{{ $match->venue }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No matches found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Tab -->
                <div class="tab-pane fade" id="stats" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Team Statistics</h5>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <h6 class="text-muted">Current Season</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td>Matches Played</td>
                                                            <td class="text-end">
                                                                {{ ($wins_count ?? 0) + ($draws_count ?? 0) + ($losses_count ?? 0) }}
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Wins</td>
                                                            <td class="text-end">{{ $wins_count ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Draws</td>
                                                            <td class="text-end">{{ $draws_count ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Losses</td>
                                                            <td class="text-end">{{ $losses_count ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Goals Scored</td>
                                                            <td class="text-end">{{ $goals_for ?? 0 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Goals Conceded</td>
                                                            <td class="text-end">{{ $goals_against ?? 0 }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card stat-card">
                                        <div class="card-body">
                                            <h6 class="text-muted">League Position</h6>
                                            <div class="text-center py-3">
                                                <h1 class="display-4 fw-bold text-primary">
                                                    {{ $league_position ?? '1' }}<sup>st</sup></h1>
                                                <p class="text-muted">{{ $league_name ?? 'Premier League' }}
                                                    {{ $season ?? '2022/23' }}</p>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success"
                                                        style="width: {{ $points_percentage ?? 75 }}%"></div>
                                                </div>
                                                <small class="text-muted">Points:
                                                    {{ $total_points ?? 53 }}/{{ $max_points ?? 72 }}
                                                    ({{ $points_percentage ?? 75 }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Tab -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Team History</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Season</th>
                                            <th>League</th>
                                            <th>Position</th>
                                            {{-- <th>Cup</th>
                                            <th>Europe</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($history ?? [] as $event)
                                            <tr>
                                                <td>{{ $event->season ?? 'N/A' }}</td>
                                                <td>{{ $event->league ?? 'N/A' }}</td>
                                                <td>{{ $event->position ?? 'N/A' }}</td>
                                                {{-- <td>{{ $event->cup ?? 'N/A' }}</td>
                                                <td>{{ $event->europe ?? 'N/A' }}</td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">No history available
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
        </main>
    @else
        <!-- Team Not Found -->
        <div class="container py-5">
            <div class="text-center">
                <h1 class="display-4 text-muted">Team Not Found</h1>
                <p class="lead">The team you're looking for doesn't exist or has been removed.</p>
                <a href="{{ route('teams.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Teams
                </a>
            </div>
        </div>
    @endif

    <!-- Add Player Modal -->
    <div class="modal fade" id="addPlayerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Player</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Player Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Position</label>
                            <select class="form-select" name="position" required>
                                <option value="">Select Position</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                                <option value="Defender">Defender</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Forward">Forward</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jersey Number</label>
                                <input type="number" class="form-control" name="jersey_number">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="birth_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nationality</label>
                            <input type="text" class="form-control" name="nationality">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Player Photo</label>
                            <input type="file" class="form-control" name="avatar">
                        </div>
                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                        <button type="submit" class="btn btn-primary">Add Player</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Team Details Modal -->
    <div class="modal fade" id="editDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Team Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('teams.update', $team->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Team Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $team->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Short Name</label>
                            <input type="text" class="form-control" name="short_name"
                                value="{{ $team->short_name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nickname</label>
                            <input type="text" class="form-control" name="nickname"
                                value="{{ $team->nickname }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Founded</label>
                            <input type="number" class="form-control" name="founded_year"
                                value="{{ $team->founded_year }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stadium</label>
                            <input type="text" class="form-control" name="stadium" value="{{ $team->stadium }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Capacity</label>
                            <input type="number" class="form-control" name="capacity"
                                value="{{ $team->capacity }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $team->description }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Primary Color</label>
                            <input type="color" class="form-control form-control-color" name="primary_color"
                                value="{{ $team->primary_color }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Secondary Color</label>
                            <input type="color" class="form-control form-control-color" name="secondary_color"
                                value="{{ $team->secondary_color }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Team Website</label>
                            <input type="url" class="form-control" name="website" value="{{ $team->website }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Team Logo</label>
                            <input type="file" class="form-control" name="logo">
                            @if ($team->logo)
                                <p class="text-muted small mt-1">Current Logo: <a
                                        href="{{ Storage::url($team->logo) }}"
                                        target="_blank">{{ basename($team->logo) }}</a></p>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Team Profile Modal -->
    <div class="modal fade" id="editTeamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Team Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('teams.update', $team->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <img src="{{ $team->logo ? Storage::url($team->logo) : 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180' }}"
                                            alt="Team Logo" class="img-fluid mb-3" style="max-height: 150px;">
                                        <button type="button" class="btn btn-sm btn-outline-primary w-100"
                                            onclick="changeLogo()">Change Logo</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Team Name</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $team->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{ $team->description }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Primary Color</label>
                                        <input type="color" class="form-control form-control-color"
                                            name="primary_color" value="{{ $team->primary_color }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Secondary Color</label>
                                        <input type="color" class="form-control form-control-color"
                                            name="secondary_color" value="{{ $team->secondary_color }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Team Website</label>
                                    <input type="url" class="form-control" name="website"
                                        value="{{ $team->website }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Team Logo</label>
                                    <input type="file" class="form-control" name="logo">
                                    @if ($team->logo)
                                        <p class="text-muted small mt-1">Current Logo: <a
                                                href="{{ Storage::url($team->logo) }}"
                                                target="_blank">{{ basename($team->logo) }}</a></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </form>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Initialize toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right'
        };

        // Handle form submissions
        document.addEventListener('DOMContentLoaded', function() {
            // Add Player Modal Form
            const addPlayerForm = document.querySelector('#addPlayerModal form');
            if (addPlayerForm) {
                addPlayerForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch('/players', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success('Player added successfully');
                                $('#addPlayerModal').modal('hide');
                                // Reload page to show new player
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                toastr.error(data.message || 'Failed to add player');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('An error occurred while adding the player');
                        });
                });
            }

            // Edit Team Details Form
            // const editDetailsForm = document.querySelector('#editDetailsModal form');
            // if (editDetailsForm) {
            //     editDetailsForm.addEventListener('submit', function(e) {
            //         e.preventDefault();
            //         const formData = new FormData(this);

            //         fetch('{{ route('teams.update', $team->id) }}', {
            //                 method: 'POST',
            //                 headers: {
            //                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //                         .getAttribute('content')
            //                 },
            //                 body: formData
            //             })
            //             .then(response => response.json())
            //             .then(data => {
            //                 if (data.success) {
            //                     toastr.success('Team details updated successfully');
            //                     $('#editDetailsModal').modal('hide');
            //                     // Reload page to show updated data
            //                     setTimeout(() => location.reload(), 1000);
            //                 } else {
            //                     toastr.error(data.message || 'Failed to update team details');
            //                 }
            //             })
            //             .catch(error => {
            //                 toastr.error('An error occurred while updating team details');
            //             });
            //     });
            // }

            // Edit Team Profile Form
            const editProfileForm = document.querySelector('#editTeamModal form');
            if (editProfileForm) {
                editProfileForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);

                    fetch('{{ route('teams.update', $team->id) }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                toastr.success('Team profile updated successfully');
                                $('#editTeamModal').modal('hide');
                                // Reload page to show updated data
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                toastr.error(data.message || 'Failed to update team profile');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            toastr.error('An error occurred while updating team profile');
                        });
                });
            }

            const hash = window.location.hash;

            if (hash) {
                // Temukan tab trigger yg targetnya sama dengan hash
                const trigger = document.querySelector(`[data-bs-target="${hash}"]`);

                if (trigger) {
                    // Aktifkan tab
                    new bootstrap.Tab(trigger).show();
                }

                // Hapus hash dari URL (tab tetap aktif)
                history.replaceState(
                    null,
                    document.title,
                    window.location.pathname + window.location.search
                );
            }
        });

        function deleteTeam(teamId) {
            if (confirm('Are you sure you want to delete this team? This action cannot be undone.')) {
                fetch(`/teams/${teamId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            toastr.success('Team deleted successfully');
                            setTimeout(() => {
                                window.location.href = '{{ route('teams.index') }}';
                            }, 1500);
                        } else {
                            toastr.error(data.message || 'Failed to delete team');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('An error occurred while deleting the team');
                    });
            }
        }

        function viewPlayer(playerId) {
            // This would redirect to player detail page
            toastr.info('Player detail functionality will be implemented here');
        }

        function editPlayer(playerId) {
            // This would open edit player modal
            toastr.info('Edit player functionality will be implemented here');
        }

        // Handle logo change button
        function changeLogo() {
            document.querySelector('#editTeamModal input[name="logo"]').click();
        }
    </script>
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $player->name ?? 'Player Details' }} - Football Tournament Management</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <!-- Custom CSS -->
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
            color: var(--dark-color);
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

        .page-header {
            background: var(--blue-gradient);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .player-profile-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            border: none;
        }

        .player-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .player-avatar-lg {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .player-avatar-lg:hover {
            transform: scale(1.05);
        }

        .position-badge-lg {
            display: inline-block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            text-align: center;
            line-height: 40px;
            font-size: 1rem;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .position-badge-lg:hover {
            transform: scale(1.1);
        }

        .badge-gk {
            background-color: #f59e0b;
            color: white;
        }

        .badge-df {
            background-color: #10b981;
            color: white;
        }

        .badge-mf {
            background-color: #3b82f6;
            color: white;
        }

        .badge-fw {
            background-color: #ef4444;
            color: white;
        }

        .stat-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            border-left: 3px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--dark-color);
            opacity: 0.8;
        }

        .progress {
            height: 10px;
            border-radius: 5px;
        }

        .progress-bar {
            background-color: var(--primary-color);
        }

        .skill-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
            transition: all 0.2s;
        }

        .skill-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            background-color: rgba(241, 245, 249, 0.5);
            border-radius: 8px;
            padding: 10px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: var(--primary-color);
            border: 2px solid white;
            z-index: 1;
        }

        .tab-content {
            padding: 20px;
            background-color: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link.active {
            font-weight: 600;
            color: var(--primary-color);
            border-color: var(--primary-color) var(--primary-color) white;
        }

        .nav-tabs .nav-link {
            color: var(--dark-color);
            transition: all 0.3s;
        }

        .nav-tabs .nav-link:hover {
            border-color: #e9ecef #e9ecef #dee2e6;
            color: var(--primary-color);
        }
    </style>
</head>

<body>
    <!-- Navigation with Gradient -->
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
                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('matches.index') }}">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('players.index') }}">Players</a>
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

    @if (isset($player))
        <!-- Page Header -->
        <header class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="fw-bold mb-3">Player Details</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('players.index') }}">Players</a></li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ $player->name }}
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </button>
                        <a href="{{ route('players.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-2"></i> Back to Players
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container py-4">
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Player Profile Column -->
                <div class="col-lg-4 mb-4">
                    <div class="player-profile-card card">
                        <div class="card-body text-center">
                            <div class="position-relative mb-4">
                                <img src="{{ $player->avatar ? Storage::url($player->avatar) : 'https://placehold.co/150' }}"
                                    alt="Player" class="player-avatar-lg mb-3">
                                <span class="position-badge-lg badge-{{ strtolower(substr($player->position, 0, 2)) }}"
                                    title="{{ $player->position }}">{{ substr($player->position, 0, 2) }}</span>
                            </div>
                            <h3 class="mb-1">{{ $player->name }}</h3>
                            <div class="d-flex justify-content-center align-items-center mb-3">
                                <img src="https://i.pinimg.com/474x/52/a1/28/52a12853824cd120f1465526dbe21404.jpg"
                                    alt="Flag" style="width:24px; margin-right:10px;">
                                <h5 class="mb-0 text-muted">{{ $player->nationality }}</h5>
                            </div>
                            <div class="d-flex justify-content-center mb-4">
                                <div class="text-center me-4">
                                    <h4 class="mb-0">#{{ $player->jersey_number ?? 'N/A' }}</h4>
                                    <small class="text-muted">Number</small>
                                </div>
                                <div class="text-center me-4">
                                    <h4 class="mb-0">{{ $player->age ?? 'N/A' }}</h4>
                                    <small class="text-muted">Age</small>
                                </div>
                                <div class="text-center">
                                    <h4 class="mb-0">
                                        {{ $player->height ? number_format($player->height / 100, 2) . 'm' : 'N/A' }}
                                    </h4>
                                    <small class="text-muted">Height</small>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <span class="badge bg-success badge-pill">
                                    <i class="fas fa-check-circle me-1"></i> Active
                                </span>
                            </div>
                            <hr>
                            <h5 class="mb-3">{{ $player->team ? $player->team->name : 'Free Agent' }}</h5>
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-primary me-2">
                                    <i class="fas fa-envelope me-1"></i> Contact
                                </button>
                                <button class="btn btn-outline-secondary">
                                    <i class="fas fa-share-alt me-1"></i> Share
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Player Stats -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $player->goals_scored ?? 0 }}</div>
                                        <div class="stat-label">Goals</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $player->assists ?? 0 }}</div>
                                        <div class="stat-label">Assists</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $matches_count ?? 0 }}</div>
                                        <div class="stat-label">Matches</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $minutes_played ?? 0 }}'</div>
                                        <div class="stat-label">Minutes</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $player->yellow_cards ?? 0 }}</div>
                                        <div class="stat-label">Yellow Cards</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card p-3 text-center">
                                        <div class="stat-value">{{ $player->red_cards ?? 0 }}</div>
                                        <div class="stat-label">Red Cards</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Player Details Column -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#overview">Overview</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#stats">Statistics</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#matches">Matches</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#career">Career</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview">
                                <h4 class="mb-4">About {{ $player->name }}</h4>
                                <p class="mb-4">
                                    {{ $player->bio ?? 'No biography available.' }}
                                </p>

                                <h5 class="mb-3">Skills & Attributes</h5>
                                <div class="mb-4">
                                    @if ($player->position !== 'GK')
                                        <div class="mb-3">
                                            <label class="form-label d-flex justify-content-between">
                                                <span>Shooting</span>
                                                <span>{{ $player->shooting_skill }}%</span>
                                            </label>
                                            <div class="progress">
                                                <div class="progress-bar bg-danger"
                                                    style="width: {{ $player->shooting_skill }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between">
                                            <span>Dribbling</span>
                                            <span>{{ $player->dribbling_skill }}%</span>
                                        </label>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ $player->dribbling_skill }}%"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between">
                                            <span>Passing</span>
                                            <span>{{ $player->passing_skill }}%</span>
                                        </label>
                                        <div class="progress">
                                            <div class="progress-bar bg-info"
                                                style="width: {{ $player->passing_skill }}%"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between">
                                            <span>Physical</span>
                                            <span>{{ $player->physical_skill }}%</span>
                                        </label>
                                        <div class="progress">
                                            <div class="progress-bar bg-warning"
                                                style="width: {{ $player->physical_skill }}%"></div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label d-flex justify-content-between">
                                            <span>Speed</span>
                                            <span>{{ $player->speed_skill }}%</span>
                                        </label>
                                        <div class="progress">
                                            <div class="progress-bar bg-success"
                                                style="width: {{ $player->speed_skill }}%"></div>
                                        </div>
                                    </div>

                                    @if ($player->position !== 'FWD')
                                        <div class="mb-3">
                                            <label class="form-label d-flex justify-content-between">
                                                <span>Defending</span>
                                                <span>{{ $player->defending_skill }}%</span>
                                            </label>
                                            <div class="progress">
                                                <div class="progress-bar bg-secondary"
                                                    style="width: {{ $player->defending_skill }}%"></div>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($player->position === 'GK')
                                        <div class="mb-3">
                                            <label class="form-label d-flex justify-content-between">
                                                <span>Goalkeeping</span>
                                                <span>{{ $player->goalkeeping_skill }}%</span>
                                            </label>
                                            <div class="progress">
                                                <div class="progress-bar bg-dark"
                                                    style="width: {{ $player->goalkeeping_skill }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <h5 class="mb-3">Player Traits</h5>
                                <div class="mb-4">
                                    @php
                                        $colors = [
                                            'bg-primary',
                                            'bg-success',
                                            'bg-danger',
                                            'bg-warning',
                                            'bg-info',
                                            'bg-dark',
                                        ];
                                    @endphp

                                    @forelse($player->player_traits as $trait)
                                        @php
                                            $color = $colors[$loop->index % count($colors)]; // loop warna secara berulang
                                        @endphp
                                        <span
                                            class="skill-badge {{ $color }} text-white">{{ $trait }}</span>
                                    @empty
                                        <span class="skill-badge bg-secondary">No special traits yet</span>
                                    @endforelse
                                </div>

                                <h5 class="mb-3">Personal Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <p>{{ $player->name }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Date of Birth</label>
                                        <p>{{ $player->birth_date ? $player->birth_date->format('F j, Y') . ' (' . $player->age . ' years)' : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Nationality</label>
                                        <p>{{ $player->nationality }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Height</label>
                                        <p>{{ $player->height ? number_format($player->height / 100, 2) . ' m' : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Weight</label>
                                        <p>{{ $player->weight ? $player->weight . ' kg' : 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Position</label>
                                        <p>{{ $player->position }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Jersey Number</label>
                                        <p>{{ $player->jersey_number ?? 'N/A' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted">Team</label>
                                        <p>{{ $player->team ? $player->team->name : 'Free Agent' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics Tab -->
                            <div class="tab-pane fade" id="stats">
                                <h4 class="mb-4">Season Statistics</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Competition</th>
                                                <th>Matches</th>
                                                <th>Goals</th>
                                                <th>Assists</th>
                                                <th>Yellow</th>
                                                <th>Red</th>
                                                <th>Minutes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>All Competitions</td>
                                                <td>{{ $matches_count ?? 0 }}</td>
                                                <td>
                                                    @if (isset($recent_matches) && $recent_matches->count() > 0)
                                                        {{ $recent_matches->sum(function ($match) {return $match->player_performance['goals'] ?? 0;}) }}
                                                    @else
                                                        {{ $player->goals_scored ?? 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($recent_matches) && $recent_matches->count() > 0)
                                                        {{ $recent_matches->sum(function ($match) {return $match->player_performance['assists'] ?? 0;}) }}
                                                    @else
                                                        {{ $player->assists ?? 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($recent_matches) && $recent_matches->count() > 0)
                                                        {{ $recent_matches->sum(function ($match) {return $match->player_performance['yellow_cards'] ?? 0;}) }}
                                                    @else
                                                        {{ $player->yellow_cards ?? 0 }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (isset($recent_matches) && $recent_matches->count() > 0)
                                                        {{ $recent_matches->count() > 0? $recent_matches->sum(function ($match) {return $match->player_performance['red_cards'] ?? 0;}): 0 }}
                                                    @else
                                                        {{ $player->red_cards ?? 0 }}
                                                    @endif
                                                </td>
                                                <td>{{ $minutes_played ?? 0 }}'</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="mt-5 mb-3">
                                    <i class="fas fa-chart-line me-2"></i>Performance Charts
                                </h5>

                                <!-- Chart Navigation -->
                                <div class="d-flex flex-wrap mb-3">
                                    <ul class="nav nav-pills" id="chartTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="goals-tab" data-bs-toggle="pill"
                                                data-bs-target="#goals-chart" type="button" role="tab">
                                                <i class="fas fa-bullseye me-1"></i> Goals
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="assists-tab" data-bs-toggle="pill"
                                                data-bs-target="#assists-chart" type="button" role="tab">
                                                <i class="fas fa-hands-helping me-1"></i> Assists
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="skills-tab" data-bs-toggle="pill"
                                                data-bs-target="#skills-chart" type="button" role="tab">
                                                <i class="fas fa-star me-1"></i> Skills
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="monthly-tab" data-bs-toggle="pill"
                                                data-bs-target="#monthly-chart" type="button" role="tab">
                                                <i class="fas fa-calendar-alt me-1"></i> Monthly
                                            </button>
                                        </li>
                                        @if ($player->team)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="comparison-tab" data-bs-toggle="pill"
                                                    data-bs-target="#comparison-chart" type="button" role="tab">
                                                    <i class="fas fa-balance-scale me-1"></i> Team Comparison
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                                <!-- Chart Content -->
                                <div class="tab-content" id="chartTabContent">
                                    <!-- Goals Chart -->
                                    <div class="tab-pane fade" id="goals-chart" role="tabpanel">
                                        <div class="chart-container" style="position: relative; height:300px;">
                                            <canvas id="goalsChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- Assists Chart -->
                                    <div class="tab-pane fade" id="assists-chart" role="tabpanel">
                                        <div class="chart-container" style="position: relative; height:300px;">
                                            <canvas id="assistsChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- Skills Chart -->
                                    <div class="tab-pane fade show active" id="skills-chart" role="tabpanel">
                                        <div class="chart-container" style="position: relative; height:300px;">
                                            <canvas id="skillsChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- Monthly Performance Chart -->
                                    <div class="tab-pane fade" id="monthly-chart" role="tabpanel">
                                        <div class="chart-container" style="position: relative; height:300px;">
                                            <canvas id="monthlyChart"></canvas>
                                        </div>
                                    </div>

                                    <!-- Team Comparison Chart -->
                                    @if ($player->team)
                                        <div class="tab-pane fade" id="comparison-chart" role="tabpanel">
                                            <div class="chart-container" style="position: relative; height:300px;">
                                                <canvas id="comparisonChart"></canvas>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <h5 class="mt-5 mb-3">Career Statistics</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Season</th>
                                                <th>Team</th>
                                                <th>Matches</th>
                                                <th>Goals</th>
                                                <th>Assists</th>
                                                <th>Clean Sheets</th>
                                                <th>Yellow</th>
                                                <th>Red</th>
                                                <th>Minutes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($player->career_stats as $stat)
                                                <tr>
                                                    <td><strong>{{ $stat['season'] }}</strong></td>
                                                    <td>{{ $stat['team'] }}</td>
                                                    <td>{{ $stat['matches'] }}</td>
                                                    <td>{{ $stat['goals'] }}</td>
                                                    <td>{{ $stat['assists'] }}</td>
                                                    <td>{{ $stat['clean_sheets'] }}</td>
                                                    <td>{{ $stat['yellow_cards'] }}</td>
                                                    <td>{{ $stat['red_cards'] }}</td>
                                                    <td>{{ number_format($stat['minutes']) }}'</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">No career
                                                        statistics available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Matches Tab -->
                            <div class="tab-pane fade" id="matches">
                                <h4 class="mb-4">Recent Matches</h4>
                                @if (isset($recent_matches) && $recent_matches->count() > 0)
                                    <div class="list-group">
                                        @foreach ($recent_matches as $match)
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h5 class="mb-1">{{ $match->homeTeam->name ?? 'TBD' }} vs
                                                        {{ $match->awayTeam->name ?? 'TBD' }}</h5>
                                                    <small
                                                        class="text-muted">{{ $match->scheduled_at ? \Carbon\Carbon::parse($match->scheduled_at)->format('M d, Y') : 'TBD' }}</small>
                                                </div>
                                                <div class="d-flex align-items-center mt-2">
                                                    <span class="badge bg-success me-3">Played</span>
                                                    @if (isset($match->player_performance))
                                                        <span class="me-3">
                                                            <strong>{{ $match->player_performance['goals'] }}</strong>
                                                            goal(s)
                                                        </span>
                                                        <span class="me-3">
                                                            <strong>{{ $match->player_performance['assists'] }}</strong>
                                                            assist(s)
                                                        </span>
                                                        @if ($match->player_performance['clean_sheets'] > 0)
                                                            <span class="me-2">
                                                                <strong>{{ $match->player_performance['clean_sheets'] }}</strong>
                                                                clean sheet(s)
                                                            </span>
                                                        @endif
                                                        @if ($match->player_performance['yellow_cards'] > 0)
                                                            <span class="me-2">
                                                                <i class="fas fa-square text-warning"></i>
                                                                {{ $match->player_performance['yellow_cards'] }}
                                                            </span>
                                                        @endif
                                                        @if ($match->player_performance['red_cards'] > 0)
                                                            <span class="me-2">
                                                                <i class="fas fa-square text-danger"></i>
                                                                {{ $match->player_performance['red_cards'] }}
                                                            </span>
                                                        @endif
                                                    @else
                                                        <span class="text-muted">No performance data available</span>
                                                    @endif
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No recent matches found</p>
                                @endif
                            </div>

                            <!-- Career Tab -->
                            <div class="tab-pane fade" id="career">
                                <h4 class="mb-4">Career History</h4>
                                <div class="timeline">
                                    @if ($player->team)
                                        <div class="timeline-item mb-4">
                                            <h5>{{ $player->team->name }}</h5>
                                            <p class="text-muted mb-1">Current Team</p>
                                            <p>
                                                Currently playing for {{ $player->team->name }} as a
                                                {{ strtolower($player->position) }}.
                                            </p>
                                        </div>
                                    @endif

                                    <div class="timeline-item">
                                        <h5>Career Start</h5>
                                        <p class="text-muted mb-1">Professional Debut</p>
                                        <p>
                                            Started professional career as a {{ strtolower($player->position) }}.
                                        </p>
                                    </div>
                                </div>

                                <h4 class="mt-5 mb-4">Achievements</h4>
                                @if($player->all_achievements && count($player->all_achievements) > 0)
                                    <div class="row">
                                        @foreach($player->all_achievements as $achievement)
                                            <div class="col-md-6 mb-3">
                                                <div class="card achievement-card">
                                                    <div class="card-body">
                                                        <div class="d-flex align-items-center">
                                                            <div class="{{ $achievement['color'] }} bg-opacity-10 p-3 rounded me-3">
                                                                <i class="{{ $achievement['icon'] }} {{ $achievement['color'] }}"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h5 class="mb-1">{{ $achievement['title'] }}</h5>
                                                                <p class="text-muted mb-1 small">{{ $achievement['description'] }}</p>
                                                                                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge {{ $achievement['color'] }}">{{ $achievement['year'] }}</span>
                                                    <small class="text-muted">{{ $achievement['value'] }}</small>
                                                </div>
                                                @if(isset($achievement['tournament']))
                                                    <div class="mt-2">
                                                        <small class="text-muted">
                                                            <i class="fas fa-trophy me-1"></i>{{ $achievement['tournament'] }}
                                                        </small>
                                                    </div>
                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                            <i class="fas fa-trophy text-muted fa-2x"></i>
                                        </div>
                                        <h5 class="text-muted">No Achievements Yet</h5>
                                        <p class="text-muted">Keep playing and improving to unlock achievements!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Player Not Found -->
        <div class="container py-5">
            <div class="text-center">
                <h1 class="display-4 text-muted">Player Not Found</h1>
                <p class="lead">The player you're looking for doesn't exist or has been removed.</p>
                <a href="{{ route('players.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Players
                </a>
            </div>
        </div>
    @endif

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('players.update', $player->id ?? 0) }}" method="POST"
                    enctype="multipart/form-data" id="editProfileForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="fas fa-user-edit me-2"></i>Edit Player Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="name"
                                    value="{{ $player->name ?? '' }}" required>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="birth_date"
                                    value="{{ $player->birth_date ? $player->birth_date->format('Y-m-d') : '' }}">
                            </div>

                            <!-- Nationality -->
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality"
                                    value="{{ $player->nationality ?? '' }}" required>
                            </div>
                            <!-- Position -->
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="GK" {{ $player->position === 'GK' ? 'selected' : '' }}>
                                        Goalkeeper</option>
                                    <option value="DEF" {{ $player->position === 'DEF' ? 'selected' : '' }}>
                                        Defender</option>
                                    <option value="MID" {{ $player->position === 'MID' ? 'selected' : '' }}>
                                        Midfielder</option>
                                    <option value="FWD" {{ $player->position === 'FWD' ? 'selected' : '' }}>
                                        Forward</option>
                                </select>
                            </div>

                            <!-- Height -->
                            <div class="col-md-6 mb-3">
                                <label for="height" class="form-label">Height (in cm)</label>
                                <input type="number" class="form-control" id="height" name="height"
                                    value="{{ $player->height ?? '' }}" min="100" max="250"
                                    step="0.01">
                            </div>
                            <!-- Weight -->
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (in kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight"
                                    value="{{ $player->weight ?? '' }}" min="30" max="150"
                                    step="0.01">
                            </div>

                            <!-- Jersey Number -->
                            <div class="col-md-6 mb-3">
                                <label for="jerseyNumber" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control" id="jerseyNumber" name="jersey_number"
                                    value="{{ $player->jersey_number ?? '' }}" min="1" max="99">
                            </div>
                            <!-- Team -->
                            <div class="col-md-6 mb-3">
                                <label for="team" class="form-label">Team</label>
                                <select class="form-select" id="team" name="team_id">
                                    <option value="">Free Agent</option>
                                    @foreach ($teams ?? [] as $team)
                                        <option value="{{ $team->id }}"
                                            {{ $player->team_id == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Avatar -->
                            <div class="col-12 mb-3">
                                <label for="avatar" class="form-label">Player Photo</label>
                                <input type="file" class="form-control" id="avatar" name="avatar"
                                    accept="image/*">
                                @if ($player->avatar)
                                    <p class="text-muted small mt-1">Current Photo: <a
                                            href="{{ Storage::url($player->avatar) }}"
                                            target="_blank">{{ basename($player->avatar) }}</a></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS for Charts -->
    <style>
        /* Chart container styling */
        .chart-container {
            position: relative;
            height: 300px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Tab navigation styling */
        #chartTabs .nav-link {
            border-radius: 20px;
            margin-right: 8px;
            margin-bottom: 8px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
            user-select: none;
        }

        #chartTabs .nav-link:hover:not(:disabled) {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #chartTabs .nav-link.active {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.3);
            transform: translateY(-1px);
        }

        #chartTabs .nav-link:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Loading spinner animation */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Tab content transitions */
        .tab-pane {
            transition: opacity 0.3s ease-in-out;
        }

        .tab-pane.fade {
            opacity: 0;
        }

        .tab-pane.fade.show {
            opacity: 1;
        }

        /* Chart loading states */
        .chart-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 300px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #6c757d;
        }

        .chart-loading .spinner-border {
            margin-right: 10px;
        }

        /* Achievement cards styling */
        .achievement-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .achievement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .achievement-card .card-body {
            padding: 1.25rem;
        }

        .achievement-card i {
            font-size: 1.5rem;
        }

        .achievement-card .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .achievement-card h5 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .achievement-card p {
            font-size: 0.875rem;
            line-height: 1.4;
        }

        .achievement-card small {
            font-size: 0.8rem;
        }

        /* Responsive chart adjustments */
        @media (max-width: 768px) {
            .chart-container {
                height: 250px;
                padding: 15px;
            }

            #chartTabs .nav-link {
                padding: 6px 12px;
                font-size: 14px;
                margin-right: 6px;
                margin-bottom: 6px;
            }
        }
    </style>

    <!-- Custom JS -->
    <script>
        // Initialize toastr
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right'
        };

        // Show success/error messages
        @if (session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if (session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        // Handle form submission
        document.getElementById('editProfileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success('Player profile updated successfully!');
                        $('#editProfileModal').modal('hide');
                        // Reload page to show updated data
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        toastr.error(data.message || 'Failed to update player profile');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred while updating player profile');
                });
        });

        // ==================== PERFORMANCE CHARTS ====================

        // Chart.js configuration
        Chart.defaults.font.family = 'Inter, -apple-system, BlinkMacSystemFont, sans-serif';
        Chart.defaults.color = '#64748b';
        Chart.defaults.plugins.legend.position = 'top';
        Chart.defaults.plugins.legend.labels.usePointStyle = true;

        // Goals Chart
        const goalsCtx = document.getElementById('goalsChart');
        if (goalsCtx) {
            new Chart(goalsCtx, {
                type: 'line',
                data: {
                    labels: @json($player->goals_chart_data['labels']),
                    datasets: [{
                        label: 'Goals per Season',
                        data: @json($player->goals_chart_data['data']),
                        backgroundColor: @json($player->goals_chart_data['backgroundColor']),
                        borderColor: @json($player->goals_chart_data['borderColor']),
                        borderWidth: 3,
                        tension: @json($player->goals_chart_data['tension']),
                        fill: true,
                        pointBackgroundColor: '#dc3545',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Goals Performance Over Seasons',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Assists Chart
        const assistsCtx = document.getElementById('assistsChart');
        if (assistsCtx) {
            new Chart(assistsCtx, {
                type: 'line',
                data: {
                    labels: @json($player->assists_chart_data['labels']),
                    datasets: [{
                        label: 'Assists per Season',
                        data: @json($player->assists_chart_data['data']),
                        backgroundColor: @json($player->assists_chart_data['backgroundColor']),
                        borderColor: @json($player->assists_chart_data['borderColor']),
                        borderWidth: 3,
                        tension: @json($player->assists_chart_data['tension']),
                        fill: true,
                        pointBackgroundColor: '#0dcaf0',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Assists Performance Over Seasons',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Skills Chart (Radar Chart)
        const skillsCtx = document.getElementById('skillsChart');
        if (skillsCtx) {
            new Chart(skillsCtx, {
                type: 'radar',
                data: {
                    labels: @json($player->skills_chart_data['labels']),
                    datasets: @json($player->skills_chart_data['datasets'])
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Player Skills Overview',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                stepSize: 20
                            }
                        }
                    }
                }
            });
        }

        // Monthly Performance Chart
        const monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            new Chart(monthlyCtx, {
                type: 'line',
                data: @json($player->monthly_performance_data),
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Monthly Performance (Last 12 Months)',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    }
                }
            });
        }

        // Team Comparison Chart
        @if ($player->team)
            const comparisonCtx = document.getElementById('comparisonChart');
            if (comparisonCtx) {
                new Chart(comparisonCtx, {
                    type: 'bar',
                    data: @json($player->team_comparison_data),
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Player vs Team Average',
                                font: {
                                    size: 16,
                                    weight: 'bold'
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            }
                        }
                    }
                });
            }
        @endif

        // Chart tab switching with smooth transitions
        document.querySelectorAll('#chartTabs .nav-link').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // Get target content
                const target = this.getAttribute('data-bs-target');
                const targetContent = document.querySelector(target);

                // Don't do anything if already active
                if (this.classList.contains('active')) {
                    return;
                }

                // Show loading state
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
                this.disabled = true;

                // Remove active class from all tabs
                document.querySelectorAll('#chartTabs .nav-link').forEach(t => {
                    t.classList.remove('active');
                    t.disabled = false;
                    // Reset button content
                    const icon = t.querySelector('i');
                    const text = t.textContent.replace('Loading...', '').trim();
                    if (icon && text) {
                        t.innerHTML = icon.outerHTML + ' ' + text;
                    }
                });

                // Add active class to clicked tab
                this.classList.add('active');

                // Smooth transition between charts
                const activeContent = document.querySelector('.tab-pane.show.active');
                if (activeContent && activeContent !== targetContent) {
                    // Fade out current content
                    activeContent.style.transition = 'opacity 0.3s ease-out';
                    activeContent.style.opacity = '0';

                    setTimeout(() => {
                        // Hide current content
                        activeContent.classList.remove('show', 'active');
                        activeContent.style.opacity = '';

                        // Show new content
                        targetContent.classList.add('show', 'active');
                        targetContent.style.opacity = '0';

                        // Fade in new content
                        setTimeout(() => {
                            targetContent.style.transition = 'opacity 0.3s ease-in';
                            targetContent.style.opacity = '1';

                            // Reset button content after transition
                            setTimeout(() => {
                                const icon = this.querySelector('i');
                                const text = this.textContent.replace('Loading...',
                                    '').trim();
                                if (icon && text) {
                                    this.innerHTML = icon.outerHTML + ' ' + text;
                                }
                                this.disabled = false;
                            }, 300);
                        }, 50);
                    }, 300);
                } else {
                    // First time loading or same tab
                    targetContent.classList.add('show', 'active');
                    // Reset button content
                    setTimeout(() => {
                        const icon = this.querySelector('i');
                        const text = this.textContent.replace('Loading...', '').trim();
                        if (icon && text) {
                            this.innerHTML = icon.outerHTML + ' ' + text;
                        }
                        this.disabled = false;
                    }, 100);
                }
            });
        });

        // Initialize first chart tab as active
        document.addEventListener('DOMContentLoaded', function() {
            const firstTab = document.querySelector('#chartTabs .nav-link.active');
            const firstContent = document.querySelector(firstTab.getAttribute('data-bs-target'));

            if (firstTab && firstContent) {
                firstContent.classList.add('show', 'active');
            }
        });
    </script>
</body>

</html>

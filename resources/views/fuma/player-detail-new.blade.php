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

        .navbar {
            background: var(--blue-gradient);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            border: none;
        }

        .player-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .player-avatar-lg {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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

        .badge-gk { background-color: #f59e0b; color: white; }
        .badge-df { background-color: #10b981; color: white; }
        .badge-mf { background-color: #3b82f6; color: white; }
        .badge-fw { background-color: #ef4444; color: white; }

        .stat-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            border-left: 3px solid var(--primary-color);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
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
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .tab-content {
            padding: 20px;
            background-color: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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
                        <a class="nav-link" href="matches.html">Matches</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('players.index') }}">Players</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    @if(isset($player))
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
                            <li class="breadcrumb-item active" aria-current="page">{{ $player->name }}</li>
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
                                <h4 class="mb-0">{{ $player->height ? number_format($player->height / 100, 2) . 'm' : 'N/A' }}</h4>
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
                        <h5 class="mb-0">Season Statistics</h5>
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
                                {{ $player->name }} is a professional footballer who plays as a {{ strtolower($player->position) }} for {{ $player->team ? $player->team->name : 'various teams' }}.
                                With {{ $player->goals_scored ?? 0 }} goals this season, he's been a key player for his team.
                            </p>

                            <h5 class="mb-3">Skills & Attributes</h5>
                            <div class="mb-4">
                                <div class="mb-3">
                                    <label class="form-label">Shooting <span class="float-end">{{ $player->rating ? round($player->rating * 20) : 70 }}%</span></label>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" style="width: {{ $player->rating ? round($player->rating * 20) : 70 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Dribbling <span class="float-end">{{ $player->rating ? round($player->rating * 18) : 65 }}%</span></label>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" style="width: {{ $player->rating ? round($player->rating * 18) : 65 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Passing <span class="float-end">{{ $player->rating ? round($player->rating * 16) : 60 }}%</span></label>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" style="width: {{ $player->rating ? round($player->rating * 16) : 60 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Physical <span class="float-end">{{ $player->rating ? round($player->rating * 19) : 68 }}%</span></label>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: {{ $player->rating ? round($player->rating * 19) : 68 }}%"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Speed <span class="float-end">{{ $player->rating ? round($player->rating * 22) : 75 }}%</span></label>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" style="width: {{ $player->rating ? round($player->rating * 22) : 75 }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">Player Traits</h5>
                            <div class="mb-4">
                                <span class="skill-badge bg-primary">Clinical Finisher</span>
                                <span class="skill-badge bg-success">Speed Dribbler</span>
                                <span class="skill-badge bg-info">First Touch</span>
                                <span class="skill-badge bg-warning">Aerial Threat</span>
                                <span class="skill-badge bg-danger">Long Shots</span>
                            </div>

                            <h5 class="mb-3">Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Full Name</label>
                                    <p>{{ $player->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Date of Birth</label>
                                    <p>{{ $player->birth_date ? $player->birth_date->format('F j, Y') . ' (' . $player->age . ' years)' : 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Nationality</label>
                                    <p>{{ $player->nationality }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Height</label>
                                    <p>{{ $player->height ? number_format($player->height / 100, 2) . ' m' : 'N/A' }}</p>
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
                                            <td>{{ $player->goals_scored ?? 0 }}</td>
                                            <td>{{ $player->assists ?? 0 }}</td>
                                            <td>{{ $player->yellow_cards ?? 0 }}</td>
                                            <td>{{ $player->red_cards ?? 0 }}</td>
                                            <td>{{ $minutes_played ?? 0 }}'</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <h5 class="mt-5 mb-3">Performance Chart</h5>
                            <div class="text-center py-4 bg-light rounded">
                                <p class="text-muted">
                                    <i class="fas fa-chart-line me-2"></i> Performance chart would be displayed here
                                </p>
                            </div>
                        </div>

                        <!-- Matches Tab -->
                        <div class="tab-pane fade" id="matches">
                            <h4 class="mb-4">Recent Matches</h4>
                            @if(isset($recent_matches) && $recent_matches->count() > 0)
                                <div class="list-group">
                                    @foreach($recent_matches as $match)
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h5 class="mb-1">{{ $match->homeTeam->name ?? 'TBD' }} vs {{ $match->awayTeam->name ?? 'TBD' }}</h5>
                                                <small class="text-muted">{{ $match->scheduled_at ? \Carbon\Carbon::parse($match->scheduled_at)->format('M d, Y') : 'TBD' }}</small>
                                            </div>
                                            <div class="d-flex align-items-center mt-2">
                                                <span class="badge bg-success me-3">Played</span>
                                                <span class="me-3"><strong>{{ $match->goals_scored ?? 0 }}</strong> goal(s)</span>
                                                <span><strong>{{ $match->assists ?? 0 }}</strong> assist(s)</span>
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
                                @if($player->team)
                                    <div class="timeline-item mb-4">
                                        <h5>{{ $player->team->name }}</h5>
                                        <p class="text-muted mb-1">Current Team</p>
                                        <p>
                                            Currently playing for {{ $player->team->name }} as a {{ strtolower($player->position) }}.
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                                    <i class="fas fa-trophy text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">Top Scorer</h5>
                                                    <p class="text-muted mb-0">{{ $player->team ? $player->team->name : 'Team' }} {{ date('Y') }}</p>
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
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('players.update', $player->id ?? 0) }}" method="POST" enctype="multipart/form-data" id="editProfileForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="fas fa-user-edit me-2"></i>Edit Player Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="name" value="{{ $player->name ?? '' }}" required>
                            </div>
                            <!-- Date of Birth -->
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="birth_date" value="{{ $player->birth_date ? $player->birth_date->format('Y-m-d') : '' }}">
                            </div>

                            <!-- Nationality -->
                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="{{ $player->nationality ?? '' }}" required>
                            </div>
                            <!-- Position -->
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select" id="position" name="position" required>
                                    <option value="Goalkeeper" {{ $player->position === 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                    <option value="Defender" {{ $player->position === 'Defender' ? 'selected' : '' }}>Defender</option>
                                    <option value="Midfielder" {{ $player->position === 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                    <option value="Forward" {{ $player->position === 'Forward' ? 'selected' : '' }}>Forward</option>
                                </select>
                            </div>

                            <!-- Height -->
                            <div class="col-md-6 mb-3">
                                <label for="height" class="form-label">Height (in cm)</label>
                                <input type="number" class="form-control" id="height" name="height" value="{{ $player->height ?? '' }}" min="100" max="250" step="0.01">
                            </div>
                            <!-- Weight -->
                            <div class="col-md-6 mb-3">
                                <label for="weight" class="form-label">Weight (in kg)</label>
                                <input type="number" class="form-control" id="weight" name="weight" value="{{ $player->weight ?? '' }}" min="30" max="150" step="0.01">
                            </div>

                            <!-- Jersey Number -->
                            <div class="col-md-6 mb-3">
                                <label for="jerseyNumber" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control" id="jerseyNumber" name="jersey_number" value="{{ $player->jersey_number ?? '' }}" min="1" max="99">
                            </div>
                            <!-- Team -->
                            <div class="col-md-6 mb-3">
                                <label for="team" class="form-label">Team</label>
                                <select class="form-select" id="team" name="team_id">
                                    <option value="">Free Agent</option>
                                    @foreach($teams ?? [] as $team)
                                        <option value="{{ $team->id }}" {{ $player->team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Avatar -->
                            <div class="col-12 mb-3">
                                <label for="avatar" class="form-label">Player Photo</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                @if($player->avatar)
                                    <p class="text-muted small mt-1">Current Photo: <a href="{{ Storage::url($player->avatar) }}" target="_blank">{{ basename($player->avatar) }}</a></p>
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
    </script>
</body>
</html>

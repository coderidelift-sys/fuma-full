<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Matches - Football Tournament Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            /* New primary (lighter blue from gradient) */
            --secondary-color: #1e40af;
            /* New secondary (darker blue from gradient) */
            --accent-color: #e74c3c;
            /* Keeping the original accent color (red) */
            --light-color: #f8fafc;
            /* Lighter background color */
            --dark-color: #1e293b;
            /* Darker text color that complements the blues */

            /* Optional: You can add gradient variables if you'll reuse them */
            --blue-gradient: linear-gradient(135deg, #1e40af, #2563eb);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: var(--dark-color);
        }

        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

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

        .team-logo-sm {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }

        .filter-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .action-btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .pagination .page-link {
            color: var(--primary-color);
        }

        .badge-pill {
            border-radius: 10px;
            padding: 5px 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .match-score {
            font-weight: bold;
            font-size: 1.1rem;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 5px;
        }

        .live-badge {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
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
                    <li class="nav-item">
                        <a class="nav-link" href="#login" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Matches</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createMatchModal">
                        <i class="fas fa-plus me-2"></i> Add New Match
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">Match Status</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="live">Live</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="tournamentFilter" class="form-label">Tournament</label>
                        <select id="tournamentFilter" class="form-select">
                            <option value="">All Tournaments</option>
                            @foreach ($tournaments ?? [] as $tournament)
                                <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">Date Range</label>
                        <input type="date" class="form-control" id="dateFilter">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="applyFilters">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matches Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Match</th>
                                <th>Tournament</th>
                                <th>Date & Time</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="matchesTableBody">









                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Matches pagination" class="mt-4" id="matchesPagination">
                    <!-- Dynamic pagination will be loaded here -->
                </nav>
            </div>
        </div>
    </div>

    <!-- Create Match Modal -->
    <div class="modal fade" id="createMatchModal" tabindex="-1" aria-labelledby="createMatchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createMatchModalLabel">Add New Match</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createMatchForm" action="{{ route('matches.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="homeTeam" class="form-label">Home Team</label>
                                <select class="form-select" id="homeTeam" name="home_team_id" required>
                                    <option value="">Select team</option>
                                    @foreach ($teams ?? [] as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="awayTeam" class="form-label">Away Team</label>
                                <select class="form-select" id="awayTeam" name="away_team_id" required>
                                    <option value="">Select team</option>
                                    @foreach ($teams ?? [] as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="matchDate" class="form-label">Date</label>
                                <input type="date" class="form-control" id="matchDate" name="scheduled_at"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="matchTime" class="form-label">Time</label>
                                <input type="time" class="form-control" id="matchTime" name="match_time"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tournament" class="form-label">Tournament</label>
                                <select class="form-select" id="tournament" name="tournament_id" required>
                                    <option value="">Select tournament</option>
                                    @foreach ($tournaments ?? [] as $tournament)
                                        <option value="{{ $tournament->id }}">{{ $tournament->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="stage" class="form-label">Stage</label>
                                <select class="form-select" id="stage" name="stage" required>
                                    <option value="">Select stage</option>
                                    <option value="group">Group Stage</option>
                                    <option value="round_of_16">Round of 16</option>
                                    <option value="quarter_final">Quarter Final</option>
                                    <option value="semi_final">Semi Final</option>
                                    <option value="final">Final</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="venue" class="form-label">Venue</label>
                                <select class="form-select" id="venue" name="venue_id" required>
                                    <option value="">Select venue</option>
                                    @foreach ($venues ?? [] as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}
                                            ({{ $venue->city }}, {{ $venue->capacity_formatted }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="referee" class="form-label">Referee</label>
                                <select class="form-select" id="referee" required>
                                    <option value="">Select referee</option>
                                                                    @foreach($referees ?? [] as $referee)
                                    <option value="{{ $referee->id }}">{{ $referee->name }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Match</button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for filtering -->
    <script>
        // Initialize Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
        };

        // Initialize when DOM is ready
        $(document).ready(function() {
            // Load matches on page load
            loadMatches();

            // Initialize form submission
            initializeFormSubmission();

            // Initialize filters
            initializeFilters();
        });

        // Load matches data
        function loadMatches(page = 1) {
            const statusFilter = $('#statusFilter').val();
            const tournamentFilter = $('#tournamentFilter').val();
            const dateFilter = $('#dateFilter').val();

            $.ajax({
                url: '{{ route('matches.data') }}',
                method: 'GET',
                data: {
                    page: page,
                    status: statusFilter,
                    tournament: tournamentFilter,
                    date: dateFilter
                },
                success: function(response) {
                    renderMatchesTable(response.data);
                    renderPagination(response);
                },
                error: function(xhr) {
                    toastr.error('Failed to load matches');
                    console.error('Error loading matches:', xhr);
                }
            });
        }

        // Render matches table
        function renderMatchesTable(matches) {
            const tbody = $('#matchesTableBody');
            tbody.empty();

            if (matches.length === 0) {
                tbody.append('<tr><td colspan="6" class="text-center">No matches found</td></tr>');
                return;
            }

            matches.forEach(function(match) {
                const row = `
                    <tr>
                        <td>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-end" style="width: 40%;">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <span class="me-2">${match.home_team.name}</span>
                                        <img src="${match.home_team.logo || 'https://img.freepik.com/premium-vector/soccer-ball-icon-logo-template-football-logo-symbol_7649-4092.jpg?w=2000'}" alt="Team Logo" class="team-logo-sm">
                                    </div>
                                </div>
                                <div class="px-2 text-center">
                                    <div class="text-muted small">${formatMatchTime(match.scheduled_at)}</div>
                                    <div class="match-score">${getMatchScore(match)}</div>
                                </div>
                                <div class="text-start" style="width: 40%;">
                                    <div class="d-flex align-items-center">
                                        <img src="${match.away_team.logo || 'https://img.freepik.com/premium-vector/soccer-ball-icon-logo-template-football-logo-symbol_7649-4092.jpg?w=2000'}" alt="Team Logo" class="team-logo-sm me-2">
                                        <span>${match.away_team.name}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>${match.tournament.name}</td>
                        <td>${formatMatchDateTime(match.scheduled_at)}</td>
                        <td>${match.venue || 'TBD'}</td>
                        <td>${getStatusBadge(match.status)}</td>
                        <td>
                            <a href="${'{{ route('matches.show', ':id') }}'.replace(':id', match.id)}" class="btn btn-sm btn-outline-primary action-btn">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                `;
                tbody.append(row);
            });
        }

        function renderPagination(response) {
            const pagination = $('#matchesPagination');
            pagination.empty();

            if (response.last_page <= 1) return;

            let paginationHtml = '<ul class="pagination justify-content-center">';

            // Previous button
            if (response.current_page > 1) {
                paginationHtml +=
                    `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadMatches(${response.current_page - 1})">Previous</a></li>`;
            } else {
                paginationHtml += '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
            }

            const totalPages = response.last_page;
            const current = response.current_page;
            const delta = 2; // jumlah halaman sebelum & sesudah current yang tampil

            let range = [];
            let rangeWithDots = [];
            let l;

            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= current - delta && i <= current + delta)) {
                    range.push(i);
                }
            }

            for (let i of range) {
                if (l) {
                    if (i - l === 2) {
                        rangeWithDots.push(l + 1);
                    } else if (i - l > 2) {
                        rangeWithDots.push('...');
                    }
                }
                rangeWithDots.push(i);
                l = i;
            }

            // Render page numbers
            for (let i of rangeWithDots) {
                if (i === '...') {
                    paginationHtml += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
                } else if (i === current) {
                    paginationHtml += `<li class="page-item active"><a class="page-link" href="#">${i}</a></li>`;
                } else {
                    paginationHtml +=
                        `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadMatches(${i})">${i}</a></li>`;
                }
            }

            // Next button
            if (current < totalPages) {
                paginationHtml +=
                    `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadMatches(${current + 1})">Next</a></li>`;
            } else {
                paginationHtml += '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
            }

            paginationHtml += '</ul>';
            pagination.html(paginationHtml);
        }

        // Helper functions
        function formatMatchDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            }) + '<br>' + date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatMatchTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getMatchScore(match) {
            if (match.status === 'completed' && match.home_score !== null && match.away_score !== null) {
                return `${match.home_score} - ${match.away_score}`;
            }
            return 'VS';
        }

        function getStatusBadge(status) {
            const badges = {
                'scheduled': '<span class="badge bg-primary badge-pill">Scheduled</span>',
                'live': '<span class="badge bg-danger badge-pill live-badge">Live</span>',
                'completed': '<span class="badge bg-secondary badge-pill">Completed</span>',
                'cancelled': '<span class="badge bg-warning badge-pill">Cancelled</span>'
            };
            return badges[status] || badges['scheduled'];
        }

        // Initialize form submission
        function initializeFormSubmission() {
            $('#createMatchForm').on('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const time = $('#matchTime').val();
                const date = $('#matchDate').val();

                if (time && date) {
                    formData.set('scheduled_at', date + ' ' + time);
                }

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            $('#createMatchModal').modal('hide');
                            $('#createMatchForm')[0].reset();
                            loadMatches(); // Reload matches
                        } else {
                            toastr.error(response.message || 'Failed to create match');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(function(key) {
                                toastr.error(errors[key][0]);
                            });
                        } else {
                            toastr.error('Failed to create match');
                        }
                    }
                });
            });
        }

        // Initialize filters
        function initializeFilters() {
            $('#applyFilters').on('click', function() {
                loadMatches(1); // Reset to first page when filtering
            });
        }

        // Edit match function
        function editMatch(matchId) {
            // TODO: Implement edit functionality
            toastr.info('Edit functionality will be implemented in the next phase');
        }
    </script>
</body>

</html>

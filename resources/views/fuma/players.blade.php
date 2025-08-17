<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>All Players - Football Tournament Management</title>
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

        .player-avatar {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
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

        .position-badge {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-size: 0.7rem;
            font-weight: bold;
        }

        .badge-gk {
            background-color: #f39c12;
            color: white;
        }

        .badge-def {
            background-color: #27ae60;
            color: white;
        }

        .badge-mid {
            background-color: #3498db;
            color: white;
        }

        .badge-fwd {
            background-color: #e74c3c;
            color: white;
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
                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="matches.html">Matches</a>
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

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Players</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createPlayerModal">
                        <i class="fas fa-plus me-2"></i> Add New Player
                    </button>
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

        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="searchPlayer" class="form-label">Search Player</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchPlayer" placeholder="Player name...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="positionFilter" class="form-label">Position</label>
                        <select id="positionFilter" class="form-select">
                            <option value="">All Positions</option>
                            <option value="GK">Goalkeeper</option>
                            <option value="DEF">Defender</option>
                            <option value="MID">Midfielder</option>
                            <option value="FWD">Forward</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="teamFilter" class="form-label">Team</label>
                        <select id="teamFilter" class="form-select">
                            <option value="">All Teams</option>
                            @foreach ($teams ?? [] as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="nationalityFilter" class="form-label">Nationality</label>
                        <select id="nationalityFilter" class="form-select">
                            <option value="">All Nationalities</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="England">England</option>
                            <option value="Spain">Spain</option>
                            <option value="Germany">Germany</option>
                            <option value="France">France</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="applyFilters">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Players Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="players-table">
                        <thead>
                            <tr>
                                <th>Player</th>
                                <th>Position</th>
                                <th>Team</th>
                                <th>Nationality</th>
                                <th>Age</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Players pagination" class="mt-4">
                    <ul id="pagination" class="pagination justify-content-center"></ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Create Player Modal -->
    <div class="modal fade" id="createPlayerModal" tabindex="-1" aria-labelledby="createPlayerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPlayerModalLabel">Add New Player</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('players.store') }}" method="POST" enctype="multipart/form-data"
                        id="createPlayerForm">
                        @csrf
                        <div class="mb-3">
                            <label for="playerPhoto" class="form-label">Photo</label>
                            <input class="form-control" type="file" id="playerPhoto" name="avatar"
                                accept="image/*">
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="first_name"
                                    required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="playerTeam" class="form-label">Team</label>
                                <select class="form-select" id="playerTeam" name="team_id" required>
                                    <option value="">Select team</option>
                                    @foreach ($teams ?? [] as $team)
                                        <option value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="playerNumber" class="form-label">Jersey Number</label>
                                <input type="number" class="form-control" id="playerNumber" name="jersey_number"
                                    min="1" max="99">
                            </div>
                            <div class="col-md-4">
                                <label for="playerPosition" class="form-label">Position</label>
                                <select class="form-select" id="playerPosition" name="position" required>
                                    <option value="">Select position</option>
                                    <option value="GK">Goalkeeper</option>
                                    <option value="DEF">Defender</option>
                                    <option value="MID">Midfielder</option>
                                    <option value="FWD">Forward</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="playerNationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="playerNationality" name="nationality"
                                    value="Indonesia" required>
                            </div>
                            <div class="col-md-6">
                                <label for="playerBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="playerBirthdate" name="birth_date">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="playerHeight" class="form-label">Height (cm)</label>
                                <input type="number" class="form-control" id="playerHeight" name="height"
                                    min="100" max="250" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label for="playerWeight" class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" id="playerWeight" name="weight"
                                    min="30" max="150" step="0.01">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="playerBio" class="form-label">Bio</label>
                            <textarea class="form-control" id="playerBio" name="bio" rows="3"></textarea>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Player</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom JS for filtering -->
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

        document.addEventListener('DOMContentLoaded', function() {
            // Load initial data
            loadPlayers();

            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function() {
                loadPlayers(1);
            });

            // Search on enter key
            document.getElementById('searchPlayer').addEventListener('keyup', function(e) {
                loadPlayers(1);
            });

            // Handle position filter change
            document.getElementById('positionFilter').addEventListener('change', function() {
                loadPlayers(1);
            });

            // Handle team filter change
            document.getElementById('teamFilter').addEventListener('change', function() {
                loadPlayers(1);
            });

            // handle nationality filter change
            document.getElementById('nationalityFilter').addEventListener('change', function() {
                loadPlayers(1);
            });

            // Handle form submission
            document.getElementById('createPlayerForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route('players.store') }}', {
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
                            toastr.success('Player added successfully!');
                            $('#createPlayerModal').modal('hide');
                            this.reset();
                            loadPlayers(); // Reload data

                            setTimeout(() => {
                                window.location.reload();
                            }, 300);
                        } else {
                            toastr.error(data.message || 'Failed to add player');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        toastr.error('An error occurred while adding the player');
                    });
            });
        });

        function loadPlayers(page = 1) {
            const searchTerm = document.getElementById('searchPlayer').value;
            const positionFilter = document.getElementById('positionFilter').value;
            const teamFilter = document.getElementById('teamFilter').value;
            const nationalityFilter = document.getElementById('nationalityFilter').value;

            const paramsObj = {
                page,
                search: searchTerm,
                position: positionFilter,
                team: teamFilter,
                nationality: nationalityFilter
            };

            const params = Object.entries(paramsObj)
                .map(([key, value]) => `${key}=${encodeURIComponent(value)}`)
                .join('&');

            fetch(`/players-data?${params}`)
                .then(res => res.json())
                .then(data => {
                    renderTableRows(data.data);
                    if (data.total > data.per_page) {
                        renderPagination(data);
                    } else {
                        document.querySelector('#pagination').innerHTML = '';
                    }
                })
                .catch(err => {
                    console.error('Error loading players:', err);
                    toastr.error('Failed to load players data');
                });
        }

        function renderTableRows(players) {
            const tbody = document.querySelector('#players-table tbody');

            if (!players || !players.length) {
                tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted">No players found</td></tr>`;
                return;
            }

            tbody.innerHTML = players.map(player => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${player.avatar ? '/storage/' + player.avatar : 'https://placehold.co/40'}"
                                 alt="Player" class="player-avatar me-3">
                            <div>
                                <h6 class="mb-0">${player.name}</h6>
                                <small class="text-muted">#${player.jersey_number || 'N/A'}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="position-badge badge-${getPositionBadgeClass(player.position)}"
                              title="${player.position}">${getPositionShort(player.position)}</span>
                    </td>
                    <td>${player.team ? player.team.name : 'Free Agent'}</td>
                    <td>
                        <img src="https://i.pinimg.com/474x/52/a1/28/52a12853824cd120f1465526dbe21404.jpg"
                             alt="Flag" class="me-1" style="width:20px;">
                        ${player.nationality}
                    </td>
                    <td>${player.age || 'N/A'}</td>
                    <td><span class="badge bg-success badge-pill">Active</span></td>
                    <td>
                        <a href="/players/${player.id}" class="btn btn-sm btn-outline-primary action-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn btn-sm btn-outline-secondary action-btn d-none" onclick="editPlayer(${player.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(meta) {
            const pagination = document.getElementById('pagination');
            let html = '';
            const current = meta.current_page;
            const last = meta.last_page;

            // Previous button
            html += `
                <li class="page-item ${current === 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); loadPlayers(${current - 1})">Previous</a>
                </li>
            `;

            // Always show page 1
            html += createPageItem(1, current);

            // Calculate range of pages to display around current page (max 3 before & 3 after)
            let start = Math.max(2, current - 1);
            let end = Math.min(last - 1, current + 1);

            // add dots if we skip pages
            if (start > 2) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            for (let i = start; i <= end; i++) {
                html += createPageItem(i, current);
            }

            if (end < last - 1) {
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            // Always show last page when >1
            if (last > 1) {
                html += createPageItem(last, current);
            }

            // Next button
            html += `
                <li class="page-item ${current === last ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); loadPlayers(${current + 1})">Next</a>
                </li>
            `;

            pagination.innerHTML = html;
        }

        function createPageItem(page, current) {
            return `
                <li class="page-item ${page === current ? 'active' : ''}">
                    <a class="page-link ${page === current ? 'text-white' : ''}"
                    href="#" onclick="event.preventDefault(); loadPlayers(${page})">${page}</a>
                </li>
            `;
        }


        function getPositionBadgeClass(position) {
            const positionMap = {
                'GK': 'gk',
                'DEF': 'def',
                'MID': 'mid',
                'FWD': 'fwd',
            };
            return positionMap[position] || 'mid';
        }

        function getPositionShort(position) {
            const shortMap = {
                'GK': 'GK',
                'DEF': 'DEF',
                'MID': 'MID',
                'FWD': 'FW',
            };
            return shortMap[position] || 'MID';
        }

        function editPlayer(playerId) {
            toastr.info('Edit player functionality will be implemented here');
        }
    </script>
</body>

</html>

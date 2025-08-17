<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Teams - Football Tournament Management</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
       <style>
        :root {
    --primary-color: #2563eb;       /* New primary (lighter blue from gradient) */
    --secondary-color: #1e40af;    /* New secondary (darker blue from gradient) */
    --accent-color: #e74c3c;       /* Keeping the original accent color (red) */
    --light-color: #f8fafc;        /* Lighter background color */
    --dark-color: #1e293b;         /* Darker text color that complements the blues */

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
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table th {
            font-weight: 600;
        }

        .team-logo-sm {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 50%;
        }

        .filter-card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
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

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Teams</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                        <i class="fas fa-plus me-2"></i> Add New Team
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

        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="searchTeam" class="form-label">Search Team</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchTeam" placeholder="Team name or location...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label for="countryFilter" class="form-label">Country</label>
                        <select id="countryFilter" class="form-select">
                            <option value="">All Countries</option>
                            <option value="england">England</option>
                            <option value="spain">Spain</option>
                            <option value="germany">Germany</option>
                            <option value="france">France</option>
                            <option value="italy">Italy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="establishedFilter" class="form-label">Established</label>
                        <select id="establishedFilter" class="form-select">
                            <option value="">Any Year</option>
                            <option value="before_2000">Before 2000</option>
                            <option value="2000_2010">2000-2010</option>
                            <option value="after_2010">After 2010</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="applyFilters">
                            <i class="fas fa-filter me-2"></i> Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teams Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="teams-table">
                        <thead>
                            <tr>
                                <th>Team</th>
                                <th>Location</th>
                                <th>Established</th>
                                <th>Players</th>
                                <th>Tournaments</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Teams pagination" class="mt-4">
                    <ul id="pagination" class="pagination justify-content-center"></ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Create Team Modal -->
    <div class="modal fade" id="createTeamModal" tabindex="-1" aria-labelledby="createTeamModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('teams.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTeamModalLabel">Add New Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="teamName" class="form-label">Team Name</label>
                        <input type="text" class="form-control" id="teamName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="shortName" class="form-label">Short Name</label>
                        <input type="text" class="form-control" id="shortName" name="short_name" maxlength="3" required>
                    </div>
                    <div class="mb-3">
                        <label for="teamLogo" class="form-label">Logo</label>
                        <input class="form-control" type="file" id="teamLogo" name="logo" accept="image/*">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="foundedYear" class="form-label">Founded Year</label>
                            <input type="number" class="form-control" id="foundedYear" name="founded_year" min="1800" max="2023">
                        </div>
                        <div class="col-md-6">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select country</option>
                                <option value="england">England</option>
                                <option value="spain">Spain</option>
                                <option value="germany">Germany</option>
                                <option value="france">France</option>
                                <option value="italy">Italy</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                                            <div class="mb-3">
                            <label for="manager_name" class="form-label">Manager Name</label>
                            <input type="text" class="form-control" id="manager_name" name="manager_name">
                        </div>
                        <div class="mb-3">
                            <label for="manager_phone" class="form-label">Manager Phone</label>
                            <input type="text" class="form-control" id="manager_phone" name="manager_phone">
                        </div>
                        <div class="mb-3">
                            <label for="manager_email" class="form-label">Manager Email</label>
                            <input type="email" class="form-control" id="manager_email" name="manager_email">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Team</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Custom JS for filtering and AJAX -->
    <script>
        const urlShowTeam = "{{ route('teams.show', ':id') }}";
        const storageLink = "{{ Storage::url(':path') }}";

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize toastr
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right'
            };

            // Load teams on page load
            loadTeams();

            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function() {
                loadTeams(1);
            });

            // Search functionality
            document.getElementById('searchTeam').addEventListener('input', function() {
                loadTeams(1);
            });

            // Country filter
            document.getElementById('countryFilter').addEventListener('change', function() {
                loadTeams(1);
            });

            // Established filter
            document.getElementById('establishedFilter').addEventListener('change', function() {
                loadTeams(1);
            });
        });

        function loadTeams(page = 1) {
            const searchTerm = document.getElementById('searchTeam').value;
            const countryFilter = document.getElementById('countryFilter').value;
            const establishedFilter = document.getElementById('establishedFilter').value;

            const params = new URLSearchParams({
                page: page,
                search: searchTerm,
                country: countryFilter,
                established: establishedFilter
            }).toString();

            fetch(`/teams-data?${params}`)
                .then(res => res.json())
                .then(data => {
                    renderTableRows(data.data);
                    if (data.total > data.per_page) {
                        renderPagination(data);
                    } else {
                        document.querySelector('#pagination').innerHTML = '';
                    }
                })
                .catch(err => console.error('Error loading teams:', err));
        }

        function renderTableRows(teams) {
            const tbody = document.querySelector('#teams-table tbody');

            if (!teams.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No teams found</td></tr>`;
                return;
            }

            tbody.innerHTML = teams.map(team => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${team.logo ? storageLink.replace(':path', team.logo) : 'https://tse4.mm.bing.net/th/id/OIP.KVu2tTbpWum5f0bBJh3JGwHaHa?pid=Api&P=0&h=180'}"
                                 alt="Team Logo" class="team-logo-sm me-3">
                            <div>
                                <h6 class="mb-0">${team.name}</h6>
                                <small class="text-muted">${team.short_name || ''}</small>
                            </div>
                        </div>
                    </td>
                    <td>${team.city}, ${team.country}</td>
                    <td>${team.founded_year || 'N/A'}</td>
                    <td>${team.players_count || 0}</td>
                    <td>${team.tournaments_count || 0}</td>
                    <td><span class="badge bg-success badge-pill">Active</span></td>
                    <td>
                        <a href="${urlShowTeam.replace(':id', team.id)}" class="btn btn-sm btn-outline-primary action-btn">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button class="btn btn-sm btn-outline-secondary action-btn d-none" onclick="editTeam(${team.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(meta) {
            const pagination = document.getElementById('pagination');
            let html = '';

            // Previous button
            if (meta.current_page > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadTeams(${meta.current_page - 1})">Previous</a></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;
            }

            // Page numbers
            for (let i = 1; i <= meta.last_page; i++) {
                html += `
                    <li class="page-item ${i === meta.current_page ? 'active' : ''}">
                        <a class="page-link ${i === meta.current_page ? 'text-white' : ''}" href="#" onclick="event.preventDefault(); loadTeams(${i})">${i}</a>
                    </li>`;
            }

            // Next button
            if (meta.current_page < meta.last_page) {
                html += `<li class="page-item"><a class="page-link" href="#" onclick="event.preventDefault(); loadTeams(${meta.current_page + 1})">Next</a></li>`;
            } else {
                html += `<li class="page-item disabled"><span class="page-link">Next</span></li>`;
            }

            pagination.innerHTML = html;
        }

        function editTeam(teamId) {
            // This would open an edit modal
            // For now, just show a message
            toastr.info('Edit functionality will be implemented here');
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tournaments - Football Tournament Management</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-active {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--primary-color);
        }

        .badge-upcoming {
            background-color: rgba(241, 196, 15, 0.2);
            color: #f39c12;
        }

        .badge-completed {
            background-color: rgba(149, 165, 166, 0.2);
            color: #7f8c8d;
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
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
                        <a class="nav-link active" href="{{ route('tournaments.index') }}">Tournaments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('teams.index') }}">Teams</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('matches.index') }}">Matches</a>
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
                    <h1 class="fw-bold mb-3">All Tournaments</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createTournamentModal">
                        <i class="fas fa-plus me-2"></i> Create Tournament
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

        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">Status</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">All Status</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="typeFilter" class="form-label">Type</label>
                        <select id="typeFilter" class="form-select">
                            <option value="">All Types</option>
                            <option value="league">League</option>
                            <option value="knockout">Knockout</option>
                            <option value="group_knockout">Group + Knockout</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="dateFilter" class="form-label">Date Range</label>
                        <select id="dateFilter" class="form-select">
                            <option value="">All Dates</option>
                            <option value="this_month">This Month</option>
                            <option value="next_month">Next Month</option>
                            <option value="past">Past Tournaments</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="applyFilters">
                            <i class="fas fa-filter me-2"></i> Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tournaments Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tournaments-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Teams</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Tournaments pagination" class="mt-4">
                    <ul id="pagination" class="pagination justify-content-center"></ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Create Tournament Modal -->
    <div class="modal fade" id="createTournamentModal" tabindex="-1" aria-labelledby="createTournamentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" method="POST" enctype="multipart/form-data"
                action="{{ route('tournaments.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="createTournamentModalLabel">Create New Tournament</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tournamentName" class="form-label">Tournament Name</label>
                            <input type="text" id="tournamentName" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tournamentLogo" class="form-label">Logo</label>
                            <input type="file" id="tournamentLogo" name="logo" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="startDate" class="form-label">Start Date</label>
                            <input type="date" id="startDate" name="start_date" class="form-control" required>
                            <small id="dateError" class="text-danger"></small>
                        </div>
                        <div class="col-md-6">
                            <label for="endDate" class="form-label">End Date</label>
                            <input type="date" id="endDate" name="end_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="tournamentDescription" class="form-label">Description</label>
                        <textarea id="tournamentDescription" name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="teamsNumber" class="form-label">Number of Teams</label>
                            <select id="teamsNumber" name="max_teams" class="form-select" required>
                                <option value="" disabled selected>Select teams</option>
                                <option value="2">2</option>
                                <option value="4">4</option>
                                <option value="8">8</option>
                                <option value="16">16</option>
                                <option value="32">32</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="organizerId" class="form-label">Organizer</label>
                            <select id="organizerId" name="organizer_id" class="form-select" required>
                                <option value="" disabled selected>Select organizer</option>
                                @foreach ($organizers as $organizer)
                                    <option value="{{ $organizer->id }}">{{ $organizer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5 card-info"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Tournament</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const urlShowTournament = "{{ route('tournaments.show', ':id') }}";
        const storageLink = "{{ Storage::url(':path') }}";
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

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

            // =================== DATE VALIDATION + PREVIEW ===================
            const previewContainer = document.querySelector('.card-info');
            const startInput = document.getElementById('startDate');
            const endInput = document.getElementById('endDate');
            const today = new Date().toISOString().split('T')[0];
            startInput.min = today;
            endInput.min = today;

            const inputs = {
                name: document.getElementById('tournamentName'),
                start: startInput,
                end: endInput,
                desc: document.getElementById('tournamentDescription'),
                teams: document.getElementById('teamsNumber'),
                logo: document.getElementById('tournamentLogo')
            };

            Object.values(inputs).forEach(input => {
                input.addEventListener('input', () => {
                    validateDates();
                    updatePreview();
                });
            });

            function validateDates() {
                const start = inputs.start.value;
                const end = inputs.end.value;
                if (start) {
                    inputs.end.min = start;
                }
                const errorLabel = document.getElementById('dateError');
                errorLabel.textContent = (start && end && new Date(start) > new Date(end)) ?
                    'End date must be after start date' :
                    '';
            }

            function updatePreview() {
                const name = inputs.name.value || '(Tournament Name)';
                const desc = inputs.desc.value || 'No description…';
                const start = inputs.start.value ? formatDate(inputs.start.value) : '-';
                const end = inputs.end.value ? formatDate(inputs.end.value) : '-';
                const teams = inputs.teams.value || '-';
                const type = inputs.teams.value ? getTypeLabel(Number(inputs.teams.value)) : '-';

                const logoUrl = inputs.logo.files?.[0] ?
                    URL.createObjectURL(inputs.logo.files[0]) :
                    'https://tse1.mm.bing.net/th/id/OIP.MaIk4N5rw51_K6gHkokGUgHaGl?pid=Api';

                previewContainer.innerHTML = `
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <img src="${logoUrl}" class="rounded-circle me-3" width="60" height="60"/>
                            <div>
                                <h5 class="card-title mb-1">${name}</h5>
                                <p class="card-text small text-muted mb-1">${desc}</p>
                                <p class="mb-0 small">
                                    <strong>Dates:</strong> ${start} – ${end} &nbsp; | &nbsp;
                                    <strong>Teams:</strong> ${teams} &nbsp; | &nbsp;
                                    <strong>Type:</strong> ${type}
                                </p>
                            </div>
                        </div>
                    </div>`;
            }

            // =================== TABLE + FILTER ===================
            loadTournaments();

            document.getElementById('applyFilters').addEventListener('click', () => {
                const status = document.getElementById('statusFilter').value;
                const type = document.getElementById('typeFilter').value;
                const date = document.getElementById('dateFilter').value;
                loadTournaments(1, {
                    status,
                    type,
                    date
                });
            });

        });

        // ========== Helper functions ===========

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function getTypeLabel(max) {
            if (max <= 16) return 'Knockout';
            if (max <= 32) return 'Group & Knockout';
            return 'League';
        }

        function statusBadgeClass(status) {
            return {
                upcoming: 'badge-upcoming',
                ongoing: 'badge-active',
                completed: 'badge-completed'
            } [status] || 'badge-active';
        }

        function toTitleCase(str) {
            return str.replace(/_/g, ' ').toLowerCase()
                .replace(/\b\w/g, (c) => c.toUpperCase());
        }

        function truncate(text, max) {
            return text.length > max ? text.slice(0, max) + '…' : text;
        }

        // ============ Fetch + Render ===========

        function loadTournaments(page = 1, filters = {}) {
            const params = new URLSearchParams({
                page,
                ...filters
            }).toString();
            fetch(`/tournaments-data?${params}`)
                .then(res => res.json())
                .then(data => {
                    renderTableRows(data.data);
                    if (data.total > data.per_page) renderPagination(data);
                    else document.querySelector('#pagination').innerHTML = '';
                })
                .catch(err => console.error(err));
        }

        function renderTableRows(list) {
            const tbody = document.querySelector('#tournaments-table tbody');
            if (!list.length) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No data available</td></tr>`;
                return;
            }
            tbody.innerHTML = list.map(t => `
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${t.logo ? storageLink.replace(':path', t.logo) : 'https://tse1.mm.bing.net/th/id/OIP.MaIk4N5rw51_K6gHkokGUgHaGl?pid=Api'}"
                                 class="rounded-circle me-3" width="40" height="40" />
                            <div>
                                <h6 class="mb-0">${t.name}</h6>
                                <small class="text-muted">${truncate(t.description,30)}</small>
                            </div>
                        </div>
                    </td>
                    <td>${formatDate(t.start_date)}</td>
                    <td>${formatDate(t.end_date)}</td>
                    <td>${t.max_teams}</td>
                    <td>${t.type}</td>
                    <td><span class="status-badge ${statusBadgeClass(t.status)}">${toTitleCase(t.status)}</span></td>
                    <td>
                        <a href="${urlShowTournament.replace(':id', t.id)}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
            `).join('');
        }

        function renderPagination(meta) {
            const pagination = document.getElementById('pagination');
            let html = '';

            html += (meta.current_page > 1) ?
                `<li class="page-item"><a class="page-link" href="#" onclick="loadTournaments(${meta.current_page - 1})">Previous</a></li>` :
                `<li class="page-item disabled"><span class="page-link">Previous</span></li>`;

            for (let i = 1; i <= meta.last_page; i++) {
                html += `
                    <li class="page-item ${i === meta.current_page ? 'active' : ''}">
                        <a class="page-link ${i === meta.current_page ? 'text-white' : ''}" href="#" onclick="loadTournaments(${i})">${i}</a>
                    </li>`;
            }

            html += (meta.current_page < meta.last_page) ?
                `<li class="page-item"><a class="page-link" href="#" onclick="loadTournaments(${meta.current_page + 1})">Next</a></li>` :
                `<li class="page-item disabled"><span class="page-link">Next</span></li>`;

            pagination.innerHTML = html;
        }
    </script>
</body>

</html>

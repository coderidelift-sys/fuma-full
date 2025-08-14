@extends('layouts.fuma')

@section('title', 'Standings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0 me-2">Tournament Standings</h5>
                </div>
                <div class="card-body">
                    <!-- Tournament Filter -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="tournament_filter" class="form-label">Select Tournament</label>
                            <select class="form-select" id="tournament_filter">
                                <option value="">All Tournaments</option>
                                <option value="1">Tournament A</option>
                                <option value="2">Tournament B</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-primary w-100" onclick="loadStandings()">
                                <i class="ri-search-line me-2"></i>View Standings
                            </button>
                        </div>
                    </div>

                    <!-- Standings Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pos</th>
                                    <th>Team</th>
                                    <th>P</th>
                                    <th>W</th>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>GF</th>
                                    <th>GA</th>
                                    <th>GD</th>
                                    <th>Pts</th>
                                    <th>Form</th>
                                </tr>
                            </thead>
                            <tbody id="standingsTableBody">
                                <tr>
                                    <td colspan="10" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-list-check-2 ri-3x text-muted mb-2"></i>
                                            <h6 class="text-muted">No standings available</h6>
                                            <p class="text-muted mb-0">Select a tournament to view standings</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Standings Legend -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title m-0">
                        <i class="ri-information-line me-2"></i>Standings Legend
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-success me-2">1-4</div>
                                <small>Qualify for Next Round</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-warning me-2">5-8</div>
                                <small>Group Stage</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-danger me-2">9-12</div>
                                <small>Relegation Zone</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-secondary me-2">-</div>
                                <small>No Match Played</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <small class="text-muted">
                                <strong>Abbreviations:</strong> P = Played, W = Won, D = Drawn, L = Lost,
                                GF = Goals For, GA = Goals Against, GD = Goal Difference, Pts = Points
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadStandings() {
    const tournamentId = document.getElementById('tournament_filter').value;

    if (!tournamentId) {
        alert('Please select a tournament');
        return;
    }

    // Show loading state
    const tbody = document.getElementById('standingsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 mb-0">Loading standings...</p>
            </td>
        </tr>
    `;

    // Fetch standings from API
    fetch(`/fuma/standings?tournament_id=${tournamentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                displayStandings(data.data);
            } else {
                showError('Failed to load standings');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Error loading standings');
        });
}

function displayStandings(standings) {
    const tbody = document.getElementById('standingsTableBody');

    if (!standings.teams || standings.teams.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-4">
                    <div class="d-flex flex-column align-items-center">
                        <i class="ri-list-check-2 ri-3x text-muted mb-2"></i>
                        <h6 class="text-muted">No standings available</h6>
                        <p class="text-muted mb-0">This tournament has no teams or matches yet</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }

    let html = '';
    standings.teams.forEach((team, index) => {
        const position = index + 1;
        const positionClass = getPositionClass(position);
        const form = generateFormString(team.recent_form || []);

        html += `
            <tr class="${positionClass}">
                <td>
                    <span class="badge ${getPositionBadge(position)}">${position}</span>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-2">
                            ${team.logo ?
                                `<img src="/storage/${team.logo}" alt="${team.name}" class="rounded-circle">` :
                                `<span class="avatar-initial rounded bg-label-secondary">
                                    <i class="ri-team-line"></i>
                                </span>`
                            }
                        </div>
                        <div>
                            <h6 class="mb-0">${team.name}</h6>
                            <small class="text-muted">${team.city}, ${team.country}</small>
                        </div>
                    </div>
                </td>
                <td><strong>${team.played || 0}</strong></td>
                <td><span class="text-success">${team.won || 0}</span></td>
                <td><span class="text-warning">${team.drawn || 0}</span></td>
                <td><span class="text-danger">${team.lost || 0}</span></td>
                <td><strong>${team.goals_for || 0}</strong></td>
                <td><strong>${team.goals_against || 0}</strong></td>
                <td>
                    <span class="badge ${team.goal_difference >= 0 ? 'bg-label-success' : 'bg-label-danger'}">
                        ${team.goal_difference >= 0 ? '+' : ''}${team.goal_difference || 0}
                    </span>
                </td>
                <td><strong>${team.points || 0}</strong></td>
                <td>
                    <div class="d-flex gap-1">
                        ${form}
                    </div>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

function getPositionClass(position) {
    if (position <= 4) return 'table-success';
    if (position <= 8) return 'table-warning';
    if (position >= 9) return 'table-danger';
    return '';
}

function getPositionBadge(position) {
    if (position === 1) return 'bg-warning';
    if (position === 2) return 'bg-secondary';
    if (position === 3) return 'bg-warning';
    if (position <= 4) return 'bg-success';
    if (position <= 8) return 'bg-warning';
    if (position >= 9) return 'bg-danger';
    return 'bg-secondary';
}

function generateFormString(form) {
    if (!form || form.length === 0) {
        return '<span class="text-muted">-</span>';
    }

    let html = '';
    form.forEach(result => {
        let badgeClass = 'bg-label-secondary';
        let icon = 'ri-minus-line';

        switch(result) {
            case 'W':
                badgeClass = 'bg-label-success';
                icon = 'ri-check-line';
                break;
            case 'D':
                badgeClass = 'bg-label-warning';
                icon = 'ri-minus-line';
                break;
            case 'L':
                badgeClass = 'bg-label-danger';
                icon = 'ri-close-line';
                break;
        }

        html += `<span class="badge ${badgeClass}"><i class="${icon}"></i></span>`;
    });

    return html;
}

function showError(message) {
    const tbody = document.getElementById('standingsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="10" class="text-center py-4">
                <div class="d-flex flex-column align-items-center">
                    <i class="ri-error-warning-line ri-3x text-danger mb-2"></i>
                    <h6 class="text-danger">Error</h6>
                    <p class="text-muted mb-0">${message}</p>
                </div>
            </td>
        </tr>
    `;
}

// Auto-load standings when tournament is selected
document.getElementById('tournament_filter').addEventListener('change', function() {
    if (this.value) {
        loadStandings();
    }
});
</script>
@endpush

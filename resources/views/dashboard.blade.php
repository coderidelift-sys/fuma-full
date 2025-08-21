@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <span class="d-block mb-1">Users</span>
                        <h3 class="card-title mb-2" id="stat-users">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <span class="d-block mb-1">Tournaments</span>
                        <h3 class="card-title mb-1" id="stat-tournaments">-</h3>
                        <small class="text-muted" id="stat-tournaments-breakdown">-</small>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <span class="d-block mb-1">Teams</span>
                        <h3 class="card-title mb-2" id="stat-teams">-</h3>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <span class="d-block mb-1">Players</span>
                        <h3 class="card-title mb-2" id="stat-players">-</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Aktivitas 14 Hari</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-activity"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Users per Role</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-users-by-role"></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Match Status</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-matches-status"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Top Scorers</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-top-scorers"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Top Teams (Wins)</h5>
                    </div>
                    <div class="card-body">
                        <div id="chart-top-teams"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title m-0 me-2">Recent Matches</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="table-recent-matches">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Home</th>
                                        <th>Away</th>
                                        <th>Scheduled At</th>
                                        <th>Status</th>
                                        <th>Score</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function() {
        const endpoint = '{{ route('console.analytics') }}';
        $.getJSON(endpoint, function(resp) {
            $('#stat-users').text(resp.summary.users);
            $('#stat-tournaments').text(resp.summary.tournaments.total);
            $('#stat-tournaments-breakdown').text(
                `Upcoming ${resp.summary.tournaments.upcoming} • Ongoing ${resp.summary.tournaments.ongoing} • Completed ${resp.summary.tournaments.completed}`
            );
            $('#stat-teams').text(resp.summary.teams);
            $('#stat-players').text(resp.summary.players);

            new ApexCharts(document.querySelector('#chart-activity'), {
                chart: { type: 'line', height: 300 },
                series: resp.charts.activity.series,
                xaxis: { categories: resp.charts.activity.labels }
            }).render();

            new ApexCharts(document.querySelector('#chart-users-by-role'), {
                chart: { type: 'donut', height: 260 },
                labels: resp.charts.usersByRole.labels,
                series: resp.charts.usersByRole.series
            }).render();

            new ApexCharts(document.querySelector('#chart-matches-status'), {
                chart: { type: 'pie', height: 260 },
                labels: resp.charts.matchesStatus.labels,
                series: resp.charts.matchesStatus.series
            }).render();

            new ApexCharts(document.querySelector('#chart-top-scorers'), {
                chart: { type: 'bar', height: 300 },
                plotOptions: { bar: { horizontal: true } },
                series: [{ name: 'Goals', data: resp.charts.topScorers.series }],
                xaxis: { categories: resp.charts.topScorers.categories }
            }).render();

            new ApexCharts(document.querySelector('#chart-top-teams'), {
                chart: { type: 'bar', height: 300 },
                plotOptions: { bar: { horizontal: true } },
                series: [{ name: 'Wins', data: resp.charts.topTeams.series }],
                xaxis: { categories: resp.charts.topTeams.categories }
            }).render();

            const tbody = $('#table-recent-matches tbody');
            resp.recent.matches.forEach(row => {
                const tr = `<tr>
                    <td>${row.id}</td>
                    <td>${row.home_team ?? '-'}</td>
                    <td>${row.away_team ?? '-'}</td>
                    <td>${row.scheduled_at ?? '-'}</td>
                    <td><span class="badge bg-label-${row.status === 'live' ? 'success' : (row.status === 'completed' ? 'primary' : 'warning')}">${row.status ?? '-'}</span></td>
                    <td>${row.score ?? '-'}</td>
                </tr>`;
                tbody.append(tr);
            });
        });
    });
</script>
@endpush

@extends('layouts.fuma')

@section('title', 'Statistics')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Statistics Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="ri-trophy-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $stats['tournaments']['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Tournaments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="ri-team-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $stats['teams']['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Teams</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="ri-user-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $stats['players']['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Players</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="ri-football-line"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ $stats['matches']['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total Matches</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tournament Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-trophy-line me-2"></i>Tournament Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-warning mb-1">{{ $stats['tournaments']['upcoming'] ?? 0 }}</span>
                                <small class="text-muted">Upcoming</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-primary mb-1">{{ $stats['tournaments']['ongoing'] ?? 0 }}</span>
                                <small class="text-muted">Ongoing</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-success mb-1">{{ $stats['tournaments']['completed'] ?? 0 }}</span>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Match Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-football-line me-2"></i>Match Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-warning mb-1">{{ $stats['matches']['upcoming'] ?? 0 }}</span>
                                <small class="text-muted">Scheduled</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-danger mb-1">{{ $stats['matches']['live'] ?? 0 }}</span>
                                <small class="text-muted">Live</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="d-flex flex-column">
                                <span class="h4 text-success mb-1">{{ $stats['matches']['completed'] ?? 0 }}</span>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Teams -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-medal-line me-2"></i>Top Rated Teams
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($stats['teams']['top_rated']) && count($stats['teams']['top_rated']) > 0)
                        @foreach($stats['teams']['top_rated'] as $index => $team)
                        <div class="d-flex align-items-center mb-3 {{ $index === 0 ? 'border-bottom pb-3' : '' }}">
                            <div class="avatar avatar-sm me-3">
                                @if($index === 0)
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @elseif($index === 1)
                                    <span class="avatar-initial rounded bg-secondary">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @elseif($index === 2)
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @else
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        {{ $index + 1 }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $team['name'] }}</h6>
                                <small class="text-muted">{{ $team['city'] }}, {{ $team['country'] }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-label-primary">{{ number_format($team['rating'], 1) }}</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ri-team-line ri-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No team data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Scorers -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-fire-line me-2"></i>Top Scorers
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($stats['players']['top_scorers']) && count($stats['players']['top_scorers']) > 0)
                        @foreach($stats['players']['top_scorers'] as $index => $player)
                        <div class="d-flex align-items-center mb-3 {{ $index === 0 ? 'border-bottom pb-3' : '' }}">
                            <div class="avatar avatar-sm me-3">
                                @if($index === 0)
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @elseif($index === 1)
                                    <span class="avatar-initial rounded bg-secondary">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @elseif($index === 2)
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="ri-medal-fill"></i>
                                    </span>
                                @else
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        {{ $index + 1 }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $player['name'] }}</h6>
                                <small class="text-muted">{{ $player['position'] }} • {{ $player['team']['name'] ?? 'Free Agent' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-label-success">{{ $player['goals_scored'] ?? 0 }} goals</span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="ri-user-line ri-3x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No player data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-bar-chart-line me-2"></i>Performance Charts
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <canvas id="tournamentChart" width="400" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <canvas id="matchChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tournament Status Chart
    const tournamentCtx = document.getElementById('tournamentChart').getContext('2d');
    new Chart(tournamentCtx, {
        type: 'doughnut',
        data: {
            labels: ['Upcoming', 'Ongoing', 'Completed'],
            datasets: [{
                data: [
                    {{ $stats['tournaments']['upcoming'] ?? 0 }},
                    {{ $stats['tournaments']['ongoing'] ?? 0 }},
                    {{ $stats['tournaments']['completed'] ?? 0 }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#0d6efd',
                    '#198754'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Tournament Status Distribution'
                }
            }
        }
    });

    // Match Status Chart
    const matchCtx = document.getElementById('matchChart').getContext('2d');
    new Chart(matchCtx, {
        type: 'doughnut',
        data: {
            labels: ['Scheduled', 'Live', 'Completed'],
            datasets: [{
                data: [
                    {{ $stats['matches']['upcoming'] ?? 0 }},
                    {{ $stats['matches']['live'] ?? 0 }},
                    {{ $stats['matches']['completed'] ?? 0 }}
                ],
                backgroundColor: [
                    '#ffc107',
                    '#dc3545',
                    '#198754'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Match Status Distribution'
                }
            }
        }
    });
});
</script>
@endpush

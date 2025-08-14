@extends('layouts.fuma')

@section('title', 'Tournament Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Tournament Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl me-3">
                                @if($tournament['logo'])
                                    <img src="{{ asset('storage/' . $tournament['logo']) }}"
                                         alt="{{ $tournament['name'] }}"
                                         class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="ri-trophy-line"></i>
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $tournament['name'] }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="ri-map-pin-line me-1"></i>
                                    {{ $tournament['venue'] ?? 'Venue not specified' }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('fuma.tournaments.edit', $tournament['id']) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i>Edit Tournament
                            </a>
                            <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="mb-3">
                        @if($tournament['status'] === 'upcoming')
                            <span class="badge bg-label-warning">Upcoming</span>
                        @elseif($tournament['status'] === 'ongoing')
                            <span class="badge bg-label-primary">Ongoing</span>
                        @elseif($tournament['status'] === 'completed')
                            <span class="badge bg-label-success">Completed</span>
                        @else
                            <span class="badge bg-label-secondary">{{ ucfirst($tournament['status']) }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Tournament Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-information-line me-2"></i>Tournament Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Start Date</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($tournament['start_date'])->format('d M Y') }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">End Date</label>
                                    <p class="mb-0">{{ \Carbon\Carbon::parse($tournament['end_date'])->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Duration</label>
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($tournament['start_date'])->diffInDays($tournament['end_date']) + 1 }} days
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Maximum Teams</label>
                                    <p class="mb-0">{{ $tournament['max_teams'] }} teams</p>
                                </div>
                            </div>

                            @if($tournament['description'])
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <p class="mb-0">{{ $tournament['description'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Teams in Tournament -->
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">
                                <i class="ri-team-line me-2"></i>Teams ({{ count($tournament['teams'] ?? []) }}/{{ $tournament['max_teams'] }})
                            </h5>
                            @if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                <i class="ri-add-line me-2"></i>Add Team
                            </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if(isset($tournament['teams']) && count($tournament['teams']) > 0)
                                <div class="row">
                                    @foreach($tournament['teams'] as $team)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center p-3 border rounded">
                                            <div class="avatar avatar-lg me-3">
                                                @if($team['logo'])
                                                    <img src="{{ asset('storage/' . $team['logo']) }}"
                                                         alt="{{ $team['name'] }}"
                                                         class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary">
                                                        <i class="ri-team-line"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $team['name'] }}</h6>
                                                <small class="text-muted">{{ $team['city'] }}, {{ $team['country'] }}</small>
                                                <div class="mt-2">
                                                    <span class="badge bg-label-primary">{{ number_format($team['rating'], 1) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-team-line ri-3x text-muted mb-2"></i>
                                    <h6 class="text-muted">No teams added yet</h6>
                                    <p class="text-muted mb-0">Teams will appear here once they are added to the tournament</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Recent Matches -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-football-line me-2"></i>Recent Matches
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($tournament['recent_matches']) && count($tournament['recent_matches']) > 0)
                                @foreach($tournament['recent_matches'] as $match)
                                <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            @if($match['home_team']['logo'])
                                                <img src="{{ asset('storage/' . $match['home_team']['logo']) }}"
                                                     alt="{{ $match['home_team']['name'] }}"
                                                     class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary">H</span>
                                            @endif
                                        </div>
                                        <span class="fw-semibold">{{ $match['home_team']['name'] }}</span>
                                    </div>

                                    <div class="text-center mx-3">
                                        @if($match['status'] === 'completed')
                                            <span class="badge bg-success">{{ $match['home_score'] }} - {{ $match['away_score'] }}</span>
                                        @elseif($match['status'] === 'live')
                                            <span class="badge bg-danger">LIVE</span>
                                        @else
                                            <span class="badge bg-secondary">VS</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold me-2">{{ $match['away_team']['name'] }}</span>
                                        <div class="avatar avatar-sm">
                                            @if($match['away_team']['logo'])
                                                <img src="{{ asset(['away_team']['logo']) }}" alt="{{ $match['away_team']['name'] }}" class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary">A</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($match['scheduled_at'])->format('d M H:i') }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-football-line ri-3x text-muted mb-2"></i>
                                    <h6 class="text-muted">No matches scheduled yet</h6>
                                    <p class="text-muted mb-0">Matches will appear here once they are scheduled</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-md-4">
                    <!-- Organizer Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-user-settings-line me-2"></i>Organizer
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="avatar avatar-lg mb-3">
                                @if($tournament['organizer']['avatar'])
                                    <img src="{{ asset('storage/' . $tournament['organizer']['avatar']) }}"
                                         alt="{{ $tournament['organizer']['name'] }}"
                                         class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="ri-user-line"></i>
                                    </span>
                                @endif
                            </div>
                            <h6 class="mb-1">{{ $tournament['organizer']['name'] }}</h6>
                            <p class="text-muted mb-2">{{ $tournament['organizer']['email'] }}</p>
                            @if($tournament['organizer']['phone'])
                                <small class="text-muted">
                                    <i class="ri-phone-line me-1"></i>{{ $tournament['organizer']['phone'] }}
                                </small>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-tools-line me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('fuma.tournaments.standings', $tournament['id']) }}" class="btn btn-outline-primary">
                                    <i class="ri-list-check-2 me-2"></i>View Standings
                                </a>
                                <a href="{{ route('fuma.matches.create') }}?tournament_id={{ $tournament['id'] }}" class="btn btn-outline-success">
                                    <i class="ri-add-line me-2"></i>Schedule Match
                                </a>
                                @if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin'))
                                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                    <i class="ri-team-line me-2"></i>Manage Teams
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tournament Stats -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-bar-chart-line me-2"></i>Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ count($tournament['teams'] ?? []) }}</span>
                                        <small class="text-muted">Teams</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ count($tournament['recent_matches'] ?? []) }}</span>
                                        <small class="text-muted">Matches</small>
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

<!-- Add Team Modal -->
@if(auth()->user()->hasRole('organizer') || auth()->user()->hasRole('admin'))
<div class="modal fade" id="addTeamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Team to Tournament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="team_id" class="form-label">Select Team</label>
                        <select class="form-select" id="team_id" name="team_id" required>
                            <option value="">Choose a team...</option>
                            <!-- Teams will be loaded dynamically -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Team</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load available teams for the modal
    if (document.getElementById('addTeamModal')) {
        fetch('/fuma/teams?available_for_tournament={{ $tournament['id'] }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const select = document.getElementById('team_id');
                    data.data.forEach(team => {
                        const option = document.createElement('option');
                        option.value = team.id;
                        option.textContent = `${team.name} (${team.city})`;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading teams:', error);
            });
    }
});
</script>
@endpush

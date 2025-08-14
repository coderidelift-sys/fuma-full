@extends('layouts.fuma')

@section('title', 'Match Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Match Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ri-football-line"></i>
                                </span>
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $match['home_team']['name'] }} vs {{ $match['away_team']['name'] }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="ri-trophy-line me-1"></i>{{ $match['tournament']['name'] }}
                                    <span class="badge bg-label-secondary ms-2">{{ ucfirst(str_replace('_', ' ', $match['stage'])) }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('fuma.matches.edit', $match['id']) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i>Edit Match
                            </a>
                            <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Back to Matches
                            </a>
                        </div>
                    </div>

                    <!-- Match Status & Score -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-center">
                            @if($match['status'] === 'completed')
                                <span class="badge bg-success me-2">Completed</span>
                                <span class="h3 mb-0">{{ $match['home_score'] }} - {{ $match['away_score'] }}</span>
                            @elseif($match['status'] === 'live')
                                <span class="badge bg-danger me-2">LIVE</span>
                                <span class="h3 mb-0">{{ $match['home_score'] ?? 0 }} - {{ $match['away_score'] ?? 0 }}</span>
                            @elseif($match['status'] === 'upcoming')
                                <span class="badge bg-warning me-2">Upcoming</span>
                                <span class="h3 mb-0">VS</span>
                            @else
                                <span class="badge bg-secondary me-2">{{ ucfirst($match['status']) }}</span>
                                <span class="h3 mb-0">VS</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Match Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-information-line me-2"></i>Match Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Tournament</label>
                                    <p class="mb-0">{{ $match['tournament']['name'] }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Stage</label>
                                    <p class="mb-0">
                                        <span class="badge bg-label-secondary">{{ ucfirst(str_replace('_', ' ', $match['stage'])) }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Date & Time</label>
                                    <p class="mb-0">
                                        {{ \Carbon\Carbon::parse($match['scheduled_at'])->format('d M Y, H:i') }}
                                        <small class="text-muted d-block">
                                            ({{ \Carbon\Carbon::parse($match['scheduled_at'])->diffForHumans() }})
                                        </small>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Venue</label>
                                    <p class="mb-0">{{ $match['venue'] ?? 'Not specified' }}</p>
                                </div>
                            </div>

                            @if($match['notes'])
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes</label>
                                <p class="mb-0">{{ $match['notes'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Teams Comparison -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-team-line me-2"></i>Teams
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Home Team -->
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded">
                                        <div class="avatar avatar-lg mb-3">
                                            @if($match['home_team']['logo'])
                                                <img src="{{ asset('storage/' . $match['home_team']['logo']) }}"
                                                     alt="{{ $match['home_team']['name'] }}"
                                                     class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri-team-line"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="mb-2">{{ $match['home_team']['name'] }}</h5>
                                        <p class="text-muted mb-2">{{ $match['home_team']['city'] }}, {{ $match['home_team']['country'] ?? 'N/A' }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-label-primary">{{ number_format($match['home_team']['rating'], 1) }}</span>
                                        </div>
                                        @if($match['status'] === 'completed' || $match['status'] === 'live')
                                            <div class="h4 text-success mb-0">{{ $match['home_score'] ?? 0 }}</div>
                                        @endif
                                    </div>
                                </div>

                                <!-- VS -->
                                <div class="col-md-12 text-center my-3">
                                    <span class="h2 text-muted">VS</span>
                                </div>

                                <!-- Away Team -->
                                <div class="col-md-6">
                                    <div class="text-center p-3 border rounded">
                                        <div class="avatar avatar-lg mb-3">
                                            @if($match['away_team']['logo'])
                                                <img src="{{ asset('storage/' . $match['away_team']['logo']) }}"
                                                     alt="{{ $match['away_team']['name'] }}"
                                                     class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri-team-line"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <h5 class="mb-2">{{ $match['away_team']['name'] }}</h5>
                                        <p class="text-muted mb-2">{{ $match['away_team']['city'] }}, {{ $match['away_team']['country'] ?? 'N/A' }}</p>
                                        <div class="mb-2">
                                            <span class="badge bg-label-primary">{{ number_format($match['away_team']['rating'], 1) }}</span>
                                        </div>
                                        @if($match['status'] === 'completed' || $match['status'] === 'live')
                                            <div class="h4 text-success mb-0">{{ $match['away_score'] ?? 0 }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Match Events -->
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">
                                <i class="ri-list-check-2 me-2"></i>Match Events
                            </h5>
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                                <i class="ri-add-line me-2"></i>Add Event
                            </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if(isset($match['events']) && count($match['events']) > 0)
                                <div class="timeline">
                                    @foreach($match['events'] as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-label-{{ $event['type'] === 'goal' ? 'success' : ($event['type'] === 'card' ? 'warning' : 'info') }}">
                                            @if($event['type'] === 'goal')
                                                <i class="ri-football-line"></i>
                                            @elseif($event['type'] === 'card')
                                                <i class="ri-error-warning-line"></i>
                                            @else
                                                <i class="ri-information-line"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">
                                                        @if($event['type'] === 'goal')
                                                            Goal by {{ $event['player']['name'] }}
                                                        @elseif($event['type'] === 'card')
                                                            {{ ucfirst($event['card_type']) }} card for {{ $event['player']['name'] }}
                                                        @else
                                                            {{ ucfirst($event['type']) }} - {{ $event['player']['name'] }}
                                                        @endif
                                                    </h6>
                                                    <small class="text-muted">
                                                        {{ $event['minute'] }}' - {{ $event['team']['name'] }}
                                                    </small>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="deleteEvent({{ $event['id'] }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-list-check-2 ri-3x text-muted mb-2"></i>
                                    <h6 class="text-muted">No events recorded yet</h6>
                                    <p class="text-muted mb-0">Events will appear here once they are added during the match</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-md-4">
                    <!-- Match Status -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-list-check-2 me-2"></i>Match Status
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            @if($match['status'] === 'completed')
                                <span class="badge bg-success fs-6">Completed</span>
                                <div class="mt-3">
                                    <h4 class="text-success">{{ $match['home_score'] }} - {{ $match['away_score'] }}</h4>
                                    <small class="text-muted">Final Score</small>
                                </div>
                            @elseif($match['status'] === 'live')
                                <span class="badge bg-danger fs-6">LIVE</span>
                                <div class="mt-3">
                                    <h4 class="text-danger">{{ $match['home_score'] ?? 0 }} - {{ $match['away_score'] ?? 0 }}</h4>
                                    <small class="text-muted">Current Score</small>
                                </div>
                            @elseif($match['status'] === 'upcoming')
                                <span class="badge bg-warning fs-6">Upcoming</span>
                                <div class="mt-3">
                                    <h4 class="text-warning">VS</h4>
                                    <small class="text-muted">Not Started</small>
                                </div>
                            @else
                                <span class="badge bg-secondary fs-6">{{ ucfirst($match['status']) }}</span>
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
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                                <button type="button" class="btn btn-outline-success" onclick="showScoreModal()">
                                    <i class="ri-football-line me-2"></i>Update Score
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="showEventModal()">
                                    <i class="ri-add-line me-2"></i>Add Event
                                </button>
                                @endif
                                <a href="{{ route('fuma.tournaments.show', $match['tournament']['id']) }}" class="btn btn-outline-info">
                                    <i class="ri-trophy-line me-2"></i>View Tournament
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Match Statistics -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-bar-chart-line me-2"></i>Match Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ count($match['events'] ?? []) }}</span>
                                        <small class="text-muted">Total Events</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ count(array_filter($match['events'] ?? [], function($e) { return $e['type'] === 'goal'; })) }}</span>
                                        <small class="text-muted">Goals</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-warning mb-1">{{ count(array_filter($match['events'] ?? [], function($e) { return $e['type'] === 'card'; })) }}</span>
                                        <small class="text-muted">Cards</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-info mb-1">{{ count(array_filter($match['events'] ?? [], function($e) { return $e['type'] === 'substitution'; })) }}</span>
                                        <small class="text-muted">Substitutions</small>
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

<!-- Add Event Modal -->
@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
<div class="modal fade" id="addEventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Match Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fuma.matches.add-event', $match['id']) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="event_type" class="form-label">Event Type</label>
                            <select class="form-select" id="event_type" name="event_type" required>
                                <option value="">Select event type</option>
                                <option value="goal">Goal</option>
                                <option value="card">Card</option>
                                <option value="substitution">Substitution</option>
                                <option value="injury">Injury</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="minute" class="form-label">Minute</label>
                            <input type="number" class="form-control" id="minute" name="minute"
                                   min="1" max="120" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="team_id" class="form-label">Team</label>
                            <select class="form-select" id="team_id" name="team_id" required>
                                <option value="">Select team</option>
                                <option value="{{ $match['home_team']['id'] }}">{{ $match['home_team']['name'] }}</option>
                                <option value="{{ $match['away_team']['id'] }}">{{ $match['away_team']['name'] }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="player_id" class="form-label">Player</label>
                            <select class="form-select" id="player_id" name="player_id" required>
                                <option value="">Select player</option>
                                <!-- Players will be loaded dynamically -->
                            </select>
                        </div>
                    </div>

                    <div class="mb-3" id="card_type_group" style="display: none;">
                        <label for="card_type" class="form-label">Card Type</label>
                        <select class="form-select" id="card_type" name="card_type">
                            <option value="yellow">Yellow</option>
                            <option value="red">Red</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="2"
                                  placeholder="Additional details about the event..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Update Score Modal -->
@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
<div class="modal fade" id="scoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Match Score</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fuma.matches.update-score', $match['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6>{{ $match['home_team']['name'] }}</h6>
                            <input type="number" class="form-control form-control-lg text-center"
                                   id="home_score" name="home_score"
                                   value="{{ $match['home_score'] ?? 0 }}" min="0" required>
                        </div>
                        <div class="col-6">
                            <h6>{{ $match['away_team']['name'] }}</h6>
                            <input type="number" class="form-control form-control-lg text-center"
                                   id="away_score" name="away_score"
                                   value="{{ $match['away_score'] ?? 0 }}" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Score</button>
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
    // Show/hide card type field based on event type
    const eventType = document.getElementById('event_type');
    const cardTypeGroup = document.getElementById('card_type_group');

    eventType.addEventListener('change', function() {
        if (this.value === 'card') {
            cardTypeGroup.style.display = 'block';
        } else {
            cardTypeGroup.style.display = 'none';
        }
    });

    // Load players when team is selected
    const teamSelect = document.getElementById('team_id');
    const playerSelect = document.getElementById('player_id');

    teamSelect.addEventListener('change', function() {
        if (this.value) {
            loadTeamPlayers(this.value);
        } else {
            playerSelect.innerHTML = '<option value="">Select player</option>';
        }
    });
});

function loadTeamPlayers(teamId) {
    fetch(`/fuma/teams/${teamId}/players`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const playerSelect = document.getElementById('player_id');
                playerSelect.innerHTML = '<option value="">Select player</option>';

                data.data.forEach(player => {
                    const option = document.createElement('option');
                    option.value = player.id;
                    option.textContent = `${player.name} (${player.position})`;
                    playerSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading players:', error);
        });
}

function showScoreModal() {
    new bootstrap.Modal(document.getElementById('scoreModal')).show();
}

function showEventModal() {
    new bootstrap.Modal(document.getElementById('addEventModal')).show();
}

function deleteEvent(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        fetch(`/fuma/matches/events/${eventId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Failed to delete event');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting event');
        });
    }
}
</script>
@endpush

@extends('layouts.fuma')

@section('title', 'Matches')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Matches</h5>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                    <a href="{{ route('fuma.matches.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Schedule Match
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="tournament_filter" class="form-label">Tournament</label>
                            <select class="form-select" id="tournament_filter">
                                <option value="">All Tournaments</option>
                                <option value="1">Tournament A</option>
                                <option value="2">Tournament B</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter">
                                <option value="">All Status</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="live">Live</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="stage_filter" class="form-label">Stage</label>
                            <select class="form-select" id="stage_filter">
                                <option value="">All Stages</option>
                                <option value="group">Group Stage</option>
                                <option value="round_of_16">Round of 16</option>
                                <option value="quarter_final">Quarter Final</option>
                                <option value="semi_final">Semi Final</option>
                                <option value="final">Final</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search matches...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-secondary w-100" onclick="applyFilters()">
                                <i class="ri-search-line me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Matches List -->
                    <div class="row">
                        @forelse($matches['data'] as $match)
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Match Info -->
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1">{{ $match['tournament']['name'] }}</h6>
                                                <small class="text-muted">{{ $match['stage'] }}</small>
                                                @if($match['venue'])
                                                    <small class="text-muted">{{ $match['venue'] }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Teams -->
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
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
                                                    <span class="fw-semibold">{{ $match['home_team']['name'] }}</span>
                                                </div>

                                                <div class="text-center mx-3">
                                                    @if($match['status'] === 'completed')
                                                        <div class="d-flex align-items-center">
                                                            <span class="h4 mb-0 fw-bold text-primary">{{ $match['home_score'] }}</span>
                                                            <span class="mx-2">-</span>
                                                            <span class="h4 mb-0 fw-bold text-primary">{{ $match['away_score'] }}</span>
                                                        </div>
                                                    @elseif($match['status'] === 'live')
                                                        <div class="d-flex align-items-center">
                                                            <span class="h4 mb-0 fw-bold text-danger">{{ $match['home_score'] }}</span>
                                                            <span class="mx-2">-</span>
                                                            <span class="h4 mb-0 fw-bold text-danger">{{ $match['away_score'] }}</span>
                                                        </div>
                                                        <small class="text-danger">LIVE</small>
                                                    @else
                                                        <span class="text-muted">vs</span>
                                                    @endif
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <span class="fw-semibold me-2">{{ $match['away_team']['name'] }}</span>
                                                    <div class="avatar avatar-sm">
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
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Match Details -->
                                        <div class="col-md-3">
                                            <div class="d-flex flex-column">
                                                <div class="mb-1">
                                                    @switch($match['status'])
                                                        @case('scheduled')
                                                            <span class="badge bg-label-warning">Scheduled</span>
                                                            @break
                                                        @case('live')
                                                            <span class="badge bg-label-danger">Live</span>
                                                            @break
                                                        @case('completed')
                                                            <span class="badge bg-label-success">Completed</span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="badge bg-label-secondary">Cancelled</span>
                                                            @break
                                                        @default
                                                            <span class="badge bg-label-secondary">{{ $match['status'] }}</span>
                                                    @endswitch
                                                </div>
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    {{ \Carbon\Carbon::parse($match['scheduled_at'])->format('M d, Y') }}
                                                </small>
                                                <small class="text-muted">
                                                    <i class="ri-time-line me-1"></i>
                                                    {{ \Carbon\Carbon::parse($match['scheduled_at'])->format('H:i') }}
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Actions -->
                                        <div class="col-md-2">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown">
                                                    Actions
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('fuma.matches.show', $match['id']) }}">
                                                            <i class="ri-eye-line me-2"></i>View
                                                        </a>
                                                    </li>
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer'))
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('fuma.matches.edit', $match['id']) }}">
                                                            <i class="ri-edit-line me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                           onclick="showScoreModal({{ $match['id'] }}, '{{ $match['home_team']['name'] }}', '{{ $match['away_team']['name'] }}')">
                                                            <i class="ri-football-line me-2"></i>Update Score
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                           onclick="showEventModal({{ $match['id'] }})">
                                                            <i class="ri-add-line me-2"></i>Add Event
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer'))
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('fuma.matches.destroy', $match['id']) }}"
                                                              method="POST"
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="dropdown-item text-danger delete-confirm"
                                                                    data-item-name="match">
                                                                <i class="ri-delete-bin-line me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ri-football-line ri-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No matches found</h5>
                                <p class="text-muted mb-3">Schedule your first match to get started</p>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer') || auth()->user()->hasRole('committee'))
                                <a href="{{ route('fuma.matches.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-2"></i>Schedule First Match
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if(isset($matches['current_page']) && $matches['last_page'] > 1)
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Matches pagination">
                            <ul class="pagination">
                                @if($matches['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $matches['current_page'] - 1 }}">
                                            <i class="ri-arrow-left-line"></i>
                                        </a>
                                    </li>
                                @endif

                                @for($i = 1; $i <= $matches['last_page']; $i++)
                                    <li class="page-item {{ $i == $matches['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($matches['current_page'] < $matches['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $matches['current_page'] + 1 }}">
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Score Update Modal -->
<div class="modal fade" id="scoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Match Score</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="scoreForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Home Team</label>
                            <div class="d-flex align-items-center">
                                <span id="homeTeamName" class="fw-semibold"></span>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <label class="form-label">Score</label>
                            <div class="d-flex align-items-center justify-content-center">
                                <input type="number" class="form-control text-center" id="home_score" name="home_score" min="0" style="width: 60px;">
                                <span class="mx-2">-</span>
                                <input type="number" class="form-control text-center" id="away_score" name="away_score" min="0" style="width: 60px;">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Away Team</label>
                            <div class="d-flex align-items-center justify-content-end">
                                <span id="awayTeamName" class="fw-semibold"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Score</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Event Modal -->
<div class="modal fade" id="eventModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Match Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eventForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="event_type" class="form-label">Event Type</label>
                            <select class="form-select" id="event_type" name="type" required>
                                <option value="">Select event type</option>
                                <option value="goal">Goal</option>
                                <option value="yellow_card">Yellow Card</option>
                                <option value="red_card">Red Card</option>
                                <option value="substitution">Substitution</option>
                                <option value="injury">Injury</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="event_minute" class="form-label">Minute</label>
                            <input type="number" class="form-control" id="event_minute" name="minute" min="1" max="120" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="event_player" class="form-label">Player</label>
                        <select class="form-select" id="event_player" name="player_id">
                            <option value="">Select player</option>
                            <!-- Players will be populated dynamically -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="event_description" class="form-label">Description</label>
                        <textarea class="form-control" id="event_description" name="description" rows="3" placeholder="Describe the event..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const tournament = document.getElementById('tournament_filter').value;
    const status = document.getElementById('status_filter').value;
    const stage = document.getElementById('stage_filter').value;
    const search = document.getElementById('search').value;

    let url = new URL(window.location);
    if (tournament) url.searchParams.set('tournament_id', tournament);
    if (status) url.searchParams.set('status', status);
    if (stage) url.searchParams.set('stage', stage);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

function showScoreModal(matchId, homeTeam, awayTeam) {
    document.getElementById('homeTeamName').textContent = homeTeam;
    document.getElementById('awayTeamName').textContent = awayTeam;
    document.getElementById('scoreForm').action = `/fuma/matches/${matchId}/score`;

    // Reset form
    document.getElementById('scoreForm').reset();

    // Show modal
    new bootstrap.Modal(document.getElementById('scoreModal')).show();
}

function showEventModal(matchId) {
    document.getElementById('eventForm').action = `/fuma/matches/${matchId}/events`;

    // Reset form
    document.getElementById('eventForm').reset();

    // Show modal
    new bootstrap.Modal(document.getElementById('eventModal')).show();
}

// Auto-apply filters on enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endpush

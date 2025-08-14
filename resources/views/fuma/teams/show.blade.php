@extends('layouts.fuma')

@section('title', 'Team Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Team Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl me-3">
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
                            <div>
                                <h4 class="mb-1">{{ $team['name'] }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="ri-map-pin-line me-1"></i>
                                    {{ $team['city'] }}, {{ $team['country'] ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('fuma.teams.edit', $team['id']) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i>Edit Team
                            </a>
                            <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Back to Teams
                            </a>
                        </div>
                    </div>

                    <!-- Team Rating -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <span class="fw-semibold me-2">Rating:</span>
                            <span class="fw-bold me-2">{{ number_format($team['rating'], 1) }}</span>
                            <div class="progress flex-grow-1" style="width: 100px; height: 8px;">
                                <div class="progress-bar"
                                     style="width: {{ ($team['rating'] / 5) * 100 }}%"></div>
                            </div>
                            <span class="ms-2 text-muted">/ 5.0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Team Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-information-line me-2"></i>Team Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">City</label>
                                    <p class="mb-0">{{ $team['city'] }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Country</label>
                                    <p class="mb-0">{{ $team['country'] ?? 'Not specified' }}</p>
                                </div>
                            </div>

                            @if($team['manager_name'])
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Manager</label>
                                    <p class="mb-0">{{ $team['manager_name'] }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Manager Phone</label>
                                    <p class="mb-0">{{ $team['manager_phone'] ?? 'Not specified' }}</p>
                                </div>
                            </div>

                            @if($team['manager_email'])
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Manager Email</label>
                                <p class="mb-0">{{ $team['manager_email'] }}</p>
                            </div>
                            @endif
                            @endif

                            @if($team['description'])
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <p class="mb-0">{{ $team['description'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Players in Team -->
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h5 class="card-title m-0">
                                <i class="ri-user-line me-2"></i>Players ({{ count($team['players'] ?? []) }})
                            </h5>
                            @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                                <i class="ri-add-line me-2"></i>Add Player
                            </button>
                            @endif
                        </div>
                        <div class="card-body">
                            @if(isset($team['players']) && count($team['players']) > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Player</th>
                                                <th>Position</th>
                                                <th>Jersey</th>
                                                <th>Rating</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($team['players'] as $player)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-2">
                                                            @if($player['avatar'])
                                                                <img src="{{ asset('storage/' . $player['avatar']) }}"
                                                                     alt="{{ $player['name'] }}"
                                                                     class="rounded-circle">
                                                            @else
                                                                <span class="avatar-initial rounded bg-label-secondary">
                                                                    <i class="ri-user-line"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $player['name'] }}</h6>
                                                            <small class="text-muted">{{ $player['nationality'] ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-label-primary">{{ $player['position'] }}</span>
                                                </td>
                                                <td>
                                                    @if($player['jersey_number'])
                                                        <span class="badge bg-label-secondary">#{{ $player['jersey_number'] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="fw-bold me-2">{{ number_format($player['rating'], 1) }}</span>
                                                        <div class="progress flex-grow-1" style="width: 60px; height: 6px;">
                                                            <div class="progress-bar"
                                                                 style="width: {{ ($player['rating'] / 5) * 100 }}%"></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                type="button"
                                                                data-bs-toggle="dropdown">
                                                            Actions
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('fuma.players.show', $player['id']) }}">
                                                                    <i class="ri-eye-line me-2"></i>View Details
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('fuma.players.edit', $player['id']) }}">
                                                                    <i class="ri-edit-line me-2"></i>Edit Player
                                                                </a>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <form action="{{ route('fuma.teams.remove-player', $team['id']) }}"
                                                                      method="POST"
                                                                      class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" name="player_id" value="{{ $player['id'] }}">
                                                                    <button type="submit"
                                                                            class="dropdown-item text-danger"
                                                                            onclick="return confirm('Remove {{ $player['name'] }} from team?')">
                                                                        <i class="ri-user-unfollow-line me-2"></i>Remove from Team
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="ri-user-line ri-3x text-muted mb-2"></i>
                                    <h6 class="text-muted">No players in team yet</h6>
                                    <p class="text-muted mb-0">Players will appear here once they are added to the team</p>
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
                            @if(isset($team['recent_matches']) && count($team['recent_matches']) > 0)
                                @foreach($team['recent_matches'] as $match)
                                <div class="d-flex align-items-center justify-content-between p-3 border rounded mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="fw-semibold me-2">{{ $match['tournament']['name'] }}</span>
                                        <span class="badge bg-label-secondary">{{ $match['stage'] }}</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                @if($match['home_team']['logo'])
                                                    <img src="{{ asset('storage/' . $match['home_team']['logo']) }}"
                                                         alt="{{ $match['home_team']['name'] }}"
                                                         class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary">H</span>
                                                @endif
                                            </div>
                                            <span class="fw-semibold {{ $match['home_team']['id'] == $team['id'] ? 'text-primary' : '' }}">
                                                {{ $match['home_team']['name'] }}
                                            </span>
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
                                            <span class="fw-semibold me-2 {{ $match['away_team']['id'] == $team['id'] ? 'text-primary' : '' }}">
                                                {{ $match['away_team']['name'] }}
                                            </span>
                                            <div class="avatar avatar-sm">
                                                @if($match['away_team']['logo'])
                                                    <img src="{{ asset('storage/' . $match['away_team']['logo']) }}"
                                                         alt="{{ $match['away_team']['name'] }}"
                                                         class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary">A</span>
                                                @endif
                                            </div>
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
                                    <h6 class="text-muted">No matches played yet</h6>
                                    <p class="text-muted mb-0">Matches will appear here once the team participates in tournaments</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-md-4">
                    <!-- Team Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-bar-chart-line me-2"></i>Team Statistics
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ count($team['players'] ?? []) }}</span>
                                        <small class="text-muted">Players</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ count($team['recent_matches'] ?? []) }}</span>
                                        <small class="text-muted">Matches</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-warning mb-1">{{ $team['tournaments_count'] ?? 0 }}</span>
                                        <small class="text-muted">Tournaments</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-info mb-1">{{ $team['wins_count'] ?? 0 }}</span>
                                        <small class="text-muted">Wins</small>
                                    </div>
                                </div>
                            </div>
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
                                @if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
                                <a href="{{ route('fuma.players.create') }}?team_id={{ $team['id'] }}" class="btn btn-outline-success">
                                    <i class="ri-add-line me-2"></i>Add New Player
                                </a>
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPlayerModal">
                                    <i class="ri-user-add-line me-2"></i>Add Existing Player
                                </button>
                                @endif
                                <a href="{{ route('fuma.matches.index') }}?team_id={{ $team['id'] }}" class="btn btn-outline-info">
                                    <i class="ri-football-line me-2"></i>View Team Matches
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Team Achievements -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-medal-line me-2"></i>Achievements
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(isset($team['achievements']) && count($team['achievements']) > 0)
                                @foreach($team['achievements'] as $achievement)
                                <div class="d-flex align-items-center mb-2">
                                    <div class="avatar avatar-sm me-2">
                                        <span class="avatar-initial rounded bg-warning">
                                            <i class="ri-medal-fill"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <small class="fw-semibold">{{ $achievement['title'] }}</small>
                                        <br>
                                        <small class="text-muted">{{ $achievement['tournament'] }} - {{ $achievement['year'] }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-3">
                                    <i class="ri-medal-line ri-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No achievements yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Player Modal -->
@if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
<div class="modal fade" id="addPlayerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Player to Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fuma.teams.add-player', $team['id']) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="player_id" class="form-label">Select Player</label>
                        <select class="form-select" id="player_id" name="player_id" required>
                            <option value="">Choose a player...</option>
                            <!-- Players will be loaded dynamically -->
                        </select>
                        <small class="form-text text-muted">Only free agents (players without team) will be shown</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Player</button>
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
    // Load available players for the modal
    if (document.getElementById('addPlayerModal')) {
        fetch('/fuma/players?available_for_team={{ $team['id'] }}')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const select = document.getElementById('player_id');
                    data.data.forEach(player => {
                        const option = document.createElement('option');
                        option.value = player.id;
                        option.textContent = `${player.name} (${player.position}) - ${player.nationality || 'N/A'}`;
                        select.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading players:', error);
            });
    }
});
</script>
@endpush

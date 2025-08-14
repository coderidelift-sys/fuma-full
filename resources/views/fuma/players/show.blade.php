@extends('layouts.fuma')

@section('title', 'Player Details')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <!-- Player Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl me-3">
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
                                <h4 class="mb-1">{{ $player['name'] }}</h4>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-label-primary me-2">{{ $player['position'] }}</span>
                                    @if($player['team'])
                                        <i class="ri-team-line me-1"></i>{{ $player['team']['name'] }}
                                    @else
                                        <span class="text-muted">Free Agent</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('fuma.players.edit', $player['id']) }}" class="btn btn-primary">
                                <i class="ri-edit-line me-2"></i>Edit Player
                            </a>
                            <a href="{{ route('fuma.players.index') }}" class="btn btn-outline-secondary">
                                <i class="ri-arrow-left-line me-2"></i>Back to Players
                            </a>
                        </div>
                    </div>

                    <!-- Player Rating -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <span class="fw-semibold me-2">Rating:</span>
                            <span class="fw-bold me-2">{{ number_format($player['rating'], 1) }}</span>
                            <div class="progress flex-grow-1" style="width: 100px; height: 8px;">
                                <div class="progress-bar"
                                     style="width: {{ ($player['rating'] / 5) * 100 }}%"></div>
                            </div>
                            <span class="ms-2 text-muted">/ 5.0</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Player Info -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-information-line me-2"></i>Player Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Position</label>
                                    <p class="mb-0">
                                        <span class="badge bg-label-primary">{{ $player['position'] }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Jersey Number</label>
                                    <p class="mb-0">
                                        @if($player['jersey_number'])
                                            <span class="badge bg-label-secondary">#{{ $player['jersey_number'] }}</span>
                                        @else
                                            <span class="text-muted">Not assigned</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Birth Date</label>
                                    <p class="mb-0">
                                        @if($player['birth_date'])
                                            {{ \Carbon\Carbon::parse($player['birth_date'])->format('d M Y') }}
                                            <small class="text-muted d-block">
                                                ({{ \Carbon\Carbon::parse($player['birth_date'])->age }} years old)
                                            </small>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Nationality</label>
                                    <p class="mb-0">{{ $player['nationality'] ?? 'Not specified' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Height</label>
                                    <p class="mb-0">
                                        @if($player['height'])
                                            {{ $player['height'] }} cm
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Weight</label>
                                    <p class="mb-0">
                                        @if($player['weight'])
                                            {{ $player['weight'] }} kg
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($player['team'])
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Current Team</label>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        @if($player['team']['logo'])
                                            <img src="{{ asset('storage/' . $player['team']['logo']) }}"
                                                 alt="{{ $player['team']['name'] }}"
                                                 class="rounded-circle">
                                        @else
                                            <span class="avatar-initial rounded bg-label-secondary">
                                                <i class="ri-team-line"></i>
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $player['team']['name'] }}</h6>
                                        <small class="text-muted">{{ $player['team']['city'] }}, {{ $player['team']['country'] ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Player Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title m-0">
                                <i class="ri-bar-chart-line me-2"></i>Player Statistics
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ $player['stats']['goals_scored'] ?? 0 }}</span>
                                        <small class="text-muted">Goals Scored</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ $player['stats']['assists'] ?? 0 }}</span>
                                        <small class="text-muted">Assists</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-warning mb-1">{{ $player['stats']['yellow_cards'] ?? 0 }}</span>
                                        <small class="text-muted">Yellow Cards</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-danger mb-1">{{ $player['stats']['red_cards'] ?? 0 }}</span>
                                        <small class="text-muted">Red Cards</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-info mb-1">{{ $player['stats']['matches_played'] ?? 0 }}</span>
                                        <small class="text-muted">Matches Played</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ $player['stats']['minutes_played'] ?? 0 }}</span>
                                        <small class="text-muted">Minutes Played</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ $player['stats']['clean_sheets'] ?? 0 }}</span>
                                        <small class="text-muted">Clean Sheets</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-warning mb-1">{{ $player['stats']['saves'] ?? 0 }}</span>
                                        <small class="text-muted">Saves</small>
                                    </div>
                                </div>
                            </div>
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
                            @if(isset($player['recent_matches']) && count($player['recent_matches']) > 0)
                                @foreach($player['recent_matches'] as $match)
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
                                            <span class="fw-semibold {{ $match['home_team']['id'] == ($player['team']['id'] ?? 0) ? 'text-primary' : '' }}">
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
                                            <span class="fw-semibold me-2 {{ $match['away_team']['id'] == ($player['team']['id'] ?? 0) ? 'text-primary' : '' }}">
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
                                    <p class="text-muted mb-0">Matches will appear here once the player participates in tournaments</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-md-4">
                    <!-- Player Stats Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-bar-chart-line me-2"></i>Performance Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-success mb-1">{{ $player['stats']['goals_scored'] ?? 0 }}</span>
                                        <small class="text-muted">Goals</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-primary mb-1">{{ $player['stats']['assists'] ?? 0 }}</span>
                                        <small class="text-muted">Assists</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-info mb-1">{{ $player['stats']['matches_played'] ?? 0 }}</span>
                                        <small class="text-muted">Matches</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="d-flex flex-column">
                                        <span class="h4 text-warning mb-1">{{ $player['stats']['minutes_played'] ?? 0 }}</span>
                                        <small class="text-muted">Minutes</small>
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
                                <button type="button" class="btn btn-outline-warning" onclick="showStatsModal()">
                                    <i class="ri-bar-chart-line me-2"></i>Update Statistics
                                </button>
                                @endif
                                @if($player['team'])
                                <a href="{{ route('fuma.teams.show', $player['team']['id']) }}" class="btn btn-outline-info">
                                    <i class="ri-team-line me-2"></i>View Team
                                </a>
                                @endif
                                <a href="{{ route('fuma.matches.index') }}?player_id={{ $player['id'] }}" class="btn btn-outline-primary">
                                    <i class="ri-football-line me-2"></i>View Matches
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Player Achievements -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title m-0">
                                <i class="ri-medal-line me-2"></i>Achievements
                            </h6>
                        </div>
                        <div class="card-body">
                            @if(isset($player['achievements']) && count($player['achievements']) > 0)
                                @foreach($player['achievements'] as $achievement)
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

<!-- Update Stats Modal -->
@if(auth()->user()->hasRole('manager') || auth()->user()->hasRole('admin'))
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Statistics - {{ $player['name'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fuma.players.update-stats', $player['id']) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="goals_scored" class="form-label">Goals Scored</label>
                            <input type="number" class="form-control" id="goals_scored" name="goals_scored"
                                   value="{{ $player['stats']['goals_scored'] ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="assists" class="form-label">Assists</label>
                            <input type="number" class="form-control" id="assists" name="assists"
                                   value="{{ $player['stats']['assists'] ?? 0 }}" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="yellow_cards" class="form-label">Yellow Cards</label>
                            <input type="number" class="form-control" id="yellow_cards" name="yellow_cards"
                                   value="{{ $player['stats']['yellow_cards'] ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="red_cards" class="form-label">Red Cards</label>
                            <input type="number" class="form-control" id="red_cards" name="red_cards"
                                   value="{{ $player['stats']['red_cards'] ?? 0 }}" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="matches_played" class="form-label">Matches Played</label>
                            <input type="number" class="form-control" id="matches_played" name="matches_played"
                                   value="{{ $player['stats']['matches_played'] ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="minutes_played" class="form-label">Minutes Played</label>
                            <input type="number" class="form-control" id="minutes_played" name="minutes_played"
                                   value="{{ $player['stats']['minutes_played'] ?? 0 }}" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="clean_sheets" class="form-label">Clean Sheets</label>
                            <input type="number" class="form-control" id="clean_sheets" name="clean_sheets"
                                   value="{{ $player['stats']['clean_sheets'] ?? 0 }}" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="saves" class="form-label">Saves</label>
                            <input type="number" class="form-control" id="saves" name="saves"
                                   value="{{ $player['stats']['saves'] ?? 0 }}" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Statistics</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function showStatsModal() {
    new bootstrap.Modal(document.getElementById('statsModal')).show();
}
</script>
@endpush

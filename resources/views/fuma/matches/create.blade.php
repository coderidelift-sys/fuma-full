@extends('layouts.fuma')

@section('title', 'Schedule Match')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Schedule New Match</h5>
                    <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Matches
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.matches.store') }}" method="POST" class="fuma-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tournament_id" class="form-label">Tournament <span class="text-danger">*</span></label>
                                        <select class="form-select @error('tournament_id') is-invalid @enderror"
                                                id="tournament_id"
                                                name="tournament_id"
                                                required>
                                            <option value="">Select tournament</option>
                                            <option value="1">Tournament A</option>
                                            <option value="2">Tournament B</option>
                                        </select>
                                        @error('tournament_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="stage" class="form-label">Stage <span class="text-danger">*</span></label>
                                        <select class="form-select @error('stage') is-invalid @enderror"
                                                id="stage"
                                                name="stage"
                                                required>
                                            <option value="">Select stage</option>
                                            <option value="group">Group Stage</option>
                                            <option value="round_of_16">Round of 16</option>
                                            <option value="quarter_final">Quarter Final</option>
                                            <option value="semi_final">Semi Final</option>
                                            <option value="final">Final</option>
                                        </select>
                                        @error('stage')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="home_team_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                                        <select class="form-select @error('home_team_id') is-invalid @enderror"
                                                id="home_team_id"
                                                name="home_team_id"
                                                required>
                                            <option value="">Select home team</option>
                                            <option value="1">Team A</option>
                                            <option value="2">Team B</option>
                                            <option value="3">Team C</option>
                                        </select>
                                        @error('home_team_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="away_team_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                                        <select class="form-select @error('away_team_id') is-invalid @enderror"
                                                id="away_team_id"
                                                name="away_team_id"
                                                required>
                                            <option value="">Select away team</option>
                                            <option value="1">Team A</option>
                                            <option value="2">Team B</option>
                                            <option value="3">Team C</option>
                                        </select>
                                        @error('away_team_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="scheduled_at" class="form-label">Date & Time <span class="text-danger">*</span></label>
                                        <input type="datetime-local"
                                               class="form-control @error('scheduled_at') is-invalid @enderror"
                                               id="scheduled_at"
                                               name="scheduled_at"
                                               value="{{ old('scheduled_at') }}"
                                               min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}"
                                               required>
                                        @error('scheduled_at')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="venue" class="form-label">Venue</label>
                                        <input type="text"
                                               class="form-control @error('venue') is-invalid @enderror"
                                               id="venue"
                                               name="venue"
                                               value="{{ old('venue') }}"
                                               placeholder="e.g., Gelora Bung Karno Stadium">
                                        @error('venue')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror"
                                              id="notes"
                                              name="notes"
                                              rows="3"
                                              placeholder="Additional notes about the match...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="ri-information-line me-2"></i>Match Info
                                        </h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-trophy-line me-1"></i>
                                                    Tournament: <span id="tournament_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-list-check-2 me-1"></i>
                                                    Stage: <span id="stage_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    Date: <span id="date_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-time-line me-1"></i>
                                                    Time: <span id="time_info">-</span>
                                                </small>
                                            </li>
                                            <li>
                                                <small class="text-muted">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    Venue: <span id="venue_info">-</span>
                                                </small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="card bg-light mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="ri-team-line me-2"></i>Teams
                                        </h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-primary">H</span>
                                            </div>
                                            <span id="home_team_info">-</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-secondary">A</span>
                                            </div>
                                            <span id="away_team_info">-</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-close-line me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-2"></i>Schedule Match
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tournament = document.getElementById('tournament_id');
    const stage = document.getElementById('stage');
    const homeTeam = document.getElementById('home_team_id');
    const awayTeam = document.getElementById('away_team_id');
    const scheduledAt = document.getElementById('scheduled_at');
    const venue = document.getElementById('venue');

    // Update tournament info
    function updateTournamentInfo() {
        if (tournament.value) {
            const tournamentText = tournament.options[tournament.selectedIndex].text;
            document.getElementById('tournament_info').textContent = tournamentText;
        } else {
            document.getElementById('tournament_info').textContent = '-';
        }
    }

    // Update stage info
    function updateStageInfo() {
        if (stage.value) {
            const stageText = stage.options[stage.selectedIndex].text;
            document.getElementById('stage_info').textContent = stageText;
        } else {
            document.getElementById('stage_info').textContent = '-';
        }
    }

    // Update home team info
    function updateHomeTeamInfo() {
        if (homeTeam.value) {
            const teamText = homeTeam.options[homeTeam.selectedIndex].text;
            document.getElementById('home_team_info').textContent = teamText;
        } else {
            document.getElementById('home_team_info').textContent = '-';
        }
    }

    // Update away team info
    function updateAwayTeamInfo() {
        if (awayTeam.value) {
            const teamText = awayTeam.options[awayTeam.selectedIndex].text;
            document.getElementById('away_team_info').textContent = teamText;
        } else {
            document.getElementById('away_team_info').textContent = '-';
        }
    }

    // Update date and time info
    function updateDateTimeInfo() {
        if (scheduledAt.value) {
            const date = new Date(scheduledAt.value);
            document.getElementById('date_info').textContent = date.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('time_info').textContent = date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        } else {
            document.getElementById('date_info').textContent = '-';
            document.getElementById('time_info').textContent = '-';
        }
    }

    // Update venue info
    function updateVenueInfo() {
        if (venue.value) {
            document.getElementById('venue_info').textContent = venue.value;
        } else {
            document.getElementById('venue_info').textContent = 'TBD';
        }
    }

    // Prevent same team selection
    function validateTeamSelection() {
        if (homeTeam.value && awayTeam.value && homeTeam.value === awayTeam.value) {
            awayTeam.setCustomValidity('Away team must be different from home team');
        } else {
                            awayTeam.setCustomValidity('');
        }
    }

    tournament.addEventListener('change', updateTournamentInfo);
    stage.addEventListener('change', updateStageInfo);
    homeTeam.addEventListener('change', function() {
        updateHomeTeamInfo();
        validateTeamSelection();
    });
    awayTeam.addEventListener('change', function() {
        updateAwayTeamInfo();
        validateTeamSelection();
    });
    scheduledAt.addEventListener('change', updateDateTimeInfo);
    venue.addEventListener('input', updateVenueInfo);

    // Initialize
    updateTournamentInfo();
    updateStageInfo();
    updateHomeTeamInfo();
    updateAwayTeamInfo();
    updateDateTimeInfo();
    updateVenueInfo();
});
</script>
@endpush

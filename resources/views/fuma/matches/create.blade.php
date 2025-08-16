@extends('layouts.fuma')

@section('title', 'Schedule Match - Football Tournament Management')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <h1 class="fw-bold mb-2">Schedule New Match</h1>
                    <p class="mb-0">Schedule a match between two teams</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-arrow-left me-2"></i> Back to Matches
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('fuma.matches.store') }}" method="POST">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="tournament_id" class="form-label">Tournament <span class="text-danger">*</span></label>
                                <select class="form-select @error('tournament_id') is-invalid @enderror" id="tournament_id" name="tournament_id" required>
                                    <option value="">Select tournament</option>
                                    @foreach($tournaments as $tournament)
                                        <option value="{{ $tournament->id }}" {{ old('tournament_id') == $tournament->id ? 'selected' : '' }}>
                                            {{ $tournament->name }} ({{ ucfirst($tournament->status) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('tournament_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="home_team_id" class="form-label">Home Team <span class="text-danger">*</span></label>
                                    <select class="form-select @error('home_team_id') is-invalid @enderror" id="home_team_id" name="home_team_id" required>
                                        <option value="">Select home team</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ old('home_team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('home_team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="away_team_id" class="form-label">Away Team <span class="text-danger">*</span></label>
                                    <select class="form-select @error('away_team_id') is-invalid @enderror" id="away_team_id" name="away_team_id" required>
                                        <option value="">Select away team</option>
                                        @foreach($teams as $team)
                                            <option value="{{ $team->id }}" {{ old('away_team_id') == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('away_team_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stage" class="form-label">Stage <span class="text-danger">*</span></label>
                                    <select class="form-select @error('stage') is-invalid @enderror" id="stage" name="stage" required>
                                        <option value="">Select stage</option>
                                        <option value="group" {{ old('stage') === 'group' ? 'selected' : '' }}>Group Stage</option>
                                        <option value="round_of_16" {{ old('stage') === 'round_of_16' ? 'selected' : '' }}>Round of 16</option>
                                        <option value="quarter_final" {{ old('stage') === 'quarter_final' ? 'selected' : '' }}>Quarter Final</option>
                                        <option value="semi_final" {{ old('stage') === 'semi_final' ? 'selected' : '' }}>Semi Final</option>
                                        <option value="final" {{ old('stage') === 'final' ? 'selected' : '' }}>Final</option>
                                    </select>
                                    @error('stage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="venue" class="form-label">Venue</label>
                                    <input type="text" class="form-control @error('venue') is-invalid @enderror" 
                                           id="venue" name="venue" value="{{ old('venue') }}">
                                    @error('venue')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_date" class="form-label">Match Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           id="scheduled_date" name="scheduled_date" value="{{ old('scheduled_date') }}" required>
                                    @error('scheduled_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_time" class="form-label">Match Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('scheduled_at') is-invalid @enderror" 
                                           id="scheduled_time" name="scheduled_time" value="{{ old('scheduled_time') }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Schedule Match
                                </button>
                                <a href="{{ route('fuma.matches.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
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
// Prevent selecting the same team for both home and away
document.getElementById('home_team_id').addEventListener('change', function() {
    const homeTeamId = this.value;
    const awaySelect = document.getElementById('away_team_id');
    
    // Enable all options first
    Array.from(awaySelect.options).forEach(option => {
        option.disabled = false;
    });
    
    // Disable the selected home team in away team dropdown
    if (homeTeamId) {
        Array.from(awaySelect.options).forEach(option => {
            if (option.value === homeTeamId) {
                option.disabled = true;
            }
        });
        
        // If away team is same as home team, reset it
        if (awaySelect.value === homeTeamId) {
            awaySelect.value = '';
        }
    }
});

document.getElementById('away_team_id').addEventListener('change', function() {
    const awayTeamId = this.value;
    const homeSelect = document.getElementById('home_team_id');
    
    // Enable all options first
    Array.from(homeSelect.options).forEach(option => {
        option.disabled = false;
    });
    
    // Disable the selected away team in home team dropdown
    if (awayTeamId) {
        Array.from(homeSelect.options).forEach(option => {
            if (option.value === awayTeamId) {
                option.disabled = true;
            }
        });
        
        // If home team is same as away team, reset it
        if (homeSelect.value === awayTeamId) {
            homeSelect.value = '';
        }
    }
});

// Combine date and time before form submission
document.querySelector('form').addEventListener('submit', function(e) {
    const date = document.getElementById('scheduled_date').value;
    const time = document.getElementById('scheduled_time').value;
    
    if (date && time) {
        const scheduledAt = date + ' ' + time;
        
        // Create hidden input for scheduled_at
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'scheduled_at';
        hiddenInput.value = scheduledAt;
        
        this.appendChild(hiddenInput);
    }
});
</script>
@endpush
@extends('layouts.fuma')

@section('title', 'Create Tournament')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Create New Tournament</h5>
                    <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Tournaments
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.tournaments.store') }}" method="POST" enctype="multipart/form-data" class="fuma-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Tournament Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name') }}"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="max_teams" class="form-label">Maximum Teams <span class="text-danger">*</span></label>
                                        <select class="form-select @error('max_teams') is-invalid @enderror"
                                                id="max_teams"
                                                name="max_teams"
                                                required>
                                            <option value="">Select max teams</option>
                                            <option value="8" {{ old('max_teams') == '8' ? 'selected' : '' }}>8 Teams</option>
                                            <option value="16" {{ old('max_teams') == '16' ? 'selected' : '' }}>16 Teams</option>
                                            <option value="32" {{ old('max_teams') == '32' ? 'selected' : '' }}>32 Teams</option>
                                            <option value="64" {{ old('max_teams') == '64' ? 'selected' : '' }}>64 Teams</option>
                                        </select>
                                        @error('max_teams')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control @error('start_date') is-invalid @enderror"
                                               id="start_date"
                                               name="start_date"
                                               value="{{ old('start_date') }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                               required>
                                        @error('start_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                        <input type="date"
                                               class="form-control @error('end_date') is-invalid @enderror"
                                               id="end_date"
                                               name="end_date"
                                               value="{{ old('end_date') }}"
                                               min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                               required>
                                        @error('end_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
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

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Describe the tournament, rules, prizes, etc.">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Tournament Logo</label>
                                    <div class="file-upload">
                                        <input type="file"
                                               class="form-control @error('logo') is-invalid @enderror"
                                               id="logo"
                                               name="logo"
                                               accept="image/*">
                                        <div class="file-preview mt-2"></div>
                                    </div>
                                    @error('logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended: 512x512px, Max: 2MB, Formats: JPG, PNG, GIF
                                    </small>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="ri-information-line me-2"></i>Tournament Info
                                        </h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    Duration: <span id="duration_days">-</span> days
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-team-line me-1"></i>
                                                    Teams: <span id="teams_info">-</span>
                                                </small>
                                            </li>
                                            <li>
                                                <small class="text-muted">
                                                    <i class="ri-time-line me-1"></i>
                                                    Status: <span class="badge bg-label-warning">Upcoming</span>
                                                </small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('fuma.tournaments.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-close-line me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-2"></i>Create Tournament
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
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    const maxTeams = document.getElementById('max_teams');

    // Calculate duration when dates change
    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            document.getElementById('duration_days').textContent = diffDays;
        }
    }

    // Update teams info when max teams change
    function updateTeamsInfo() {
        if (maxTeams.value) {
            document.getElementById('teams_info').textContent = maxTeams.value + ' teams';
        }
    }

    // Set minimum end date based on start date
    startDate.addEventListener('change', function() {
        if (this.value) {
            const minEndDate = new Date(this.value);
            minEndDate.setDate(minEndDate.getDate() + 1);
            endDate.min = minEndDate.toISOString().split('T')[0];

            if (endDate.value && new Date(endDate.value) <= new Date(this.value)) {
                endDate.value = '';
            }
        }
        calculateDuration();
    });

    endDate.addEventListener('change', calculateDuration);
    maxTeams.addEventListener('change', updateTeamsInfo);

    // Initialize
    calculateDuration();
    updateTeamsInfo();
});
</script>
@endpush

@extends('layouts.fuma')

@section('title', 'Edit Tournament')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Edit Tournament: {{ $tournament['name'] }}</h5>
                    <a href="{{ route('fuma.tournaments.show', $tournament['id']) }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Tournament
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.tournaments.update', $tournament['id']) }}" method="POST" enctype="multipart/form-data" class="fuma-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Tournament Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('name') is-invalid @enderror"
                                               id="name"
                                               name="name"
                                               value="{{ old('name', $tournament['name']) }}"
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
                                            @for($i = 4; $i <= 32; $i += 4)
                                                <option value="{{ $i }}" {{ old('max_teams', $tournament['max_teams']) == $i ? 'selected' : '' }}>
                                                    {{ $i }} teams
                                                </option>
                                            @endfor
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
                                               value="{{ old('start_date', $tournament['start_date']) }}"
                                               min="{{ date('Y-m-d') }}"
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
                                               value="{{ old('end_date', $tournament['end_date']) }}"
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
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
                                           value="{{ old('venue', $tournament['venue']) }}"
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
                                              placeholder="Describe the tournament, rules, prizes, etc.">{{ old('description', $tournament['description']) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status"
                                            name="status">
                                        <option value="upcoming" {{ old('status', $tournament['status']) == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                        <option value="ongoing" {{ old('status', $tournament['status']) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="completed" {{ old('status', $tournament['status']) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $tournament['status']) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
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
                                        <div class="file-preview mt-2">
                                            @if($tournament['logo'])
                                                <img src="{{ asset('storage/' . $tournament['logo']) }}"
                                                     alt="Current logo"
                                                     class="img-thumbnail"
                                                     style="max-width: 150px;">
                                                <small class="d-block text-muted mt-1">Current logo</small>
                                            @endif
                                        </div>
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
                                                    Duration: <span id="duration_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-team-line me-1"></i>
                                                    Teams: <span id="teams_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    Venue: <span id="venue_info">-</span>
                                                </small>
                                            </li>
                                            <li>
                                                <small class="text-muted">
                                                    <i class="ri-list-check-2 me-1"></i>
                                                    Status: <span id="status_info">-</span>
                                                </small>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <!-- Current Teams Count -->
                                <div class="card bg-light mt-3">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="ri-team-line me-2"></i>Current Teams
                                        </h6>
                                        <div class="text-center">
                                            <span class="h3 text-primary">{{ count($tournament['teams'] ?? []) }}</span>
                                            <small class="d-block text-muted">of {{ $tournament['max_teams'] }} teams</small>
                                        </div>
                                        @if(count($tournament['teams'] ?? []) > 0)
                                            <div class="mt-3">
                                                <small class="text-muted">Teams:</small>
                                                <div class="mt-2">
                                                    @foreach(array_slice($tournament['teams'] ?? [], 0, 3) as $team)
                                                        <span class="badge bg-label-secondary me-1">{{ $team['name'] }}</span>
                                                    @endforeach
                                                    @if(count($tournament['teams'] ?? []) > 3)
                                                        <span class="badge bg-label-secondary">+{{ count($tournament['teams'] ?? []) - 3 }} more</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-outline-danger" onclick="confirmDelete()">
                                            <i class="ri-delete-bin-line me-2"></i>Delete Tournament
                                        </button>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('fuma.tournaments.show', $tournament['id']) }}" class="btn btn-outline-secondary">
                                            <i class="ri-close-line me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-2"></i>Update Tournament
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Tournament</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>"{{ $tournament['name'] }}"</strong>?</p>
                <p class="text-danger mb-0">
                    <i class="ri-error-warning-line me-1"></i>
                    This action cannot be undone. All associated matches and team assignments will also be removed.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('fuma.tournaments.destroy', $tournament['id']) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Tournament</button>
                </form>
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
    const venue = document.getElementById('venue');
    const status = document.getElementById('status');

    // Calculate duration when dates change
    function calculateDuration() {
        if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('duration_info').textContent = `${diffDays} days`;
        } else {
            document.getElementById('duration_info').textContent = '-';
        }
    }

    // Update teams info when max teams change
    function updateTeamsInfo() {
        if (maxTeams.value) {
            document.getElementById('teams_info').textContent = `${maxTeams.value} teams`;
        } else {
            document.getElementById('teams_info').textContent = '-';
        }
    }

    // Update venue info
    function updateVenueInfo() {
        if (venue.value) {
            document.getElementById('venue_info').textContent = venue.value;
        } else {
            document.getElementById('venue_info').textContent = 'Not specified';
        }
    }

    // Update status info
    function updateStatusInfo() {
        if (status.value) {
            const statusText = status.options[status.selectedIndex].text;
            document.getElementById('status_info').textContent = statusText;
        } else {
            document.getElementById('status_info').textContent = '-';
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
    venue.addEventListener('input', updateVenueInfo);
    status.addEventListener('change', updateStatusInfo);

    // Initialize
    calculateDuration();
    updateTeamsInfo();
    updateVenueInfo();
    updateStatusInfo();
});

function confirmDelete() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// File upload preview
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.querySelector('.file-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 150px;">
                <small class="d-block text-muted mt-1">New logo preview</small>
            `;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush

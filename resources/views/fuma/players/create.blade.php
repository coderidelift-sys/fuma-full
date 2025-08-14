@extends('layouts.fuma')

@section('title', 'Add Player')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Add New Player</h5>
                    <a href="{{ route('fuma.players.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Players
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.players.store') }}" method="POST" enctype="multipart/form-data" class="fuma-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Player Name <span class="text-danger">*</span></label>
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
                                        <label for="position" class="form-label">Position <span class="text-danger">*</span></label>
                                        <select class="form-select @error('position') is-invalid @enderror"
                                                id="position"
                                                name="position"
                                                required>
                                            <option value="">Select position</option>
                                            <option value="Forward" {{ old('position') == 'Forward' ? 'selected' : '' }}>Forward</option>
                                            <option value="Midfielder" {{ old('position') == 'Midfielder' ? 'selected' : '' }}>Midfielder</option>
                                            <option value="Defender" {{ old('position') == 'Defender' ? 'selected' : '' }}>Defender</option>
                                            <option value="Goalkeeper" {{ old('position') == 'Goalkeeper' ? 'selected' : '' }}>Goalkeeper</option>
                                        </select>
                                        @error('position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jersey_number" class="form-label">Jersey Number</label>
                                        <input type="text"
                                               class="form-control @error('jersey_number') is-invalid @enderror"
                                               id="jersey_number"
                                               name="jersey_number"
                                               value="{{ old('jersey_number') }}"
                                               placeholder="e.g., 10, 7, 9">
                                        @error('jersey_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="birth_date" class="form-label">Birth Date</label>
                                        <input type="date"
                                               class="form-control @error('birth_date') is-invalid @enderror"
                                               id="birth_date"
                                               name="birth_date"
                                               value="{{ old('birth_date') }}"
                                               max="{{ date('Y-m-d', strtotime('-16 years')) }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nationality" class="form-label">Nationality</label>
                                        <select class="form-select @error('nationality') is-invalid @enderror"
                                                id="nationality"
                                                name="nationality">
                                            <option value="">Select nationality</option>
                                            <option value="Indonesia" {{ old('nationality') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                            <option value="Malaysia" {{ old('nationality') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                            <option value="Singapore" {{ old('nationality') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                            <option value="Thailand" {{ old('nationality') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                            <option value="Vietnam" {{ old('nationality') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        </select>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="team_id" class="form-label">Team</label>
                                        <select class="form-select @error('team_id') is-invalid @enderror"
                                                id="team_id"
                                                name="team_id">
                                            <option value="">Select team (optional)</option>
                                            <option value="1">Team A</option>
                                            <option value="2">Team B</option>
                                            <option value="3">Team C</option>
                                        </select>
                                        @error('team_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="height" class="form-label">Height (cm)</label>
                                        <input type="number"
                                               class="form-control @error('height') is-invalid @enderror"
                                               id="height"
                                               name="height"
                                               value="{{ old('height') }}"
                                               min="100"
                                               max="250"
                                               placeholder="e.g., 175">
                                        @error('height')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input type="number"
                                               class="form-control @error('weight') is-invalid @enderror"
                                               id="weight"
                                               name="weight"
                                               value="{{ old('weight') }}"
                                               min="30"
                                               max="150"
                                               placeholder="e.g., 70">
                                        @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="avatar" class="form-label">Player Photo</label>
                                    <div class="file-upload">
                                        <input type="file"
                                               class="form-control @error('avatar') is-invalid @enderror"
                                               id="avatar"
                                               name="avatar"
                                               accept="image/*">
                                        <div class="file-preview mt-2"></div>
                                    </div>
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Recommended: 400x400px, Max: 2MB, Formats: JPG, PNG, GIF
                                    </small>
                                </div>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="ri-information-line me-2"></i>Player Info
                                        </h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-user-line me-1"></i>
                                                    Position: <span id="position_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-calendar-line me-1"></i>
                                                    Age: <span id="age_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    Nationality: <span id="nationality_info">-</span>
                                                </small>
                                            </li>
                                            <li>
                                                <small class="text-muted">
                                                    <i class="ri-team-line me-1"></i>
                                                    Team: <span id="team_info">-</span>
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
                                    <a href="{{ route('fuma.players.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-close-line me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-2"></i>Add Player
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
    const position = document.getElementById('position');
    const birthDate = document.getElementById('birth_date');
    const nationality = document.getElementById('nationality');
    const team = document.getElementById('team_id');

    // Update position info
    function updatePositionInfo() {
        if (position.value) {
            document.getElementById('position_info').textContent = position.value;
        } else {
            document.getElementById('position_info').textContent = '-';
        }
    }

    // Calculate and update age
    function updateAgeInfo() {
        if (birthDate.value) {
            const today = new Date();
            const birth = new Date(birthDate.value);
            let age = today.getFullYear() - birth.getFullYear();
            const monthDiff = today.getMonth() - birth.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
                age--;
            }

            document.getElementById('age_info').textContent = `${age} years`;
        } else {
            document.getElementById('age_info').textContent = '-';
        }
    }

    // Update nationality info
    function updateNationalityInfo() {
        if (nationality.value) {
            document.getElementById('nationality_info').textContent = nationality.value;
        } else {
            document.getElementById('nationality_info').textContent = '-';
        }
    }

    // Update team info
    function updateTeamInfo() {
        if (team.value) {
            const teamText = team.options[team.selectedIndex].text;
            document.getElementById('team_info').textContent = teamText;
        } else {
            document.getElementById('team_info').textContent = 'Free Agent';
        }
    }

    position.addEventListener('change', updatePositionInfo);
    birthDate.addEventListener('change', updateAgeInfo);
    nationality.addEventListener('change', updateNationalityInfo);
    team.addEventListener('change', updateTeamInfo);

    // Initialize
    updatePositionInfo();
    updateAgeInfo();
    updateNationalityInfo();
    updateTeamInfo();
});
</script>
@endpush

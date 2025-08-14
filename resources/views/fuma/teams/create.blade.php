@extends('layouts.fuma')

@section('title', 'Create Team')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Create New Team</h5>
                    <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-secondary">
                        <i class="ri-arrow-left-line me-2"></i>Back to Teams
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.teams.store') }}" method="POST" enctype="multipart/form-data" class="fuma-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Team Name <span class="text-danger">*</span></label>
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
                                        <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control @error('city') is-invalid @enderror"
                                               id="city"
                                               name="city"
                                               value="{{ old('city') }}"
                                               required>
                                        @error('city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="country" class="form-label">Country</label>
                                        <select class="form-select @error('country') is-invalid @enderror"
                                                id="country"
                                                name="country">
                                            <option value="">Select country</option>
                                            <option value="Indonesia" {{ old('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                                            <option value="Malaysia" {{ old('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                                            <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                                            <option value="Thailand" {{ old('country') == 'Thailand' ? 'selected' : '' }}>Thailand</option>
                                            <option value="Vietnam" {{ old('country') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                                        </select>
                                        @error('country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="manager_name" class="form-label">Manager Name</label>
                                        <input type="text"
                                               class="form-control @error('manager_name') is-invalid @enderror"
                                               id="manager_name"
                                               name="manager_name"
                                               value="{{ old('manager_name') }}">
                                        @error('manager_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="manager_phone" class="form-label">Manager Phone</label>
                                        <input type="text"
                                               class="form-control @error('manager_phone') is-invalid @enderror"
                                               id="manager_phone"
                                               name="manager_phone"
                                               value="{{ old('manager_phone') }}"
                                               placeholder="+62 xxx-xxxx-xxxx">
                                        @error('manager_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="manager_email" class="form-label">Manager Email</label>
                                        <input type="email"
                                               class="form-control @error('manager_email') is-invalid @enderror"
                                               id="manager_email"
                                               name="manager_email"
                                               value="{{ old('manager_email') }}">
                                        @error('manager_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Describe the team, history, achievements, etc.">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Team Logo</label>
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
                                            <i class="ri-information-line me-2"></i>Team Info
                                        </h6>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-map-pin-line me-1"></i>
                                                    Location: <span id="location_info">-</span>
                                                </small>
                                            </li>
                                            <li class="mb-2">
                                                <small class="text-muted">
                                                    <i class="ri-user-settings-line me-1"></i>
                                                    Manager: <span id="manager_info">-</span>
                                                </small>
                                            </li>
                                            <li>
                                                <small class="text-muted">
                                                    <i class="ri-team-line me-1"></i>
                                                    Players: <span class="badge bg-label-info">0</span>
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
                                    <a href="{{ route('fuma.teams.index') }}" class="btn btn-outline-secondary">
                                        <i class="ri-close-line me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-save-line me-2"></i>Create Team
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
    const city = document.getElementById('city');
    const country = document.getElementById('country');
    const managerName = document.getElementById('manager_name');

    // Update location info when city/country change
    function updateLocationInfo() {
        const cityVal = city.value;
        const countryVal = country.value;

        if (cityVal && countryVal) {
            document.getElementById('location_info').textContent = `${cityVal}, ${countryVal}`;
        } else if (cityVal) {
            document.getElementById('location_info').textContent = cityVal;
        } else {
            document.getElementById('location_info').textContent = '-';
        }
    }

    // Update manager info when manager name changes
    function updateManagerInfo() {
        if (managerName.value) {
            document.getElementById('manager_info').textContent = managerName.value;
        } else {
            document.getElementById('manager_info').textContent = 'Not specified';
        }
    }

    city.addEventListener('input', updateLocationInfo);
    country.addEventListener('change', updateLocationInfo);
    managerName.addEventListener('input', updateManagerInfo);

    // Initialize
    updateLocationInfo();
    updateManagerInfo();
});
</script>
@endpush

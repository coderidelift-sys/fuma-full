@extends('layouts.fuma')

@section('title', 'My Profile')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-4 mb-4">
            <!-- Profile Card -->
            <div class="card">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mb-3">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="rounded-circle">
                        @else
                            <span class="avatar-initial rounded bg-label-secondary">
                                <i class="ri-user-line"></i>
                            </span>
                        @endif
                    </div>

                    <h5 class="card-title mb-1">{{ auth()->user()->name }}</h5>
                    <p class="text-muted mb-3">
                        {{ auth()->user()->roles->first()->display_name ?? 'User' }}
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('avatar').click()">
                            <i class="ri-camera-line me-2"></i>Change Photo
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="card-title m-0">
                        <i class="ri-information-line me-2"></i>Account Info
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Member Since</small>
                        <small>{{ auth()->user()->created_at->format('M Y') }}</small>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Last Login</small>
                        <small>{{ auth()->user()->last_login_at ? \Carbon\Carbon::parse(auth()->user()->last_login_at)->diffForHumans() : 'Never' }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Status</small>
                        <span class="badge bg-label-success">Active</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Profile Form -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title m-0">
                        <i class="ri-user-settings-line me-2"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('fuma.profile.update') }}" method="POST" enctype="multipart/form-data" class="fuma-form">
                        @csrf
                        @method('PUT')

                        <!-- Hidden avatar input -->
                        <input type="file" id="avatar" name="avatar" accept="image/*" class="d-none">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="whatsapp" class="form-label">WhatsApp</label>
                                <input type="text"
                                       class="form-control @error('whatsapp') is-invalid @enderror"
                                       id="whatsapp"
                                       name="whatsapp"
                                       value="{{ old('whatsapp', auth()->user()->whatsapp) }}"
                                       placeholder="+62 xxx-xxxx-xxxx">
                                @error('whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       id="phone"
                                       name="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       placeholder="+62 xxx-xxxx-xxxx">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Required if changing password</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password"
                                       class="form-control @error('new_password') is-invalid @enderror"
                                       id="new_password"
                                       name="new_password">
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Leave blank to keep current password</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password"
                                       class="form-control @error('new_password_confirmation') is-invalid @enderror"
                                       id="new_password_confirmation"
                                       name="new_password_confirmation">
                                @error('new_password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ri-save-line me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Role Information -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title m-0">
                        <i class="ri-shield-user-line me-2"></i>Role & Permissions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Current Role</h6>
                            <p class="mb-2">
                                <span class="badge bg-label-primary">
                                    {{ auth()->user()->roles->first()->display_name ?? 'User' }}
                                </span>
                            </p>
                            <small class="text-muted">
                                {{ auth()->user()->roles->first()->description ?? 'No description available' }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <h6>Permissions</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @if(auth()->user()->hasRole('admin'))
                                    <span class="badge bg-label-success">Full Access</span>
                                    <span class="badge bg-label-primary">User Management</span>
                                    <span class="badge bg-label-info">System Settings</span>
                                @elseif(auth()->user()->hasRole('organizer'))
                                    <span class="badge bg-label-primary">Tournament Management</span>
                                    <span class="badge bg-label-info">Team Management</span>
                                    <span class="badge bg-label-warning">Committee Management</span>
                                @elseif(auth()->user()->hasRole('manager'))
                                    <span class="badge bg-label-info">Team Management</span>
                                    <span class="badge bg-label-warning">Player Management</span>
                                @elseif(auth()->user()->hasRole('committee'))
                                    <span class="badge bg-label-warning">Match Management</span>
                                    <span class="badge bg-label-info">Statistics</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.querySelector('.avatar img');

    // Handle avatar change
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Preview image
            const reader = new FileReader();
            reader.onload = function(e) {
                if (avatarPreview) {
                    avatarPreview.src = e.target.result;
                } else {
                    // Create new image if doesn't exist
                    const newImg = document.createElement('img');
                    newImg.src = e.target.result;
                    newImg.alt = '{{ auth()->user()->name }}';
                    newImg.className = 'rounded-circle';

                    const avatarDiv = document.querySelector('.avatar');
                    avatarDiv.innerHTML = '';
                    avatarDiv.appendChild(newImg);
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Password validation
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    function validatePassword() {
        if (newPassword.value && confirmPassword.value) {
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Passwords do not match');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
    }

    newPassword.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);
});
</script>
@endpush

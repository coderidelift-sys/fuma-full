@extends('layouts.app')
@section('title', 'Profile')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-2 gap-lg-0" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="account-tab" data-bs-toggle="pill" href="#account" role="tab"
                                aria-controls="account" aria-selected="true">
                                <i class="ri-group-line me-2"></i>Account
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="security-tab" data-bs-toggle="pill" href="#security" role="tab"
                                aria-controls="security" aria-selected="false">
                                <i class="ri-lock-line me-2"></i>Security
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="myTabContent">
                    <!-- Account Tab Content -->
                    <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                        <div class="card mb-6">
                            <form id="formAccountSettings" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="card-body">
                                    <div class="d-flex align-items-start align-items-sm-center gap-6">
                                        <img src="{{ $user->avatarUrl }}" alt="user-avatar"
                                            class="d-block w-px-100 h-px-100 rounded-4" id="uploadedAvatar" />
                                        <div class="button-wrapper">
                                            <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Upload new photo</span>
                                                <i class="ri-upload-2-line d-block d-sm-none"></i>
                                                <input type="file" id="upload" class="account-file-input" hidden
                                                    accept="image/png, image/jpeg" name="file_avatar"/>
                                            </label>
                                            <button type="button" class="btn btn-outline-danger account-image-reset mb-4">
                                                <i class="ri-refresh-line d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                            </button>
                                            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row mt-1 g-5">
                                        <!-- First Name -->
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input class="form-control" type="text" id="name" name="name"
                                                    value="{{ old('name', $user->name) }}" autofocus />
                                                <label for="name"> Name</label>
                                            </div>
                                        </div>
                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <div class="form-floating form-floating-outline">
                                                <input class="form-control" type="text" id="email" name="email"
                                                    value="{{ old('name', $user->email) }}"
                                                    placeholder="{{ old('name', $user->email) }}" />
                                                <label for="email">E-mail</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security Tab Content -->
                    <div class="tab-pane fade" id="security" role="tabpanel" aria-labelledby="security-tab">
                        <div class="card mb-6">
                            <h5 class="card-header">Change Password</h5>
                            <div class="card-body pt-1">
                                <form id="formUpdatePassword" action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="mb-5 col-md-6 form-password-toggle">
                                            <div class="input-group input-group-merge">
                                                <div class="form-floating form-floating-outline">
                                                    <input class="form-control" type="password" name="current_password"
                                                        id="current_password"
                                                        placeholder="{!! passwordPlainText() !!}" />
                                                    <label for="current_password">Current Password</label>
                                                </div>
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="ri-eye-off-line"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-5 mb-6">
                                        <div class="col-md-6 form-password-toggle">
                                            <div class="input-group input-group-merge">
                                                <div class="form-floating form-floating-outline">
                                                    <input class="form-control" type="password" id="password"
                                                        name="password"
                                                        placeholder="{!! passwordPlainText() !!}" />
                                                    <label for="password">New Password</label>
                                                </div>
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="ri-eye-off-line"></i></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-password-toggle">
                                            <div class="input-group input-group-merge">
                                                <div class="form-floating form-floating-outline">
                                                    <input class="form-control" type="password"
                                                        id="password_confirmation" name="password_confirmation"
                                                        placeholder="{!! passwordPlainText() !!}" />
                                                    <label for="password_confirmation">Confirm New Password</label>
                                                </div>
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="ri-eye-off-line"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                                    </div>
                                </form>
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
        const defaultImagePath = "{{ $user->avatarUrl }}";
    </script>
    @vite('resources/js/profile/script.js')
@endpush

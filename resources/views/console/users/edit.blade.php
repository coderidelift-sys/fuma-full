@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">

            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">
                    <span class="fw-normal">Edit User</span>
                </h5>
            </div>

            <div class="card-body">
                <div class="offcanvas-body mx-0 flex-grow-0 h-100 mt-2">

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <h4 class="alert-heading d-flex align-items-center">
                                <span class="alert-icon rounded">
                                    <i class="ri-error-warning-line ri-22px"></i>
                                </span>
                                Something went wrong!
                            </h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form class="edit-user pt-0" id="editUserForm" method="POST" onsubmit="return false"
                        action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-floating form-floating-outline mb-5 mt-2">
                            <input type="text"
                                class="form-control @error('name')
                                is-invalid
                            @enderror"
                                id="edit-user-name" placeholder="John Doe" name="name" aria-label="John Doe"
                                value="{{ old('name', $user->name) }}" />
                            <label for="edit-user-name">Name</label>

                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="text" id="edit-user-email"
                                class="form-control @error('email')
                                is-invalid
                            @enderror"
                                placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email"
                                value="{{ old('email', $user->email) }}" />
                            <label for="edit-user-email">Email</label>

                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <select id="user-role"
                                class="form-select @error('role_id')
                                is-invalid
                            @enderror"
                                name="role_id">
                                <option value="">Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @if (old('role_id', $user->role_id) == $role->id) selected @endif>
                                        {{ $role->name }}</option>
                                @endforeach
                            </select>
                            <label for="user-role">User Role</label>

                            @error('role_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="password" id="edit-user-password"
                                class="form-control @error('password')
                                is-invalid
                            @enderror"
                                placeholder="{!! passwordPlainText() !!}" aria-label="{!! passwordPlainText() !!}" name="password"
                                value="" />
                            <label for="edit-user-password">Password</label>

                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating form-floating-outline mb-5">
                            <input type="password" id="edit-user-password-confirm"
                                class="form-control @error('password_confirmation')
                                is-invalid
                            @enderror"
                                placeholder="{!! passwordPlainText() !!}" aria-label="{!! passwordPlainText() !!}"
                                name="password_confirmation" />
                            <label for="edit-user-password-confirm">Confirm Password</label>

                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/console/users/edit_script.js')
@endpush

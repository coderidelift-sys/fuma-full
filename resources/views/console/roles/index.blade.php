@extends('layouts.app')
@section('title', 'Roles')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <h4 class="mb-1">Roles List</h4>
        <p class="mb-6">
            A role provided access to predefined menus and features so that depending on assigned role an
            administrator can have access to what user needs.
        </p>

        @if ($errors->any())
            <div class="row">
                <div class="col-xl-4 col-lg-6 col-md-6">
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
                </div>
            </div>
        @endif

        <div class="row g-6">
            @foreach ($roles as $role)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <p class="mb-0">Total {{ $role->users_count }} users</p>
                                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                                    @foreach ($role->users as $index => $user)
                                        @if ($index < 3)
                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                                                title="{{ $user->name }}" class="avatar pull-up">
                                                <img class="rounded-circle"
                                                    src="{{ $user->avatar ? asset($user->avatar_url) : asset('/materialize/assets/img/avatars/1.png') }}"
                                                    alt="Avatar" />
                                            </li>
                                        @endif
                                    @endforeach

                                    @if ($role->users->count() > 3)
                                        <li class="avatar">
                                            <span class="avatar-initial rounded-circle pull-up bg-lighter text-body"
                                                data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                title="{{ $role->users->count() - 3 }} more">
                                                +{{ $role->users->count() - 3 }}
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="role-heading">
                                    <h5 class="mb-1">{{ $role->name }}</h5>
                                    @if ($role->id != 1)
                                        <a href="javascript:;" data-role-id="{{ $role->id }}"
                                            data-role-name="{{ $role->name }}"
                                            data-role-permissions="{{ $role->permissions->pluck('id') }}"
                                            class="role-edit-modal text-black-50">
                                            <i class="ri-edit-box-line ri-20px"></i>
                                        </a>
                                        <a href="javascript:;delete-role" data-id="{{ $role->id }}"
                                            class="role-delete-modal text-black-50">
                                            <i class="ri-delete-bin-7-line ri-20px"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            @include('_partials._widgets.add_role')
        </div>

        @include('_partials._modals.roles.add_role')
    </div>
@endsection

@push('scripts')
    <script>
        const urlDeleteRole = "{{ route('roles.destroy', ':id') }}";
        const urlUpdateRole = "{{ route('roles.update', ':id') }}";
    </script>

    @vite('resources/js/console/roles/script.js')
@endpush

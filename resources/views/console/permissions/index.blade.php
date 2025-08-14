@extends('layouts.app')
@section('title', 'Permissions')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card">
            <div class="card-datatable table-responsive">
                {{ $dataTable->table(['class' => 'datatables-permissions table']) }}
            </div>
        </div>

        @include('_partials._modals.permissions.add')

        @include('_partials._modals.permissions.edit')
    </div>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}

    <script>
        var urlUpdatePermission = "{{ route('permissions.update', ':id') }}";
    </script>
    @vite(['resources/js/console/permissions/script.js']);
@endpush

@extends('layouts.fuma')

@section('title', 'Tournaments')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Tournaments</h5>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer'))
                    <a href="{{ route('fuma.tournaments.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Create Tournament
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter">
                                <option value="">All Status</option>
                                <option value="upcoming">Upcoming</option>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search tournaments...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-secondary w-100" onclick="applyFilters()">
                                <i class="ri-search-line me-2"></i>Apply Filters
                            </button>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                                <i class="ri-refresh-line me-2"></i>Clear
                            </button>
                        </div>
                    </div>

                    <!-- Tournaments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover fuma-datatable">
                            <thead>
                                <tr>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Teams</th>
                                    <th>Organizer</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tournaments['data'] as $tournament)
                                <tr>
                                    <td>
                                        @if($tournament['logo'])
                                            <img src="{{ asset('storage/' . $tournament['logo']) }}"
                                                 alt="{{ $tournament['name'] }}"
                                                 class="rounded-circle"
                                                 width="40" height="40">
                                        @else
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri-trophy-line"></i>
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0">{{ $tournament['name'] }}</h6>
                                            <small class="text-muted">{{ Str::limit($tournament['description'], 50) }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($tournament['status'])
                                            @case('upcoming')
                                                <span class="badge bg-label-warning">Upcoming</span>
                                                @break
                                            @case('ongoing')
                                                <span class="badge bg-label-primary">Ongoing</span>
                                                @break
                                            @case('completed')
                                                <span class="badge bg-label-success">Completed</span>
                                                @break
                                            @default
                                                <span class="badge bg-label-secondary">{{ $tournament['status'] }}</span>
                                        @endswitch
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($tournament['start_date'])->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($tournament['end_date'])->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $tournament['teams_count'] ?? 0 }}/{{ $tournament['max_teams'] }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                @if($tournament['organizer']['avatar'])
                                                    <img src="{{ asset('storage/' . $tournament['organizer']['avatar']) }}"
                                                         alt="{{ $tournament['organizer']['name'] }}"
                                                         class="rounded-circle">
                                                @else
                                                    <span class="avatar-initial rounded bg-label-secondary">
                                                        <i class="ri-user-line"></i>
                                                    </span>
                                                @endif
                                            </div>
                                            <span>{{ $tournament['organizer']['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.tournaments.show', $tournament['id']) }}">
                                                        <i class="ri-eye-line me-2"></i>View
                                                    </a>
                                                </li>
                                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('organizer'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.tournaments.edit', $tournament['id']) }}">
                                                        <i class="ri-edit-line me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.tournaments.standings', $tournament['id']) }}">
                                                        <i class="ri-list-check-2 me-2"></i>Standings
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('fuma.tournaments.destroy', $tournament['id']) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-item-name="{{ $tournament['name'] }}">
                                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-trophy-line ri-3x text-muted mb-2"></i>
                                            <h6 class="text-muted">No tournaments found</h6>
                                            <p class="text-muted mb-0">Create your first tournament to get started</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($tournaments['current_page']) && $tournaments['last_page'] > 1)
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Tournaments pagination">
                            <ul class="pagination">
                                @if($tournaments['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $tournaments['current_page'] - 1 }}">
                                            <i class="ri-arrow-left-line"></i>
                                        </a>
                                    </li>
                                @endif

                                @for($i = 1; $i <= $tournaments['last_page']; $i++)
                                    <li class="page-item {{ $i == $tournaments['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($tournaments['current_page'] < $tournaments['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $tournaments['current_page'] + 1 }}">
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const status = document.getElementById('status_filter').value;
    const search = document.getElementById('search').value;

    let url = new URL(window.location);
    if (status) url.searchParams.set('status', status);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

function clearFilters() {
    document.getElementById('status_filter').value = '';
    document.getElementById('search').value = '';
    window.location.href = window.location.pathname;
}

// Auto-apply filters on enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endpush

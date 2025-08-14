@extends('layouts.fuma')

@section('title', 'Teams')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Teams</h5>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                    <a href="{{ route('fuma.teams.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Create Team
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="city_filter" class="form-label">City</label>
                            <select class="form-select" id="city_filter">
                                <option value="">All Cities</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Surabaya">Surabaya</option>
                                <option value="Medan">Medan</option>
                                <option value="Semarang">Semarang</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="rating_filter" class="form-label">Min Rating</label>
                            <select class="form-select" id="rating_filter">
                                <option value="">Any Rating</option>
                                <option value="3.0">3.0+</option>
                                <option value="3.5">3.5+</option>
                                <option value="4.0">4.0+</option>
                                <option value="4.5">4.5+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" placeholder="Search teams...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-secondary w-100" onclick="applyFilters()">
                                <i class="ri-search-line me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Teams Grid -->
                    <div class="row">
                        @forelse($teams['data'] as $team)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-lg me-3">
                                            @if($team['logo'])
                                                <img src="{{ asset('storage/' . $team['logo']) }}"
                                                     alt="{{ $team['name'] }}"
                                                     class="rounded-circle">
                                            @else
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri-team-line"></i>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $team['name'] }}</h6>
                                            <small class="text-muted">{{ $team['city'] }}, {{ $team['country'] }}</small>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary"
                                                    type="button"
                                                    data-bs-toggle="dropdown">
                                                <i class="ri-more-2-fill"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.teams.show', $team['id']) }}">
                                                        <i class="ri-eye-line me-2"></i>View Details
                                                    </a>
                                                </li>
                                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.teams.edit', $team['id']) }}">
                                                        <i class="ri-edit-line me-2"></i>Edit Team
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('fuma.teams.destroy', $team['id']) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-item-name="team '{{ $team['name'] }}'">
                                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-primary">{{ number_format($team['rating'], 1) }}</span>
                                                <small class="text-muted">Rating</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-success">{{ $team['trophies_count'] }}</span>
                                                <small class="text-muted">Trophies</small>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-info">{{ $team['players_count'] ?? 0 }}</span>
                                                <small class="text-muted">Players</small>
                                            </div>
                                        </div>
                                    </div>

                                    @if($team['manager_name'])
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial rounded bg-label-info">
                                                <i class="ri-user-line"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted d-block">Manager</small>
                                            <span class="fw-semibold">{{ $team['manager_name'] }}</span>
                                        </div>
                                    </div>
                                    @endif

                                    <div class="d-grid">
                                        <a href="{{ route('fuma.teams.show', $team['id']) }}"
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="ri-eye-line me-2"></i>View Team
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ri-team-line ri-4x text-muted mb-3"></i>
                                <h5 class="text-muted">No teams found</h5>
                                <p class="text-muted mb-3">Create your first team to get started</p>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                <a href="{{ route('fuma.teams.create') }}" class="btn btn-primary">
                                    <i class="ri-add-line me-2"></i>Create First Team
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if(isset($teams['current_page']) && $teams['last_page'] > 1)
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Teams pagination">
                            <ul class="pagination">
                                @if($teams['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $teams['current_page'] - 1 }}">
                                            <i class="ri-arrow-left-line"></i>
                                        </a>
                                    </li>
                                @endif

                                @for($i = 1; $i <= $teams['last_page']; $i++)
                                    <li class="page-item {{ $i == $teams['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($teams['current_page'] < $teams['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $teams['current_page'] + 1 }}">
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
    const city = document.getElementById('city_filter').value;
    const rating = document.getElementById('rating_filter').value;
    const search = document.getElementById('search').value;

    let url = new URL(window.location);
    if (city) url.searchParams.set('city', city);
    if (rating) url.searchParams.set('min_rating', rating);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

// Auto-apply filters on enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endpush

@extends('layouts.fuma')

@section('title', 'All Teams')

@section('content')
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Teams</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createTeamModal">
                                <i class="fas fa-plus me-2"></i> Create Team
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Filter Section -->
        <div class="filter-card card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('fuma.teams') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <select name="city" id="city" class="form-select">
                                <option value="all">All Cities</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search teams..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Teams Grid -->
        <div class="row g-4">
            @forelse($teams as $team)
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            @if($team->logo)
                                <img src="{{ asset('storage/' . $team->logo) }}" alt="Team Logo" class="team-logo mb-3">
                            @else
                                <div class="team-logo mb-3 bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto">
                                    <i class="fas fa-shield-alt text-white fa-2x"></i>
                                </div>
                            @endif
                            
                            <h5 class="card-title">{{ $team->name }}</h5>
                            <p class="text-muted mb-3">{{ Str::limit($team->description, 60) }}</p>
                            
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="small text-muted">Players</div>
                                    <div class="fw-bold text-primary">{{ $team->players->count() }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Trophies</div>
                                    <div class="fw-bold text-warning">{{ $team->trophies_count ?? 0 }}</div>
                                </div>
                                <div class="col-4">
                                    <div class="small text-muted">Rating</div>
                                    <div class="fw-bold text-success">{{ number_format($team->rating ?? 0, 1) }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $team->city }}, {{ $team->country }}
                                </small>
                            </div>
                            
                            @if($team->manager_name)
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i> Manager: {{ $team->manager_name }}
                                    </small>
                                </div>
                            @endif
                            
                            <a href="{{ route('fuma.team-detail', $team->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i> View Team
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No teams found</h4>
                        <p class="text-muted">Try adjusting your search criteria.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($teams->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $teams->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

    <!-- Create Team Modal -->
    @auth
        @if(auth()->user()->hasAnyRole(['admin', 'manager']))
            <div class="modal fade" id="createTeamModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Team</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('api.teams.store') }}" method="POST" enctype="multipart/form-data" id="createTeamForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="team_name" class="form-label">Team Name</label>
                                        <input type="text" class="form-control" id="team_name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="team_description" class="form-label">Description</label>
                                        <textarea class="form-control" id="team_description" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" class="form-control" id="country" name="country">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="team_logo" class="form-label">Team Logo</label>
                                        <input type="file" class="form-control" id="team_logo" name="logo" accept="image/*">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="manager_name" class="form-label">Manager Name</label>
                                        <input type="text" class="form-control" id="manager_name" name="manager_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="manager_email" class="form-label">Manager Email</label>
                                        <input type="email" class="form-control" id="manager_email" name="manager_email">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="manager_phone" class="form-label">Manager Phone</label>
                                        <input type="text" class="form-control" id="manager_phone" name="manager_phone">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Team</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endauth
@endsection

@push('styles')
<style>
    .filter-card {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
@endpush

@push('scripts')
<script>
    // Handle team creation form
    document.getElementById('createTeamForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("api.teams.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('createTeamModal')).hide();
                location.reload();
            } else {
                alert('Error creating team: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating team');
        });
    });
</script>
@endpush
@extends('layouts.fuma')

@section('title', 'All Tournaments')

@section('content')
    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold mb-3">All Tournaments</h1>
                </div>
                <div class="col-md-6 text-md-end">
                    @auth
                        @if(auth()->user()->hasAnyRole(['admin', 'organizer']))
                            <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#createTournamentModal">
                                <i class="fas fa-plus me-2"></i> Create Tournament
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
                <form method="GET" action="{{ route('fuma.tournaments') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Search tournaments..." value="{{ request('search') }}">
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

        <!-- Tournaments Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Tournament</th>
                                <th>Date Range</th>
                                <th>Teams</th>
                                <th>Venue</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tournaments as $tournament)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($tournament->logo)
                                                <img src="{{ asset('storage/' . $tournament->logo) }}" alt="Tournament Logo" class="rounded-circle me-3" width="40" height="40">
                                            @else
                                                <div class="rounded-circle me-3 bg-primary d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-trophy text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $tournament->name }}</h6>
                                                <small class="text-muted">{{ Str::limit($tournament->description, 30) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div>{{ $tournament->start_date->format('M j, Y') }}</div>
                                            <small class="text-muted">to {{ $tournament->end_date->format('M j, Y') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">
                                            {{ $tournament->teams->count() }}/{{ $tournament->max_teams }}
                                        </span>
                                    </td>
                                    <td>{{ $tournament->venue ?? 'TBD' }}</td>
                                    <td>
                                        @if($tournament->status === 'ongoing')
                                            <span class="badge bg-success">Ongoing</span>
                                        @elseif($tournament->status === 'upcoming')
                                            <span class="badge bg-warning text-dark">Upcoming</span>
                                        @else
                                            <span class="badge bg-secondary">Completed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('fuma.tournament-detail', $tournament->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No tournaments found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($tournaments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $tournaments->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Tournament Modal -->
    @auth
        @if(auth()->user()->hasAnyRole(['admin', 'organizer']))
            <div class="modal fade" id="createTournamentModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Create New Tournament</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('api.tournaments.store') }}" method="POST" enctype="multipart/form-data" id="createTournamentForm">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Tournament Name</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="venue" class="form-label">Venue</label>
                                        <input type="text" class="form-control" id="venue" name="venue">
                                    </div>
                                    <div class="col-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="max_teams" class="form-label">Maximum Teams</label>
                                        <select class="form-select" id="max_teams" name="max_teams" required>
                                            <option value="8">8 Teams</option>
                                            <option value="16">16 Teams</option>
                                            <option value="20">20 Teams</option>
                                            <option value="32">32 Teams</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="logo" class="form-label">Tournament Logo</label>
                                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Tournament</button>
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
    
    .status-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .badge-active {
        background-color: #10b981;
        color: white;
    }
    
    .badge-upcoming {
        background-color: #f59e0b;
        color: white;
    }
    
    .badge-completed {
        background-color: #6b7280;
        color: white;
    }
    
    .action-btn {
        transition: all 0.3s ease;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@push('scripts')
<script>
    // Handle tournament creation form
    document.getElementById('createTournamentForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("api.tournaments.store") }}', {
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
                bootstrap.Modal.getInstance(document.getElementById('createTournamentModal')).hide();
                location.reload();
            } else {
                alert('Error creating tournament: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error creating tournament');
        });
    });
</script>
@endpush
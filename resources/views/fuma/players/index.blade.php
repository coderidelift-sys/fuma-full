@extends('layouts.fuma')

@section('title', 'Players')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title m-0 me-2">Players</h5>
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('organizer'))
                    <a href="{{ route('fuma.players.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Add Player
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <label for="position_filter" class="form-label">Position</label>
                            <select class="form-select" id="position_filter">
                                <option value="">All Positions</option>
                                <option value="Forward">Forward</option>
                                <option value="Midfielder">Midfielder</option>
                                <option value="Defender">Defender</option>
                                <option value="Goalkeeper">Goalkeeper</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="team_filter" class="form-label">Team</label>
                            <select class="form-select" id="team_filter">
                                <option value="">All Teams</option>
                                <option value="1">Team A</option>
                                <option value="2">Team B</option>
                                <option value="3">Team C</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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
                            <input type="text" class="form-control" id="search" placeholder="Search players...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="button" class="btn btn-secondary w-100" onclick="applyFilters()">
                                <i class="ri-search-line me-2"></i>Apply Filters
                            </button>
                        </div>
                    </div>

                    <!-- Players Table -->
                    <div class="table-responsive">
                        <table class="table table-hover fuma-datatable">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Team</th>
                                    <th>Rating</th>
                                    <th>Goals</th>
                                    <th>Assists</th>
                                    <th>Cards</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($players['data'] as $player)
                                <tr>
                                    <td>
                                        @if($player['avatar'])
                                            <img src="{{ asset('storage/' . $player['avatar']) }}"
                                                 alt="{{ $player['name'] }}"
                                                 class="rounded-circle"
                                                 width="40" height="40">
                                        @else
                                            <div class="avatar avatar-sm">
                                                <span class="avatar-initial rounded bg-label-secondary">
                                                    <i class="ri-user-line"></i>
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-0">{{ $player['name'] }}</h6>
                                            @if($player['jersey_number'])
                                                <small class="text-muted">#{{ $player['jersey_number'] }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @switch($player['position'])
                                            @case('Forward')
                                                <span class="badge bg-label-danger">Forward</span>
                                                @break
                                            @case('Midfielder')
                                                <span class="badge bg-label-primary">Midfielder</span>
                                                @break
                                            @case('Defender')
                                                <span class="badge bg-label-warning">Defender</span>
                                                @break
                                            @case('Goalkeeper')
                                                <span class="badge bg-label-success">Goalkeeper</span>
                                                @break
                                            @default
                                                <span class="badge bg-label-secondary">{{ $player['position'] }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($player['team'])
                                            <div class="d-flex align-items-center">
                                                @if($player['team']['logo'])
                                                    <img src="{{ asset('storage/' . $player['team']['logo']) }}"
                                                         alt="{{ $player['team']['name'] }}"
                                                         class="rounded-circle me-2"
                                                         width="24" height="24">
                                                @endif
                                                <span>{{ $player['team']['name'] }}</span>
                                            </div>
                                        @else
                                            <span class="text-muted">Free Agent</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold me-2">{{ number_format($player['rating'], 1) }}</span>
                                            <div class="progress flex-grow-1" style="height: 6px;">
                                                <div class="progress-bar"
                                                     style="width: {{ ($player['rating'] / 5) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-success">{{ $player['goals_scored'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-info">{{ $player['assists'] }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($player['yellow_cards'] > 0)
                                                <span class="badge bg-label-warning">{{ $player['yellow_cards'] }}</span>
                                            @endif
                                            @if($player['red_cards'] > 0)
                                                <span class="badge bg-label-danger">{{ $player['red_cards'] }}</span>
                                            @endif
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
                                                    <a class="dropdown-item" href="{{ route('fuma.players.show', $player['id']) }}">
                                                        <i class="ri-eye-line me-2"></i>View
                                                    </a>
                                                </li>
                                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager') || auth()->user()->hasRole('organizer'))
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('fuma.players.edit', $player['id']) }}">
                                                        <i class="ri-edit-line me-2"></i>Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#"
                                                       onclick="showStatsModal({{ $player['id'] }}, '{{ $player['name'] }}')">
                                                        <i class="ri-bar-chart-line me-2"></i>Update Stats
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('fuma.players.destroy', $player['id']) }}"
                                                          method="POST"
                                                          class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="dropdown-item text-danger delete-confirm"
                                                                data-item-name="player '{{ $player['name'] }}'">
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
                                    <td colspan="9" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="ri-user-line ri-3x text-muted mb-2"></i>
                                            <h6 class="text-muted">No players found</h6>
                                            <p class="text-muted mb-0">Add your first player to get started</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($players['current_page']) && $players['last_page'] > 1)
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Players pagination">
                            <ul class="pagination">
                                @if($players['current_page'] > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $players['current_page'] - 1 }}">
                                            <i class="ri-arrow-left-line"></i>
                                        </a>
                                    </li>
                                @endif

                                @for($i = 1; $i <= $players['last_page']; $i++)
                                    <li class="page-item {{ $i == $players['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($players['current_page'] < $players['last_page'])
                                    <li class="page-item">
                                        <a class="page-link" href="?page={{ $players['current_page'] + 1 }}">
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

<!-- Stats Update Modal -->
<div class="modal fade" id="statsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Player Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statsForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="goals_scored" class="form-label">Goals Scored</label>
                            <input type="number" class="form-control" id="goals_scored" name="goals_scored" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="assists" class="form-label">Assists</label>
                            <input type="number" class="form-control" id="assists" name="assists" min="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="yellow_cards" class="form-label">Yellow Cards</label>
                            <input type="number" class="form-control" id="yellow_cards" name="yellow_cards" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="red_cards" class="form-label">Red Cards</label>
                            <input type="number" class="form-control" id="red_cards" name="red_cards" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">Select rating</option>
                            <option value="1.0">1.0</option>
                            <option value="1.5">1.5</option>
                            <option value="2.0">2.0</option>
                            <option value="2.5">2.5</option>
                            <option value="3.0">3.0</option>
                            <option value="3.5">3.5</option>
                            <option value="4.0">4.0</option>
                            <option value="4.5">4.5</option>
                            <option value="5.0">5.0</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stats</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function applyFilters() {
    const position = document.getElementById('position_filter').value;
    const team = document.getElementById('team_filter').value;
    const rating = document.getElementById('rating_filter').value;
    const search = document.getElementById('search').value;

    let url = new URL(window.location);
    if (position) url.searchParams.set('position', position);
    if (team) url.searchParams.set('team_id', team);
    if (rating) url.searchParams.set('min_rating', rating);
    if (search) url.searchParams.set('search', search);

    window.location.href = url.toString();
}

function showStatsModal(playerId, playerName) {
    document.getElementById('statsModal').querySelector('.modal-title').textContent =
        `Update Statistics - ${playerName}`;
    document.getElementById('statsForm').action = `/fuma/players/${playerId}/stats`;

    // Reset form
    document.getElementById('statsForm').reset();

    // Show modal
    new bootstrap.Modal(document.getElementById('statsModal')).show();
}

// Auto-apply filters on enter key
document.getElementById('search').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        applyFilters();
    }
});
</script>
@endpush

@extends('layouts.fuma')

@section('title', $tournament->name . ' - Tournament Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        @if($tournament->logo)
                            <img src="{{ asset('storage/' . $tournament->logo) }}" alt="{{ $tournament->name }}" class="rounded-circle me-3" width="60" height="60">
                        @else
                            <img src="https://tse1.mm.bing.net/th/id/OIP.MaIk4N5rw51_K6gHkokGUgHaGl?pid=Api" alt="{{ $tournament->name }}" class="rounded-circle me-3" width="60" height="60">
                        @endif
                        <div>
                            <h1 class="fw-bold mb-1">{{ $tournament->name }}</h1>
                            <p class="mb-0">{{ $tournament->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex gap-2 justify-content-md-end">
                        @if(auth()->check())
                            <button class="btn btn-light me-2" data-bs-toggle="modal" data-bs-target="#editTournamentModal">
                                <i class="fas fa-edit me-2"></i> Edit
                            </button>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cog me-2"></i> Manage
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addTeamModal">
                                        <i class="fas fa-plus me-2"></i>Add Team
                                    </a></li>
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#scheduleMatchModal">
                                        <i class="fas fa-calendar-plus me-2"></i>Schedule Match
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $tournament->id }}')">
                                        <i class="fas fa-trash me-2"></i>Delete Tournament
                                    </a></li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="container">
        <ul class="nav nav-tabs mb-4" id="tournamentTabs">
            <li class="nav-item">
                <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button">
                    <i class="fas fa-info-circle me-2"></i> Overview
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="standings-tab" data-bs-toggle="tab" data-bs-target="#standings" type="button">
                    <i class="fas fa-trophy me-2"></i> Standings
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#matches" type="button">
                    <i class="fas fa-futbol me-2"></i> Matches
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="teams-tab" data-bs-toggle="tab" data-bs-target="#teams" type="button">
                    <i class="fas fa-users me-2"></i> Teams
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button">
                    <i class="fas fa-chart-bar me-2"></i> Statistics
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="tournamentTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Tournament Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Start Date:</strong> {{ $tournament->start_date->format('F d, Y') }}</p>
                                        <p><strong>End Date:</strong> {{ $tournament->end_date->format('F d, Y') }}</p>
                                        <p><strong>Venue:</strong> {{ $tournament->venue ?: 'TBD' }}</p>
                                        <p><strong>Max Teams:</strong> {{ $tournament->max_teams }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> 
                                            <span class="status-badge badge-{{ $tournament->status === 'ongoing' ? 'active' : $tournament->status }}">
                                                {{ ucfirst($tournament->status) }}
                                            </span>
                                        </p>
                                        <p><strong>Registered Teams:</strong> {{ $tournament->teams->count() }}</p>
                                        <p><strong>Organizer:</strong> {{ $tournament->organizer->name }}</p>
                                    </div>
                                </div>
                                @if($tournament->description)
                                    <hr>
                                    <p><strong>Description:</strong></p>
                                    <p>{{ $tournament->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Quick Stats</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="stats-number text-primary">{{ $tournament->teams->count() }}</div>
                                        <div class="stats-label">Teams</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stats-number text-primary">{{ $tournament->matches->count() }}</div>
                                        <div class="stats-label">Matches</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stats-number text-primary">{{ $tournament->matches->where('status', 'completed')->count() }}</div>
                                        <div class="stats-label">Completed</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stats-number text-primary">{{ $tournament->matches->where('status', 'scheduled')->count() }}</div>
                                        <div class="stats-label">Upcoming</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Standings Tab -->
            <div class="tab-pane fade" id="standings" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tournament Standings</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Pos</th>
                                        <th>Team</th>
                                        <th>MP</th>
                                        <th>W</th>
                                        <th>D</th>
                                        <th>L</th>
                                        <th>GF</th>
                                        <th>GA</th>
                                        <th>GD</th>
                                        <th>Pts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tournament->standings as $index => $team)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($team->logo)
                                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $team->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $team->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $team->pivot->matches_played }}</td>
                                        <td>{{ $team->pivot->wins }}</td>
                                        <td>{{ $team->pivot->draws }}</td>
                                        <td>{{ $team->pivot->losses }}</td>
                                        <td>{{ $team->pivot->goals_for }}</td>
                                        <td>{{ $team->pivot->goals_against }}</td>
                                        <td>{{ $team->pivot->goal_difference }}</td>
                                        <td><strong>{{ $team->pivot->points }}</strong></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <p class="text-muted">No teams registered yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matches Tab -->
            <div class="tab-pane fade" id="matches" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Tournament Matches</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Stage</th>
                                        <th>Home Team</th>
                                        <th>Score</th>
                                        <th>Away Team</th>
                                        <th>Venue</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tournament->matches as $match)
                                    <tr>
                                        <td>{{ $match->scheduled_at->format('M d, H:i') }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucwords(str_replace('_', ' ', $match->stage)) }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($match->homeTeam->logo)
                                                    <img src="{{ asset('storage/' . $match->homeTeam->logo) }}" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $match->homeTeam->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $match->homeTeam->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            @if($match->status === 'completed')
                                                <span class="match-score">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                            @elseif($match->status === 'live')
                                                <span class="badge bg-danger live-badge">LIVE</span>
                                            @else
                                                <span class="text-muted">vs</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($match->awayTeam->logo)
                                                    <img src="{{ asset('storage/' . $match->awayTeam->logo) }}" alt="{{ $match->awayTeam->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse2.mm.bing.net/th/id/OIP.lpgOZ4hPNpsQjk22cDyIegHaFf?pid=Api&P=0&h=180" alt="{{ $match->awayTeam->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $match->awayTeam->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $match->venue ?: 'TBD' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $match->status === 'live' ? 'danger' : ($match->status === 'completed' ? 'success' : 'warning') }}">
                                                {{ ucfirst($match->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.matches.show', $match) }}" class="btn btn-sm btn-outline-primary action-btn">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <p class="text-muted">No matches scheduled yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Tab -->
            <div class="tab-pane fade" id="teams" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Participating Teams</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Team</th>
                                        <th>City</th>
                                        <th>Manager</th>
                                        <th>Players</th>
                                        <th>Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tournament->teams as $team)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($team->logo)
                                                    <img src="{{ asset('storage/' . $team->logo) }}" alt="{{ $team->name }}" class="team-logo-sm me-2">
                                                @else
                                                    <img src="https://tse4.mm.bing.net/th/id/OIP.4eLwPDOhLiS4DWexutPB7AHaEK?pid=Api&P=0&h=180" alt="{{ $team->name }}" class="team-logo-sm me-2">
                                                @endif
                                                <span>{{ $team->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $team->city }}</td>
                                        <td>{{ $team->manager_name ?: 'TBD' }}</td>
                                        <td>{{ $team->players->count() }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-star text-warning me-1"></i>{{ number_format($team->rating, 1) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('fuma.teams.show', $team) }}" class="btn btn-sm btn-outline-primary">View Team</a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <p class="text-muted">No teams registered yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Tab -->
            <div class="tab-pane fade" id="stats" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Top Scorers</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @forelse($tournament->topScorers ?? [] as $player)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            @if($player->avatar)
                                                <img src="{{ asset('storage/' . $player->avatar) }}" alt="{{ $player->name }}" class="rounded-circle me-2" width="30" height="30">
                                            @else
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&size=30&background=2563eb&color=fff" alt="{{ $player->name }}" class="rounded-circle me-2" width="30" height="30">
                                            @endif
                                            <div>
                                                <strong>{{ $player->name }}</strong><br>
                                                <small class="text-muted">{{ $player->team->name ?? 'No Team' }}</small>
                                            </div>
                                        </div>
                                        <span class="badge bg-primary">{{ $player->goals_scored }} goals</span>
                                    </div>
                                    @empty
                                    <div class="text-center py-3">
                                        <p class="text-muted">No goals scored yet.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Match Statistics</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6 mb-3">
                                        <div class="stats-number text-primary">{{ $tournament->matches->sum('home_score') + $tournament->matches->sum('away_score') }}</div>
                                        <div class="stats-label">Total Goals</div>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <div class="stats-number text-primary">{{ $tournament->matches->where('status', 'completed')->count() }}</div>
                                        <div class="stats-label">Matches Played</div>
                                    </div>
                                    <div class="col-12">
                                        <div class="stats-number text-primary">
                                            {{ $tournament->matches->where('status', 'completed')->count() > 0 ? 
                                               number_format(($tournament->matches->sum('home_score') + $tournament->matches->sum('away_score')) / $tournament->matches->where('status', 'completed')->count(), 1) : 0 }}
                                        </div>
                                        <div class="stats-label">Goals per Match</div>
                                    </div>
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
function confirmDelete(tournamentId) {
    if (confirm('Are you sure you want to delete this tournament? This action cannot be undone.')) {
        // Create a form to submit DELETE request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/fuma/tournaments/${tournamentId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
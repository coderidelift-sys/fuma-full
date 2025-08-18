<!-- Complete Match Modal -->
<div class="modal fade" id="completeMatchModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-flag-checkered me-2"></i>Complete Match</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="completeMatchForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Home Team Score</label>
                            <input type="number" class="form-control" id="finalHomeScore" name="home_score"
                                min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Away Team Score</label>
                            <input type="number" class="form-control" id="finalAwayScore" name="away_score"
                                min="0" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Attendance</label>
                            <input type="number" class="form-control" id="attendance" name="attendance" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Weather</label>
                            <select class="form-select" id="weather" name="weather">
                                <option value="">Select weather</option>
                                <option value="Sunny">Sunny</option>
                                <option value="Cloudy">Cloudy</option>
                                <option value="Rainy">Rainy</option>
                                <option value="Windy">Windy</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Match Notes</label>
                        <textarea class="form-control" id="matchNotes" name="notes" rows="3"
                            placeholder="Additional notes about the match..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="completeMatchForm" class="btn btn-danger">Complete Match</button>
            </div>
        </div>
    </div>
</div>

<!-- Score Update Modal -->
<div class="modal fade" id="scoreUpdateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-futbol me-2"></i>Update Score</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scoreUpdateForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Home Team Score</label>
                            <input type="number" class="form-control" id="liveHomeScore" name="home_score"
                                min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Away Team Score</label>
                            <input type="number" class="form-control" id="liveAwayScore" name="away_score"
                                min="0" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Scorer</label>
                            <select class="form-select" id="scorerSelect" name="scorer_id">
                                <option value="">Select scorer</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Assist</label>
                            <select class="form-select" id="assistSelect" name="assist_id">
                                <option value="">Select assist</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Minute</label>
                        <input type="number" class="form-control" id="goalMinute" name="minute" min="1"
                            max="120" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="scoreUpdateForm" id="scoreUpdateBtn" class="btn btn-primary">Update
                    Score</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Match Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addEventForm">
                    <input type="hidden" name="match_id" value="{{ $match->id }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Event Type</label>
                            <select class="form-select" id="eventType" name="type" required>
                                <option value="">Select event type</option>
                                <option value="yellow_card">Yellow Card</option>
                                <option value="red_card">Red Card</option>
                                <option value="foul">Foul</option>
                                <option value="injury">Injury</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Team</label>
                            <select class="form-select" id="eventTeam" name="team_id" required>
                                <option value="">Select team</option>
                                <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}
                                </option>
                                <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Player</label>
                            <select class="form-select" id="eventPlayer" name="player_id">
                                <option value="">Select player (optional)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Minute</label>
                            <input type="number" class="form-control" id="eventMinute" name="minute"
                                min="1" max="120" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" id="eventDescription" name="description" rows="3"
                            placeholder="Describe the event..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addEventForm" id="addEventFormBtn" class="btn btn-warning">Add
                    Event</button>
            </div>
        </div>
    </div>
</div>

<!-- Substitution Modal -->
<div class="modal fade" id="substitutionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i>Player Substitution</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="substitutionForm" action="{{ route('match-lineups.update', $match->id) }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Team</label>
                            <select class="form-select" id="subTeam" name="team_id" required>
                                <option value="">Select team</option>
                                <option value="{{ $match->home_team_id }}">{{ $match->homeTeam->name }}
                                </option>
                                <option value="{{ $match->away_team_id }}">{{ $match->awayTeam->name }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Minute</label>
                            <input type="number" class="form-control" id="subMinute" name="minute" min="1"
                                max="120" required>
                        </div>
                    </div>

                    <!-- Player Selection Interface -->
                    <div class="row">
                        <!-- Players Out (Starting XI) -->
                        <div class="col-md-6">
                            <h6 class="text-danger mb-3"><i class="fas fa-sign-out-alt me-2"></i>Player Out
                                (Starting XI)</h6>
                            <div id="playersOutContainer" class="border rounded p-3"
                                style="min-height: 200px; background-color: #f8f9fa;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-users fa-2x mb-2"></i>
                                    <p class="mb-0">No players in Starting XI</p>
                                </div>
                            </div>
                            <input type="hidden" id="playerOutId" name="player_out_id" required>
                        </div>

                        <!-- Players In (Substitutes) -->
                        <div class="col-md-6">
                            <h6 class="text-success mb-3"><i class="fas fa-sign-in-alt me-2"></i>Player In
                                (Substitutes)</h6>
                            <div id="playersInContainer" class="border rounded p-3"
                                style="min-height: 200px; background-color: #f8f9fa;">
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                                    <p class="mb-0">No substitute players available</p>
                                </div>
                            </div>
                            <input type="hidden" id="playerInId" name="player_in_id" required>
                        </div>
                    </div>

                    <!-- Position Selection -->
                    <div class="mt-3">
                        <label class="form-label">Position for Player In</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="position" id="posGK" value="GK"
                                required>
                            <label class="btn btn-outline-primary" for="posGK">GK</label>

                            <input type="radio" class="btn-check" name="position" id="posDEF" value="DEF"
                                required>
                            <label class="btn btn-outline-success" for="posDEF">DEF</label>

                            <input type="radio" class="btn-check" name="position" id="posMID" value="MID"
                                required>
                            <label class="btn btn-outline-warning" for="posMID">MID</label>

                            <input type="radio" class="btn-check" name="position" id="posFWD" value="FWD"
                                required>
                            <label class="btn btn-outline-danger" for="posFWD">FWD</label>
                        </div>
                    </div>

                    <!-- Selected Players Display -->
                    <div class="mt-3 p-3 bg-light rounded" id="selectedPlayersDisplay" style="display: none;">
                        <h6 class="mb-2">Selected Substitution:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <span class="badge bg-danger">OUT:</span>
                                <span id="selectedPlayerOut">None selected</span>
                            </div>
                            <div class="col-md-6">
                                <span class="badge bg-success">IN:</span>
                                <span id="selectedPlayerIn">None selected</span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="substitutionForm" class="btn btn-info" id="makeSubstitutionBtn">
                    <i class="fas fa-exchange-alt me-2"></i>Make Substitution
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Commentary Modal -->
<div class="modal fade" id="addCommentaryModal" tabindex="-1" aria-labelledby="addCommentaryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCommentaryModalLabel">
                    <i class="fas fa-comment-plus me-2"></i>Add Live Commentary
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCommentaryForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="commentaryMinute" class="form-label">Match Minute *</label>
                                <input type="number" class="form-control" id="commentaryMinute" name="minute"
                                    min="0" max="120" required>
                                <div class="form-text">Enter the minute when this event occurred</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="commentaryType" class="form-label">Commentary Type *</label>
                                <select class="form-select" id="commentaryType" name="commentary_type" required>
                                    <option value="">Select type</option>
                                    <option value="general">General</option>
                                    <option value="tactical">Tactical Analysis</option>
                                    <option value="incident">Incident</option>
                                    <option value="highlight">Highlight</option>
                                    <option value="warning">Warning</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="commentaryDescription" class="form-label">Description *</label>
                        <textarea class="form-control" id="commentaryDescription" name="description" rows="4" maxlength="1000"
                            required placeholder="Describe what happened at this moment..."></textarea>
                        <div class="form-text">Maximum 1000 characters</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="commentaryImportant"
                                name="is_important">
                            <label class="form-check-label" for="commentaryImportant">
                                Mark as Important
                            </label>
                            <div class="form-text">Important commentary will be highlighted</div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="addCommentary()">
                    <i class="fas fa-plus me-2"></i>Add Commentary
                </button>
            </div>
        </div>
    </div>
</div>

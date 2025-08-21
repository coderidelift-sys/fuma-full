    // Global variables
    let currentMatchData = null;
    let currentLineup = {
        starting_xi: [],
        substitutes: [],
        bench: []
    };
    let availablePlayers = [];
    let selectedTeamId = null;

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        initializeMatchManagement();
        setupEventListeners();
    });

    // Toastr function to replace alerts
    function showToastr(type, message) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        }
    }

    // Replace all alerts with showToastr
    function replaceAlerts() {
        // Lineup management alerts
        if (typeof addPlayerToLineup === 'function') {
            // This will be handled by the actual function calls
        }
    }

    // Initialize match management
    function initializeMatchManagement() {
        // // console.log('Initializing match management...');
        selectedTeamId = document.getElementById('lineupTeamSelect').value;
        // // console.log('Selected team ID:', selectedTeamId);

        loadAvailablePlayers(selectedTeamId);
        loadCurrentLineup();

        // Set current scores in modals
        document.getElementById('finalHomeScore').value = globalHomeScore;
        document.getElementById('finalAwayScore').value = globalAwayScore;
        document.getElementById('liveHomeScore').value = globalHomeScore;
        document.getElementById('liveAwayScore').value = globalAwayScore;

        // Load existing lineup data if available
        loadExistingLineupData();

        // Populate player selects in modals
        setTimeout(() => {
            // console.log('Delayed populatePlayerSelects called');
            populatePlayerSelects();
        }, 1000); // Delay to ensure players are loaded

        // Auto-populate team selections in modals
        autoPopulateTeamSelections();

        // console.log('Match management initialized');
    }

    // Setup event listeners
    function setupEventListeners() {
        // Team selection change
        document.getElementById('lineupTeamSelect').addEventListener('change', function() {
            selectedTeamId = this.value;
            loadAvailablePlayers(selectedTeamId);
            loadCurrentLineup();
        });

        // Formation change
        document.getElementById('formationSelect').addEventListener('change', function() {
            updateFormationDisplay();
        });

        // Team selection change in modals
        document.getElementById('eventTeam')?.addEventListener('change', function() {
            populatePlayerSelects(); // Refresh player dropdowns
        });

        document.getElementById('subTeam')?.addEventListener('change', function() {
            populatePlayerSelects(); // Refresh player dropdowns
        });

        // Form submissions
        document.getElementById('completeMatchForm').addEventListener('submit', completeMatch);
        document.getElementById('scoreUpdateForm').addEventListener('submit', updateScore);
        document.getElementById('addEventForm').addEventListener('submit', addEvent);
        document.getElementById('substitutionForm').addEventListener('submit', makeSubstitution);
    }

    // Load available players for a team
    function loadAvailablePlayers(teamId) {
        // console.log('Loading available players for team:', teamId);
        fetch(`${urlFetchAvailablePlayers}/${teamId}`)
            .then(response => response.json())
            .then(data => {
                // console.log('Available players response:', data);
                if (data.success) {
                    // Handle both data structures
                    if (data.data && data.data.players) {
                        availablePlayers = data.data.players;
                    } else if (data.players) {
                        availablePlayers = data.players;
                    } else {
                        availablePlayers = [];
                    }

                    // console.log('Available players loaded:', availablePlayers);
                    if(isScheduled){
                        renderAvailablePlayers();
                    }
                    populatePlayerSelects();
                } else {
                    console.error('Failed to load players:', data.message);
                    // Load test data if backend fails
                }
            })
            .catch(error => {
                console.error('Error loading players:', error);
                // Load test data if backend fails
            });
    }

    // Load current lineup
    function loadCurrentLineup() {
        // console.log('Loading current lineup for team:', selectedTeamId);
        fetch(`${urlCurrentLineUp}?team_id=${selectedTeamId}`)
            .then(response => response.json())
            .then(data => {
                // console.log('Lineup response:', data);
                if (data.success && data.data) {
                    // Map backend data structure to frontend
                    currentLineup = {
                        starting_xi: data.data.starting_xi || [],
                        substitutes: data.data.substitutes ||
                    [], // Map from backend 'substitute' to frontend 'substitutes'
                        bench: data.data.bench || []
                    };
                    // console.log('Current lineup loaded:', currentLineup);
                    renderLineup();
                    updateFormationDisplay();
                } else {
                    // console.log('No existing lineup, initializing empty');
                    // Initialize empty lineup if none exists
                    currentLineup = {
                        starting_xi: [],
                        substitutes: [],
                        bench: []
                    };
                    renderLineup();
                }
            })
            .catch(error => {
                console.error('Error loading lineup:', error);
                // Initialize empty lineup on error
                currentLineup = {
                    starting_xi: [],
                    substitutes: [],
                    bench: []
                };
                renderLineup();
            });
    }

    // Load existing lineup data from database
    function loadExistingLineupData() {
        // Try to load lineup for both teams
        // Load home team lineup
        fetch(`${urlCurrentLineUp}?team_id=${homeTeamId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.starting_xi && data.data.starting_xi.length > 0) {
                    // console.log('Home team lineup loaded:', data.data.formation);
                    // Update formation selector if lineup exists
                    if (data.data.formation) {
                        document.getElementById('formationSelect').value = data.data.formation;
                    }
                }
            })
            .catch(error => console.error('Error loading home team lineup:', error));

        // Load away team lineup
        fetch(`${urlCurrentLineUp}?team_id=${awayTeamId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.starting_xi && data.data.starting_xi.length > 0) {
                    // console.log('Away team lineup loaded:', data.data);
                }
            })
            .catch(error => console.error('Error loading away team lineup:', error));
    }

    // Render available players - Simple click interface
    function renderAvailablePlayers() {
        const container = document.getElementById('availablePlayersContainer');
        if (!availablePlayers.length) {
            container.innerHTML = '<div class="col-12 text-center text-muted py-3">No players available</div>';
            return;
        }

        // Group players by position
        const playersByPosition = {
            'GK': availablePlayers.filter(p => p.position === 'GK'),
            'DEF': availablePlayers.filter(p => p.position === 'DEF'),
            'MID': availablePlayers.filter(p => p.position === 'MID'),
            'FWD': availablePlayers.filter(p => p.position === 'FWD')
        };

        let playersHtml = '';

        Object.entries(playersByPosition).forEach(([position, players]) => {
            if (players.length > 0) {
                playersHtml += `
                    <div class="col-12 mb-3">
                        <h6 class="text-muted mb-2">${position} (${players.length})</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${players.map(player => `
                                <div class="player-card-simple p-2 border rounded bg-light"
                                        onclick="addPlayerToLineup('${getBestLineupType(player)}', ${JSON.stringify(player).replace(/"/g, '&quot;')})"
                                        style="cursor: pointer; min-width: 80px;">
                                    <div class="text-center">
                                        <div class="fw-bold small">${player.name}</div>
                                        <div class="badge bg-${getPositionColor(player.position)}">${player.position}</div>
                                        <div class="small text-muted">#${player.jersey_number || '?'}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
            }
        });

        container.innerHTML = playersHtml;
    }

    // Determine best lineup type for a player
    function getBestLineupType(player) {
        if (currentLineup.starting_xi.length < 11) {
            return 'starting_xi';
        } else if (currentLineup.substitutes.length < 7) {
            return 'substitutes';
        }
        return 'substitutes'; // Default to substitutes if both are full
    }

    // Render current lineup
    function renderLineup() {
        renderStartingXI();
        renderSubstitutes();
        updateSaveButton();
    }

    // Render Starting XI
    function renderStartingXI() {
        const container = document.getElementById('startingXIContainer');
        if (!currentLineup.starting_xi || !currentLineup.starting_xi.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <p>Drag players here to set Starting XI</p>
                    <small class="text-muted">Need exactly 11 players</small>
                </div>
            `;
            return;
        }

        const playersHtml = currentLineup.starting_xi.map(player => {
            // Handle different data structures
            const playerName = player.player ? player.player.name : player.name;
            const playerId = player.player_id || player.id;
            const position = player.position || 'N/A';
            const jerseyNumber = player.jersey_number || '?';
            const isCaptain = player.is_captain || false;

            return `
                <div class="player-card d-inline-block m-1 p-2 border rounded bg-light"
                     data-player-id="${playerId}">
                    <div class="text-center">
                        <div class="fw-bold small">${playerName}</div>
                        <div class="badge bg-${getPositionColor(position)}">${position}</div>
                        <div class="small text-muted">#${jerseyNumber}</div>
                        ${isCaptain ? '<div class="badge bg-warning">C</div>' : ''}
                        ${isScheduled ? `<button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromLineup('starting_xi', ${playerId})">
                            <i class="fas fa-times"></i>
                        </button>` : ''}
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = playersHtml;
    }

    // Render Substitutes
    function renderSubstitutes() {
        const container = document.getElementById('substitutesContainer');

        if (!currentLineup.substitutes || !currentLineup.substitutes.length) {
            container.innerHTML = `
                <div class="text-center text-muted py-5">
                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                    <p>Drag players here for substitutes</p>
                    <small class="text-muted">Optional - max 12 players</small>
                </div>
            `;
            return;
        }

        const playersHtml = currentLineup.substitutes.map(player => {
            // Handle different data structures
            const playerName = player.player ? player.player.name : player.name;
            const playerId = player.player_id || player.id;
            const position = player.position || 'N/A';
            const jerseyNumber = player.jersey_number || '?';

            return `
                <div class="player-card d-inline-block m-1 p-2 border rounded bg-light"
                     data-player-id="${playerId}">
                    <div class="text-center">
                        <div class="fw-bold small">${playerName}</div>
                        <div class="badge bg-${getPositionColor(position)}">${position}</div>
                        <div class="small text-muted">#${jerseyNumber}</div>
                        ${isScheduled ? `<button class="btn btn-sm btn-outline-danger mt-1" onclick="removeFromLineup('substitutes', ${playerId})">
                            <i class="fas fa-times"></i>
                        </button>` : ''}
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = playersHtml;
    }

    // Simple click-based player addition
    function addPlayerToLineup(type, player) {
        // Check if player is already in lineup
        const isAlreadyInLineup = [...currentLineup.starting_xi, ...currentLineup.substitutes]
            .some(p => p.player_id === player.id);

        if (isAlreadyInLineup) {
            showToastr('warning', `${player.name} is already in the lineup!`);
            return;
        }

        // Auto-switch to substitutes if Starting XI is full
        if (type === 'starting_xi' && currentLineup.starting_xi.length >= 11) {
            if (currentLineup.substitutes.length >= 7) {
                showToastr('warning', 'Both Starting XI and Substitutes are full!');
                return;
            }
            type = 'substitute'; // Use singular form to match backend
        }

        if (type === 'substitute' && currentLineup.substitutes.length >= 7) {
            showToastr('warning', 'Substitutes are full!');
            return;
        }

        const lineupPlayer = {
            player_id: parseInt(player.id),
            player: player,
            position: player.position,
            jersey_number: parseInt(player.jersey_number || generateJerseyNumber(type)),
            is_captain: false
        };

        // Map frontend type to backend type
        const backendType = type === 'substitute' ? 'substitutes' : type;

        currentLineup[backendType].push(lineupPlayer);

        renderLineup();
        updateSaveButton();
        updateStatusBar();
    }

    // Remove player from lineup
    function removeFromLineup(type, playerId) {
        currentLineup[type] = currentLineup[type].filter(p => parseInt(p.player_id) !== parseInt(playerId));
        renderLineup();
        updateSaveButton();
    }

    // Generate jersey number
    function generateJerseyNumber(type) {
        const usedNumbers = [...currentLineup.starting_xi, ...currentLineup.substitutes]
            .map(p => p.jersey_number)
            .filter(n => n);

        for (let i = 1; i <= 99; i++) {
            if (!usedNumbers.includes(i)) {
                return i;
            }
        }
        return 1;
    }

    // Update formation display
    function updateFormationDisplay() {
        const formation = document.getElementById('formationSelect').value;
        // This could show a visual formation diagram
        // console.log('Formation changed to:', formation);
    }

    // Update save button state
    function updateSaveButton() {
        const saveBtn = document.getElementById('saveLineupBtn');
        const canSave = currentLineup.starting_xi.length === 11 && currentLineup.substitutes.length > 0;
        if(saveBtn){
            saveBtn.disabled = !canSave;
        }

        // Update status bar
        updateStatusBar();
    }

    // Update status bar
    function updateStatusBar() {
        const startingXICount = currentLineup.starting_xi ? currentLineup.starting_xi.length : 0;
        const substitutesCount = currentLineup.substitutes ? currentLineup.substitutes.length : 0;
        const formation = document.getElementById('formationSelect').value;

        document.getElementById('startingXICount').textContent = startingXICount;
        document.getElementById('substitutesCount').textContent = substitutesCount;
        document.getElementById('formationDisplay').textContent = formation;
    }

    // Reset lineup
    function uiConfirm(message){
        return new Promise((resolve)=>{
            try{
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.tabIndex = -1;
                modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header"><h5 class="modal-title">Confirm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body"><p>${message}</p></div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="__confirmOkBtn">OK</button>
                        </div>
                    </div>
                </div>`;
                document.body.appendChild(modal);
                const bsModal = new bootstrap.Modal(modal);
                modal.addEventListener('hidden.bs.modal', ()=>{ modal.remove(); });
                modal.querySelector('#__confirmOkBtn').addEventListener('click', ()=>{ resolve(true); bsModal.hide(); });
                bsModal.show();
            }catch(_){ resolve(window.confirm(message)); }
        });
    }

    function resetLineup() {
        uiConfirm('Are you sure you want to reset the lineup? This will clear all selected players.')
            .then((ok)=>{
                if(!ok) return;
                currentLineup = {
                    starting_xi: [],
                    substitutes: [],
                    bench: []
                };
                renderLineup();
                updateSaveButton();
                updateStatusBar();
            });
    }

    // Save lineup
    function saveLineup() {
        const formation = document.getElementById('formationSelect').value;

        // Validate lineup before saving
        if (currentLineup.starting_xi.length !== 11) {
            showToastr('error', 'Starting XI must have exactly 11 players');
            return;
        }

        if (currentLineup.substitutes.length === 0) {
            showToastr('error', 'At least one substitute is required');
            return;
        }

        // Validate that all players have required data
        const allPlayers = [...currentLineup.starting_xi, ...currentLineup.substitutes];
        const invalidPlayers = allPlayers.filter(player =>
            !player.player_id || !player.position || !player.jersey_number
        );

        if (invalidPlayers.length > 0) {
            showToastr('error', 'Some players are missing required information');
            console.error('Invalid players:', invalidPlayers);
            return;
        }

        // Validate that all players belong to the selected team
        const selectedTeamIdInt = parseInt(selectedTeamId);
        const teamPlayers = availablePlayers.filter(p => p.team_id === selectedTeamIdInt);
        const teamPlayerIds = teamPlayers.map(p => p.id);

        const invalidTeamPlayers = allPlayers.filter(player =>
            !teamPlayerIds.includes(parseInt(player.player_id))
        );

        if (invalidTeamPlayers.length > 0) {
            showToastr('error', 'Some players do not belong to the selected team');
            console.error('Invalid team players:', invalidTeamPlayers);
            console.error('Team player IDs:', teamPlayerIds);
            console.error('Selected team ID:', selectedTeamIdInt);
            return;
        }

        // Transform data to match backend expectations
        const lineupData = {
            team_id: parseInt(selectedTeamId),
            formation: formation,
            lineup: {
                starting_xi: currentLineup.starting_xi.map(player => ({
                    player_id: parseInt(player.player_id || player.id),
                    jersey_number: parseInt(player.jersey_number),
                    position: player.position,
                    is_captain: player.is_captain || false
                })),
                substitutes: currentLineup.substitutes.map(player => ({ // Use singular form for backend
                    player_id: parseInt(player.player_id || player.id),
                    jersey_number: parseInt(player.jersey_number),
                    position: player.position,
                    is_captain: false
                }))
            }
        };

        // console.log('Sending lineup data:', lineupData);
        // console.log('Current lineup state:', currentLineup);
        // console.log('Selected team ID:', selectedTeamId);
        // console.log('Formation:', formation);

        fetch(urlCurrentLineUp, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(lineupData)
            })
            .then(response => response.json())
            .then(data => {
                // console.log('Save lineup response:', data);
                if (data.success) {
                    showToastr('success', 'Lineup saved successfully!');
                    loadCurrentLineup();
                    window.location.reload();
                } else {
                    showToastr('error', 'Error saving lineup: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error saving lineup:', error);
                showToastr('error', 'Error saving lineup');
            });
    }

    // Auto-populate team selections in modals
    function autoPopulateTeamSelections() {
        // Set default team selections for modals

        // Event modal - default to home team
        const eventTeamSelect = document.getElementById('eventTeam');
        if (eventTeamSelect) {
            eventTeamSelect.value = homeTeamId;
        }

        // Substitution modal - default to home team
        const subTeamSelect = document.getElementById('subTeam');
        if (subTeamSelect) {
            subTeamSelect.value = homeTeamId;
        }
    }

    // Player selection functions for substitution modal
    let selectedPlayerOut = null;
    let selectedPlayerIn = null;

    function selectPlayerOut(playerId, playerName, position) {
        // Clear previous selection
        document.querySelectorAll('#playersOutContainer .substitution-player-card').forEach(card => {
            card.classList.remove('selected-out');
        });

        // Select new player
        const playerCard = document.querySelector(`#playersOutContainer [data-player-id="${playerId}"]`);
        if (playerCard) {
            playerCard.classList.add('selected-out');
        }

        selectedPlayerOut = {
            id: playerId,
            name: playerName,
            position: position
        };
        document.getElementById('playerOutId').value = playerId;

        updateSubstitutionDisplay();
        // console.log('Player OUT selected:', selectedPlayerOut);
    }

    function selectPlayerIn(playerId, playerName, position) {
        // Clear previous selection
        document.querySelectorAll('#playersInContainer .substitution-player-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Select new player
        const playerCard = document.querySelector(`#playersInContainer [data-player-id="${playerId}"]`);
        if (playerCard) {
            playerCard.classList.add('selected');
        }

        selectedPlayerIn = {
            id: playerId,
            name: playerName,
            position: position
        };
        document.getElementById('playerInId').value = playerId;

        updateSubstitutionDisplay();
        // console.log('Player IN selected:', selectedPlayerIn);
    }

    function updateSubstitutionDisplay() {
        const display = document.getElementById('selectedPlayersDisplay');
        const playerOutSpan = document.getElementById('selectedPlayerOut');
        const playerInSpan = document.getElementById('selectedPlayerIn');

        if (selectedPlayerOut && selectedPlayerIn) {
            playerOutSpan.textContent = `${selectedPlayerOut.name} (${selectedPlayerOut.position})`;
            playerInSpan.textContent = `${selectedPlayerIn.name} (${selectedPlayerIn.position})`;
            display.style.display = 'block';
        } else {
            display.style.display = 'none';
        }
    }

    // Populate player selects in modals
    function populatePlayerSelects() {

        // Populate scorer and assist selects
        const scorerSelect = document.getElementById('scorerSelect');
        const assistSelect = document.getElementById('assistSelect');
        const eventPlayerSelect = document.getElementById('eventPlayer');

        if (scorerSelect && assistSelect && availablePlayers.length > 0) {
            // Clear existing options
            scorerSelect.innerHTML = '<option value="">Select scorer</option>';
            assistSelect.innerHTML = '<option value="">Select assist</option>';

            // Add players from both teams
            availablePlayers.forEach(player => {
                const teamName = player.team_id === homeTeamId ? globalHomeName : globalAwayName;
                const option =
                    `<option value="${player.id}">${player.name} (${player.position}) - ${teamName}</option>`;
                scorerSelect.innerHTML += option;
                assistSelect.innerHTML += option;
            });
            // console.log('Scorer and assist selects populated');
        }

        if (eventPlayerSelect && availablePlayers.length > 0) {
            eventPlayerSelect.innerHTML = '<option value="">Select player (optional)</option>';
            availablePlayers.forEach(player => {
                const teamName = player.team_id === homeTeamId ? globalHomeName : globalAwayName;
                const option =
                    `<option value="${player.id}">${player.name} (${player.position}) - ${teamName}</option>`;
                eventPlayerSelect.innerHTML += option;
            });
            // console.log('Event player select populated');
        }

        // Populate substitution containers
        populateSubstitutionContainers();
    }

    // Populate substitution containers with clickable player cards
    function populateSubstitutionContainers() {
        // console.log('Populating substitution containers...');

        const playersOutContainer = document.getElementById('playersOutContainer');
        const playersInContainer = document.getElementById('playersInContainer');

        if (!playersOutContainer || !playersInContainer) return;

        // Clear containers
        playersOutContainer.innerHTML = '';
        playersInContainer.innerHTML = '';

        // Populate Players Out (Starting XI)
        if (currentLineup.starting_xi && currentLineup.starting_xi.length > 0) {
            currentLineup.starting_xi.forEach(player => {
                const playerName = player.player ? player.player.name : player.name;
                const playerId = player.player_id || player.id;
                const position = player.position || 'N/A';
                const jerseyNumber = player.jersey_number || '?';

                const playerCard = `
                    <div class="substitution-player-card"
                         onclick="selectPlayerOut(${playerId}, '${playerName}', '${position}')"
                         data-player-id="${playerId}">
                        <div class="fw-bold">${playerName}</div>
                        <div class="badge bg-${getPositionColor(position)}">${position}</div>
                        <div class="small text-muted">#${jerseyNumber}</div>
                    </div>
                `;
                playersOutContainer.innerHTML += playerCard;
            });
            // console.log('Players out container populated with', currentLineup.starting_xi.length, 'players');
        } else {
            playersOutContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-users fa-2x mb-2"></i>
                    <p class="mb-0">No players in Starting XI</p>
                </div>
            `;
        }

        // Populate Players In (Substitutes)
        if (currentLineup.substitutes && currentLineup.substitutes.length > 0) {
            currentLineup.substitutes.forEach(player => {
                const playerName = player.player ? player.player.name : player.name;
                const playerId = player.player_id || player.id;
                const position = player.position || 'N/A';
                const jerseyNumber = player.jersey_number || '?';

                const playerCard = `
                    <div class="substitution-player-card"
                         onclick="selectPlayerIn(${playerId}, '${playerName}', '${position}')"
                         data-player-id="${playerId}">
                        <div class="fw-bold">${playerName}</div>
                        <div class="badge bg-${getPositionColor(position)}">${position}</div>
                        <div class="small text-muted">#${jerseyNumber}</div>
                    </div>
                `;
                playersInContainer.innerHTML += playerCard;
            });
            // console.log('Players in container populated with', currentLineup.substitutes.length, 'players');
        } else {
            playersInContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                    <p class="mb-0">No substitute players available</p>
                </div>
            `;
        }
    }

    // Get position color for badges
    function getPositionColor(position) {
        const colors = {
            'GK': 'primary',
            'DEF': 'success',
            'MID': 'warning',
            'FWD': 'danger'
        };
        return colors[position] || 'secondary';
    }

    // Match Control Functions
    function startMatch() {
        uiConfirm('Are you sure you want to start this match?').then((ok)=>{ if(!ok) return;
            fetch(urlStartMatch, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        showToastr('error','Error starting match: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error starting match:', error);
                    showToastr('error', 'Error starting match');
                });
        });
    }

    function pauseMatch() {
        uiConfirm('Are you sure you want to pause this match?').then((ok)=>{ if(!ok) return;
            fetch(urlPauseMatch, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastr('success', "Successfully paused match");
                        location.reload();
                    } else {
                        showToastr('error', 'Error pausing match: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error pausing match:', error);
                    showToastr('error', 'Error pausing match');
                });
        });
    }

    function resumeMatch() {
        uiConfirm('Are you sure you want to resume this match?').then((ok)=>{ if(!ok) return;
            fetch(urlResumeMatch, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToastr('success', 'Success resuming match: ' + data.message);
                        location.reload();
                    } else {
                        showToastr('error', 'Error resuming match: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error resuming match:', error);
                    showToastr('error', 'Error resuming match');
                });
        });
    }

    // Modal show functions
    function showCompleteMatchModal() {
        new bootstrap.Modal(document.getElementById('completeMatchModal')).show();
    }

    function showScoreUpdateModal() {
        new bootstrap.Modal(document.getElementById('scoreUpdateModal')).show();
    }

    function showEventModal() {
        new bootstrap.Modal(document.getElementById('addEventModal')).show();
    }

    function showSubstitutionModal() {
        new bootstrap.Modal(document.getElementById('substitutionModal')).show();
    }

    // Form submission functions
    function completeMatch(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        fetch(urlCompleteMatch, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message before reload
                    location.reload();
                } else {
                    // Show detailed error message
                    let errorMsg = data.message || 'Unknown error occurred';
                    if (data.errors) {
                        errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                    }
                }
            })
            .catch(error => {
                console.error('Error completing match:', error);
            })
            .finally(() => {

            });
    }

    function updateScore(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        formData.append('_method', 'PUT');

        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]') || document.querySelector('#scoreUpdateBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

        fetch(urlScoreMatch, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToastr('success', 'Successfully updated score')
                    location.reload();
                } else {
                    let errorMsg = data.message || 'Unknown error occurred';
                    if (data.errors) {
                        errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                    }
                    showToastr('error', errorMsg)
                }
            })
            .catch(error => {
                console.error('Error updating score:', error);
                showToastr('error', 'Error updating score')
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    }

    function addEvent(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        // Validate required fields
        const eventType = formData.get('type');
        const teamId = formData.get('team_id');
        const minute = formData.get('minute');

        if (!eventType || !teamId || !minute) {
            showToastr('Please fill in all required fields: Event Type, Team, and Minute');
            return;
        }

        // Show loading state
        const submitBtn = e.target.querySelector('button[type="submit"]') || document.querySelector('#addEventFormBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';

        fetch(urlMatchEvents, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToastr('Event added successfully!');
                    location.reload();
                } else {
                    let errorMsg = data.message || 'Unknown error occurred';
                    if (data.errors) {
                        errorMsg += '\n\nDetails:\n' + Object.values(data.errors).flat().join('\n');
                    }
                    showToastr('Error adding event:\n' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Error adding event:', error);
                showToastr('Network error: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    }

    function makeSubstitution(e) {
        e.preventDefault();
        const formData = new FormData(e.target);

        // Validate required fields
        const teamId = formData.get('team_id');
        const minute = formData.get('minute');
        const playerOutId = formData.get('player_out_id');
        const playerInId = formData.get('player_in_id');
        const position = formData.get('position');

        if (!teamId || !minute || !playerOutId || !playerInId || !position) {
            showToastr('error',
                'Please fill in all required fields: Team, Minute, Player Out, Player In, and Position');
            return;
        }

        // Validate that players are different
        if (playerOutId === playerInId) {
            showToastr('error', 'Player Out and Player In must be different players');
            return;
        }

        // Validate that players are selected
        if (!selectedPlayerOut || !selectedPlayerIn) {
            showToastr('error', 'Please select both Player Out and Player In');
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('#makeSubstitutionBtn');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Substituting...';

        // SUBMIT FORM LANGSUNG
        document.getElementById('substitutionForm').submit();
    }

    // Reset substitution modal
    function resetSubstitutionModal() {
        selectedPlayerOut = null;
        selectedPlayerIn = null;

        // Clear selections
        document.querySelectorAll('#playersOutContainer .substitution-player-card').forEach(card => {
            card.classList.remove('selected-out');
        });
        document.querySelectorAll('#playersInContainer .substitution-player-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Clear hidden inputs
        document.getElementById('playerOutId').value = '';
        document.getElementById('playerInId').value = '';

        // Hide display
        document.getElementById('selectedPlayersDisplay').style.display = 'none';

        // Reset form
        document.getElementById('substitutionForm').reset();
    }

    // Commentary Management Functions
    function addCommentary() {
        const minute = document.getElementById('commentaryMinute').value;
        const type = document.getElementById('commentaryType').value;
        const description = document.getElementById('commentaryDescription').value;
        const isImportant = document.getElementById('commentaryImportant').checked;

        if (!minute || !type || !description) {
            showToastr('error', 'Please fill in all required fields');
            return;
        }

        const formData = new FormData();
        formData.append('minute', minute);
        formData.append('commentary_type', type);
        formData.append('description', description);
        formData.append('is_important', isImportant);

        fetch(urlMatchComments, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToastr('success', 'Commentary added successfully!');
                document.getElementById('addCommentaryForm').reset();
                // Reload commentary
                location.reload();
            } else {
                showToastr('error', data.message || 'Error adding commentary');
            }
        })
        .catch(error => {
            console.error('Error adding commentary:', error);
            showToastr('error', 'Network error: ' + error.message);
        });
    }

    function deleteCommentary(commentaryId) {
        uiConfirm('Are you sure you want to delete this commentary?').then((ok)=>{ if(!ok) return;
            fetch(`${urlDeleteComment}/${commentaryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToastr('success', 'Commentary deleted successfully!');
                    location.reload();
                } else {
                    showToastr('error', data.message || 'Error deleting commentary');
                }
            })
            .catch(error => {
                console.error('Error deleting commentary:', error);
                showToastr('error', 'Network error: ' + error.message);
            });
        });
    }

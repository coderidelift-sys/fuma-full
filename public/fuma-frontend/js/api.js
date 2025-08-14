// API.js - API Communication Functions
class API {
    constructor() {
        this.baseUrl = '/fuma';
        this.auth = window.auth || null;
    }

    // Get auth token
    getAuthToken() {
        if (this.auth) {
            return this.auth.getToken();
        }
        return localStorage.getItem('fuma_jwt_token');
    }

    // Get headers for API requests
    getHeaders(includeAuth = true) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        if (includeAuth) {
            const token = this.getAuthToken();
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
        }

        return headers;
    }

    // Generic API request method
    async request(endpoint, options = {}) {
        try {
            const url = `${this.baseUrl}${endpoint}`;
            const config = {
                headers: this.getHeaders(options.requireAuth !== false),
                ...options
            };

            const response = await fetch(url, config);
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            return data;
        } catch (error) {
            console.error(`API Error (${endpoint}):`, error);
            throw error;
        }
    }

    // GET request
    async get(endpoint, requireAuth = true) {
        return this.request(endpoint, {
            method: 'GET',
            requireAuth
        });
    }

    // POST request
    async post(endpoint, data, requireAuth = true) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data),
            requireAuth
        });
    }

    // PUT request
    async put(endpoint, data, requireAuth = true) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data),
            requireAuth
        });
    }

    // DELETE request
    async delete(endpoint, requireAuth = true) {
        return this.request(endpoint, {
            method: 'DELETE',
            requireAuth
        });
    }

    // Tournament APIs
    async getTournaments(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/tournaments${queryString ? `?${queryString}` : ''}`;
        return this.get(endpoint, false);
    }

    async getTournament(id) {
        return this.get(`/tournaments/${id}`, false);
    }

    async createTournament(data) {
        return this.post('/tournaments', data, true);
    }

    async updateTournament(id, data) {
        return this.put(`/tournaments/${id}`, data, true);
    }

    async deleteTournament(id) {
        return this.delete(`/tournaments/${id}`, true);
    }

    async getTournamentStandings(id) {
        return this.get(`/tournaments/${id}/standings`, false);
    }

    // Team APIs
    async getTeams(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/teams${queryString ? `?${queryString}` : ''}`;
        return this.get(endpoint, false);
    }

    async getTeam(id) {
        return this.get(`/teams/${id}`, false);
    }

    async createTeam(data) {
        return this.post('/teams', data, true);
    }

    async updateTeam(id, data) {
        return this.put(`/teams/${id}`, data, true);
    }

    async deleteTeam(id) {
        return this.delete(`/teams/${id}`, true);
    }

    async addPlayerToTeam(teamId, playerId) {
        return this.post(`/teams/${teamId}/players`, { player_id: playerId }, true);
    }

    async removePlayerFromTeam(teamId, playerId) {
        return this.delete(`/teams/${teamId}/players`, true);
    }

    // Player APIs
    async getPlayers(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/players${queryString ? `?${queryString}` : ''}`;
        return this.get(endpoint, false);
    }

    async getPlayer(id) {
        return this.get(`/players/${id}`, false);
    }

    async createPlayer(data) {
        return this.post('/players', data, true);
    }

    async updatePlayer(id, data) {
        return this.put(`/players/${id}`, data, true);
    }

    async deletePlayer(id) {
        return this.delete(`/players/${id}`, true);
    }

    async updatePlayerStats(id, stats) {
        return this.put(`/players/${id}/stats`, stats, true);
    }

    // Match APIs
    async getMatches(params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/matches${queryString ? `?${queryString}` : ''}`;
        return this.get(endpoint, false);
    }

    async getMatch(id) {
        return this.get(`/matches/${id}`, false);
    }

    async createMatch(data) {
        return this.post('/matches', data, true);
    }

    async updateMatch(id, data) {
        return this.put(`/matches/${id}`, data, true);
    }

    async deleteMatch(id) {
        return this.delete(`/matches/${id}`, true);
    }

    async updateMatchScore(id, homeScore, awayScore) {
        return this.put(`/matches/${id}/score`, {
            home_score: homeScore,
            away_score: awayScore
        }, true);
    }

    async addMatchEvent(id, eventData) {
        return this.post(`/matches/${id}/events`, eventData, true);
    }

    // Statistics APIs
    async getStatistics() {
        return this.get('/statistics', false);
    }

    // Standings APIs
    async getStandings(tournamentId = null) {
        const params = tournamentId ? { tournament_id: tournamentId } : {};
        const queryString = new URLSearchParams(params).toString();
        const endpoint = `/standings${queryString ? `?${queryString}` : ''}`;
        return this.get(endpoint, false);
    }

    // User Management APIs (Admin only)
    async getUsers() {
        return this.get('/users', true);
    }

    async createUser(data) {
        return this.post('/users', data, true);
    }

    async updateUser(id, data) {
        return this.put(`/users/${id}`, data, true);
    }

    async deleteUser(id) {
        return this.delete(`/users/${id}`, true);
    }

    // Role Management APIs (Admin only)
    async getRoles() {
        return this.get('/roles', true);
    }

    async createRole(data) {
        return this.post('/roles', data, true);
    }

    async updateRole(id, data) {
        return this.put(`/roles/${id}`, data, true);
    }

    async deleteRole(id) {
        return this.delete(`/roles/${id}`, true);
    }

    // Committee APIs
    async getCommittees() {
        return this.get('/committees', false);
    }

    async getCommittee(id) {
        return this.get(`/committees/${id}`, false);
    }

    async createCommittee(data) {
        return this.post('/committees', data, true);
    }

    async updateCommittee(id, data) {
        return this.put(`/committees/${id}`, data, true);
    }

    async deleteCommittee(id) {
        return this.delete(`/committees/${id}`, true);
    }

    // Profile APIs
    async getProfile() {
        return this.get('/profile', true);
    }

    async updateProfile(data) {
        return this.put('/profile', data, true);
    }

    // Utility methods
    async uploadFile(file, endpoint = '/upload') {
        const formData = new FormData();
        formData.append('file', file);

        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${this.getAuthToken()}`
                },
                body: formData
            });

            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.message || 'Upload failed');
            }

            return data;
        } catch (error) {
            console.error('File upload error:', error);
            throw error;
        }
    }

    // Error handling
    handleError(error, elementId = null) {
        console.error('API Error:', error);

        let message = 'An error occurred';
        if (error.message) {
            message = error.message;
        }

        // Show error in UI
        if (elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.innerHTML = `<div class="alert alert-danger">${message}</div>`;
            }
        } else {
            // Show global error notification
            this.showNotification(message, 'error');
        }
    }

    // Show notification
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.body.appendChild(notification);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Loading state management
    showLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Loading...</p>
                </div>
            `;
        }
    }

    hideLoading(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = '';
        }
    }

    // Data formatting utilities
    formatDate(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    formatDateTime(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    formatRelativeTime(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`;

        return this.formatDate(dateString);
    }

    // Status badge formatting
    getStatusBadge(status, type = 'default') {
        const statusMap = {
            tournament: {
                upcoming: 'warning',
                ongoing: 'primary',
                completed: 'success',
                cancelled: 'danger'
            },
            match: {
                upcoming: 'warning',
                live: 'danger',
                completed: 'success',
                cancelled: 'secondary'
            },
            default: {
                active: 'success',
                inactive: 'secondary',
                pending: 'warning',
                rejected: 'danger'
            }
        };

        const colorMap = statusMap[type] || statusMap.default;
        const color = colorMap[status] || 'secondary';

        return `<span class="badge bg-${color}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
    }
}

// Create global API instance
const api = new API();

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = API;
}

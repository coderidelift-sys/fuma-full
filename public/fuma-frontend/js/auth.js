// Auth.js - Authentication and Authorization Management
class Auth {
    constructor() {
        this.tokenKey = 'fuma_jwt_token';
        this.userKey = 'fuma_user_data';
        this.baseUrl = '/fuma'; // Base URL untuk API
    }

    // Check if user is logged in
    isLoggedIn() {
        return !!this.getToken();
    }

    // Get JWT token from localStorage
    getToken() {
        return localStorage.getItem(this.tokenKey);
    }

    // Set JWT token to localStorage
    setToken(token) {
        localStorage.setItem(this.tokenKey, token);
    }

    // Get user data from localStorage
    getUser() {
        const userData = localStorage.getItem(this.userKey);
        return userData ? JSON.parse(userData) : null;
    }

    // Set user data to localStorage
    setUser(userData) {
        localStorage.setItem(this.userKey, JSON.stringify(userData));
    }

    // Login function
    async login(email, password) {
        try {
            const response = await fetch(`${this.baseUrl}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (data.success) {
                this.setToken(data.data.token);
                this.setUser(data.data.user);
                return { success: true, user: data.data.user };
            } else {
                return { success: false, message: data.message || 'Login failed' };
            }
        } catch (error) {
            console.error('Login error:', error);
            return { success: false, message: 'Network error occurred' };
        }
    }

    // Logout function
    logout() {
        localStorage.removeItem(this.tokenKey);
        localStorage.removeItem(this.userKey);
        window.location.href = '/fuma-frontend/login.html';
    }

    // Check if user has specific role
    hasRole(role) {
        const user = this.getUser();
        if (!user || !user.roles) return false;

        return user.roles.some(userRole =>
            userRole.name === role || userRole.display_name === role
        );
    }

    // Check if user has any of the specified roles
    hasAnyRole(roles) {
        return roles.some(role => this.hasRole(role));
    }

    // Check if user has all of the specified roles
    hasAllRoles(roles) {
        return roles.every(role => this.hasRole(role));
    }

    // Get user's primary role
    getPrimaryRole() {
        const user = this.getUser();
        if (!user || !user.roles || user.roles.length === 0) return null;
        return user.roles[0];
    }

    // Show login required modal
    showLoginRequired() {
        const modal = `
            <div class="modal fade" id="loginRequiredModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Login Required</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Anda harus login untuk melakukan aksi ini.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <a href="/fuma-frontend/login.html" class="btn btn-primary">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('loginRequiredModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modal);

        // Show modal
        const modalInstance = new bootstrap.Modal(document.getElementById('loginRequiredModal'));
        modalInstance.show();
    }

    // Show access denied message
    showAccessDenied() {
        const modal = `
            <div class="modal fade" id="accessDeniedModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Access Denied</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('accessDeniedModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modal);

        // Show modal
        const modalInstance = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
        modalInstance.show();
    }

    // Check permission for action
    checkPermission(action, requiredRoles = []) {
        if (!this.isLoggedIn()) {
            this.showLoginRequired();
            return false;
        }

        if (requiredRoles.length > 0 && !this.hasAnyRole(requiredRoles)) {
            this.showAccessDenied();
            return false;
        }

        return true;
    }

    // Update UI based on authentication status
    updateUI() {
        const user = this.getUser();
        const isLoggedIn = this.isLoggedIn();

        // Update navigation
        const authNav = document.querySelector('.auth-nav');
        const guestNav = document.querySelector('.guest-nav');

        if (authNav && guestNav) {
            if (isLoggedIn) {
                authNav.style.display = 'block';
                guestNav.style.display = 'none';

                // Update user info
                const userName = authNav.querySelector('.user-name');
                if (userName && user) {
                    userName.textContent = user.name;
                }

                const userRole = authNav.querySelector('.user-role');
                if (userRole && user) {
                    const primaryRole = this.getPrimaryRole();
                    userRole.textContent = primaryRole ? primaryRole.display_name : 'User';
                }
            } else {
                authNav.style.display = 'none';
                guestNav.style.display = 'block';
            }
        }

        // Update action buttons based on permissions
        this.updateActionButtons();
    }

    // Update action buttons based on user permissions
    updateActionButtons() {
        const createButtons = document.querySelectorAll('[data-requires-auth="create"]');
        const editButtons = document.querySelectorAll('[data-requires-auth="edit"]');
        const deleteButtons = document.querySelectorAll('[data-requires-auth="delete"]');
        const adminButtons = document.querySelectorAll('[data-requires-role="admin"]');
        const organizerButtons = document.querySelectorAll('[data-requires-role="organizer"]');
        const managerButtons = document.querySelectorAll('[data-requires-role="manager"]');
        const committeeButtons = document.querySelectorAll('[data-requires-role="committee"]');

        // Update create buttons
        createButtons.forEach(button => {
            if (this.checkPermission('create', ['admin', 'organizer', 'manager'])) {
                button.style.display = 'block';
                button.disabled = false;
            } else {
                button.style.display = 'none';
            }
        });

        // Update edit buttons
        editButtons.forEach(button => {
            if (this.checkPermission('edit', ['admin', 'organizer', 'manager'])) {
                button.style.display = 'block';
                button.disabled = false;
            } else {
                button.style.display = 'none';
            }
        });

        // Update delete buttons
        deleteButtons.forEach(button => {
            if (this.checkPermission('delete', ['admin'])) {
                button.style.display = 'block';
                button.disabled = false;
            } else {
                button.style.display = 'none';
            }
        });

        // Update role-specific buttons
        adminButtons.forEach(button => {
            button.style.display = this.hasRole('admin') ? 'block' : 'none';
        });

        organizerButtons.forEach(button => {
            button.style.display = this.hasAnyRole(['admin', 'organizer']) ? 'block' : 'none';
        });

        managerButtons.forEach(button => {
            button.style.display = this.hasAnyRole(['admin', 'organizer', 'manager']) ? 'block' : 'none';
        });

        committeeButtons.forEach(button => {
            button.style.display = this.hasAnyRole(['admin', 'organizer', 'committee']) ? 'block' : 'none';
        });
    }

    // Initialize authentication
    init() {
        this.updateUI();

        // Add event listeners for logout
        const logoutBtn = document.querySelector('.logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.logout();
            });
        }
    }
}

// Create global auth instance
const auth = new Auth();

// Initialize auth when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    auth.init();
});

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Auth;
}

/**
 * FUMA Backoffice JavaScript
 * Handles API integration and UI interactions
 */

class FumaBackoffice {
    constructor() {
        this.apiBaseUrl = window.FUMA_CONFIG?.API_BASE_URL || '/api';
        this.csrfToken = window.FUMA_CONFIG?.CSRF_TOKEN || '';
        this.userRole = window.FUMA_CONFIG?.USER_ROLE || '';
        this.userId = window.FUMA_CONFIG?.USER_ID || null;

        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeDataTables();
        this.initializeFormValidation();
        this.setupFileUploads();
    }

    setupEventListeners() {
        // Global event listeners
        document.addEventListener('DOMContentLoaded', () => {
            this.setupGlobalEvents();
        });

        // Form submissions
        document.addEventListener('submit', (e) => {
            if (e.target.classList.contains('fuma-form')) {
                this.handleFormSubmission(e);
            }
        });

        // Delete confirmations
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('delete-confirm')) {
                this.handleDeleteConfirmation(e);
            }
        });
    }

    setupGlobalEvents() {
        // Setup tooltips
        if (typeof bootstrap !== 'undefined') {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Setup popovers
        if (typeof bootstrap !== 'undefined') {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }
    }

    initializeDataTables() {
        // Initialize DataTables if they exist
        if (typeof $.fn.DataTable !== 'undefined') {
            $('.fuma-datatable').each(function() {
                const table = $(this);
                const options = {
                    responsive: true,
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
                    pageLength: 25,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries per page",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        paginate: {
                            first: "First",
                            last: "Last",
                            next: "Next",
                            previous: "Previous"
                        }
                    }
                };

                // Merge with data attributes
                const dataOptions = table.data('options');
                if (dataOptions) {
                    Object.assign(options, JSON.parse(dataOptions));
                }

                table.DataTable(options);
            });
        }
    }

    initializeFormValidation() {
        // Initialize form validation if the library exists
        if (typeof FormValidation !== 'undefined') {
            document.querySelectorAll('.fuma-form').forEach(form => {
                FormValidation.formValidation(form, {
                    fields: {
                        // Add validation rules here
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            eleValidClass: '',
                            rowSelector: '.mb-3'
                        }),
                        submitButton: new FormValidation.plugins.SubmitButton(),
                        defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                        icon: new FormValidation.plugins.Icon({
                            valid: 'ri-check-line',
                            invalid: 'ri-close-line',
                            validating: 'ri-refresh-line'
                        })
                    }
                });
            });
        }
    }

    setupFileUploads() {
        // Handle file uploads with preview
        document.querySelectorAll('.file-upload').forEach(input => {
            input.addEventListener('change', (e) => {
                this.handleFileUpload(e);
            });
        });
    }

    handleFileUpload(event) {
        const input = event.target;
        const preview = input.parentNode.querySelector('.file-preview');
        const file = input.files[0];

        if (file && preview) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `<div class="alert alert-info">File: ${file.name}</div>`;
            }
        }
    }

    async handleFormSubmission(event) {
        event.preventDefault();

        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        try {
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Processing...';

            // Get form data
            const formData = new FormData(form);
            const url = form.action;
            const method = form.method.toUpperCase();

            // Make API request
            const response = await this.makeApiRequest(url, method, formData);

            if (response.success) {
                this.showNotification('Success!', response.message || 'Operation completed successfully', 'success');

                // Redirect if specified
                if (response.redirect) {
                    window.location.href = response.redirect;
                } else if (form.dataset.redirect) {
                    window.location.href = form.dataset.redirect;
                }
            } else {
                this.showNotification('Error!', response.message || 'Operation failed', 'error');
            }
        } catch (error) {
            console.error('Form submission error:', error);
            this.showNotification('Error!', 'An unexpected error occurred', 'error');
        } finally {
            // Re-enable submit button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async makeApiRequest(url, method, data) {
        const headers = {
            'X-CSRF-TOKEN': this.csrfToken,
            'Accept': 'application/json'
        };

        // Add authorization header if we have a token
        if (sessionStorage.getItem('fuma_token')) {
            headers['Authorization'] = `Bearer ${sessionStorage.getItem('fuma_token')}`;
        }

        const options = {
            method: method,
            headers: headers,
            body: data
        };

        // For non-form data requests
        if (!(data instanceof FormData)) {
            options.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(data);
        }

        const response = await fetch(url, options);
        return await response.json();
    }

    handleDeleteConfirmation(event) {
        event.preventDefault();

        const button = event.target;
        const url = button.dataset.url || button.href;
        const itemName = button.dataset.itemName || 'this item';

        if (confirm(`Are you sure you want to delete ${itemName}? This action cannot be undone.`)) {
            this.performDelete(url);
        }
    }

    async performDelete(url) {
        try {
            const response = await this.makeApiRequest(url, 'DELETE');

            if (response.success) {
                this.showNotification('Success!', response.message || 'Item deleted successfully', 'success');

                // Reload page or remove element
                if (response.reload) {
                    window.location.reload();
                } else {
                    // Find and remove the deleted element
                    const element = document.querySelector(`[data-id="${response.deletedId}"]`);
                    if (element) {
                        element.remove();
                    }
                }
            } else {
                this.showNotification('Error!', response.message || 'Failed to delete item', 'error');
            }
        } catch (error) {
            console.error('Delete error:', error);
            this.showNotification('Error!', 'An unexpected error occurred', 'error');
        }
    }

    showNotification(title, message, type = 'info') {
        // Use toastr if available
        if (typeof toastr !== 'undefined') {
            toastr[type](message, title);
        } else {
            // Fallback to alert
            alert(`${title}: ${message}`);
        }
    }

    // Utility methods
    formatDate(dateString) {
        if (!dateString) return '';

        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR'
        }).format(amount);
    }

    // API helper methods
    async fetchTournaments(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        const url = `${this.apiBaseUrl}/tournaments${queryString ? '?' + queryString : ''}`;

        try {
            const response = await this.makeApiRequest(url, 'GET');
            return response.data || [];
        } catch (error) {
            console.error('Error fetching tournaments:', error);
            return [];
        }
    }

    async fetchTeams(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        const url = `${this.apiBaseUrl}/teams${queryString ? '?' + queryString : ''}`;

        try {
            const response = await this.makeApiRequest(url, 'GET');
            return response.data || [];
        } catch (error) {
            console.error('Error fetching teams:', error);
            return [];
        }
    }

    async fetchPlayers(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        const url = `${this.apiBaseUrl}/players${queryString ? '?' + queryString : ''}`;

        try {
            const response = await this.makeApiRequest(url, 'GET');
            return response.data || [];
        } catch (error) {
            console.error('Error fetching players:', error);
            return [];
        }
    }

    async fetchMatches(filters = {}) {
        const queryString = new URLSearchParams(filters).toString();
        const url = `${this.apiBaseUrl}/matches${queryString ? '?' + queryString : ''}`;

        try {
            const response = await this.makeApiRequest(url, 'GET');
            return response.data || [];
        } catch (error) {
            console.error('Error fetching matches:', error);
            return [];
        }
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.fumaBackoffice = new FumaBackoffice();
});

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = FumaBackoffice;
}

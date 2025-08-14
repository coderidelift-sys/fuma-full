// Utils.js - Utility Functions
class Utils {
    constructor() {
        this.auth = window.auth || null;
        this.api = window.api || null;
    }

    // DOM manipulation utilities
    createElement(tag, className = '', innerHTML = '') {
        const element = document.createElement(tag);
        if (className) element.className = className;
        if (innerHTML) element.innerHTML = innerHTML;
        return element;
    }

    // Create card element
    createCard(title, content, className = '') {
        const card = this.createElement('div', `card ${className}`);
        card.innerHTML = `
            <div class="card-header">
                <h5 class="card-title m-0">${title}</h5>
            </div>
            <div class="card-body">
                ${content}
            </div>
        `;
        return card;
    }

    // Create table row
    createTableRow(data, columns) {
        const row = this.createElement('tr');
        columns.forEach(column => {
            const cell = this.createElement('td');
            if (column.render) {
                cell.innerHTML = column.render(data[column.key], data);
            } else {
                cell.textContent = data[column.key] || '-';
            }
            row.appendChild(cell);
        });
        return row;
    }

    // Create table
    createTable(headers, data, columns, options = {}) {
        const table = this.createElement('table', 'table table-hover');

        // Create header
        const thead = this.createElement('thead');
        const headerRow = this.createElement('tr');
        headers.forEach(header => {
            const th = this.createElement('th', '', header);
            headerRow.appendChild(th);
        });
        thead.appendChild(headerRow);
        table.appendChild(thead);

        // Create body
        const tbody = this.createElement('tbody');
        if (data && data.length > 0) {
            data.forEach(item => {
                const row = this.createTableRow(item, columns);
                tbody.appendChild(row);
            });
        } else {
            const emptyRow = this.createElement('tr');
            const emptyCell = this.createElement('td', 'text-center py-4', `
                <div class="d-flex flex-column align-items-center">
                    <i class="ri-inbox-line ri-3x text-muted mb-2"></i>
                    <h6 class="text-muted">No data available</h6>
                    <p class="text-muted mb-0">${options.emptyMessage || 'No records found'}</p>
                </div>
            `);
            emptyCell.colSpan = headers.length;
            emptyRow.appendChild(emptyCell);
            tbody.appendChild(emptyRow);
        }
        table.appendChild(tbody);

        return table;
    }

    // Create pagination
    createPagination(currentPage, totalPages, onPageChange) {
        if (totalPages <= 1) return '';

        const pagination = this.createElement('nav');
        const ul = this.createElement('ul', 'pagination justify-content-center');

        // Previous button
        const prevLi = this.createElement('li', `page-item ${currentPage === 1 ? 'disabled' : ''}`);
        const prevLink = this.createElement('a', 'page-link', 'Previous');
        prevLink.href = '#';
        prevLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage > 1) onPageChange(currentPage - 1);
        });
        prevLi.appendChild(prevLink);
        ul.appendChild(prevLi);

        // Page numbers
        const startPage = Math.max(1, currentPage - 2);
        const endPage = Math.min(totalPages, currentPage + 2);

        for (let i = startPage; i <= endPage; i++) {
            const li = this.createElement('li', `page-item ${i === currentPage ? 'active' : ''}`);
            const link = this.createElement('a', 'page-link', i.toString());
            link.href = '#';
            link.addEventListener('click', (e) => {
                e.preventDefault();
                onPageChange(i);
            });
            li.appendChild(link);
            ul.appendChild(li);
        }

        // Next button
        const nextLi = this.createElement('li', `page-item ${currentPage === totalPages ? 'disabled' : ''}`);
        const nextLink = this.createElement('a', 'page-link', 'Next');
        nextLink.href = '#';
        nextLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (currentPage < totalPages) onPageChange(currentPage + 1);
        });
        nextLi.appendChild(nextLink);
        ul.appendChild(nextLi);

        pagination.appendChild(ul);
        return pagination;
    }

    // Create search and filter form
    createSearchForm(fields, onSearch, onReset) {
        const form = this.createElement('form', 'row g-3 mb-4');

        fields.forEach(field => {
            const col = this.createElement('div', `col-md-${field.col || 3}`);

            const label = this.createElement('label', 'form-label', field.label);
            label.setAttribute('for', field.name);

            let input;
            if (field.type === 'select') {
                input = this.createElement('select', 'form-select', field.placeholder ? `<option value="">${field.placeholder}</option>` : '');
                if (field.options) {
                    field.options.forEach(option => {
                        const optionElement = this.createElement('option', '', option.label);
                        optionElement.value = option.value;
                        input.appendChild(optionElement);
                    });
                }
            } else {
                input = this.createElement('input', 'form-control');
                input.type = field.type || 'text';
                input.placeholder = field.placeholder || '';
            }

            input.id = field.name;
            input.name = field.name;

            col.appendChild(label);
            col.appendChild(input);
            form.appendChild(col);
        });

        // Search and Reset buttons
        const buttonCol = this.createElement('div', 'col-md-12 text-end');
        const searchBtn = this.createElement('button', 'btn btn-primary me-2', 'Search');
        const resetBtn = this.createElement('button', 'btn btn-outline-secondary', 'Reset');

        searchBtn.type = 'submit';
        resetBtn.type = 'button';

        searchBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const formData = this.getFormData(form);
            onSearch(formData);
        });

        resetBtn.addEventListener('click', () => {
            form.reset();
            onReset();
        });

        buttonCol.appendChild(searchBtn);
        buttonCol.appendChild(resetBtn);
        form.appendChild(buttonCol);

        return form;
    }

    // Get form data
    getFormData(form) {
        const formData = {};
        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            if (input.name) {
                if (input.type === 'checkbox') {
                    formData[input.name] = input.checked;
                } else if (input.type === 'radio') {
                    if (input.checked) {
                        formData[input.name] = input.value;
                    }
                } else {
                    formData[input.name] = input.value;
                }
            }
        });

        return formData;
    }

    // Create modal
    createModal(id, title, content, footer = '') {
        const modal = this.createElement('div', 'modal fade', `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${content}
                    </div>
                    ${footer ? `<div class="modal-footer">${footer}</div>` : ''}
                </div>
            </div>
        `);

        modal.id = id;
        return modal;
    }

    // Show confirmation dialog
    showConfirm(message, onConfirm, onCancel) {
        const modal = this.createModal('confirmModal', 'Confirm Action', `
            <p>${message}</p>
        `, `
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
        `);

        document.body.appendChild(modal);

        const modalInstance = new bootstrap.Modal(modal);
        modalInstance.show();

        // Add event listeners
        const confirmBtn = modal.querySelector('#confirmBtn');
        confirmBtn.addEventListener('click', () => {
            modalInstance.hide();
            if (onConfirm) onConfirm();
        });

        modal.addEventListener('hidden.bs.modal', () => {
            if (onCancel) onCancel();
            modal.remove();
        });
    }

    // Show alert
    showAlert(message, type = 'info', duration = 5000) {
        const alert = this.createElement('div', `alert alert-${type} alert-dismissible fade show position-fixed`, `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `);

        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        document.body.appendChild(alert);

        // Auto remove
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, duration);
    }

    // Format currency
    formatCurrency(amount, currency = 'IDR') {
        if (amount === null || amount === undefined) return '-';

        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: currency
        }).format(amount);
    }

    // Format number
    formatNumber(number, decimals = 0) {
        if (number === null || number === undefined) return '-';

        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        }).format(number);
    }

    // Format percentage
    formatPercentage(value, decimals = 1) {
        if (value === null || value === undefined) return '-';

        return `${this.formatNumber(value, decimals)}%`;
    }

    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Local storage utilities
    setLocalStorage(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
        } catch (error) {
            console.error('Error saving to localStorage:', error);
        }
    }

    getLocalStorage(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('Error reading from localStorage:', error);
            return defaultValue;
        }
    }

    removeLocalStorage(key) {
        try {
            localStorage.removeItem(key);
        } catch (error) {
            console.error('Error removing from localStorage:', error);
        }
    }

    // Session storage utilities
    setSessionStorage(key, value) {
        try {
            sessionStorage.setItem(key, JSON.stringify(value));
        } catch (error) {
            console.error('Error saving to sessionStorage:', error);
        }
    }

    getSessionStorage(key, defaultValue = null) {
        try {
            const item = sessionStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch (error) {
            console.error('Error reading from sessionStorage:', error);
            return defaultValue;
        }
    }

    removeSessionStorage(key) {
        try {
            sessionStorage.removeItem(key);
        } catch (error) {
            console.error('Error removing from sessionStorage:', error);
        }
    }

    // URL utilities
    getUrlParams() {
        const params = new URLSearchParams(window.location.search);
        const result = {};
        for (const [key, value] of params) {
            result[key] = value;
        }
        return result;
    }

    setUrlParams(params) {
        const url = new URL(window.location);
        Object.keys(params).forEach(key => {
            if (params[key] !== null && params[key] !== undefined) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });
        window.history.pushState({}, '', url);
    }

    // Validation utilities
    validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    validatePhone(phone) {
        const re = /^[\+]?[0-9\s\-\(\)]{10,}$/;
        return re.test(phone);
    }

    validateRequired(value) {
        return value !== null && value !== undefined && value.toString().trim() !== '';
    }

    // File utilities
    validateFile(file, maxSize = 5 * 1024 * 1024, allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) {
        const errors = [];

        if (file.size > maxSize) {
            errors.push(`File size must be less than ${this.formatFileSize(maxSize)}`);
        }

        if (!allowedTypes.includes(file.type)) {
            errors.push(`File type must be one of: ${allowedTypes.join(', ')}`);
        }

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Date utilities
    isValidDate(dateString) {
        const date = new Date(dateString);
        return date instanceof Date && !isNaN(date);
    }

    getDateRange(days) {
        const end = new Date();
        const start = new Date();
        start.setDate(start.getDate() - days);
        return { start, end };
    }

    // Color utilities
    getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // String utilities
    capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    truncateText(text, maxLength = 100) {
        if (!text || text.length <= maxLength) return text;
        return text.substring(0, maxLength) + '...';
    }

    slugify(text) {
        return text
            .toString()
            .toLowerCase()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-')
            .replace(/^-+/, '')
            .replace(/-+$/, '');
    }
}

// Create global utils instance
const utils = new Utils();

// Export for use in other files
if (typeof module !== 'undefined' && module.exports) {
    module.exports = Utils;
}

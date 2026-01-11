// WMedi Plus Healthcare Platform JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize WMedi Plus
    console.log('WMedi Plus Healthcare Platform Loaded');
    
    // Initialize all components
    initializeForms();
    initializeNavigation();
});

/**
 * Initialize form handlers
 */
function initializeForms() {
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

/**
 * Validate form inputs
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('error');
            isValid = false;
        } else {
            input.classList.remove('error');
        }
    });

    return isValid;
}

/**
 * Initialize navigation handlers
 */
function initializeNavigation() {
    // Smooth scroll navigation
    const navLinks = document.querySelectorAll('a[href^="#"]');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
}

/**
 * Show error message
 */
function showError(message) {
    const errorEl = document.createElement('div');
    errorEl.className = 'error-message';
    errorEl.textContent = message;
    errorEl.style.cssText = `
        background: #fee;
        color: #c00;
        padding: 12px;
        margin: 10px 0;
        border-radius: 4px;
        border-left: 4px solid #c00;
    `;
    return errorEl;
}

/**
 * Show success message
 */
function showSuccess(message) {
    const successEl = document.createElement('div');
    successEl.className = 'success-message';
    successEl.textContent = message;
    successEl.style.cssText = `
        background: #efe;
        color: #0a0;
        padding: 12px;
        margin: 10px 0;
        border-radius: 4px;
        border-left: 4px solid #0a0;
    `;
    return successEl;
}

/**
 * Handle API requests
 */
async function wmediApiRequest(action, data) {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('nonce', wmediNonce);

    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });

    try {
        const response = await fetch(wmediAjaxUrl, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error:', error);
        return { success: false, data: { message: 'An error occurred. Please try again.' } };
    }
}

/**
 * Format date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

/**
 * Format time
 */
function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

/**
 * Get URL parameter
 */
function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

/**
 * Store data in localStorage
 */
const WMediStorage = {
    set(key, value) {
        localStorage.setItem('wmedi_' + key, JSON.stringify(value));
    },
    get(key) {
        const value = localStorage.getItem('wmedi_' + key);
        return value ? JSON.parse(value) : null;
    },
    remove(key) {
        localStorage.removeItem('wmedi_' + key);
    },
    clear() {
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('wmedi_')) {
                localStorage.removeItem(key);
            }
        });
    }
};

/**
 * Format appointment status
 */
function getStatusBadge(status) {
    const badges = {
        'scheduled': '<span class="badge" style="background: #e7f3ff; color: var(--primary-color);">Scheduled</span>',
        'confirmed': '<span class="badge" style="background: #e8f5e9; color: var(--success-color);">Confirmed</span>',
        'completed': '<span class="badge" style="background: #f3e5f5; color: #7b1fa2;">Completed</span>',
        'cancelled': '<span class="badge" style="background: #ffebee; color: var(--danger-color);">Cancelled</span>'
    };
    return badges[status] || badges['scheduled'];
}

/**
 * Initialize dashboard if needed
 */
function initializeDashboard() {
    // Add dashboard-specific initialization here
    console.log('Dashboard initialized');
}

// Export functions for external use
window.wmediApiRequest = wmediApiRequest;
window.WMediStorage = WMediStorage;
window.showError = showError;
window.showSuccess = showSuccess;
window.formatDate = formatDate;
window.formatTime = formatTime;
window.getUrlParam = getUrlParam;
window.getStatusBadge = getStatusBadge;

/**
 * Advanced Toast Notification System
 * Handles all toast notifications for admin panel
 */

(function() {
    'use strict';

    // Initialize Notyf if available, otherwise use Bootstrap toasts
    let notyf = null;
    if (typeof Notyf !== 'undefined') {
        notyf = new Notyf({
            duration: 4000,
            position: {
                x: 'right',
                y: 'top',
            },
            types: [
                {
                    type: 'success',
                    background: '#3ea572',
                    icon: {
                        className: 'ri-check-line',
                        tagName: 'i',
                        text: ''
                    }
                },
                {
                    type: 'error',
                    background: '#dc3545',
                    icon: {
                        className: 'ri-error-warning-line',
                        tagName: 'i',
                        text: ''
                    }
                },
                {
                    type: 'warning',
                    background: '#ffc107',
                    icon: {
                        className: 'ri-alert-line',
                        tagName: 'i',
                        text: ''
                    }
                },
                {
                    type: 'info',
                    background: '#0dcaf0',
                    icon: {
                        className: 'ri-information-line',
                        tagName: 'i',
                        text: ''
                    }
                }
            ]
        });
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'success') {
        if (notyf) {
            notyf.open({
                type: type,
                message: message
            });
        } else {
            // Fallback to Bootstrap toast
            showBootstrapToast(message, type);
        }
    }

    /**
     * Bootstrap toast fallback
     */
    function showBootstrapToast(message, type = 'success') {
        const toastContainer = document.getElementById('toast-container') || createToastContainer();
        
        const toastId = 'toast-' + Date.now();
        const iconClass = {
            'success': 'ri-checkbox-circle-fill text-success',
            'error': 'ri-error-warning-fill text-danger',
            'warning': 'ri-alert-fill text-warning',
            'info': 'ri-information-fill text-info'
        }[type] || 'ri-information-fill text-info';

        const toastHTML = `
            <div id="${toastId}" class="bs-toast toast fade show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="toast-header">
                    <i class="icon-base ${iconClass} icon-sm me-2"></i>
                    <div class="me-auto fw-medium">${type.charAt(0).toUpperCase() + type.slice(1)}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHTML);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();

        // Remove element after hide
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    /**
     * Create toast container if it doesn't exist
     */
    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '9999';
        document.body.appendChild(container);
        return container;
    }

    /**
     * Handle session messages
     */
    function handleSessionMessages() {
        // Success messages
        const successElements = document.querySelectorAll('[data-session-success]');
        successElements.forEach(el => {
            const message = el.getAttribute('data-session-success');
            if (message) {
                showToast(message, 'success');
                el.remove();
            }
        });

        // Error messages
        const errorElements = document.querySelectorAll('[data-session-error]');
        errorElements.forEach(el => {
            const message = el.getAttribute('data-session-error');
            if (message) {
                showToast(message, 'error');
                el.remove();
            }
        });

        // Warning messages
        const warningElements = document.querySelectorAll('[data-session-warning]');
        warningElements.forEach(el => {
            const message = el.getAttribute('data-session-warning');
            if (message) {
                showToast(message, 'warning');
                el.remove();
            }
        });

        // Info messages
        const infoElements = document.querySelectorAll('[data-session-info]');
        infoElements.forEach(el => {
            const message = el.getAttribute('data-session-info');
            if (message) {
                showToast(message, 'info');
                el.remove();
            }
        });
    }

    /**
     * Handle AJAX form submissions
     */
    function handleAjaxForms() {
        // Handle delete forms
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.classList.contains('delete-form') || form.id && form.id.includes('delete')) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const url = form.action;
                const method = formData.get('_method') || 'POST';

                fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Operation completed successfully!', 'success');
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else {
                            location.reload();
                        }
                    } else {
                        showToast(data.message || 'An error occurred!', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            handleSessionMessages();
            handleAjaxForms();
        });
    } else {
        handleSessionMessages();
        handleAjaxForms();
    }

    // Make showToast available globally
    window.showToast = showToast;
})();

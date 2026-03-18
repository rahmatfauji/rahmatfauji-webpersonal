/**
 * Toast Notification System
 */
class Toast {
    static show(message, type = 'info', duration = 3000) {
        const container = document.getElementById('toast-container') || this.createContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast fade-in`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        
        let bgClass = 'bg-info';
        let icon = 'ℹ️';
        
        if (type === 'success') {
            bgClass = 'bg-success';
            icon = '✓';
        } else if (type === 'error' || type === 'danger') {
            bgClass = 'bg-danger';
            icon = '✕';
        } else if (type === 'warning') {
            bgClass = 'bg-warning';
            icon = '⚠';
        }
        
        toast.innerHTML = `
            <div class="${bgClass} text-white p-3 rounded" style="box-shadow: 0 8px 24px rgba(0,0,0,0.15); animation: slideInRight 0.3s ease-out;">
                <div class="d-flex gap-2 align-items-center">
                    <span style="font-size: 1.2rem;">${icon}</span>
                    <span>${message}</span>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
        
        container.appendChild(toast);
        
        const bsToast = new bootstrap.Toast(toast, { delay: duration });
        bsToast.show();
        
        toast.addEventListener('hidden.bs.toast', () => toast.remove());
    }
    
    static createContainer() {
        const container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position: fixed; top: 1rem; right: 1rem; z-index: 9999;';
        document.body.appendChild(container);
        return container;
    }
}

/**
 * Form Validation with Real-time Feedback
 */
class FormValidator {
    static init() {
        const forms = document.querySelectorAll('form[data-validate="true"]');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', () => this.validateField(input));
                input.addEventListener('input', () => {
                    if (input.classList.contains('is-invalid')) {
                        this.validateField(input);
                    }
                });
            });
            
            form.addEventListener('submit', (e) => {
                if (!this.validateForm(form)) {
                    e.preventDefault();
                }
            });
        });
    }
    
    static validateField(input) {
        const feedback = input.parentElement.querySelector('.invalid-feedback');
        
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            if (feedback) feedback.textContent = 'This field is required';
            return false;
        }
        
        if (input.type === 'email') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                input.classList.add('is-invalid');
                if (feedback) feedback.textContent = 'Please enter a valid email';
                return false;
            }
        }
        
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        return true;
    }
    
    static validateForm(form) {
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }
}

/**
 * Smooth Delete Confirmation Modal
 */
class DeleteConfirm {
    static init() {
        document.addEventListener('submit', (e) => {
            const form = e.target;
            
            if (form.querySelector('input[name="_method"][value="DELETE"]') || 
                form.method.toUpperCase() === 'DELETE') {
                e.preventDefault();
                
                const itemName = form.closest('[data-delete-item]')?.getAttribute('data-delete-item') || 'this item';
                
                this.show(itemName, () => form.submit());
            }
        });
    }
    
    static show(itemName, onConfirm) {
        const modal = document.createElement('div');
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        modal.innerHTML = `
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title">⚠️ Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to delete <strong>${itemName}</strong>? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        const bsModal = new bootstrap.Modal(modal);
        
        modal.querySelector('#confirm-delete-btn').addEventListener('click', () => {
            bsModal.hide();
            onConfirm();
        });
        
        modal.addEventListener('hidden.bs.modal', () => modal.remove());
        bsModal.show();
    }
}

/**
 * Lazy Loading Images
 */
class LazyLoad {
    static init() {
        if ('IntersectionObserver' in window) {
            const images = document.querySelectorAll('img[data-src]');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.getAttribute('data-src');
                        img.removeAttribute('data-src');
                        img.classList.add('fade-in');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px'
            });
            
            images.forEach(img => imageObserver.observe(img));
        } else {
            // Fallback for older browsers
            document.querySelectorAll('img[data-src]').forEach(img => {
                img.src = img.getAttribute('data-src');
                img.removeAttribute('data-src');
            });
        }
    }
}

/**
 * Scroll to Top Button
 */
class ScrollToTop {
    static init() {
        const btn = document.getElementById('scroll-to-top-btn');
        if (!btn) return;
        
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            } else {
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            }
        });
        
        btn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

/**
 * Smooth Scroll Behavior
 */
class SmoothScroll {
    static init() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const href = anchor.getAttribute('href');
                if (href === '#') return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }
}

/**
 * Fade In Animation for Elements
 */
class FadeInOnScroll {
    static init() {
        if ('IntersectionObserver' in window) {
            const elements = document.querySelectorAll('[data-fade-in]');
            
            const fadeObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-active');
                        fadeObserver.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });
            
            elements.forEach(el => fadeObserver.observe(el));
        }
    }
}

/**
 * Skeleton loader for images
 */
class SkeletonImages {
    static init() {
        const images = document.querySelectorAll('img.js-skeleton-image');

        images.forEach((img) => {
            const container = img.closest('.image-skeleton');
            if (!container) return;

            const markLoaded = () => container.classList.add('is-loaded');

            if (img.complete && img.naturalWidth > 0) {
                markLoaded();
            } else {
                img.addEventListener('load', markLoaded, { once: true });
                img.addEventListener('error', markLoaded, { once: true });
            }
        });
    }
}

/**
 * Initialize all modules
 */
document.addEventListener('DOMContentLoaded', () => {
    FormValidator.init();
    DeleteConfirm.init();
    LazyLoad.init();
    ScrollToTop.init();
    SmoothScroll.init();
    FadeInOnScroll.init();
    SkeletonImages.init();
});

// Export for use in other scripts
window.Toast = Toast;
window.FormValidator = FormValidator;
window.DeleteConfirm = DeleteConfirm;
window.SkeletonImages = SkeletonImages;

// Performance optimization utilities
class PerformanceOptimizer {
    constructor() {
        this.init();
    }

    init() {
        this.setupLazyLoading();
        this.setupDebouncing();
        this.setupCaching();
        this.monitorPerformance();
    }

    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const element = entry.target;
                        
                        if (element.tagName === 'IMG' && element.dataset.src) {
                            element.src = element.dataset.src;
                            element.classList.remove('lazy');
                            observer.unobserve(element);
                        }
                        
                        if (element.classList.contains('lazy-content')) {
                            element.classList.add('loaded');
                            observer.unobserve(element);
                        }
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('img[data-src], .lazy-content').forEach(el => {
                observer.observe(el);
            });
        }
    }

    setupDebouncing() {
        window.debounce = function(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        };

        window.throttle = function(func, limit) {
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
        };
    }

    setupCaching() {
        window.cache = new Map();
        
        window.cachedFetch = function(url, options = {}) {
            const cacheKey = url + JSON.stringify(options);
            
            if (window.cache.has(cacheKey)) {
                return Promise.resolve(window.cache.get(cacheKey));
            }
            
            return fetch(url, options)
                .then(response => response.json())
                .then(data => {
                    window.cache.set(cacheKey, data);
                    return data;
                });
        };
    }

    monitorPerformance() {
        window.addEventListener('load', () => {
            const loadTime = performance.now();
            if (loadTime > 3000) {
                console.warn(`Slow page load: ${loadTime.toFixed(2)}ms`);
            }
        });
    }

    static optimizeForm(formId) {
        const form = document.getElementById(formId);
        if (!form) return;

        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn?.innerHTML;

        form.addEventListener('submit', function(e) {
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mx-auto"></div>';
            }

            setTimeout(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }, 5000);
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new PerformanceOptimizer();
    });
} else {
    new PerformanceOptimizer();
}

window.PerformanceOptimizer = PerformanceOptimizer;
<!-- Performance Optimization Component -->
<script>
// Lazy loading untuk gambar
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// Debounce untuk search dan input
function debounce(func, wait) {
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

// Cache untuk API calls
const apiCache = new Map();
function cachedFetch(url, options = {}) {
    const cacheKey = url + JSON.stringify(options);
    if (apiCache.has(cacheKey)) {
        return Promise.resolve(apiCache.get(cacheKey));
    }
    
    return fetch(url, options)
        .then(response => response.json())
        .then(data => {
            apiCache.set(cacheKey, data);
            return data;
        });
}

// Preload critical resources
function preloadCriticalResources() {
    const criticalCSS = document.createElement('link');
    criticalCSS.rel = 'preload';
    criticalCSS.as = 'style';
    criticalCSS.href = 'https://cdn.tailwindcss.com';
    document.head.appendChild(criticalCSS);
}

preloadCriticalResources();
</script>

<style>
/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.lazy {
    opacity: 0;
    transition: opacity 0.3s;
}

/* Skeleton loading */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

/* Smooth transitions */
* {
    transition: transform 0.2s ease, opacity 0.2s ease;
}

/* Reduce motion for accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
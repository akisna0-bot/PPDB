// Image Preview Handler untuk PPDB
class ImagePreview {
    constructor() {
        this.modal = null;
        this.init();
    }

    init() {
        this.createModal();
        this.bindEvents();
    }

    createModal() {
        // Create modal if not exists
        if (!document.getElementById('imageModal')) {
            const modal = document.createElement('div');
            modal.id = 'imageModal';
            modal.className = 'image-modal';
            modal.style.display = 'none';
            modal.innerHTML = `
                <div class="modal-content" style="position: relative; max-width: 90%; max-height: 90%;">
                    <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 100%; object-fit: contain; border-radius: 8px;">
                    <button id="closeModal" style="position: absolute; top: -40px; right: 0; background: rgba(255,255,255,0.9); border: none; border-radius: 50%; width: 35px; height: 35px; font-size: 18px; cursor: pointer; color: #333;">×</button>
                    <div id="imageInfo" style="position: absolute; bottom: -40px; left: 0; right: 0; text-align: center; color: white; font-size: 14px;"></div>
                </div>
            `;
            document.body.appendChild(modal);
            this.modal = modal;
        }
    }

    bindEvents() {
        // Close modal events
        document.addEventListener('click', (e) => {
            if (e.target.id === 'imageModal' || e.target.id === 'closeModal') {
                this.closeModal();
            }
        });

        // ESC key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeModal();
            }
        });

        // Auto-bind click events to images with data-preview attribute
        document.addEventListener('click', (e) => {
            if (e.target.hasAttribute('data-preview')) {
                e.preventDefault();
                const src = e.target.getAttribute('data-preview') || e.target.src;
                const alt = e.target.alt || 'Preview Image';
                this.openModal(src, alt);
            }
        });
    }

    openModal(src, alt = '', info = '') {
        const modal = document.getElementById('imageModal');
        const img = document.getElementById('modalImage');
        const infoDiv = document.getElementById('imageInfo');
        
        if (modal && img) {
            img.src = src;
            img.alt = alt;
            infoDiv.textContent = info || alt;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }
    }

    closeModal() {
        const modal = document.getElementById('imageModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = ''; // Restore scroll
        }
    }

    // Static method for easy access
    static open(src, alt, info) {
        if (!window.imagePreviewInstance) {
            window.imagePreviewInstance = new ImagePreview();
        }
        window.imagePreviewInstance.openModal(src, alt, info);
    }

    static close() {
        if (window.imagePreviewInstance) {
            window.imagePreviewInstance.closeModal();
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.imagePreviewInstance = new ImagePreview();
});

// Global functions for backward compatibility
function openImageModal(src, alt, info) {
    ImagePreview.open(src, alt, info);
}

function closeImageModal() {
    ImagePreview.close();
}

// Utility function to make images previewable
function makeImagePreviewable(selector) {
    document.querySelectorAll(selector).forEach(img => {
        img.setAttribute('data-preview', img.src);
        img.style.cursor = 'pointer';
        img.title = 'Klik untuk memperbesar';
    });
}

// Auto-apply to common image selectors
document.addEventListener('DOMContentLoaded', () => {
    makeImagePreviewable('.document-thumbnail');
    makeImagePreviewable('.preview-image');
    makeImagePreviewable('[data-preview]');
});
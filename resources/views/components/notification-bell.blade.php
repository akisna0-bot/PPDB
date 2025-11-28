<div class="relative" x-data="{ open: false, unreadCount: 0 }" x-init="fetchUnreadCount()">
    <!-- Notification Bell -->
    <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-gray-900 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Badge -->
        <span x-show="unreadCount > 0" 
              x-text="unreadCount" 
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
        </span>
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border z-50">
         
        <div class="p-4 border-b">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold">Notifikasi</h3>
                <button @click="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>
        
        <div class="max-h-96 overflow-y-auto" id="notifications-list">
            <!-- Notifications will be loaded here -->
            <div class="p-4 text-center text-gray-500">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto"></div>
                <p class="mt-2">Memuat notifikasi...</p>
            </div>
        </div>
        
        <div class="p-4 border-t">
            <a href="/notifications" class="block text-center text-sm text-blue-600 hover:text-blue-800">
                Lihat Semua Notifikasi
            </a>
        </div>
    </div>
</div>

<script>
function fetchUnreadCount() {
    fetch('/notifications/unread-count')
        .then(response => response.json())
        .then(data => {
            this.unreadCount = data.count;
        });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            this.unreadCount = 0;
            loadNotifications();
        }
    });
}

function loadNotifications() {
    fetch('/notifications/recent')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('notifications-list');
            if (data.notifications.length === 0) {
                container.innerHTML = `
                    <div class="p-4 text-center text-gray-500">
                        <p>Tidak ada notifikasi</p>
                    </div>
                `;
            } else {
                container.innerHTML = data.notifications.map(notification => `
                    <div class="p-4 border-b hover:bg-gray-50 ${!notification.is_read ? 'bg-blue-50' : ''}">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center ${getNotificationColor(notification.type)}">
                                    ${getNotificationIcon(notification.type)}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                <p class="text-sm text-gray-600">${notification.message}</p>
                                <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        });
}

function getNotificationColor(type) {
    const colors = {
        'info': 'bg-blue-100 text-blue-600',
        'success': 'bg-green-100 text-green-600',
        'warning': 'bg-yellow-100 text-yellow-600',
        'error': 'bg-red-100 text-red-600'
    };
    return colors[type] || colors['info'];
}

function getNotificationIcon(type) {
    const icons = {
        'info': 'ℹ️',
        'success': '✅',
        'warning': '⚠️',
        'error': '❌'
    };
    return icons[type] || icons['info'];
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);
    
    if (minutes < 1) return 'Baru saja';
    if (minutes < 60) return `${minutes} menit lalu`;
    if (hours < 24) return `${hours} jam lalu`;
    if (days < 7) return `${days} hari lalu`;
    return date.toLocaleDateString('id-ID');
}

// Load notifications when dropdown opens
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationBell', () => ({
        open: false,
        unreadCount: 0,
        init() {
            this.fetchUnreadCount();
            // Refresh every 30 seconds
            setInterval(() => this.fetchUnreadCount(), 30000);
        },
        fetchUnreadCount() {
            fetch('/notifications/unread-count')
                .then(response => response.json())
                .then(data => {
                    this.unreadCount = data.count;
                });
        },
        toggleDropdown() {
            this.open = !this.open;
            if (this.open) {
                loadNotifications();
            }
        }
    }));
});
</script>
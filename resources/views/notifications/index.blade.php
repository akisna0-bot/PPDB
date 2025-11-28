@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <h1 class="text-3xl font-bold text-white mb-2">🔔 Notifikasi</h1>
                <p class="text-blue-100">Semua pemberitahuan terkait pendaftaran Anda</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="loadNotifications()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        🔄 Refresh
                    </button>
                    <button onclick="markAllAsRead()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                        ✅ Tandai Semua Dibaca
                    </button>
                </div>
                <div class="text-sm text-gray-600">
                    <span id="unreadCount">0</span> notifikasi belum dibaca
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div id="notificationsList" class="divide-y divide-gray-200">
                <!-- Loading state -->
                <div class="text-center py-8">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-500">Memuat notifikasi...</p>
                </div>
            </div>
        </div>

        <!-- Back to Dashboard -->
        <div class="text-center mt-6">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script>
function loadNotifications() {
    fetch('/notifications/api')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('notificationsList');
            const unreadCountEl = document.getElementById('unreadCount');
            
            if (data.notifications && data.notifications.length > 0) {
                const unreadCount = data.notifications.filter(n => !n.is_read).length;
                unreadCountEl.textContent = unreadCount;
                
                container.innerHTML = data.notifications.map(notification => `
                    <div class="p-6 hover:bg-gray-50 cursor-pointer ${!notification.is_read ? 'bg-blue-50 border-l-4 border-blue-500' : ''}" 
                         onclick="markAsRead(${notification.id})">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-4 h-4 rounded-full ${getNotificationColor(notification.type)} mt-1"></div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">${notification.title}</h3>
                                    <div class="flex items-center space-x-2">
                                        ${!notification.is_read ? '<span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}
                                        <span class="text-sm text-gray-500">${formatDate(notification.created_at)}</span>
                                    </div>
                                </div>
                                <p class="text-gray-700 leading-relaxed">${notification.message}</p>
                                <div class="mt-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getNotificationBadge(notification.type)}">
                                        ${getNotificationTypeText(notification.type)}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                unreadCountEl.textContent = '0';
                container.innerHTML = `
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🔕</div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Notifikasi</h3>
                        <p class="text-gray-500">Notifikasi terkait pendaftaran Anda akan muncul di sini</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsList').innerHTML = `
                <div class="text-center py-8 text-red-500">
                    <div class="text-4xl mb-2">❌</div>
                    <p>Gagal memuat notifikasi</p>
                    <button onclick="loadNotifications()" class="mt-2 text-blue-600 hover:text-blue-800">
                        Coba Lagi
                    </button>
                </div>
            `;
        });
}

function getNotificationColor(type) {
    switch(type) {
        case 'success': return 'bg-green-500';
        case 'error': return 'bg-red-500';
        case 'warning': return 'bg-yellow-500';
        default: return 'bg-blue-500';
    }
}

function getNotificationBadge(type) {
    switch(type) {
        case 'success': return 'bg-green-100 text-green-800';
        case 'error': return 'bg-red-100 text-red-800';
        case 'warning': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-blue-100 text-blue-800';
    }
}

function getNotificationTypeText(type) {
    switch(type) {
        case 'success': return 'Berhasil';
        case 'error': return 'Perlu Perhatian';
        case 'warning': return 'Peringatan';
        default: return 'Informasi';
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long', 
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking notification as read:', error));
}

function markAllAsRead() {
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(() => loadNotifications())
    .catch(error => console.error('Error marking all notifications as read:', error));
}

// Load notifications when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications();
});
</script>
@endsection
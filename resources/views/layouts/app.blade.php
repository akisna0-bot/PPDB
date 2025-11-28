<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script>
        // Refresh CSRF token setiap 30 menit
        setInterval(function() {
            fetch('/csrf-token')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('meta[name="csrf-token"]').setAttribute('content', data.csrf_token);
                })
                .catch(error => console.log('CSRF refresh failed'));
        }, 1800000); // 30 menit
    </script>
    <title>{{ config('app.name', 'Laravel') }} - PPDB</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }
    </style>
    
    @auth
    @if(auth()->user()->role === 'siswa' || auth()->user()->role === 'user')
    <script>
        // Notification functions
        function loadNotifications() {
            fetch('/notifications/api')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const notificationBadge = document.getElementById('notificationBadge');
                    
                    if (data.notifications && data.notifications.length > 0) {
                        notificationList.innerHTML = data.notifications.map(notification => `
                            <div class="px-4 py-3 hover:bg-gray-50 ${!notification.is_read ? 'bg-blue-50' : ''}" onclick="markAsRead(${notification.id})">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 mt-2 rounded-full ${getNotificationColor(notification.type)}"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm font-medium text-gray-900">${notification.title}</p>
                                        <p class="text-sm text-gray-600">${notification.message}</p>
                                        <p class="text-xs text-gray-400 mt-1">${formatDate(notification.created_at)}</p>
                                    </div>
                                </div>
                            </div>
                        `).join('');
                        
                        const unreadCount = data.notifications.filter(n => !n.is_read).length;
                        if (unreadCount > 0) {
                            notificationBadge.textContent = unreadCount;
                            notificationBadge.classList.remove('hidden');
                        } else {
                            notificationBadge.classList.add('hidden');
                        }
                    } else {
                        notificationList.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 text-center">Tidak ada notifikasi</div>';
                        notificationBadge.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        
        function getNotificationColor(type) {
            switch(type) {
                case 'success': return 'bg-green-500';
                case 'error': return 'bg-red-500';
                case 'warning': return 'bg-yellow-500';
                default: return 'bg-blue-500';
            }
        }
        
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
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
        
        // Toggle notification dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notificationButton');
            const notificationDropdown = document.getElementById('notificationDropdown');
            
            if (notificationButton && notificationDropdown) {
                notificationButton.addEventListener('click', function() {
                    notificationDropdown.classList.toggle('hidden');
                    if (!notificationDropdown.classList.contains('hidden')) {
                        loadNotifications();
                    }
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!notificationButton.contains(event.target) && !notificationDropdown.contains(event.target)) {
                        notificationDropdown.classList.add('hidden');
                    }
                });
                
                // Load notifications on page load
                loadNotifications();
                
                // Refresh notifications every 30 seconds
                setInterval(loadNotifications, 30000);
            }
        });
    </script>
    @endif
    @endauth
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</body>
</html>
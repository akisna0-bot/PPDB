<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-white font-bold text-lg">🏫</span>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-blue-600">PPDB SMK BAKTI NUSANTARA 666</span>
                            <div class="text-xs text-gray-500">Sistem Penerimaan Peserta Didik Baru</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role === 'admin'): ?>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard Admin
                            </a>
                        <?php elseif(auth()->user()->role === 'kepsek'): ?>
                            <a href="<?php echo e(route('kepsek.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard Kepsek
                            </a>
                        <?php elseif(auth()->user()->role === 'keuangan'): ?>
                            <a href="<?php echo e(route('keuangan.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard Keuangan
                            </a>
                        <?php elseif(auth()->user()->role === 'verifikator_adm' || auth()->user()->role === 'verifikator'): ?>
                            <a href="<?php echo e(route('verifikator.dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard Verifikator
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                Dashboard
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <?php if(auth()->guard()->check()): ?>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <!-- Notifications for students -->
                <?php if(auth()->user()->role === 'siswa' || auth()->user()->role === 'user'): ?>
                <div class="relative mr-4">
                    <button id="notificationButton" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span id="notificationBadge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full hidden"></span>
                    </button>
                    
                    <!-- Notification Dropdown -->
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg z-50">
                        <div class="py-2">
                            <div class="px-4 py-2 text-sm font-semibold text-gray-700 border-b">Notifikasi</div>
                            <div id="notificationList" class="max-h-64 overflow-y-auto">
                                <!-- Notifications will be loaded here -->
                            </div>
                            <div class="px-4 py-2 text-center border-t">
                                <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">Tandai Semua Dibaca</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <div class="ml-3 relative">
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-700"><?php echo e(auth()->user()->nama ?? auth()->user()->name); ?></span>
                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full"><?php echo e(ucfirst(auth()->user()->role)); ?></span>
                        
                        <a href="#" onclick="refreshTokenAndLogout()" class="text-sm text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition duration-150 ease-in-out">
                            Logout
                        </a>
                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                            <?php echo csrf_field(); ?>
                        </form>
                        
                        <script>
                        function refreshTokenAndLogout() {
                            // Refresh CSRF token sebelum logout
                            fetch('/csrf-token')
                                .then(response => response.json())
                                .then(data => {
                                    // Update CSRF token di form
                                    const csrfInput = document.querySelector('#logout-form input[name="_token"]');
                                    if (csrfInput) {
                                        csrfInput.value = data.csrf_token;
                                    }
                                    // Submit form logout
                                    document.getElementById('logout-form').submit();
                                })
                                .catch(error => {
                                    // Jika gagal refresh token, tetap coba logout
                                    document.getElementById('logout-form').submit();
                                });
                        }
                        </script>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>
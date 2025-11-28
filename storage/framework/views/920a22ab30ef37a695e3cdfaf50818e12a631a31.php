<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <h1 class="text-3xl font-bold text-white mb-2">🎓 Dashboard Siswa</h1>
                <p class="text-blue-100">Selamat datang, <?php echo e(auth()->user()->name); ?>!</p>
                <p class="text-blue-200 text-sm">PPDB SMK Bakti Nusantara 666 - 2025</p>
            </div>
        </div>

        <?php
            $applicant = App\Models\Applicant::with(['major', 'wave', 'files', 'payments'])
                ->where('user_id', auth()->id())->first();
        ?>

        <!-- Status Progress -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">📊 Status Pendaftaran</h2>
            
            <?php if(!$applicant): ?>
                <div class="text-center py-8">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Mendaftar</h3>
                    <p class="text-gray-600 mb-4">Silakan lengkapi formulir pendaftaran terlebih dahulu</p>
                    <a href="<?php echo e(route('pendaftaran.create')); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        📝 Mulai Pendaftaran
                    </a>
                </div>
            <?php else: ?>
                <!-- Progress Steps -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center <?php echo e(in_array($applicant->status, ['SUBMIT', 'VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']) || $applicant->final_status ? 'text-green-600' : 'text-gray-400'); ?>">
                        <div class="w-8 h-8 rounded-full <?php echo e(in_array($applicant->status, ['SUBMIT', 'VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']) || $applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?> flex items-center justify-center text-white text-sm font-bold">1</div>
                        <span class="ml-2 text-sm font-medium">Pendaftaran</span>
                    </div>
                    
                    <div class="flex-1 h-1 mx-4 <?php echo e(in_array($applicant->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']) || $applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?>"></div>
                    
                    <div class="flex items-center <?php echo e(in_array($applicant->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']) || $applicant->final_status ? 'text-green-600' : 'text-gray-400'); ?>">
                        <div class="w-8 h-8 rounded-full <?php echo e(in_array($applicant->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']) || $applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?> flex items-center justify-center text-white text-sm font-bold">2</div>
                        <span class="ml-2 text-sm font-medium">Verifikasi</span>
                    </div>
                    
                    <div class="flex-1 h-1 mx-4 <?php echo e($applicant->status == 'PAYMENT_VERIFIED' || $applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?>"></div>
                    
                    <div class="flex items-center <?php echo e($applicant->status == 'PAYMENT_VERIFIED' || $applicant->final_status ? 'text-green-600' : 'text-gray-400'); ?>">
                        <div class="w-8 h-8 rounded-full <?php echo e($applicant->status == 'PAYMENT_VERIFIED' || $applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?> flex items-center justify-center text-white text-sm font-bold">3</div>
                        <span class="ml-2 text-sm font-medium">Pembayaran</span>
                    </div>
                    
                    <div class="flex-1 h-1 mx-4 <?php echo e($applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?>"></div>
                    
                    <div class="flex items-center <?php echo e($applicant->final_status ? 'text-green-600' : 'text-gray-400'); ?>">
                        <div class="w-8 h-8 rounded-full <?php echo e($applicant->final_status ? 'bg-green-600' : 'bg-gray-300'); ?> flex items-center justify-center text-white text-sm font-bold">4</div>
                        <span class="ml-2 text-sm font-medium">Pengumuman</span>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <?php if($applicant->final_status == 'ACCEPTED'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">🎉</div>
                            <h3 class="text-2xl font-bold text-green-600 mb-2">SELAMAT! ANDA DITERIMA</h3>
                            <p class="text-gray-600"><?php echo e($applicant->final_notes ?? 'Selamat bergabung di SMK Bakti Nusantara 666!'); ?></p>
                        </div>
                    <?php elseif($applicant->final_status == 'REJECTED'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">😔</div>
                            <h3 class="text-2xl font-bold text-red-600 mb-2">MOHON MAAF</h3>
                            <p class="text-gray-600"><?php echo e($applicant->final_notes ?? 'Anda belum berhasil pada seleksi kali ini.'); ?></p>
                        </div>
                    <?php elseif($applicant->status == 'PAYMENT_VERIFIED'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">⏳</div>
                            <h3 class="text-xl font-semibold text-blue-600 mb-2">Menunggu Pengumuman</h3>
                            <p class="text-gray-600">Pembayaran telah dikonfirmasi. Menunggu keputusan akhir dari sekolah.</p>
                        </div>
                    <?php elseif($applicant->status == 'PAYMENT_PENDING'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">💰</div>
                            <h3 class="text-xl font-semibold text-green-600 mb-2">Berkas Diverifikasi - Silakan Bayar</h3>
                            <p class="text-gray-600 mb-4">Selamat! Berkas Anda telah diverifikasi oleh <?php echo e($applicant->verifier->name ?? 'Tim Verifikator'); ?> pada <?php echo e($applicant->verified_at ? $applicant->verified_at->format('d M Y H:i') : ''); ?>.</p>
                            <?php if($applicant->catatan_verifikasi): ?>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-blue-800"><strong>Catatan:</strong> <?php echo e($applicant->catatan_verifikasi); ?></p>
                                </div>
                            <?php endif; ?>
                            <p class="text-gray-600 mb-4">Silakan lakukan pembayaran untuk melanjutkan proses pendaftaran.</p>
                            <a href="<?php echo e(route('payment.index')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                💳 Lakukan Pembayaran
                            </a>
                        </div>
                    <?php elseif($applicant->status == 'VERIFIED'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">💰</div>
                            <h3 class="text-xl font-semibold text-green-600 mb-2">Berkas Diverifikasi</h3>
                            <p class="text-gray-600 mb-4">Selamat! Berkas Anda telah diverifikasi oleh <?php echo e($applicant->verifier->name ?? 'Tim Verifikator'); ?> pada <?php echo e($applicant->verified_at ? $applicant->verified_at->format('d M Y H:i') : ''); ?>.</p>
                            <?php if($applicant->catatan_verifikasi): ?>
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-blue-800"><strong>Catatan:</strong> <?php echo e($applicant->catatan_verifikasi); ?></p>
                                </div>
                            <?php endif; ?>
                            <p class="text-gray-600 mb-4">Silakan lakukan pembayaran untuk melanjutkan proses pendaftaran.</p>
                            <a href="<?php echo e(route('payment.index')); ?>" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                💳 Lakukan Pembayaran
                            </a>
                        </div>
                    <?php elseif($applicant->status == 'REJECTED'): ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">❌</div>
                            <h3 class="text-xl font-semibold text-red-600 mb-2">Berkas Ditolak</h3>
                            <p class="text-gray-600 mb-3">Berkas Anda ditolak oleh <?php echo e($applicant->verifier->name ?? 'Tim Verifikator'); ?> pada <?php echo e($applicant->verified_at ? $applicant->verified_at->format('d M Y H:i') : ''); ?>.</p>
                            <?php if($applicant->catatan_verifikasi): ?>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-sm text-red-800"><strong>Alasan:</strong> <?php echo e($applicant->catatan_verifikasi); ?></p>
                                </div>
                            <?php endif; ?>
                            <p class="text-gray-600 mb-4">Silakan perbaiki berkas sesuai catatan dan hubungi panitia untuk verifikasi ulang.</p>
                            <a href="<?php echo e(route('dokumen.index')); ?>" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                📄 Perbaiki Berkas
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center">
                            <div class="text-6xl mb-4">🔍</div>
                            <h3 class="text-xl font-semibold text-yellow-600 mb-2">Menunggu Verifikasi</h3>
                            <p class="text-gray-600">Berkas Anda sedang dalam proses verifikasi oleh tim panitia.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if($applicant): ?>
        <!-- Notifications Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold flex items-center">
                    <span class="text-2xl mr-2">🔔</span>
                    Notifikasi Terbaru
                </h2>
                <button onclick="loadDashboardNotifications()" class="text-blue-600 hover:text-blue-800 text-sm">
                    Refresh
                </button>
            </div>
            <div id="dashboardNotifications" class="space-y-3">
                <!-- Notifications will be loaded here -->
                <div class="text-center py-4 text-gray-500">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600 mx-auto mb-2"></div>
                    Memuat notifikasi...
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <a href="<?php echo e(route('pendaftaran.create')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="font-semibold text-lg mb-2">Data Pendaftaran</h3>
                    <p class="text-gray-600 text-sm">Lihat & edit data pendaftaran</p>
                </div>
            </a>
            
            <a href="<?php echo e(route('status.index')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="font-semibold text-lg mb-2">Status Pendaftaran</h3>
                    <p class="text-gray-600 text-sm">Pantau progress pendaftaran</p>
                </div>
            </a>
            
            <a href="<?php echo e(route('dokumen.index')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="font-semibold text-lg mb-2">Upload Dokumen</h3>
                    <p class="text-gray-600 text-sm">Upload berkas persyaratan</p>
                    <?php if($applicant && $applicant->files->count() > 0): ?>
                        <div class="mt-2">
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                <?php echo e($applicant->files->count()); ?> berkas
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </a>
            
            <?php if(in_array($applicant->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED'])): ?>
            <a href="<?php echo e(route('payment.index')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">💳</div>
                    <h3 class="font-semibold text-lg mb-2">Pembayaran</h3>
                    <p class="text-gray-600 text-sm">
                        <?php if($applicant->status == 'PAYMENT_VERIFIED'): ?>
                            Lihat status pembayaran
                        <?php else: ?>
                            Lakukan pembayaran pendaftaran
                        <?php endif; ?>
                    </p>
                </div>
            </a>
            <?php else: ?>
            <div class="bg-gray-100 rounded-xl shadow-lg p-6 opacity-50">
                <div class="text-center">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="font-semibold text-lg mb-2">Pembayaran</h3>
                    <p class="text-gray-600 text-sm">Tersedia setelah verifikasi</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Pribadi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">👤 Data Pribadi</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">No. Pendaftaran:</span>
                        <span class="font-medium"><?php echo e($applicant->no_pendaftaran ?? 'Belum ada'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-medium"><?php echo e($applicant->nama_lengkap ?? auth()->user()->name); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jurusan:</span>
                        <span class="font-medium"><?php echo e($applicant->major->name ?? 'Belum dipilih'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gelombang:</span>
                        <span class="font-medium"><?php echo e($applicant->wave->nama ?? 'Belum dipilih'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Berkas:</span>
                        <span class="font-medium">
                            <?php if($applicant->files->count() > 0): ?>
                                <span class="text-green-600"><?php echo e($applicant->files->count()); ?> file</span>
                            <?php else: ?>
                                <span class="text-red-600">Belum upload</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">📅 Timeline Penting</h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-sm">Pendaftaran Dibuka</p>
                            <p class="text-xs text-gray-500">1 Januari 2025</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-sm">Batas Pendaftaran</p>
                            <p class="text-xs text-gray-500">31 Maret 2025</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div>
                            <p class="font-medium text-sm">Pengumuman</p>
                            <p class="text-xs text-gray-500">
                                <?php if($applicant && $applicant->final_status): ?>
                                    <?php echo e($applicant->decided_at ? \Carbon\Carbon::parse($applicant->decided_at)->format('d M Y') : 'Sudah diumumkan'); ?>

                                <?php else: ?>
                                    5 April 2025
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if($applicant && auth()->user()->role === 'user'): ?>
<script>
function loadDashboardNotifications() {
    fetch('/notifications/api')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('dashboardNotifications');
            
            if (data.notifications && data.notifications.length > 0) {
                container.innerHTML = data.notifications.slice(0, 3).map(notification => `
                    <div class="flex items-start p-4 bg-gray-50 rounded-lg ${!notification.is_read ? 'border-l-4 border-blue-500' : ''}">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-3 h-3 rounded-full ${getNotificationColor(notification.type)} mt-2"></div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 mb-1">${notification.title}</h4>
                            <p class="text-sm text-gray-600 mb-2">${notification.message}</p>
                            <p class="text-xs text-gray-400">${formatDate(notification.created_at)}</p>
                        </div>
                        ${!notification.is_read ? '<div class="flex-shrink-0"><span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span></div>' : ''}
                    </div>
                `).join('');
                
                if (data.notifications.length > 3) {
                    container.innerHTML += `
                        <div class="text-center pt-3">
                            <a href="<?php echo e(route('notifications.index')); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Lihat Semua Notifikasi (${data.notifications.length})
                            </a>
                        </div>
                    `;
                }
            } else {
                container.innerHTML = `
                    <div class="text-center py-6 text-gray-500">
                        <div class="text-4xl mb-2">🔕</div>
                        <p>Belum ada notifikasi</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('dashboardNotifications').innerHTML = `
                <div class="text-center py-4 text-red-500">
                    <p>Gagal memuat notifikasi</p>
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

// Load notifications when page loads
document.addEventListener('DOMContentLoaded', function() {
    <?php if(auth()->user()->role === 'user'): ?>
    loadDashboardNotifications();
    <?php endif; ?>
});
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/dashboard.blade.php ENDPATH**/ ?>
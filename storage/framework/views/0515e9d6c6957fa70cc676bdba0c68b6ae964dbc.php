<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <span class="mr-3">💰</span> Dashboard Keuangan
                </h1>
                <p class="text-green-100">Monitoring Pembayaran PPDB</p>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Menunggu Pembayaran</p>
                        <p class="text-2xl font-bold text-yellow-600"><?php echo e($stats['pending_payment']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Sudah Bayar</p>
                        <p class="text-2xl font-bold text-green-600"><?php echo e($stats['paid']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <span class="text-2xl">💵</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-xl font-bold text-blue-600">Rp <?php echo e(number_format($stats['total_revenue'], 0, ',', '.')); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-full">
                        <span class="text-2xl">📅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Hari Ini</p>
                        <p class="text-2xl font-bold text-purple-600"><?php echo e($stats['today_payments']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Tren -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">📈</span> Tren Pembayaran (7 Hari)
                </h3>
                <canvas id="paymentChart" width="400" height="200"></canvas>
            </div>

            <!-- Pembayaran Terbaru -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">🔔</span> Pembayaran Terbaru
                </h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-xs font-bold"><?php echo e(substr($payment->applicant->user->name ?? 'N/A', 0, 2)); ?></span>
                            </div>
                            <div>
                                <p class="font-medium"><?php echo e($payment->applicant->user->name ?? 'N/A'); ?></p>
                                <p class="text-sm text-gray-600"><?php echo e($payment->applicant->major->name ?? 'N/A'); ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">💰 Lunas</span>
                            <p class="text-xs text-gray-500 mt-1"><?php echo e($payment->created_at->diffForHumans()); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Menu Aksi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="<?php echo e(route('keuangan.daftar-pembayaran')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📋</div>
                    <h3 class="font-semibold text-lg mb-2">Daftar Pembayaran</h3>
                    <p class="text-gray-600 text-sm">Kelola verifikasi pembayaran</p>
                </div>
            </a>
            
            <a href="<?php echo e(route('keuangan.rekap')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="font-semibold text-lg mb-2">Rekap Keuangan</h3>
                    <p class="text-gray-600 text-sm">Laporan keuangan lengkap</p>
                </div>
            </a>
            
            <a href="<?php echo e(route('keuangan.master-data')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="font-semibold text-lg mb-2">Master Data</h3>
                    <p class="text-gray-600 text-sm">Data jurusan & gelombang</p>
                </div>
            </a>
            
            <a href="<?php echo e(route('keuangan.log-aktivitas')); ?>" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="font-semibold text-lg mb-2">Log Aktivitas</h3>
                    <p class="text-gray-600 text-sm">Riwayat aktivitas keuangan</p>
                </div>
            </a>
        </div>
        
        <!-- Export Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <a href="<?php echo e(route('keuangan.export-excel')); ?>" class="bg-green-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
                <div class="text-4xl mb-4">📊</div>
                <h3 class="font-semibold text-lg mb-2">Export Excel</h3>
                <p class="text-green-100 text-sm">Download laporan dalam format Excel</p>
            </a>
            
            <a href="<?php echo e(route('keuangan.export-pdf')); ?>" class="bg-red-600 text-white rounded-xl shadow-lg p-6 hover:shadow-xl transition text-center">
                <div class="text-4xl mb-4">📄</div>
                <h3 class="font-semibold text-lg mb-2">Export PDF</h3>
                <p class="text-red-100 text-sm">Download laporan dalam format PDF</p>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('paymentChart').getContext('2d');
const paymentChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($paymentTrend, 'date')); ?>,
        datasets: [{
            label: 'Pembayaran per Hari',
            data: <?php echo json_encode(array_column($paymentTrend, 'count')); ?>,
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ppdb\resources\views/keuangan/dashboard.blade.php ENDPATH**/ ?>
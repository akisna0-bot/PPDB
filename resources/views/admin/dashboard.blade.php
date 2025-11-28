@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-new.png.png') }}" alt="SMK Bakti Nusantara 666" class="w-16 h-16 rounded-xl shadow-lg mr-4 object-cover">
                    <div>
                        <div class="mb-2">
                            <h1 class="text-3xl font-bold text-gray-900">📊 Dashboard Admin PPDB - SMK BAKTI NUSANTARA 666</h1>
                        </div>
                        <p class="text-gray-600">Selamat datang, <span class="font-semibold text-blue-600">{{ auth()->user()->name ?? 'Admin' }}</span></p>
                        <p class="text-sm text-gray-500">{{ now()->format('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <p class="text-sm text-gray-500">Waktu Server</p>
                        <p class="text-lg font-bold text-gray-900" id="current-time">{{ now()->format('H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Menu Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.applicants.index') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Data Pendaftar
                </a>
                <a href="{{ route('admin.panitia.monitoring-berkas') }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Monitoring Berkas
                </a>
                <a href="{{ route('admin.panitia.monitoring-pembayaran') }}" class="flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Monitoring Pembayaran
                </a>
                <a href="{{ route('admin.panitia.peta-sebaran') }}" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Peta Sebaran
                </a>
                <a href="{{ route('admin.panitia.master-data') }}" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                    Master Data
                </a>
                <a href="{{ route('admin.panitia.manajemen-akun') }}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                    Manajemen Akun
                </a>
                <a href="{{ route('admin.keputusan-akhir') }}" class="flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Keputusan Akhir
                </a>
            </div>
        </div>
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pendaftar</p>
                        <p class="text-3xl font-bold">{{ $totalApplicants ?? 0 }}</p>
                        <p class="text-blue-100 text-xs mt-1">📈 Data real-time</p>
                    </div>
                    <div class="text-4xl opacity-80">👥</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold">{{ $pendingApplicants ?? 0 }}</p>
                        <p class="text-yellow-100 text-xs mt-1">⏳ Perlu tindakan</p>
                    </div>
                    <div class="text-4xl opacity-80">🕰️</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Terverifikasi</p>
                        <p class="text-3xl font-bold">{{ $verifiedApplicants ?? 0 }}</p>
                        <p class="text-green-100 text-xs mt-1">✅ Siap bayar</p>
                    </div>
                    <div class="text-4xl opacity-80">✓</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Pembayaran</p>
                        <p class="text-2xl font-bold">Rp {{ number_format($paymentStats['total_revenue'] ?? 0, 0, ',', '.') }}</p>
                        <p class="text-purple-100 text-xs mt-1">💰 Revenue terkumpul</p>
                    </div>
                    <div class="text-4xl opacity-80">💵</div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Pendaftaran per Hari -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">📈 Tren Pendaftaran (7 Hari Terakhir)</h3>
                    <span class="text-sm text-gray-500">Real-time</span>
                </div>
                <canvas id="registrationChart" width="400" height="200"></canvas>
            </div>

            <!-- Grafik Status Pendaftar -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">🍰 Status Pendaftar</h3>
                    <span class="text-sm text-gray-500">Live data</span>
                </div>
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Grafik Jurusan -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">🎯 Pendaftar per Jurusan</h3>
                <div class="flex space-x-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Target: 288 siswa</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 text-xs rounded-full">Tercapai: {{ $totalApplicants ?? 0 }} siswa</span>
                </div>
            </div>
            <canvas id="majorChart" width="800" height="300"></canvas>
        </div>

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Export Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Export Laporan</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.panitia.export.excel') }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.panitia.export.pdf') }}" class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Export PDF
                    </a>
                </div>
            </div>

            <!-- System Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Aksi Sistem</h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="backupData()" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Backup Data
                    </button>
                    <button onclick="sendNotification()" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-2H4v2zM4 15h8v-2H4v2zM4 11h8V9H4v2z"></path>
                        </svg>
                        Kirim Notifikasi
                    </button>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Aktivitas Terbaru</h3>
            <div class="space-y-3">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center justify-between p-3 bg-{{ $activity['color'] }}-50 rounded-lg border-l-4 border-{{ $activity['color'] }}-500">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-{{ $activity['color'] }}-500 rounded-full mr-3 @if($loop->first) animate-pulse @endif"></div>
                            <span class="text-sm text-gray-700">{!! $activity['message'] !!}</span>
                        </div>
                        <span class="text-xs text-gray-500">{{ $activity['time']->diffForHumans() }}</span>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-sm">Belum ada aktivitas terbaru</p>
                        <p class="text-xs text-gray-400 mt-1">Aktivitas akan muncul ketika ada pendaftar baru atau perubahan status</p>
                    </div>
                @endforelse
                
                @if($recentActivities->count() > 0)
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.panitia.log-aktivitas') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat semua aktivitas →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Update waktu real-time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('id-ID');
    document.getElementById('current-time').textContent = timeString;
}
setInterval(updateTime, 1000);

// Data dari server
const registrationTrendData = {!! json_encode($registrationTrend ?? [0, 0, 0, 0, 0, 0, 0]) !!};
const statusDataFromServer = {!! json_encode($statusData ?? ['pending' => 0, 'verified' => 0, 'rejected' => 0, 'paid' => 0]) !!};
const majorDataFromServer = {!! json_encode($majorData ?? []) !!};

// Grafik Tren Pendaftaran
const registrationCtx = document.getElementById('registrationChart').getContext('2d');
new Chart(registrationCtx, {
    type: 'line',
    data: {
        labels: ['6 hari lalu', '5 hari lalu', '4 hari lalu', '3 hari lalu', '2 hari lalu', 'Kemarin', 'Hari ini'],
        datasets: [{
            label: 'Pendaftar Baru',
            data: registrationTrendData,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
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
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Grafik Status Pendaftar (Donut)
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Menunggu Verifikasi', 'Terverifikasi', 'Ditolak', 'Sudah Bayar'],
        datasets: [{
            data: [
                statusDataFromServer.pending || 0, 
                statusDataFromServer.verified || 0, 
                statusDataFromServer.rejected || 0, 
                statusDataFromServer.paid || 0
            ],
            backgroundColor: [
                '#f59e0b',
                '#10b981', 
                '#ef4444',
                '#8b5cf6'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

// Grafik Jurusan (Bar)
const majorCtx = document.getElementById('majorChart').getContext('2d');
new Chart(majorCtx, {
    type: 'bar',
    data: {
        labels: ['PPLG', 'AKT', 'ANM', 'DKV', 'PMS'],
        datasets: [{
            label: 'Pendaftar',
            data: [
                majorDataFromServer.PPLG || 0,
                majorDataFromServer.AKT || 0,
                majorDataFromServer.ANM || 0,
                majorDataFromServer.DKV || 0,
                majorDataFromServer.PMS || 0
            ],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(139, 92, 246)',
                'rgb(236, 72, 153)'
            ],
            borderWidth: 2,
            borderRadius: 8
        }, {
            label: 'Target Kuota',
            data: [72, 72, 36, 36, 72],
            type: 'line',
            borderColor: 'rgba(239, 68, 68, 0.8)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            borderWidth: 2,
            fill: false,
            tension: 0
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Backup Data Function
function backupData() {
    if (confirm('Apakah Anda yakin ingin melakukan backup data? Proses ini mungkin memakan waktu beberapa menit.')) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Memproses...';
        button.disabled = true;
        
        fetch('/admin/panitia/backup-data', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Backup berhasil! File: ' + data.filename);
            } else {
                alert('Backup gagal: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}

// Send Notification Function
function sendNotification() {
    const message = prompt('Masukkan pesan notifikasi untuk semua pendaftar:');
    if (message && message.trim()) {
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>Mengirim...';
        button.disabled = true;
        
        fetch('/admin/panitia/send-notification', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message.trim() })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Notifikasi berhasil dikirim ke ' + data.count + ' pendaftar!');
            } else {
                alert('Gagal mengirim notifikasi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>
@endsection
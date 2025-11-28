@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <img src="/images/logo-new.png.png" alt="SMK BAKTI NUSANTARA 666" class="w-10 h-10 object-contain mr-3">
                        <h1 class="text-3xl font-bold text-gray-900">🔍 Dashboard Verifikator - SMK BAKTI NUSANTARA 666</h1>
                    </div>
                    <p class="text-gray-600">Selamat datang, <span class="font-semibold text-green-600">{{ auth()->user()->name ?? 'Verifikator' }}</span></p>
                    <p class="text-sm text-gray-500">Kelola verifikasi berkas pendaftar</p>
                </div>
                <div class="text-right">
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <p class="text-sm text-gray-500">Target Hari Ini</p>
                        <p class="text-lg font-bold text-green-600">0 Berkas</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Menu Navigation -->
        <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-gray-200">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('verifikator.daftar-pendaftar') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Daftar Pendaftar
                </a>
                <a href="{{ route('verifikator.laporan') }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Laporan Verifikasi
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
                        <p class="text-yellow-100 text-xs mt-1">⏳ Perlu segera diproses</p>
                    </div>
                    <div class="text-4xl opacity-80">📄</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Sudah Diverifikasi</p>
                        <p class="text-3xl font-bold">{{ $stats['verified'] ?? 0 }}</p>
                        <p class="text-green-100 text-xs mt-1">✅ Berkas valid</p>
                    </div>
                    <div class="text-4xl opacity-80">✓</div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Ditolak</p>
                        <p class="text-3xl font-bold">{{ $stats['rejected'] ?? 0 }}</p>
                        <p class="text-red-100 text-xs mt-1">❌ Perlu perbaikan</p>
                    </div>
                    <div class="text-4xl opacity-80">❌</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Saya Verifikasi</p>
                        <p class="text-3xl font-bold">{{ $myVerifications ?? 0 }}</p>
                        <p class="text-purple-100 text-xs mt-1">👤 Oleh saya</p>
                    </div>
                    <div class="text-4xl opacity-80">👨‍💼</div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Progress Verifikasi Harian</h3>
            <canvas id="verificationChart" width="800" height="300"></canvas>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Data yang Saya Verifikasi</h3>
            <div class="space-y-3">
                @forelse($recentActivities ?? [] as $activity)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center">
                            <div class="w-3 h-3 {{ in_array($activity->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED']) ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-3"></div>
                            <div>
                                <span class="text-sm font-medium text-gray-900">{{ $activity->user->name ?? 'N/A' }}</span>
                                <span class="text-xs text-gray-500 ml-2">({{ $activity->no_pendaftaran }})</span>
                                <div class="text-xs text-gray-500">{{ $activity->major->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs px-3 py-1 rounded-full font-medium
                                @if(in_array($activity->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED'])) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($activity->status == 'VERIFIED' || $activity->status == 'PAYMENT_PENDING')
                                    ✅ Diverifikasi
                                @elseif($activity->status == 'PAYMENT_VERIFIED')
                                    💰 Sudah Bayar
                                @else
                                    ❌ Ditolak
                                @endif
                            </span>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $activity->verified_at ? $activity->verified_at->format('d M Y H:i') : ($activity->tgl_verifikasi_adm ? $activity->tgl_verifikasi_adm->format('d M H:i') : 'N/A') }}
                            </p>
                            @if($activity->catatan_verifikasi)
                                <p class="text-xs text-gray-400 mt-1 italic">"{{ Str::limit($activity->catatan_verifikasi, 30) }}"</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div class="text-4xl mb-2">📋</div>
                        <p class="text-gray-500">Belum ada data yang Anda verifikasi</p>
                        <a href="{{ route('verifikator.daftar-pendaftar') }}" class="text-blue-600 hover:text-blue-800 text-sm mt-2 inline-block">
                            Mulai verifikasi sekarang →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Progress Verifikasi
const verificationCtx = document.getElementById('verificationChart').getContext('2d');
new Chart(verificationCtx, {
    type: 'bar',
    data: {
        labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        datasets: [{
            label: 'Diverifikasi',
            data: {!! json_encode($verificationTrend ?? [0,0,0,0,0,0,0]) !!},
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
            borderColor: 'rgb(16, 185, 129)',
            borderWidth: 2,
            borderRadius: 8
        }, {
            label: 'Ditolak',
            data: {!! json_encode($rejectionTrend ?? [0,0,0,0,0,0,0]) !!},
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
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
@endsection
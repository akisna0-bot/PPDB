@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-slate-800 via-blue-800 to-indigo-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                        <span class="text-2xl">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Dashboard Admin Panitia</h1>
                        <p class="text-blue-100">SMK BAKTI NUSANTARA 666</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm">{{ now()->format('l, d F Y') }}</p>
                    <p class="text-sm">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Pendaftar</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $stats['total_pendaftar'] }}</p>
                    </div>
                    <span class="text-3xl">👥</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Kelengkapan Berkas</p>
                        <p class="text-3xl font-bold text-green-600">{{ $stats['kelengkapan_berkas']['percentage'] }}%</p>
                    </div>
                    <span class="text-3xl">📄</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Menunggu Verifikasi</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $stats['progress_verifikasi']['pending'] }}</p>
                    </div>
                    <span class="text-3xl">⏳</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pembayaran Lunas</p>
                        <p class="text-3xl font-bold text-purple-600">{{ $stats['progress_pembayaran']['paid'] }}</p>
                    </div>
                    <span class="text-3xl">💰</span>
                </div>
            </div>
        </div>

        <!-- Grafik dan Statistik -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Pendaftar per Jurusan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span class="mr-2">📊</span> Pendaftar per Jurusan
                </h3>
                <div class="space-y-4">
                    @foreach($stats['per_jurusan'] as $major)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <span class="font-medium">{{ $major->code }}</span>
                            <p class="text-sm text-gray-600">{{ $major->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-lg font-bold">{{ $major->applicants_count }}</span>
                            <p class="text-sm text-gray-500">/ {{ $major->kuota }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Menu Navigasi -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold mb-4 flex items-center">
                    <span class="mr-2">🚀</span> Menu Utama
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('admin.applicants.index') }}" class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition text-center">
                        <span class="text-2xl block mb-2">👥</span>
                        <span class="text-sm font-medium">Data Pendaftar</span>
                    </a>
                    <a href="{{ route('executive.geographic') }}" class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition text-center">
                        <span class="text-2xl block mb-2">🗺️</span>
                        <span class="text-sm font-medium">Peta Sebaran</span>
                    </a>
                    <a href="{{ route('reports.index') }}" class="p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition text-center">
                        <span class="text-2xl block mb-2">📊</span>
                        <span class="text-sm font-medium">Laporan</span>
                    </a>
                    <a href="{{ route('keuangan.payments') }}" class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition text-center">
                        <span class="text-2xl block mb-2">💰</span>
                        <span class="text-sm font-medium">Pembayaran</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Status per Gelombang -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <span class="mr-2">📅</span> Status per Gelombang
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($stats['per_gelombang'] as $wave)
                <div class="p-4 border rounded-lg">
                    <h4 class="font-semibold">{{ $wave->nama }}</h4>
                    <p class="text-sm text-gray-600 mb-2">{{ $wave->tgl_mulai }} - {{ $wave->tgl_selesai }}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-blue-600">{{ $wave->applicants_count }}</span>
                        <span class="text-sm text-gray-500">pendaftar</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold mb-4 flex items-center">
                    <span class="mr-2">⚡</span> Aksi Cepat
                </h4>
                <div class="space-y-3">
                    <a href="{{ route('admin.applicants.export.excel') }}" class="block w-full bg-green-600 text-white py-2 px-4 rounded-lg text-center hover:bg-green-700 transition">
                        📄 Export Excel
                    </a>
                    <a href="{{ route('admin.applicants.export.pdf') }}" class="block w-full bg-red-600 text-white py-2 px-4 rounded-lg text-center hover:bg-red-700 transition">
                        📑 Export PDF
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold mb-4 flex items-center">
                    <span class="mr-2">📈</span> Statistik Berkas
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Upload Berkas:</span>
                        <span class="font-medium">{{ $stats['kelengkapan_berkas']['with_files'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Belum Upload:</span>
                        <span class="font-medium">{{ $stats['kelengkapan_berkas']['total_applicants'] - $stats['kelengkapan_berkas']['with_files'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Persentase:</span>
                        <span class="font-medium text-green-600">{{ $stats['kelengkapan_berkas']['percentage'] }}%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h4 class="font-bold mb-4 flex items-center">
                    <span class="mr-2">💰</span> Status Pembayaran
                </h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Lunas:</span>
                        <span class="font-medium text-green-600">{{ $stats['progress_pembayaran']['paid'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Pending:</span>
                        <span class="font-medium text-yellow-600">{{ $stats['progress_pembayaran']['pending'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Gagal:</span>
                        <span class="font-medium text-red-600">{{ $stats['progress_pembayaran']['failed'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
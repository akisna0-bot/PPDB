@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-yellow-600 via-orange-600 to-red-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Log Aktivitas</h1>
                        <p class="text-orange-100">Riwayat aktivitas sistem dan pengguna</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Log</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Aktivitas</label>
                    <select class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua</option>
                        <option value="login">Login</option>
                        <option value="logout">Logout</option>
                        <option value="verify">Verifikasi</option>
                        <option value="export">Export</option>
                        <option value="backup">Backup</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pengguna</label>
                    <select class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Semua</option>
                        <option value="admin">Admin</option>
                        <option value="verifikator">Verifikator</option>
                        <option value="keuangan">Keuangan</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    🔍 Filter
                </button>
                <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    🔄 Reset
                </button>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    📊 Export Log
                </button>
            </div>
        </div>

        <!-- Log Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Riwayat Aktivitas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pengguna</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aktivitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $log['time']->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">
                                                {{ substr($log['user'], 0, 1) }}
                                            </span>
                                        </div>
                                        {{ $log['user'] }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($log['action'] == 'Login') bg-green-100 text-green-800
                                        @elseif($log['action'] == 'Logout') bg-red-100 text-red-800
                                        @elseif($log['action'] == 'Verifikasi Pendaftar') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $log['action'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $log['ip'] }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada log aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">👤</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Login Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">24</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Verifikasi Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Export Hari Ini</p>
                        <p class="text-2xl font-bold text-gray-900">5</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🔒</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Login Gagal</p>
                        <p class="text-2xl font-bold text-gray-900">2</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
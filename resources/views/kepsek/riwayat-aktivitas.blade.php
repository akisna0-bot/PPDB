@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <span class="mr-3">📝</span> Riwayat Aktivitas Sistem
                        </h1>
                        <p class="text-blue-100">Log semua aktivitas verifikasi dan perubahan status</p>
                    </div>
                    <a href="{{ route('kepsek.dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" class="flex items-center space-x-4">
                <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>Diverifikasi</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                    <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Diterima</option>
                </select>
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    🔍 Filter
                </button>
                <a href="{{ route('kepsek.riwayat-aktivitas') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition">
                    Reset
                </a>
            </form>
        </div>

        <!-- Tabel Aktivitas -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">📋</span> Log Aktivitas
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold">Waktu</th>
                            <th class="text-left py-3 px-6 font-semibold">Pendaftar</th>
                            <th class="text-left py-3 px-6 font-semibold">Jurusan</th>
                            <th class="text-left py-3 px-6 font-semibold">Status</th>
                            <th class="text-left py-3 px-6 font-semibold">Verifikator</th>
                            <th class="text-left py-3 px-6 font-semibold">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium">{{ $activity->tgl_verifikasi_adm ? $activity->tgl_verifikasi_adm->format('d/m/Y') : 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity->tgl_verifikasi_adm ? $activity->tgl_verifikasi_adm->format('H:i') : '' }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-bold">{{ substr($activity->user->name, 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $activity->user->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $activity->no_pendaftaran }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs">
                                    {{ $activity->major->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($activity->status == 'VERIFIED') bg-green-100 text-green-800
                                    @elseif($activity->status == 'REJECTED') bg-red-100 text-red-800
                                    @elseif($activity->status == 'PAID') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($activity->status == 'VERIFIED') ✅ Diverifikasi
                                    @elseif($activity->status == 'REJECTED') ❌ Ditolak
                                    @elseif($activity->status == 'PAID') 💰 Diterima
                                    @else {{ $activity->status }} @endif
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-medium">{{ $activity->user_verifikasi_adm ?? 'System' }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-sm text-gray-600 max-w-xs truncate" title="{{ $activity->catatan_verifikasi }}">
                                    {{ $activity->catatan_verifikasi ?? 'Tidak ada catatan' }}
                                </p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="text-4xl mb-2">📝</div>
                                <p>Belum ada aktivitas yang tercatat</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($activities->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
            @endif
        </div>

        <!-- Statistik Aktivitas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $activities->total() }}</h3>
                <p class="text-gray-600">Total Aktivitas</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-2xl font-bold text-green-600">{{ $activities->where('status', 'VERIFIED')->count() }}</h3>
                <p class="text-gray-600">Diverifikasi</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">❌</div>
                <h3 class="text-2xl font-bold text-red-600">{{ $activities->where('status', 'REJECTED')->count() }}</h3>
                <p class="text-gray-600">Ditolak</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">💰</div>
                <h3 class="text-2xl font-bold text-purple-600">{{ $activities->where('status', 'PAID')->count() }}</h3>
                <p class="text-gray-600">Diterima</p>
            </div>
        </div>
    </div>
</div>
@endsection
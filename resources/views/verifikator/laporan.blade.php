@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <span class="mr-3">📊</span> Laporan Verifikasi Administrasi
            </h2>
            <p class="text-gray-600">Laporan hasil verifikasi yang telah dilakukan</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            @php
                $totalVerified = $laporan->where('status', 'VERIFIED')->sum('total');
                $totalRejected = $laporan->where('status', 'REJECTED')->sum('total');
                $totalRevision = 0; // Status ini tidak ada lagi
                $totalAll = $totalVerified + $totalRejected + $totalRevision;
            @endphp
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Diverifikasi</p>
                        <p class="text-3xl font-bold text-blue-600">{{ $totalAll }}</p>
                    </div>
                    <span class="text-3xl">📋</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Lulus</p>
                        <p class="text-3xl font-bold text-green-600">{{ $totalVerified }}</p>
                    </div>
                    <span class="text-3xl">✅</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <p class="text-3xl font-bold text-red-600">{{ $totalRejected }}</p>
                    </div>
                    <span class="text-3xl">❌</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Perlu Perbaikan</p>
                        <p class="text-3xl font-bold text-yellow-600">{{ $totalRevision }}</p>
                    </div>
                    <span class="text-3xl">🔄</span>
                </div>
            </div>
        </div>

        <!-- Laporan Detail -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white">📈 Detail Laporan Harian</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Tanggal</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jumlah</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($laporan->groupBy('tanggal') as $tanggal => $items)
                            @php $dailyTotal = $items->sum('total'); @endphp
                            @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">{{ \Carbon\Carbon::parse($tanggal)->format('d F Y') }}</td>
                                <td class="py-4 px-6">
                                    <span class="px-2 py-1 rounded-full text-xs
                                        @if($item->status == 'VERIFIED') bg-green-100 text-green-800
                                        @elseif($item->status == 'REJECTED') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        @if($item->status == 'VERIFIED') ✅ Lulus
                                        @elseif($item->status == 'REJECTED') ❌ Ditolak
                                        @else 🔄 Menunggu @endif
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-medium">{{ $item->total }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                            <div class="h-2 rounded-full
                                                @if($item->status == 'VERIFIED') bg-green-500
                                                @elseif($item->status == 'REJECTED') bg-red-500
                                                @else bg-yellow-500 @endif" 
                                                style="width: {{ $dailyTotal > 0 ? ($item->total / $dailyTotal) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            {{ $dailyTotal > 0 ? round(($item->total / $dailyTotal) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500">
                                Belum ada data laporan verifikasi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Statistik Performa -->
        @if($totalAll > 0)
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4 flex items-center">
                <span class="mr-2">🎯</span> Statistik Performa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ round(($totalVerified / $totalAll) * 100) }}%</div>
                    <p class="text-sm text-gray-600">Tingkat Persetujuan</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600">{{ round(($totalRejected / $totalAll) * 100) }}%</div>
                    <p class="text-sm text-gray-600">Tingkat Penolakan</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ round(($totalRevision / $totalAll) * 100) }}%</div>
                    <p class="text-sm text-gray-600">Perlu Perbaikan</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
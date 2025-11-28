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
                            <span class="mr-3">📊</span> Laporan Rekapitulasi PPDB
                        </h1>
                        <p class="text-blue-100">Periode {{ now()->format('Y') }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('kepsek.export-pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            📄 Export PDF
                        </a>
                        <a href="{{ route('kepsek.export-excel') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                            📊 Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">👥</div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $stats['total_pendaftar'] }}</h3>
                <p class="text-gray-600">Total Pendaftar</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-2xl font-bold text-green-600">{{ $stats['diterima'] }}</h3>
                <p class="text-gray-600">Diterima</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">🔍</div>
                <h3 class="text-2xl font-bold text-purple-600">{{ $stats['diverifikasi'] }}</h3>
                <p class="text-gray-600">Diverifikasi</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">⏳</div>
                <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</h3>
                <p class="text-gray-600">Menunggu</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">❌</div>
                <h3 class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</h3>
                <p class="text-gray-600">Ditolak</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Data per Jurusan -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">🎯 Rekapitulasi per Jurusan</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left py-3 px-6 font-semibold">Jurusan</th>
                                <th class="text-center py-3 px-6 font-semibold">Total</th>
                                <th class="text-center py-3 px-6 font-semibold">Diterima</th>
                                <th class="text-center py-3 px-6 font-semibold">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($dataJurusan as $jurusan)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium">{{ $jurusan->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $jurusan->code }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold">{{ $jurusan->total }}</td>
                                <td class="py-4 px-6 text-center font-bold text-green-600">{{ $jurusan->diterima }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $jurusan->total > 0 ? round(($jurusan->diterima / $jurusan->total) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Data per Gelombang -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">🌊 Rekapitulasi per Gelombang</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left py-3 px-6 font-semibold">Gelombang</th>
                                <th class="text-center py-3 px-6 font-semibold">Total</th>
                                <th class="text-center py-3 px-6 font-semibold">Diterima</th>
                                <th class="text-center py-3 px-6 font-semibold">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($dataGelombang as $gelombang)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div>
                                        <p class="font-medium">{{ $gelombang->name }}</p>
                                        <p class="text-sm text-gray-600">Rp {{ number_format($gelombang->biaya, 0, ',', '.') }}</p>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center font-bold">{{ $gelombang->total }}</td>
                                <td class="py-4 px-6 text-center font-bold text-green-600">{{ $gelombang->diterima }}</td>
                                <td class="py-4 px-6 text-center">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        {{ $gelombang->total > 0 ? round(($gelombang->diterima / $gelombang->total) * 100, 1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Grafik Perbandingan -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">📈</span> Grafik Perbandingan Status Pendaftar
            </h3>
            <canvas id="statusChart" width="400" height="200"></canvas>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center">
            <a href="{{ route('kepsek.dashboard') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Status Pendaftar
const ctx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Diterima', 'Diverifikasi', 'Menunggu', 'Ditolak'],
        datasets: [{
            data: [
                {{ $stats['diterima'] }},
                {{ $stats['diverifikasi'] }},
                {{ $stats['pending'] }},
                {{ $stats['ditolak'] }}
            ],
            backgroundColor: [
                '#10B981',
                '#8B5CF6',
                '#F59E0B',
                '#EF4444'
            ]
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endsection
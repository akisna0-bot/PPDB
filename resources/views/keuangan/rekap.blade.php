@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <span class="mr-3">📊</span> Rekap Keuangan PPDB
                        </h1>
                        <p class="text-green-100">Laporan keuangan lengkap</p>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('keuangan.export-pdf') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            📄 Export PDF
                        </a>
                        <a href="{{ route('keuangan.export-excel') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                            📊 Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Keuangan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">💰</div>
                <h3 class="text-xl font-bold text-green-600">Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</h3>
                <p class="text-gray-600">Total Pendapatan</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $stats['lunas'] }}</h3>
                <p class="text-gray-600">Sudah Bayar</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">⏳</div>
                <h3 class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</h3>
                <p class="text-gray-600">Menunggu Bayar</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📈</div>
                <h3 class="text-2xl font-bold text-purple-600">{{ number_format(($stats['lunas'] / ($stats['lunas'] + $stats['pending'])) * 100, 1) }}%</h3>
                <p class="text-gray-600">Tingkat Pembayaran</p>
            </div>
        </div>

        <!-- Rekap per Gelombang -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">🌊</span> Rekap per Gelombang
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-blue-50 rounded-lg">
                        <div>
                            <p class="font-medium">Gelombang 1</p>
                            <p class="text-sm text-gray-600">Rp 150.000</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600">{{ $stats['gelombang_1'] }}</p>
                            <p class="text-sm text-gray-600">siswa</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center p-4 bg-green-50 rounded-lg">
                        <div>
                            <p class="font-medium">Gelombang 2</p>
                            <p class="text-sm text-gray-600">Rp 200.000</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-green-600">{{ $stats['gelombang_2'] }}</p>
                            <p class="text-sm text-gray-600">siswa</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Pembayaran -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">📊</span> Status Pembayaran
                </h3>
                <canvas id="paymentStatusChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Tabel Detail Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">💳</span> Detail Pembayaran
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold">No. Pendaftaran</th>
                            <th class="text-left py-3 px-6 font-semibold">Nama</th>
                            <th class="text-left py-3 px-6 font-semibold">Jurusan</th>
                            <th class="text-left py-3 px-6 font-semibold">Gelombang</th>
                            <th class="text-left py-3 px-6 font-semibold">Biaya</th>
                            <th class="text-left py-3 px-6 font-semibold">Status</th>
                            <th class="text-left py-3 px-6 font-semibold">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($paidApplicants as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium">{{ $applicant->no_pendaftaran }}</td>
                            <td class="py-4 px-6">{{ $applicant->user->name }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $applicant->major->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">{{ $applicant->wave->name ?? 'N/A' }}</td>
                            <td class="py-4 px-6 font-bold text-green-600">
                                Rp {{ number_format($applicant->wave->biaya ?? 150000, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                    ✅ Lunas
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                {{ $applicant->updated_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-8">
            <a href="{{ route('keuangan.dashboard') }}" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Status Pembayaran
const ctx = document.getElementById('paymentStatusChart').getContext('2d');
const paymentChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Sudah Bayar', 'Menunggu Bayar'],
        datasets: [{
            data: [{{ $stats['lunas'] }}, {{ $stats['pending'] }}],
            backgroundColor: ['#10B981', '#F59E0B']
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
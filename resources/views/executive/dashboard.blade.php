@extends('layouts.app')

@section('content')
<div class="bg-white">
    <!-- Header -->
    <div class="bg-purple-500 text-white p-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold">Dashboard Executive</h1>
                <p>SMK BAKTI NUSANTARA 666 - Analytics & Reports</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.dashboard') }}" class="bg-purple-600 px-3 py-1 rounded text-sm">
                    Admin Dashboard
                </a>
                <button onclick="exportReport()" class="bg-green-500 px-3 py-1 rounded text-sm">
                    Export Report
                </button>
            </div>
        </div>
    </div>

    <div class="p-4">
        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mb-4">
            <div class="bg-white border p-3">
                <p class="text-sm">Total Pendaftar</p>
                <p class="text-xl font-bold">{{ number_format($totalApplicants) }}</p>
            </div>
            <div class="bg-white border p-3">
                <p class="text-sm">Terverifikasi</p>
                <p class="text-xl font-bold">{{ number_format($verifiedApplicants) }}</p>
            </div>
            <div class="bg-white border p-3">
                <p class="text-sm">Menunggu</p>
                <p class="text-xl font-bold">{{ number_format($pendingApplicants) }}</p>
            </div>
            <div class="bg-white border p-3">
                <p class="text-sm">Ditolak</p>
                <p class="text-xl font-bold">{{ number_format($rejectedApplicants) }}</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <!-- Pendaftar per Jurusan -->
            <div class="bg-white border p-4">
                <h3 class="font-bold mb-3">Pendaftar per Jurusan</h3>
                <div class="space-y-3">
                    @foreach($majorStats as $major)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span>{{ $major['name'] }}</span>
                                <span>{{ $major['total'] }}/{{ $major['kuota'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded h-2">
                                <div class="bg-blue-500 h-2 rounded" style="width: {{ min($major['percentage'], 100) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Status Chart -->
            <div class="bg-white border p-4">
                <h3 class="font-bold mb-3">Distribusi Status</h3>
                <div style="height: 150px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="grid grid-cols-2 gap-4 mb-4">
            <!-- Sebaran Geografis -->
            <div class="bg-white border p-4">
                <h3 class="font-bold mb-3">Sebaran Geografis</h3>
                <div class="space-y-2">
                    @foreach($geographicStats->take(5) as $geo)
                        <div class="flex justify-between text-sm">
                            <span>{{ $geo->kabupaten }}</span>
                            <span class="font-bold">{{ $geo->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Asal Sekolah -->
            <div class="bg-white border p-4">
                <h3 class="font-bold mb-3">Asal Sekolah Terbanyak</h3>
                <div class="space-y-2">
                    @foreach($schoolOrigins->take(5) as $school)
                        <div class="flex justify-between text-sm">
                            <span>{{ Str::limit($school->asal_sekolah, 25) }}</span>
                            <span class="font-bold">{{ $school->count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Wave Stats -->
        <div class="bg-white border p-4">
            <h3 class="font-bold mb-3">Statistik per Gelombang</h3>
            <div class="grid grid-cols-4 gap-4">
                @foreach($waveStats as $wave)
                    <div class="bg-gray-50 border p-3">
                        <h4 class="font-medium text-sm">{{ $wave->name }}</h4>
                        <p class="text-lg font-bold">{{ $wave->applicants_count }}</p>
                        <p class="text-xs text-gray-600">pendaftar</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($wave->biaya_daftar, 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusData = {!! json_encode($statusStats) !!};

new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: statusData.map(s => s.status),
        datasets: [{
            data: statusData.map(s => s.count),
            backgroundColor: statusData.map(s => s.color),
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '50%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 5,
                    font: { size: 10 }
                }
            }
        }
    }
});

function exportReport() {
    const data = {
        total: {{ $totalApplicants }},
        verified: {{ $verifiedApplicants }},
        pending: {{ $pendingApplicants }},
        rejected: {{ $rejectedApplicants }},
        date: new Date().toLocaleDateString('id-ID')
    };
    
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Laporan PPDB SMK Bakti Nusantara 666\n"
        + "Tanggal," + data.date + "\n"
        + "Total Pendaftar," + data.total + "\n"
        + "Terverifikasi," + data.verified + "\n"
        + "Menunggu," + data.pending + "\n"
        + "Ditolak," + data.rejected + "\n";
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "laporan_ppdb_" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
@endsection
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <h1 class="text-3xl font-bold text-white flex items-center">
                    <span class="mr-3">🏫</span> Dashboard Kepala Sekolah
                </h1>
                <p class="text-blue-100">Monitoring PPDB SMK Bakti Nusantara 666</p>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Total Pendaftar</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['total_pendaftar'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Sudah Bayar</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['sudah_bayar'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Menunggu Keputusan</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $stats['menunggu_keputusan'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Diterima</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['diterima'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-red-100 rounded-full">
                        <span class="text-2xl">❌</span>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Ditolak</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['ditolak'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Grafik Tren -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">📈</span> Tren Pendaftaran (7 Hari Terakhir)
                </h3>
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>

            <!-- Data per Jurusan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">🎯</span> Data per Jurusan
                </h3>
                <div class="space-y-3">
                    @foreach($dataJurusan as $jurusan)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $jurusan->name }}</p>
                            <p class="text-sm text-gray-600">{{ $jurusan->code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ $jurusan->total_pendaftar }}</p>
                            <p class="text-sm text-green-600">{{ $jurusan->diterima }} diterima</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Siswa yang Sudah Diterima oleh Admin -->
        @if($acceptedStudents->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-green-50 px-6 py-4 border-b border-green-200">
                <h3 class="text-lg font-semibold flex items-center text-green-800">
                    <span class="mr-2">🎉</span> Siswa yang Sudah Diterima ({{ $acceptedStudents->count() }})
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($acceptedStudents as $student)
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sm font-bold">{{ substr($student->nama_lengkap ?? $student->user->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-medium">{{ $student->nama_lengkap ?? $student->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $student->no_pendaftaran }} - {{ $student->major->name ?? 'N/A' }}</p>
                                <p class="text-xs text-green-600">Diterima: {{ $student->decided_at ? $student->decided_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                ✅ DITERIMA
                            </span>
                            @if($student->final_notes)
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($student->final_notes, 50) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Pendaftar Menunggu Keputusan -->
        @if($pendingDecision->count() > 0)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-red-50 px-6 py-4 border-b border-red-200">
                <h3 class="text-lg font-semibold flex items-center text-red-800">
                    <span class="mr-2">⚠️</span> Menunggu Keputusan Akhir ({{ $pendingDecision->count() }})
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($pendingDecision as $applicant)
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sm font-bold">{{ substr($applicant->nama_lengkap ?? $applicant->user->name, 0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-medium">{{ $applicant->nama_lengkap ?? $applicant->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $applicant->no_pendaftaran }} - {{ $applicant->major->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="finalDecision({{ $applicant->id }}, 'ACCEPTED')" 
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                                ✅ Terima
                            </button>
                            <button onclick="finalDecision({{ $applicant->id }}, 'REJECTED')" 
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                                ❌ Tolak
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">🔔</span> Aktivitas Terbaru
                </h3>
            </div>
            <div class="p-6">
                @if($recentActivities->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                                    <span class="text-sm font-bold">{{ substr($activity->user->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium">{{ $activity->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $activity->major->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($activity->status == 'PAID') bg-green-100 text-green-800
                                    @elseif($activity->status == 'VERIFIED') bg-blue-100 text-blue-800
                                    @elseif($activity->status == 'REJECTED') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($activity->status == 'PAID') 💰 Diterima
                                    @elseif($activity->status == 'VERIFIED') ✅ Diverifikasi
                                    @elseif($activity->status == 'REJECTED') ❌ Ditolak
                                    @else {{ $activity->status }} @endif
                                </span>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="text-4xl mb-2 block">📋</span>
                        <p>Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Menu Aksi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('kepsek.laporan-rekapitulasi') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="font-semibold text-lg mb-2">Laporan Rekapitulasi</h3>
                    <p class="text-gray-600 text-sm">Lihat laporan lengkap PPDB</p>
                </div>
            </a>
            
            <a href="{{ route('kepsek.grafik-peta') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">🗺️</div>
                    <h3 class="font-semibold text-lg mb-2">Peta Sebaran</h3>
                    <p class="text-gray-600 text-sm">Sebaran geografis pendaftar</p>
                </div>
            </a>
            
            <a href="{{ route('kepsek.master-data') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="font-semibold text-lg mb-2">Master Data</h3>
                    <p class="text-gray-600 text-sm">Data jurusan & gelombang</p>
                </div>
            </a>
            
            <a href="{{ route('kepsek.riwayat-aktivitas') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📝</div>
                    <h3 class="font-semibold text-lg mb-2">Riwayat Aktivitas</h3>
                    <p class="text-gray-600 text-sm">Log semua aktivitas sistem</p>
                </div>
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Tren Pendaftaran
const ctx = document.getElementById('trendChart').getContext('2d');
const trendChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($trendData, 'date')) !!},
        datasets: [{
            label: 'Pendaftar per Hari',
            data: {!! json_encode(array_column($trendData, 'count')) !!},
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
                beginAtZero: true
            }
        }
    }
});

// Final Decision Function
function finalDecision(applicantId, status) {
    const notes = prompt(`Catatan untuk keputusan ${status === 'ACCEPTED' ? 'DITERIMA' : 'DITOLAK'}:`);
    
    if (notes !== null) {
        fetch(`/kepsek/final-decision/${applicantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                final_status: status,
                final_notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.ok) {
                location.reload();
            } else {
                alert('Gagal menyimpan keputusan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload(); // Reload anyway as the action might have succeeded
        });
    }
}
</script>
@endsection
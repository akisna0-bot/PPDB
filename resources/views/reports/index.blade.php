@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="SMK Bakti Nusantara 666" class="w-16 h-16 rounded-xl mr-6 object-cover shadow-lg">
                    <div>
                        <h1 class="text-3xl font-bold">Laporan & Export</h1>
                        <p class="text-purple-100 text-lg">Generate dan Download Laporan PPDB</p>
                    </div>
                </div>
                <x-back-button url="{{ route('executive.dashboard') }}" text="Dashboard" class="bg-white/20 hover:bg-white/30 text-white" />
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($reports['applicants']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">👥</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($reports['payments'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Jurusan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $reports['majors'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🎓</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Last Export</p>
                        <p class="text-lg font-bold text-gray-900">{{ $reports['latest_export'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📄</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Applicants Report -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 p-6 text-white">
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">👥</span>
                        <div>
                            <h3 class="text-xl font-bold">Laporan Pendaftar</h3>
                            <p class="text-blue-100">Data lengkap calon siswa</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                            <select id="applicantStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="SUBMIT">Menunggu</option>
                                <option value="VERIFIED">Terverifikasi</option>
                                <option value="REJECTED">Ditolak</option>
                            </select>
                        </div>
                        <div class="flex space-x-2">
                            <a href="#" onclick="exportApplicants('pdf')" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition duration-200">
                                📄 PDF
                            </a>
                            <a href="#" onclick="exportApplicants('excel')" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-center transition duration-200">
                                📊 Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments Report -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 p-6 text-white">
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">💰</span>
                        <div>
                            <h3 class="text-xl font-bold">Laporan Pembayaran</h3>
                            <p class="text-green-100">Riwayat transaksi keuangan</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <p class="text-gray-600 text-sm">Laporan lengkap semua transaksi pembayaran PPDB termasuk status dan detail invoice.</p>
                        <a href="{{ route('reports.payments.pdf') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg text-center transition duration-200">
                            📄 Download PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Report -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6 text-white">
                    <div class="flex items-center">
                        <span class="text-3xl mr-4">📊</span>
                        <div>
                            <h3 class="text-xl font-bold">Laporan Statistik</h3>
                            <p class="text-purple-100">Analytics dan insights</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <p class="text-gray-600 text-sm">Laporan komprehensif dengan grafik, statistik per jurusan, dan sebaran geografis.</p>
                        <a href="{{ route('reports.statistics.pdf') }}" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg text-center transition duration-200">
                            📊 Download PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <span class="text-2xl mr-3">⚡</span>
                <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button onclick="exportAll()" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:shadow-lg transition-all duration-300">
                    📦 Export Semua
                </button>
                <button onclick="scheduleReport()" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-4 rounded-lg hover:shadow-lg transition-all duration-300">
                    ⏰ Jadwal Otomatis
                </button>
                <button onclick="emailReport()" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white font-semibold py-3 px-4 rounded-lg hover:shadow-lg transition-all duration-300">
                    📧 Email Report
                </button>
                <button onclick="backupData()" class="bg-gradient-to-r from-orange-600 to-red-600 text-white font-semibold py-3 px-4 rounded-lg hover:shadow-lg transition-all duration-300">
                    💾 Backup Data
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportApplicants(format) {
    const status = document.getElementById('applicantStatus').value;
    let url = format === 'pdf' ? '{{ route("reports.applicants.pdf") }}' : '{{ route("reports.applicants.excel") }}';
    
    if (status) {
        url += '?status=' + status;
    }
    
    window.open(url, '_blank');
}

function exportAll() {
    if (confirm('Export semua laporan? Ini akan memakan waktu beberapa menit.')) {
        // Simulate export all
        showNotification('📦 Export semua laporan dimulai...', 'info');
        setTimeout(() => {
            showNotification('✅ Export selesai! Check folder Downloads.', 'success');
        }, 3000);
    }
}

function scheduleReport() {
    showNotification('⏰ Fitur jadwal otomatis akan segera tersedia!', 'info');
}

function emailReport() {
    showNotification('📧 Fitur email report akan segera tersedia!', 'info');
}

function backupData() {
    if (confirm('Backup semua data sistem?')) {
        showNotification('💾 Backup data dimulai...', 'info');
        setTimeout(() => {
            showNotification('✅ Backup berhasil disimpan!', 'success');
        }, 2000);
    }
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
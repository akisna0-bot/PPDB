@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Data Pendaftar</h1>
                        <p class="text-blue-100">Kelola data siswa pendaftar SPMB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Pendaftar</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $applicants->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">👥</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $applicants->where('status', 'VERIFIED')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $applicants->where('status', 'SUBMIT')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $applicants->where('status', 'REJECTED')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">❌</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Filter Data</h3>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Semua Status</option>
                        <option value="SUBMIT" {{ request('status') == 'SUBMIT' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>Menunggu Bayar</option>
                        <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Menunggu Keputusan</option>
                        <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Berkas Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jurusan</label>
                    <select name="major_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Semua Jurusan</option>
                        @foreach($majors ?? [] as $major)
                            <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gelombang</label>
                    <select name="wave_id" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="">Semua Gelombang</option>
                        @foreach($waves ?? [] as $wave)
                            <option value="{{ $wave->id }}" {{ request('wave_id') == $wave->id ? 'selected' : '' }}>
                                {{ $wave->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900">Daftar Pendaftar</h3>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.panitia.export.excel', request()->query()) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                        📊 Export Excel
                    </a>
                    <a href="{{ route('admin.panitia.export.pdf', request()->query()) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition flex items-center">
                        📄 Export PDF
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($applicants as $index => $applicant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ ($applicants->currentPage() - 1) * $applicants->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $applicant->nama_lengkap ?? $applicant->user->name ?? 'Belum diisi' }}</div>
                                    <div class="text-sm text-gray-500">{{ $applicant->no_pendaftaran ?? $applicant->registration_number ?? 'Belum ada' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $applicant->user->email ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $applicant->major->name ?? 'Belum dipilih' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($applicant->final_status == 'ACCEPTED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ DITERIMA
                                        </span>
                                    @elseif($applicant->final_status == 'REJECTED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ DITOLAK
                                        </span>
                                    @elseif($applicant->status == 'PAID')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            💰 Menunggu Keputusan
                                        </span>
                                    @elseif($applicant->status == 'VERIFIED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            🔄 Menunggu Bayar
                                        </span>
                                    @elseif($applicant->status == 'REJECTED')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Berkas Ditolak
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Menunggu Verifikasi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $applicant->created_at ? $applicant->created_at->format('d M Y H:i') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.applicants.show', $applicant->id) }}" 
                                           class="text-purple-600 hover:text-purple-900 font-medium">
                                            Detail
                                        </a>
                                        @if($applicant->status == 'SUBMIT')
                                            <span class="text-gray-300">|</span>
                                            <button class="text-green-600 hover:text-green-900 font-medium" 
                                                    onclick="verifyApplicant({{ $applicant->id }}, 'VERIFIED')">
                                                ✅ Verifikasi
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button class="text-red-600 hover:text-red-900 font-medium" 
                                                    onclick="verifyApplicant({{ $applicant->id }}, 'REJECTED')">
                                                ❌ Tolak
                                            </button>
                                        @elseif($applicant->status == 'VERIFIED')
                                            <span class="text-gray-300">|</span>
                                            <button class="text-blue-600 hover:text-blue-900 font-medium" 
                                                    onclick="verifyPayment({{ $applicant->id }})">
                                                💰 Verifikasi Bayar
                                            </button>
                                        @elseif($applicant->status == 'PAID')
                                            <span class="text-gray-300">|</span>
                                            <button class="text-green-600 hover:text-green-900 font-medium" 
                                                    onclick="finalDecision({{ $applicant->id }}, 'ACCEPTED')">
                                                ✅ Terima
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button class="text-red-600 hover:text-red-900 font-medium" 
                                                    onclick="finalDecision({{ $applicant->id }}, 'REJECTED')">
                                                ❌ Tolak
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <span class="text-4xl mb-2">📝</span>
                                        <span>Belum ada data pendaftar</span>
                                        <span class="text-sm mt-1">Data akan muncul setelah ada siswa yang mendaftar</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($applicants) && $applicants->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $applicants->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
function verifyApplicant(id, status) {
    const action = status === 'VERIFIED' ? 'memverifikasi' : 'menolak';
    const notes = prompt(`Catatan untuk ${action} pendaftar:`);
    
    if (notes !== null) {
        fetch(`/admin/applicants/${id}/verify`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                status: status,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal ' + action + ' pendaftar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    }
}

function verifyPayment(id) {
    if (confirm('Apakah Anda yakin pembayaran sudah diterima?')) {
        fetch(`/keuangan/payments/${id}/verify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal verifikasi pembayaran');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    }
}

function finalDecision(id, status) {
    const action = status === 'ACCEPTED' ? 'MENERIMA' : 'MENOLAK';
    const notes = prompt(`Catatan untuk ${action} pendaftar:`);
    
    if (notes !== null) {
        fetch(`/kepsek/final-decision/${id}`, {
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
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Gagal menyimpan keputusan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload();
        });
    }
}
</script>
@endsection
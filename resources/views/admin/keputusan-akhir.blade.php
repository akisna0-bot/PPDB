@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                        <h1 class="text-2xl font-bold">Keputusan Akhir</h1>
                        <p class="text-blue-100">Terima atau tolak siswa yang sudah bayar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu Keputusan</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['menunggu_keputusan'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Diterima</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['diterima'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Ditolak</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['ditolak'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">❌</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Decision Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">📋 Siswa Menunggu Keputusan Akhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($pendingDecision as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $applicant->no_pendaftaran }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-bold">{{ substr($applicant->user->name ?? 'N/A', 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $applicant->user->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $applicant->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $applicant->major->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                    💰 Sudah Bayar
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $applicant->updated_at->format('d M Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button onclick="finalDecision({{ $applicant->id }}, 'ACCEPTED')" 
                                            class="bg-green-600 text-white px-3 py-1 rounded text-xs hover:bg-green-700 transition">
                                        ✅ Terima
                                    </button>
                                    <button onclick="finalDecision({{ $applicant->id }}, 'REJECTED')" 
                                            class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 transition">
                                        ❌ Tolak
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-4xl mb-2">🎓</div>
                                <p>Tidak ada siswa yang menunggu keputusan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pendingDecision->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $pendingDecision->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function finalDecision(applicantId, status) {
    const notes = prompt(`Catatan untuk keputusan ${status === 'ACCEPTED' ? 'DITERIMA' : 'DITOLAK'}:`);
    
    if (notes !== null) {
        fetch(`/admin/final-decision/${applicantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                final_status: status,
                notes: notes
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.ok) {
                alert('Keputusan berhasil disimpan!');
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
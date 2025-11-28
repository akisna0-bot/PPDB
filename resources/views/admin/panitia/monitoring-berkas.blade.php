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
                        <h1 class="text-2xl font-bold">Monitoring Berkas</h1>
                        <p class="text-blue-100">Verifikasi dokumen pendaftar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Berkas</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $applicantsWithFiles->total() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📄</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Terverifikasi</p>
                        <p class="text-3xl font-bold text-gray-900">0</p>
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
                        <p class="text-3xl font-bold text-gray-900">{{ $applicantsWithFiles->total() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berkas Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Daftar Berkas Pendaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jurusan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah Berkas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($applicantsWithFiles ?? [] as $index => $applicant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ ($applicantsWithFiles->currentPage() - 1) * $applicantsWithFiles->perPage() + $index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $applicant->nama_lengkap ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $applicant->no_pendaftaran ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $applicant->major->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $applicant->files->count() }} berkas
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Menunggu Review
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.applicants.show', $applicant->id) }}" 
                                       class="text-purple-600 hover:text-purple-900 font-medium mr-3">
                                        Lihat Berkas
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada berkas yang perlu diverifikasi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(isset($applicantsWithFiles) && $applicantsWithFiles->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $applicantsWithFiles->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center">
                    <span class="mr-3">📋</span> Data Pendaftar untuk Diverifikasi
                </h2>
                <div class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="bg-white/20 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                        <div class="text-sm text-purple-100">Total</div>
                    </div>
                    <div class="bg-yellow-500/20 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['pending'] }}</div>
                        <div class="text-sm text-purple-100">Menunggu</div>
                    </div>
                    <div class="bg-green-500/20 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['verified'] }}</div>
                        <div class="text-sm text-purple-100">Diverifikasi</div>
                    </div>
                    <div class="bg-red-500/20 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['rejected'] }}</div>
                        <div class="text-sm text-purple-100">Ditolak</div>
                    </div>
                    <div class="bg-blue-500/20 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-white">{{ $stats['paid'] }}</div>
                        <div class="text-sm text-purple-100">Sudah Bayar</div>
                    </div>
                </div>
            </div>
            
            <!-- Filter -->
            <div class="p-6 border-b">
                <div class="flex items-center justify-between">
                    <form method="GET" class="flex items-center space-x-4">
                        <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">Semua Status</option>
                            <option value="SUBMIT" {{ request('status') == 'SUBMIT' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>Lulus</option>
                            <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                            <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Sudah Bayar</option>
                        </select>
                        <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            🔍 Filter
                        </button>
                    </form>
                    <a href="{{ route('verifikator.debug-data') }}" target="_blank" class="bg-gray-500 text-white px-3 py-2 rounded text-sm hover:bg-gray-600 transition">
                        🔧 Debug Data
                    </a>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">No. Pendaftaran</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Nama</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jurusan</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Berkas</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Tanggal</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($applicants as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium">{{ $applicant->no_pendaftaran ?? $applicant->registration_number ?? 'N/A' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-bold">{{ substr($applicant->nama_lengkap ?? $applicant->user->name ?? 'N/A', 0, 2) }}</span>
                                    </div>
                                    <span>{{ $applicant->nama_lengkap ?? $applicant->user->name ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $applicant->major->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                    📄 {{ $applicant->files->count() }} berkas
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 rounded-full text-xs
                                    @if($applicant->status == 'SUBMIT') bg-yellow-100 text-yellow-800
                                    @elseif($applicant->status == 'VERIFIED') bg-green-100 text-green-800
                                    @elseif($applicant->status == 'REJECTED') bg-red-100 text-red-800
                                    @elseif($applicant->status == 'PAID') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($applicant->status == 'SUBMIT') ⏳ Menunggu Verifikasi
                                    @elseif($applicant->status == 'VERIFIED') ✅ Diverifikasi
                                    @elseif($applicant->status == 'REJECTED') ❌ Ditolak
                                    @elseif($applicant->status == 'PAID') 💰 Sudah Bayar
                                    @else ❓ {{ $applicant->status }} @endif
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                {{ $applicant->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6">
                                <a href="{{ route('verifikator.show', $applicant->id) }}" 
                                   class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-purple-700 transition">
                                    👁️ Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <div class="text-6xl mb-4">📋</div>
                                    <h3 class="text-lg font-semibold mb-2">Belum Ada Data Pendaftar</h3>
                                    <p class="text-sm">Data pendaftar akan muncul di sini setelah ada yang mendaftar.</p>
                                    <div class="mt-4 text-xs bg-yellow-100 text-yellow-800 px-3 py-2 rounded">
                                        Debug: Total di database = {{ $stats['total'] }} | Filter = {{ request('status') ?: 'Semua' }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applicants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
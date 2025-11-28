@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-teal-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Status Workflow PPDB</h1>
                        <p class="text-blue-100">Monitor alur proses pendaftaran siswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pendaftar Baru</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['submit'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📝</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu Pembayaran</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['payment_pending'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pembayaran Terverifikasi</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['payment_verified'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">✅</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Diterima</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['accepted'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🎓</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Workflow Steps -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">🔄 Alur Workflow PPDB</h3>
            
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0 md:space-x-4">
                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center p-4 bg-blue-50 rounded-lg flex-1">
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mb-2">1</div>
                    <h4 class="font-semibold text-gray-900">Siswa Mendaftar</h4>
                    <p class="text-sm text-gray-600 mt-1">Mengisi formulir & upload dokumen</p>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded mt-2">SUBMIT</span>
                </div>
                
                <div class="hidden md:block text-gray-400">→</div>
                
                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center p-4 bg-yellow-50 rounded-lg flex-1">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold mb-2">2</div>
                    <h4 class="font-semibold text-gray-900">Verifikasi Berkas</h4>
                    <p class="text-sm text-gray-600 mt-1">Verifikator memeriksa dokumen</p>
                    <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded mt-2">VERIFIED</span>
                </div>
                
                <div class="hidden md:block text-gray-400">→</div>
                
                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center p-4 bg-green-50 rounded-lg flex-1">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mb-2">3</div>
                    <h4 class="font-semibold text-gray-900">Pembayaran</h4>
                    <p class="text-sm text-gray-600 mt-1">Siswa bayar & verifikasi keuangan</p>
                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded mt-2">PAYMENT_VERIFIED</span>
                </div>
                
                <div class="hidden md:block text-gray-400">→</div>
                
                <!-- Step 4 -->
                <div class="flex flex-col items-center text-center p-4 bg-purple-50 rounded-lg flex-1">
                    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold mb-2">4</div>
                    <h4 class="font-semibold text-gray-900">Keputusan Akhir</h4>
                    <p class="text-sm text-gray-600 mt-1">Admin/Kepsek memutuskan</p>
                    <span class="text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded mt-2">ACCEPTED</span>
                </div>
            </div>
        </div>

        <!-- Real-time Status -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">📊 Status Real-time Pendaftar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No Pendaftaran</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Saat Ini</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahap Selanjutnya</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($recentApplicants ?? [] as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $applicant->no_pendaftaran ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $applicant->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                @if($applicant->final_status == 'ACCEPTED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">🎓 Diterima</span>
                                @elseif($applicant->final_status == 'REJECTED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">❌ Ditolak</span>
                                @elseif($applicant->status == 'SUBMIT')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">📝 Menunggu Verifikasi</span>
                                @elseif($applicant->status == 'VERIFIED' || $applicant->status == 'PAYMENT_PENDING')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">💰 Menunggu Pembayaran</span>
                                @elseif($applicant->status == 'PAYMENT_VERIFIED')
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">✅ Menunggu Keputusan</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">🔄 {{ $applicant->status }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($applicant->final_status)
                                    Selesai
                                @elseif($applicant->status == 'SUBMIT')
                                    Verifikasi Berkas
                                @elseif($applicant->status == 'VERIFIED' || $applicant->status == 'PAYMENT_PENDING')
                                    Pembayaran
                                @elseif($applicant->status == 'PAYMENT_VERIFIED')
                                    Keputusan Akhir
                                @else
                                    Proses Berlanjut
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($applicant->final_status)
                                    <span class="text-gray-400">-</span>
                                @elseif($applicant->status == 'SUBMIT')
                                    <span class="text-blue-600 font-medium">Verifikator</span>
                                @elseif($applicant->status == 'VERIFIED' || $applicant->status == 'PAYMENT_PENDING')
                                    <span class="text-yellow-600 font-medium">Siswa + Keuangan</span>
                                @elseif($applicant->status == 'PAYMENT_VERIFIED')
                                    <span class="text-green-600 font-medium">Admin/Kepsek</span>
                                @else
                                    <span class="text-gray-400">Tim Terkait</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <div class="text-4xl mb-2">📋</div>
                                <p>Belum ada data pendaftar</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
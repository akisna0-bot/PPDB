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
                            <span class="mr-3">📋</span> Daftar Pembayaran
                        </h1>
                        <p class="text-green-100">Verifikasi pembayaran pendaftar</p>
                    </div>
                    <a href="{{ route('keuangan.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <form method="GET" class="flex items-center space-x-4">
                <select name="status" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Semua Status</option>
                    <option value="VERIFIED" {{ request('status') == 'VERIFIED' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Sudah Bayar</option>
                </select>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    🔍 Filter
                </button>
            </form>
        </div>

        <!-- Tabel Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">💰</span> Data Pembayaran
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
                            <th class="text-left py-3 px-6 font-semibold">Status</th>
                            <th class="text-left py-3 px-6 font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($applicants as $applicant)
                        @php
                            $payment = $applicant->payments->first();
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium">{{ $applicant->no_pendaftaran ?? 'N/A' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-xs font-bold">{{ substr($applicant->user->name ?? 'N/A', 0, 2) }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $applicant->user->name ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $applicant->user->email ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                    {{ $applicant->major->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium">{{ $applicant->wave->nama ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($payment->amount ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-sm
                                    @if($applicant->status == 'PAYMENT_PENDING') bg-yellow-100 text-yellow-800
                                    @elseif($applicant->status == 'PAYMENT_VERIFIED') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($applicant->status == 'PAYMENT_PENDING') ⏳ Menunggu Verifikasi
                                    @elseif($applicant->status == 'PAYMENT_VERIFIED') ✅ Sudah Bayar
                                    @else {{ $applicant->status }} @endif
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if($applicant->status == 'PAYMENT_PENDING')
                                    <form method="POST" action="{{ route('keuangan.verify', $applicant->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Konfirmasi pembayaran untuk {{ $applicant->user->name ?? 'N/A' }}?')"
                                                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700 transition">
                                            ✅ Konfirmasi Bayar
                                        </button>
                                    </form>
                                @else
                                    <span class="text-green-600 font-medium">✅ Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500">
                                <div class="text-4xl mb-2">💰</div>
                                <p>Belum ada data pembayaran</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($applicants->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applicants->links() }}
            </div>
            @endif
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">⏳</div>
                <h3 class="text-2xl font-bold text-yellow-600">{{ $applicants->where('status', 'PAYMENT_PENDING')->count() }}</h3>
                <p class="text-gray-600">Menunggu Verifikasi</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">✅</div>
                <h3 class="text-2xl font-bold text-green-600">{{ $applicants->where('status', 'PAYMENT_VERIFIED')->count() }}</h3>
                <p class="text-gray-600">Sudah Bayar</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">💵</div>
                <h3 class="text-lg font-bold text-blue-600">Rp {{ number_format($applicants->where('status', 'PAYMENT_VERIFIED')->sum(function($app) { return $app->payments->first()->amount ?? 0; }), 0, ',', '.') }}</h3>
                <p class="text-gray-600">Total Terkumpul</p>
            </div>
        </div>
    </div>
</div>
@endsection
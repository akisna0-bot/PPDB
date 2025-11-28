@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-700 to-emerald-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="SMK Bakti Nusantara 666" class="w-16 h-16 rounded-xl mr-6 object-cover shadow-lg">
                    <div>
                        <h1 class="text-3xl font-bold">💳 Kelola Pembayaran</h1>
                        <p class="text-green-100">Verifikasi dan monitoring pembayaran siswa</p>
                    </div>
                </div>
                <a href="{{ route('keuangan.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Filter & Search -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option>Semua Status</option>
                        <option>Pending</option>
                        <option>Paid</option>
                        <option>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pembayaran</label>
                    <select class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option>Semua Jenis</option>
                        <option>Pendaftaran</option>
                        <option>Seragam</option>
                        <option>Buku</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                    <input type="date" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
                    <input type="text" placeholder="No. Invoice / Nama" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white">📋 Daftar Pembayaran</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Invoice</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Siswa</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jurusan</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jenis</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jumlah</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Status</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Tanggal</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->invoice_number }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->payment_method }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $payment->applicant->user->name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-500">{{ $payment->applicant->no_pendaftaran ?? 'N/A' }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm text-gray-600">{{ $payment->applicant->major->nama ?? 'N/A' }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="text-sm">{{ $payment->payment_type_text }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-bold text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs {{ $payment->status_badge }}">
                                    {{ $payment->status_text }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $payment->created_at->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">{{ $payment->created_at->format('H:i') }}</p>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex space-x-2">
                                    <a href="{{ route('payment.show', $payment->id) }}" 
                                       class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-1 rounded text-sm transition">
                                        👁️ Detail
                                    </a>
                                    @if($payment->status == 'pending')
                                        <form method="POST" action="{{ route('keuangan.verify', $payment->id) }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="status" value="paid">
                                            <button type="submit" 
                                                    class="bg-green-100 hover:bg-green-200 text-green-700 px-3 py-1 rounded text-sm transition"
                                                    onclick="return confirm('Verifikasi pembayaran dari {{ $payment->applicant->user->name ?? 'N/A' }}?')">
                                                ✅ ACC
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('keuangan.verify', $payment->id) }}" class="inline ml-1">
                                            @csrf
                                            <input type="hidden" name="status" value="failed">
                                            <input type="hidden" name="notes" value="Ditolak oleh bagian keuangan">
                                            <button type="submit" 
                                                    class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded text-sm transition"
                                                    onclick="return confirm('Tolak pembayaran dari {{ $payment->applicant->user->name ?? 'N/A' }}?')">
                                                ❌ Tolak
                                            </button>
                                        </form>
                                    @endif
                                    @if($payment->isPaid())
                                        <a href="{{ route('payment.receipt', $payment->id) }}" 
                                           class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1 rounded text-sm transition">
                                            🧾 Kwitansi
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $payments->links() }}
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Terbayar</p>
                        <p class="text-xl font-bold text-green-600">
                            Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-xl font-bold text-yellow-600">
                            {{ $payments->where('status', 'pending')->count() }} invoice
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">📊</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Invoice</p>
                        <p class="text-xl font-bold text-blue-600">{{ $payments->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
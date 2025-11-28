@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-new.png.png') }}" alt="SMK BAKTI NUSANTARA 666" class="w-16 h-16 rounded-xl mr-6 object-cover shadow-lg">
                    <div>
                        <h1 class="text-3xl font-bold">Pembayaran</h1>
                        <p class="text-green-100 text-lg">No. Pendaftaran: {{ $applicant->no_pendaftaran }}</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                        🏠 Dashboard
                    </a>
                    <div class="flex space-x-2">
                        <a href="{{ route('payment.create') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                            💳 Bayar Tunggal
                        </a>
                        <a href="{{ route('payment.create-multiple') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                            🛒 Bayar Multiple
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Payment Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Dibayar</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }}</p>
                        <p class="text-sm text-green-600">✅ Lunas</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">💰</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu</p>
                        <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($payments->where('status', 'pending')->sum('amount'), 0, ',', '.') }}</p>
                        <p class="text-sm text-yellow-600">⏳ Pending</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">⏳</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Invoice</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $payments->count() }}</p>
                        <p class="text-sm text-blue-600">📄 Tagihan</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📋</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Riwayat Pembayaran</h3>
                    <div class="flex space-x-2">
                        <a href="{{ route('payment.create') }}" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition duration-200 text-sm">
                            + Tunggal
                        </a>
                        <a href="{{ route('payment.create-multiple') }}" class="bg-white/20 hover:bg-white/30 text-white px-3 py-2 rounded-lg transition duration-200 text-sm">
                            + Multiple
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-lg flex items-center justify-center">
                                        @if($payment->payment_type == 'registration')
                                            <span class="text-2xl">📝</span>
                                        @elseif($payment->payment_type == 'uniform')
                                            <span class="text-2xl">👕</span>
                                        @elseif($payment->payment_type == 'book')
                                            <span class="text-2xl">📚</span>
                                        @else
                                            <span class="text-2xl">💳</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $payment->payment_type_text }}</h4>
                                    <p class="text-sm text-gray-600">{{ $payment->invoice_number }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-lg font-bold text-emerald-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                <span class="text-xs px-2 py-1 rounded-full {{ $payment->status_badge }}">
                                            @if($payment->status == 'pending')
                                                ⏳ Menunggu Pembayaran
                                            @elseif($payment->status == 'paid')
                                                ✅ Sudah Dibayar
                                            @elseif($payment->status == 'failed')
                                                ❌ Pembayaran Gagal
                                            @else
                                                {{ $payment->status_text }}
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500">{{ $payment->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    @if($payment->verified_at && $payment->status == 'paid')
                                        <p class="text-xs text-green-600 mt-1">
                                            ✅ Diverifikasi: {{ $payment->verified_at->format('d M Y H:i') }}
                                        </p>
                                    @elseif($payment->verified_at && $payment->status == 'failed')
                                        <p class="text-xs text-red-600 mt-1">
                                            ❌ Ditolak: {{ $payment->verified_at->format('d M Y H:i') }}
                                            @if($payment->notes)
                                                <br><span class="italic">"{{ $payment->notes }}"</span>
                                            @endif
                                        </p>
                                    @endif
                                    @if($payment->expired_at && $payment->status == 'pending')
                                        <p class="text-xs text-red-600 mt-1">
                                            ⏰ Expired: {{ $payment->expired_at->format('d M Y H:i') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('payment.show', $payment->id) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm transition duration-200">
                                    👁️ Detail
                                </a>
                                @if($payment->canBePaid())
                                    <a href="{{ route('payment.show', $payment->id) }}" 
                                       class="bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-4 py-2 rounded-lg text-sm transition duration-200">
                                        💳 Bayar
                                    </a>
                                @endif
                                @if($payment->isPaid())
                                    <a href="{{ route('payment.receipt', $payment->id) }}" 
                                       class="bg-green-100 hover:bg-green-200 text-green-700 px-4 py-2 rounded-lg text-sm transition duration-200">
                                        🧾 Kwitansi
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">💳</div>
                        <h4 class="text-xl font-semibold text-gray-800 mb-2">Belum Ada Pembayaran</h4>
                        <p class="text-gray-600 mb-6">Mulai dengan membuat invoice pembayaran pendaftaran</p>
                        <a href="{{ route('payment.create') }}" 
                           class="inline-block bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transition-all duration-300">
                            💳 Buat Invoice Pertama
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Payment Methods Info -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <span class="text-2xl mr-3">💳</span>
                <h3 class="text-xl font-bold text-gray-900">Metode Pembayaran yang Tersedia</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="text-3xl mb-2">🏦</div>
                    <h4 class="font-semibold text-gray-900">Transfer Bank</h4>
                    <p class="text-sm text-gray-600">BCA, Mandiri, BNI, BRI</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="text-3xl mb-2">💳</div>
                    <h4 class="font-semibold text-gray-900">Virtual Account</h4>
                    <p class="text-sm text-gray-600">Otomatis terverifikasi</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="text-3xl mb-2">📱</div>
                    <h4 class="font-semibold text-gray-900">E-Wallet</h4>
                    <p class="text-sm text-gray-600">OVO, GoPay, DANA</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <div class="text-3xl mb-2">📲</div>
                    <h4 class="font-semibold text-gray-900">QRIS</h4>
                    <p class="text-sm text-gray-600">Scan & Pay</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
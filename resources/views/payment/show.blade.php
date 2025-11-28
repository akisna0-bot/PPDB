@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">🧾 Detail Invoice</h1>
            <p class="text-slate-600">{{ $payment->invoice_number }}</p>
        </div>

        <!-- Invoice Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">SMK BAKTI NUSANTARA 666</h2>
                        <p class="text-indigo-100">Jl. Percobaan No. 666, Bandung</p>
                    </div>
                    <div class="text-right">
                        <span class="px-4 py-2 rounded-lg {{ $payment->status_badge }} text-sm font-medium">
                            {{ $payment->status_text }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Payment Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                            <span class="text-2xl mr-2">💳</span> Detail Pembayaran
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Jenis Pembayaran:</span>
                                <span class="font-medium">{{ $payment->payment_type_text }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Jumlah:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Metode:</span>
                                <span class="font-medium">{{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Dibuat:</span>
                                <span class="font-medium">{{ $payment->created_at->format('d M Y H:i') }}</span>
                            </div>
                            @if($payment->expired_at)
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Expired:</span>
                                    <span class="font-medium {{ $payment->isExpired() ? 'text-red-600' : 'text-slate-800' }}">
                                        {{ $payment->expired_at->format('d M Y H:i') }}
                                    </span>
                                </div>
                            @endif
                            @if($payment->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Dibayar:</span>
                                    <span class="font-medium text-green-600">{{ $payment->paid_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Student Details -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4 flex items-center">
                            <span class="text-2xl mr-2">👤</span> Data Siswa
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Nama:</span>
                                <span class="font-medium">{{ $payment->applicant->nama_lengkap ?? $payment->applicant->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">No. Pendaftaran:</span>
                                <span class="font-medium">{{ $payment->applicant->no_pendaftaran }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Jurusan:</span>
                                <span class="font-medium">{{ $payment->applicant->major->nama ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Gelombang:</span>
                                <span class="font-medium">{{ $payment->applicant->wave->nama ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($payment->notes)
                    <div class="mb-8 p-4 bg-slate-50 rounded-lg">
                        <h4 class="font-medium text-slate-800 mb-2">📝 Catatan:</h4>
                        <p class="text-slate-600">{{ $payment->notes }}</p>
                    </div>
                @endif

                <!-- Actions -->
                <div class="border-t border-slate-200 pt-6">
                    @if($payment->canBePaid())
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-800 mb-4">💳 Lakukan Pembayaran</h3>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                                <div class="text-center">
                                    <div class="text-4xl mb-4">💳</div>
                                    <h4 class="text-xl font-bold text-slate-800 mb-2">Jumlah yang harus dibayar</h4>
                                    <p class="text-3xl font-bold text-green-600 mb-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <p class="text-sm text-slate-600 mb-4">Metode: {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}</p>
                                </div>
                            </div>
                            
                            @if($payment->payment_method === 'qris')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="text-center">
                                        <div class="bg-white border-2 border-gray-300 rounded-lg p-4 inline-block">
                                            <div class="w-48 h-48 mx-auto mb-4 flex items-center justify-center bg-white border rounded">
                                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=192x192&data={{ urlencode('QRIS Payment - ' . $payment->invoice_number . ' - Rp ' . number_format($payment->amount, 0, ',', '.')) }}" 
                                                     alt="QR Code QRIS" class="w-full h-full object-contain" 
                                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTkyIiBoZWlnaHQ9IjE5MiIgdmlld0JveD0iMCAwIDE5MiAxOTIiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxOTIiIGhlaWdodD0iMTkyIiBmaWxsPSIjRjNGNEY2Ii8+Cjx0ZXh0IHg9Ijk2IiB5PSI5NiIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNjc3NDhGIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+UVIgQ29kZTwvdGV4dD4KPC9zdmc+'">
                                            </div>
                                            <p class="text-sm text-gray-600 mb-2">Scan QR Code untuk pembayaran</p>
                                            <p class="font-bold text-lg text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                            <h4 class="font-semibold text-orange-800 mb-2">📲 Cara Bayar dengan QRIS:</h4>
                                            <ol class="text-sm text-orange-700 space-y-1">
                                                <li>1. Buka aplikasi e-wallet (GoPay, OVO, DANA, ShopeePay)</li>
                                                <li>2. Pilih menu "Scan QR" atau "Bayar"</li>
                                                <li>3. Arahkan kamera ke QR Code di samping</li>
                                                <li>4. Konfirmasi pembayaran di aplikasi</li>
                                                <li>5. Simpan bukti pembayaran</li>
                                            </ol>
                                        </div>
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                            <p class="text-blue-800 text-sm">
                                                ℹ️ QR Code berlaku selama 15 menit. Refresh halaman jika expired.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($payment->payment_method === 'bank_transfer')
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-blue-800 mb-2">🏦 Transfer Bank:</h4>
                                    <div class="text-sm text-blue-700 space-y-2">
                                        <p><strong>Bank BCA:</strong> 1234567890 a.n SMK Bakti Nusantara 666</p>
                                        <p><strong>Bank Mandiri:</strong> 0987654321 a.n SMK Bakti Nusantara 666</p>
                                        <p><strong>Jumlah:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                        <p><strong>Kode Unik:</strong> {{ $payment->invoice_number }}</p>
                                    </div>
                                </div>
                            @elseif($payment->payment_method === 'cash')
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <h4 class="font-semibold text-yellow-800 mb-2">💵 Pembayaran Tunai:</h4>
                                    <div class="text-sm text-yellow-700 space-y-1">
                                        <p>Datang ke sekolah pada jam kerja:</p>
                                        <p><strong>Senin - Jumat:</strong> 07:00 - 15:00</p>
                                        <p><strong>Sabtu:</strong> 07:00 - 12:00</p>
                                        <p>Bawa invoice ini untuk pembayaran</p>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="text-center">
                                <form method="POST" action="{{ route('payment.pay', $payment->id) }}" class="inline-block">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin sudah melakukan pembayaran?')" 
                                            class="bg-gradient-to-r from-green-600 to-emerald-600 hover:shadow-lg text-white font-semibold py-4 px-8 rounded-lg transition-all duration-300 text-lg">
                                        ✅ Konfirmasi Sudah Bayar
                                    </button>
                                </form>
                                <p class="text-xs text-slate-500 mt-2">Klik tombol ini setelah Anda melakukan pembayaran</p>
                            </div>
                        </div>
                    @endif

                    @if($payment->isPaid())
                        <div class="mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                                <p class="text-green-800 font-medium">✅ Pembayaran telah berhasil!</p>
                            </div>
                            <a href="{{ route('payment.receipt', $payment->id) }}" 
                               class="bg-gradient-to-r from-green-600 to-emerald-600 hover:shadow-lg text-white font-semibold py-3 px-6 rounded-lg inline-block transition-all duration-300">
                                🧾 Lihat Kwitansi
                            </a>
                        </div>
                    @endif

                    @if($payment->isExpired())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                            <p class="text-red-800 font-medium">⚠️ Invoice ini telah kadaluarsa</p>
                            <p class="text-red-600 text-sm mt-1">Silakan buat invoice baru untuk melanjutkan pembayaran.</p>
                        </div>
                    @endif

                    <!-- Navigation -->
                    <div class="flex justify-between items-center">
                        <x-back-button url="{{ route('payment.index') }}" text="Kembali ke Pembayaran" class="px-6 py-3 border border-slate-300 text-slate-700" />
                        
                        @if($payment->canBePaid())
                            <a href="{{ route('payment.create') }}" 
                               class="px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg transition-colors">
                                + Buat Invoice Baru
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
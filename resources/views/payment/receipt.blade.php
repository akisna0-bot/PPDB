@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">🧾 Bukti Pembayaran</h1>
            <p class="text-slate-600">Invoice #{{ $payment->invoice_number }}</p>
        </div>

        <!-- Receipt Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden receipt-print">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold">SMK BAKTI NUSANTARA 666</h2>
                        <p class="text-indigo-100">Jl. Percobaan No. 666, Bandung</p>
                        <p class="text-indigo-100">📧 ppdb@smkbn666.sch.id | 📞 (022) 123-4567</p>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 rounded-lg px-4 py-2">
                            <span class="text-sm font-medium">STATUS</span>
                            <div class="text-lg font-bold">✅ LUNAS</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8 mb-8">
                    <!-- Left Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">📋 Detail Pembayaran</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Nomor Invoice:</span>
                                <span class="font-medium">{{ $payment->invoice_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Jenis Pembayaran:</span>
                                <span class="font-medium">{{ $payment->payment_type_text }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Metode Pembayaran:</span>
                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">Tanggal Bayar:</span>
                                <span class="font-medium">{{ $payment->paid_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($payment->transaction_id)
                            <div class="flex justify-between">
                                <span class="text-slate-600">ID Transaksi:</span>
                                <span class="font-medium">{{ $payment->transaction_id }}</span>
                            </div>
                            @endif
                            @if($payment->reference_number)
                            <div class="flex justify-between">
                                <span class="text-slate-600">No. Referensi:</span>
                                <span class="font-medium">{{ $payment->reference_number }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">👤 Data Siswa</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-slate-600">Nama:</span>
                                <span class="font-medium">{{ $payment->applicant->nama_lengkap ?? $payment->applicant->user->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-600">No. Pendaftaran:</span>
                                <span class="font-medium">{{ $payment->applicant->no_pendaftaran ?? 'N/A' }}</span>
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

                <!-- Amount -->
                <div class="border-t border-slate-200 pt-6">
                    <div class="flex justify-between items-center text-2xl font-bold text-slate-800">
                        <span>💰 Total Pembayaran:</span>
                        <span class="text-green-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($payment->notes)
                <div class="mt-6 p-4 bg-slate-50 rounded-lg">
                    <h4 class="font-medium text-slate-800 mb-2">📝 Catatan:</h4>
                    <p class="text-slate-600">{{ $payment->notes }}</p>
                </div>
                @endif

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-slate-200 text-center text-sm text-slate-500">
                    <p>Bukti pembayaran ini sah dan dikeluarkan secara otomatis oleh sistem.</p>
                    <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-center space-x-4 no-print">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                🖨️ Cetak Bukti
            </button>
            <x-back-button url="{{ route('payment.index') }}" text="Kembali ke Pembayaran" class="bg-slate-600 hover:bg-slate-700 text-white px-6 py-3 font-medium" />
        </div>
    </div>
</div>

<style>
@media print {
    body { 
        margin: 0;
        padding: 20px;
        background: white !important;
    }
    
    .no-print { 
        display: none !important; 
    }
    
    .receipt-print {
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .bg-gradient-to-r {
        background: #4f46e5 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    * {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection
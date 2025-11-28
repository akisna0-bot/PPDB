@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">💳 Buat Invoice Pembayaran</h1>
            <p class="text-slate-600">Pilih jenis pembayaran dan metode yang diinginkan</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">📋</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Form Pembayaran</h2>
                        <p class="text-indigo-100">{{ $applicant->nama_lengkap ?? $applicant->user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('payment.store') }}" class="p-8">
                @csrf
                
                <!-- Payment Type -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-4">
                        🏷️ Jenis Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <input type="radio" id="registration" name="payment_type" value="registration" 
                                   class="peer sr-only" required checked>
                            <label for="registration" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-2xl">📝</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800">Biaya Pendaftaran</h3>
                                    <p class="text-sm text-slate-600">Rp {{ number_format($applicant->wave->biaya_daftar ?? 150000, 0, ',', '.') }}</p>
                                    <p class="text-xs text-blue-600">Wajib dibayar untuk melanjutkan proses</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="uniform" name="payment_type" value="uniform" class="peer sr-only">
                            <label for="uniform" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-2xl">👕</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800">Seragam</h3>
                                    <p class="text-sm text-slate-600">Rp 500.000</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="book" name="payment_type" value="book" class="peer sr-only">
                            <label for="book" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-2xl">📚</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800">Buku & LKS</h3>
                                    <p class="text-sm text-slate-600">Rp 300.000</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="other" name="payment_type" value="other" class="peer sr-only">
                            <label for="other" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-2xl">💼</span>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-slate-800">Lainnya</h3>
                                    <p class="text-sm text-slate-600">Rp 100.000</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('payment_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-4">
                        💳 Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- QRIS - Utama -->
                        <div class="relative col-span-full md:col-span-2 lg:col-span-1">
                            <input type="radio" id="qris" name="payment_method" value="qris" class="peer sr-only" required checked>
                            <label for="qris" class="flex items-center p-6 border-3 border-orange-300 bg-orange-50 rounded-xl cursor-pointer hover:border-orange-400 peer-checked:border-orange-500 peer-checked:bg-orange-100 transition-all shadow-lg">
                                <div class="w-12 h-12 bg-orange-200 rounded-lg flex items-center justify-center mr-4">
                                    <span class="text-2xl">📲</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-orange-800 text-lg">QRIS</h4>
                                    <p class="text-sm text-orange-700 font-medium">Scan & Pay - Tercepat!</p>
                                    <p class="text-xs text-orange-600">Semua e-wallet & bank</p>
                                    <div class="mt-1">
                                        <span class="bg-orange-200 text-orange-800 px-2 py-1 rounded-full text-xs font-bold">REKOMENDASI</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="peer sr-only">
                            <label for="bank_transfer" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">🏦</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Transfer Bank</h4>
                                    <p class="text-xs text-slate-600">BCA, Mandiri, BNI, BRI</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="virtual_account" name="payment_method" value="virtual_account" class="peer sr-only">
                            <label for="virtual_account" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">💳</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Virtual Account</h4>
                                    <p class="text-xs text-slate-600">Auto verification</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="e_wallet" name="payment_method" value="e_wallet" class="peer sr-only">
                            <label for="e_wallet" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">📱</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">E-Wallet</h4>
                                    <p class="text-xs text-slate-600">OVO, GoPay, DANA</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="cash" name="payment_method" value="cash" class="peer sr-only">
                            <label for="cash" class="flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">💵</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Tunai</h4>
                                    <p class="text-xs text-slate-600">Bayar di sekolah</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-8">
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">
                        📝 Catatan (Opsional)
                    </label>
                    <textarea id="notes" name="notes" rows="3" 
                              class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                              placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-slate-200">
                    <x-back-button url="{{ route('payment.index') }}" text="Kembali" class="px-6 py-3 border border-slate-300 text-slate-700" />
                    <button type="submit" 
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300">
                        🧾 Buat Invoice
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-8 bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                    <span class="text-2xl">ℹ️</span>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800 mb-2">Informasi Pembayaran</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• Invoice akan berlaku selama 3 hari setelah dibuat</li>
                        <li>• Pembayaran akan diverifikasi maksimal 1x24 jam</li>
                        <li>• Simpan bukti pembayaran untuk keperluan administrasi</li>
                        <li>• Hubungi admin jika ada kendala pembayaran</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
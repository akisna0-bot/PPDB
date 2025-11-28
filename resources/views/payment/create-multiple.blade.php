@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-indigo-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800 mb-2">🛒 Pembayaran Multiple Item</h1>
            <p class="text-slate-600">Pilih item yang ingin dibayar sekaligus</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 text-white">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🛍️</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">Keranjang Pembayaran</h2>
                        <p class="text-indigo-100">{{ $applicant->nama_lengkap ?? $applicant->user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('payment.store') }}" class="p-8">
                @csrf
                
                <!-- Payment Items -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-4">
                        🏷️ Pilih Item Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        @foreach($paymentItems as $item)
                        <div class="payment-item border-2 border-slate-200 rounded-xl p-4 hover:border-indigo-300 transition-all">
                            <label class="flex items-center justify-between cursor-pointer">
                                <div class="flex items-center">
                                    <input type="checkbox" name="items[]" value="{{ $item->id }}" 
                                           class="mr-4 h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                           data-price="{{ $item->price }}"
                                           {{ $item->is_required ? 'required checked' : '' }}>
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center mr-4
                                        @if($item->category == 'pendaftaran') bg-blue-100
                                        @elseif($item->category == 'seragam') bg-green-100
                                        @elseif($item->category == 'buku') bg-purple-100
                                        @else bg-orange-100 @endif">
                                        <span class="text-2xl">
                                            @if($item->category == 'pendaftaran') 📝
                                            @elseif($item->category == 'seragam') 👕
                                            @elseif($item->category == 'buku') 📚
                                            @else 💼 @endif
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-800">{{ $item->name }}</h3>
                                        <p class="text-sm text-slate-600">{{ $item->description }}</p>
                                        @if($item->is_required)
                                            <span class="inline-block px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full mt-1">Wajib</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-indigo-600 text-lg">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                    <p class="text-xs text-slate-500 capitalize">{{ $item->category }}</p>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Total Amount Display -->
                    <div class="mt-6 p-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl border border-indigo-200">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-slate-700">Total Pembayaran:</span>
                            <span id="totalAmount" class="text-3xl font-bold text-indigo-600">Rp 0</span>
                        </div>
                        <p class="text-sm text-slate-600 mt-2">Pilih minimal 1 item untuk melanjutkan</p>
                    </div>
                    
                    @error('items')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-4">
                        💳 Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="relative">
                            <input type="radio" id="qris" name="payment_method" value="qris" class="peer sr-only" required checked>
                            <label for="qris" class="method-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">📲</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">QRIS</h4>
                                    <p class="text-xs text-slate-600">Scan & Pay</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="peer sr-only">
                            <label for="bank_transfer" class="method-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">🏦</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">Transfer Bank</h4>
                                    <p class="text-xs text-slate-600">BCA, Mandiri, BNI</p>
                                </div>
                            </label>
                        </div>

                        <div class="relative">
                            <input type="radio" id="e_wallet" name="payment_method" value="e_wallet" class="peer sr-only">
                            <label for="e_wallet" class="method-option flex items-center p-4 border-2 border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <span class="text-xl">📱</span>
                                </div>
                                <div>
                                    <h4 class="font-medium text-slate-800">E-Wallet</h4>
                                    <p class="text-xs text-slate-600">OVO, GoPay, DANA</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center pt-6 border-t border-slate-200">
                    <a href="{{ route('payment.index') }}" class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition">
                        ← Kembali
                    </a>
                    <button type="submit" id="submitBtn" disabled
                            class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-lg hover:shadow-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        🧾 Buat Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Payment items selection
function updateTotal() {
    let total = 0;
    let checkedCount = 0;
    
    document.querySelectorAll('input[name="items[]"]:checked').forEach(checkbox => {
        total += parseInt(checkbox.dataset.price);
        checkedCount++;
    });
    
    document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
    
    // Enable/disable submit button
    const submitBtn = document.getElementById('submitBtn');
    if (checkedCount > 0) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }
}

// Add event listeners to checkboxes
document.querySelectorAll('input[name="items[]"]').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const paymentItem = this.closest('.payment-item');
        if (this.checked) {
            paymentItem.classList.add('border-indigo-500', 'bg-indigo-50');
        } else {
            paymentItem.classList.remove('border-indigo-500', 'bg-indigo-50');
        }
        updateTotal();
    });
});

// Payment method selection
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.method-option').forEach(option => {
            option.classList.remove('border-indigo-500', 'bg-indigo-50');
        });
        
        const selectedMethod = this.closest('.method-option');
        selectedMethod.classList.add('border-indigo-500', 'bg-indigo-50');
    });
});

// Auto-check required items and set initial state
document.querySelectorAll('input[name="items[]"][required]').forEach(checkbox => {
    checkbox.checked = true;
    checkbox.closest('.payment-item').classList.add('border-indigo-500', 'bg-indigo-50');
});

// Set initial method selection
document.querySelector('input[name="payment_method"]:checked').closest('.method-option').classList.add('border-indigo-500', 'bg-indigo-50');

// Initial total calculation
updateTotal();
</script>
@endsection
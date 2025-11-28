<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-navy mb-2">🔑 Reset Password</h2>
        <p class="text-gray-600">Masukkan password baru untuk akun Anda</p>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-lg">
            <div class="flex items-start">
                <span class="mr-2 mt-0.5">❌</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div class="text-sm">{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-navy mb-2">
                📧 Email Address
            </label>
            <input id="email" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy focus:border-navy transition-all duration-200 bg-gray-50" 
                   type="email" 
                   name="email" 
                   value="{{ old('email', $request->email) }}" 
                   readonly>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-navy mb-2">
                🔑 Password Baru
            </label>
            <input id="password" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy focus:border-navy transition-all duration-200" 
                   type="password" 
                   name="password" 
                   placeholder="Minimal 8 karakter"
                   required>
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-navy mb-2">
                🔒 Konfirmasi Password
            </label>
            <input id="password_confirmation" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy focus:border-navy transition-all duration-200"
                   type="password" 
                   name="password_confirmation" 
                   placeholder="Ulangi password baru"
                   required>
        </div>

        <!-- Password Requirements -->
        <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
            <h4 class="font-semibold text-navy text-sm mb-2">🔒 Syarat Password:</h4>
            <ul class="text-xs space-y-1 text-gray-700">
                <li>• Minimal 8 karakter</li>
                <li>• Kombinasi huruf besar dan kecil</li>
                <li>• Mengandung angka</li>
                <li>• Disarankan menggunakan simbol (!@#$%)</li>
            </ul>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
            <span class="mr-2">✨</span>
            Reset Password
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center text-navy hover:text-blue-800 font-medium transition-colors">
                <span class="mr-2">←</span>
                Kembali ke Login
            </a>
        </div>
    </form>
    
    <!-- Success Info -->
    <div class="mt-6 p-4 bg-green-50 rounded-xl border border-green-200">
        <h4 class="font-semibold text-navy mb-2 flex items-center">
            <span class="mr-2">✅</span>
            Setelah Reset Password:
        </h4>
        <ul class="text-xs space-y-1 text-gray-700">
            <li>• Password lama akan otomatis tidak berlaku</li>
            <li>• Gunakan password baru untuk login</li>
            <li>• Simpan password di tempat yang aman</li>
            <li>• Login kembali dengan password baru</li>
        </ul>
    </div>
</x-guest-layout>

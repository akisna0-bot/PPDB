<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-navy mb-2">🔄 Lupa Password</h2>
        <p class="text-gray-600 mb-4">Masukkan email yang terdaftar untuk mendapatkan link reset password</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg">
            <div class="flex items-start">
                <span class="mr-2 mt-0.5">✅</span>
                <div class="w-full">
                    <p class="font-semibold mb-2">Email Reset Password Berhasil Dikirim!</p>
                    <p class="text-sm mb-2">Kami telah mengirimkan link reset password ke email Anda.</p>
                    <div class="bg-white p-3 rounded border text-sm">
                        <p class="font-medium mb-1">Langkah selanjutnya:</p>
                        <ol class="list-decimal list-inside space-y-1 text-xs">
                            <li>Buka email Anda</li>
                            <li>Cari email dari SMK Bakti Nusantara 666</li>
                            <li>Klik link reset password di email</li>
                            <li>Masukkan password baru</li>
                        </ol>
                    </div>
                    <p class="text-xs mt-2 text-gray-600">Tidak menerima email? Periksa folder spam/junk atau coba kirim ulang.</p>
                </div>
            </div>
        </div>
    @endif

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

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-navy mb-2">
                📧 Email Address
            </label>
            <input id="email" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy focus:border-navy transition-all duration-200" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   placeholder="Masukkan email yang terdaftar"
                   required autofocus>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-navy hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
            <span class="mr-2">📨</span>
            Kirim Link Reset Password
        </button>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center text-navy hover:text-blue-800 font-medium transition-colors">
                <span class="mr-2">←</span>
                Kembali ke Login
            </a>
        </div>
    </form>

    <!-- Info & Bantuan -->
    <div class="mt-8 space-y-4">
        <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
            <h4 class="font-semibold text-navy mb-2 flex items-center">
                <span class="mr-2">💬</span>
                Butuh Bantuan?
            </h4>
            <div class="text-sm space-y-2">
                <p>Jika Anda mengalami kesulitan reset password:</p>
                <div class="bg-white p-3 rounded border">
                    <p class="font-medium mb-1">Hubungi Admin:</p>
                    <p class="text-xs">📞 WhatsApp: <span class="font-mono">0812-6666-9999</span></p>
                    <p class="text-xs">📧 Email: <span class="font-mono">admin@smkbn666.sch.id</span></p>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-yellow-50 rounded-xl border border-yellow-200">
            <h4 class="font-semibold text-navy mb-2 flex items-center">
                <span class="mr-2">ℹ️</span>
                Tips Keamanan:
            </h4>
            <ul class="text-xs space-y-1 text-gray-700">
                <li>• Gunakan password minimal 8 karakter</li>
                <li>• Kombinasikan huruf besar, kecil, angka, dan simbol</li>
                <li>• Jangan gunakan password yang sama dengan akun lain</li>
                <li>• Jangan bagikan password kepada siapapun</li>
            </ul>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-navy mb-2">📝 Daftar SPMB</h2>
        <p class="text-gray-600">Buat akun baru untuk memulai pendaftaran</p>
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

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-navy mb-2">
                👤 Nama Lengkap
            </label>
            <input id="name" 
                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-navy focus:border-navy transition-all duration-200" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   placeholder="Masukkan nama lengkap Anda"
                   required autofocus>
        </div>

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
                   placeholder="Masukkan email aktif Anda"
                   required>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-navy mb-2">
                🔑 Password
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
                   placeholder="Ulangi password Anda"
                   required>
        </div>

        <!-- Register Button -->
        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
            <span class="mr-2">✨</span>
            Buat Akun Sekarang
        </button>

        <!-- Links -->
        <div class="text-center space-y-3">
            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-2">
                    Sudah punya akun?
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center bg-navy hover:bg-blue-800 text-white font-bold py-2 px-6 rounded-xl transition-all duration-200">
                    <span class="mr-2">🔐</span>
                    Login di Sini
                </a>
            </div>
        </div>
    </form>

    <!-- Info -->
    <div class="mt-8 p-4 bg-yellow-50 rounded-xl border border-yellow-200">
        <h4 class="font-semibold text-navy mb-2 flex items-center">
            <span class="mr-2">ℹ️</span>
            Informasi Penting:
        </h4>
        <ul class="text-xs space-y-1 text-gray-700">
            <li>• Gunakan email aktif yang bisa Anda akses</li>
            <li>• Password minimal 8 karakter</li>
            <li>• Simpan data login Anda dengan baik</li>
            <li>• Setelah daftar, Anda bisa langsung mengisi formulir pendaftaran</li>
        </ul>
    </div>
</x-guest-layout>
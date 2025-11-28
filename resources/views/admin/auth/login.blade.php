<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-navy mb-2">🔐 Login SPMB</h2>
        <p class="text-gray-600">Masuk ke akun Anda untuk melanjutkan pendaftaran</p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg">
            <div class="flex items-center">
                <span class="mr-2">✅</span>
                {{ session('status') }}
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

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
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
                   placeholder="Masukkan email Anda"
                   required autofocus>
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
                   placeholder="Masukkan password Anda"
                   required>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" class="w-4 h-4 text-navy bg-gray-100 border-gray-300 rounded focus:ring-navy" name="remember">
            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                Ingat saya
            </label>
        </div>

        <!-- Login Button -->
        <button type="submit" class="w-full bg-navy hover:bg-blue-800 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
            <span class="mr-2">🚀</span>
            Masuk ke Akun
        </button>

        <!-- Links -->
        <div class="text-center space-y-3">
            @if (Route::has('password.request'))
                <div>
                    <a class="text-sm text-navy hover:text-blue-800 font-medium" href="{{ route('password.request') }}">
                        🔄 Lupa Password?
                    </a>
                </div>
            @endif
            
            <div class="border-t pt-4">
                <p class="text-sm text-gray-600 mb-2">
                    Belum punya akun?
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center bg-gold hover:bg-yellow-500 text-navy font-bold py-2 px-6 rounded-xl transition-all duration-200">
                    <span class="mr-2">📝</span>
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>
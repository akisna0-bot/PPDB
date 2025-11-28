<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SMK Bakti Nusantara 666</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <img src="/images/logo-new.png.png" alt="Logo" class="w-12 h-12 object-contain bg-white rounded-full p-1">
                    <div>
                        <h1 class="text-xl font-bold">SMK Bakti Nusantara 666</h1>
                        <p class="text-sm text-blue-200">PPDB Online 2025</p>
                    </div>
                </div>
                
                <div class="flex space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-yellow-500 text-black px-4 py-2 rounded font-bold hover:bg-yellow-400">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-yellow-500 text-black px-4 py-2 rounded font-bold hover:bg-yellow-400">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="border border-white px-4 py-2 rounded hover:bg-white hover:text-blue-600">
                            Daftar
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <h1 class="text-5xl font-bold mb-4">SMK Bakti Nusantara 666</h1>
            <p class="text-xl mb-8">Penerimaan Peserta Didik Baru 2025</p>
            
            @guest
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-8 rounded-lg text-lg">
                    🚀 Daftar Sekarang
                </a>
                <a href="{{ route('login') }}" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 font-bold py-3 px-8 rounded-lg text-lg">
                    Login
                </a>
            </div>
            @else
            <a href="{{ route('dashboard') }}" class="bg-yellow-500 hover:bg-yellow-400 text-black font-bold py-3 px-8 rounded-lg text-lg">
                Dashboard Saya
            </a>
            @endguest
        </div>
    </section>

    <!-- Info Cards -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center p-6 bg-green-50 rounded-lg">
                    <div class="text-4xl mb-3">📝</div>
                    <h3 class="font-bold text-green-800">Daftar Online</h3>
                    <p class="text-sm text-green-600">Formulir digital mudah</p>
                </div>
                <div class="text-center p-6 bg-blue-50 rounded-lg">
                    <div class="text-4xl mb-3">📄</div>
                    <h3 class="font-bold text-blue-800">Upload Dokumen</h3>
                    <p class="text-sm text-blue-600">Berkas digital</p>
                </div>
                <div class="text-center p-6 bg-purple-50 rounded-lg">
                    <div class="text-4xl mb-3">💳</div>
                    <h3 class="font-bold text-purple-800">Bayar Online</h3>
                    <p class="text-sm text-purple-600">QRIS & Transfer</p>
                </div>
                <div class="text-center p-6 bg-yellow-50 rounded-lg">
                    <div class="text-4xl mb-3">📊</div>
                    <h3 class="font-bold text-yellow-800">Cek Status</h3>
                    <p class="text-sm text-yellow-600">Real-time tracking</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline -->
    <section class="py-16 bg-gray-100">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12">Timeline PPDB 2025</h2>
            
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-lg border-l-4 border-green-500">
                    <h3 class="text-xl font-bold text-green-600 mb-4">Gelombang 1</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="font-bold">Pendaftaran</h4>
                            <p class="text-gray-600">1 Jan - 31 Mar 2025</p>
                        </div>
                        <div>
                            <h4 class="font-bold">Verifikasi</h4>
                            <p class="text-gray-600">1 - 4 Apr 2025</p>
                        </div>
                        <div>
                            <h4 class="font-bold">Pengumuman</h4>
                            <p class="text-gray-600">5 Apr 2025</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg border-l-4 border-blue-500">
                    <h3 class="text-xl font-bold text-blue-600 mb-4">Gelombang 2</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <h4 class="font-bold">Pendaftaran</h4>
                            <p class="text-gray-600">1 Apr - 30 Jun 2025</p>
                        </div>
                        <div>
                            <h4 class="font-bold">Verifikasi</h4>
                            <p class="text-gray-600">1 - 8 Jul 2025</p>
                        </div>
                        <div>
                            <h4 class="font-bold">Pengumuman</h4>
                            <p class="text-gray-600">10 Jul 2025</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; 2025 SMK Bakti Nusantara 666</p>
            <p class="text-sm text-gray-400 mt-2">Sistem PPDB Online</p>
        </div>
    </footer>
</body>
</html>
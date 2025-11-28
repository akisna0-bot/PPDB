<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - PPDB SMK Bakti Nusantara 666</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-blue-600">PPDB SMK Bakti Nusantara 666</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-700">{{ auth()->user()->nama }}</span>
                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">Admin</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
                <p class="text-gray-600">Selamat datang, {{ auth()->user()->nama }}</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600">0</div>
                        <div class="text-sm text-gray-600">Total Pendaftar</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">0</div>
                        <div class="text-sm text-gray-600">Menunggu Verifikasi</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">0</div>
                        <div class="text-sm text-gray-600">Terverifikasi</div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">Rp 0</div>
                        <div class="text-sm text-gray-600">Total Pembayaran</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Menu Admin</h3>
                    <div class="space-y-2">
                        <a href="#" class="block p-2 text-blue-600 hover:bg-blue-50 rounded">Data Pendaftar</a>
                        <a href="#" class="block p-2 text-blue-600 hover:bg-blue-50 rounded">Monitoring Berkas</a>
                        <a href="#" class="block p-2 text-blue-600 hover:bg-blue-50 rounded">Peta Sebaran</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Manajemen</h3>
                    <div class="space-y-2">
                        <a href="#" class="block p-2 text-green-600 hover:bg-green-50 rounded">Master Data</a>
                        <a href="#" class="block p-2 text-green-600 hover:bg-green-50 rounded">Manajemen Akun</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Laporan</h3>
                    <div class="space-y-2">
                        <a href="#" class="block p-2 text-purple-600 hover:bg-purple-50 rounded">Export Excel</a>
                        <a href="#" class="block p-2 text-purple-600 hover:bg-purple-50 rounded">Export PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
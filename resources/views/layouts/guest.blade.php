<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - SPMB SMK BAKTI NUSANTARA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .emoji { font-family: "Apple Color Emoji", "Segoe UI Emoji", "Noto Color Emoji", sans-serif; }
        .bg-navy { background-color: #1e3a8a; }
        .text-navy { color: #1e3a8a; }
        .bg-gold { background-color: #f59e0b; }
        .text-gold { color: #f59e0b; }
        .border-gold { border-color: #f59e0b; }
        .hover\:bg-gold:hover { background-color: #f59e0b; }
        .hover\:text-gold:hover { color: #f59e0b; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased emoji bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <!-- Header Logo -->
        <div class="text-center mb-8">
            <a href="/" class="flex flex-col items-center group">
                <div class="w-24 h-24 bg-gold rounded-full flex items-center justify-center overflow-hidden mb-3 shadow-lg group-hover:shadow-xl transition-all duration-200">
                    <img src="{{ asset('images/logo-smk-bakti-nusantara666.svg') }}" alt="SMK BAKTI NUSANTARA666" class="w-full h-full object-cover">
                </div>
                <h1 class="text-navy font-bold text-xl mb-1">SMK BAKTI NUSANTARA666</h1>
                <p class="text-gold font-semibold text-sm">Sistem Penerimaan Murid Baru Online</p>
            </a>
        </div>

        <!-- Login Form Container -->
        <div class="w-full sm:max-w-lg mt-6 px-8 py-8 bg-white/90 backdrop-blur-sm shadow-2xl overflow-hidden sm:rounded-2xl border border-white/20">
            @yield('content')
        </div>

        <!-- Back to Home -->
        <div class="mt-6">
            <a href="/" class="inline-flex items-center text-navy hover:text-blue-800 font-medium transition-colors">
                <span class="mr-2">🏠</span>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
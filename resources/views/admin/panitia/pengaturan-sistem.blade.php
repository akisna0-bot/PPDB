@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-gray-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Pengaturan Sistem</h1>
                        <p class="text-blue-100">Konfigurasi dan pengaturan sistem PPDB</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Pengaturan Umum -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">⚙️</span>
                    Pengaturan Umum
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah</label>
                        <input type="text" value="SMK Bakti Nusantara 666" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                        <input type="text" value="2025/2026" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Pendaftaran</label>
                        <select class="w-full px-3 py-2 border rounded-lg">
                            <option value="open">Buka</option>
                            <option value="closed">Tutup</option>
                        </select>
                    </div>
                    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Simpan Pengaturan
                    </button>
                </div>
            </div>

            <!-- Pengaturan Gelombang -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">🌊</span>
                    Pengaturan Gelombang
                </h3>
                <div class="space-y-4">
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800">Gelombang 1</h4>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <label class="block text-sm text-gray-600">Mulai</label>
                                <input type="date" value="2025-01-01" class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Selesai</label>
                                <input type="date" value="2025-03-31" class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="block text-sm text-gray-600">Biaya</label>
                            <input type="number" value="150000" class="w-full px-2 py-1 border rounded text-sm">
                        </div>
                    </div>
                    <div class="border rounded-lg p-4">
                        <h4 class="font-semibold text-gray-800">Gelombang 2</h4>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <label class="block text-sm text-gray-600">Mulai</label>
                                <input type="date" value="2025-04-01" class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-600">Selesai</label>
                                <input type="date" value="2025-06-30" class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                        </div>
                        <div class="mt-2">
                            <label class="block text-sm text-gray-600">Biaya</label>
                            <input type="number" value="200000" class="w-full px-2 py-1 border rounded text-sm">
                        </div>
                    </div>
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Update Gelombang
                    </button>
                </div>
            </div>

            <!-- Pengaturan Email -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">📧</span>
                    Pengaturan Email
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" value="smtp.gmail.com" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="number" value="587" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Pengirim</label>
                        <input type="email" value="ppdb@smkbaktinusantara.sch.id" class="w-full px-3 py-2 border rounded-lg">
                    </div>
                    <button class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                        Test & Simpan Email
                    </button>
                </div>
            </div>

            <!-- Maintenance Mode -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">🔧</span>
                    Mode Maintenance
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div>
                            <h4 class="font-semibold text-yellow-800">Maintenance Mode</h4>
                            <p class="text-sm text-yellow-600">Nonaktifkan akses untuk maintenance sistem</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan Maintenance</label>
                        <textarea class="w-full px-3 py-2 border rounded-lg" rows="3">Sistem sedang dalam maintenance. Silakan coba lagi nanti.</textarea>
                    </div>
                    <button class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">
                        Update Maintenance
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
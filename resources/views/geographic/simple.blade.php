@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center">
                <img src="{{ asset('images/logo.jpeg') }}" alt="SMK Bakti Nusantara 666" class="w-16 h-16 rounded-xl mr-6 object-cover shadow-lg">
                <div>
                    <h1 class="text-3xl font-bold">🗺️ Peta Siswa</h1>
                    <p class="text-blue-100">Lokasi Calon Siswa SMK Bakti Nusantara 666</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">👥</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Siswa</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Applicant::count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Diterima</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Applicant::where('status', 'ADM_PASS')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">⏳</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Menunggu</p>
                        <p class="text-2xl font-bold">{{ \App\Models\Applicant::where('status', 'SUBMIT')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simple Map -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-purple-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white">📍 Peta Lokasi Sekolah</h3>
            </div>
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Simple List -->
        <div class="mt-8 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">📋 Daftar Siswa Terbaru</h3>
            <div class="space-y-3">
                @foreach(\App\Models\Applicant::with('user', 'major')->latest()->take(10)->get() as $applicant)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium">{{ $applicant->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $applicant->no_pendaftaran }} - {{ $applicant->major->nama }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm
                            @if($applicant->status == 'ADM_PASS') bg-green-100 text-green-800
                            @elseif($applicant->status == 'SUBMIT') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($applicant->status == 'ADM_PASS') ✅ Diterima
                            @elseif($applicant->status == 'SUBMIT') ⏳ Menunggu
                            @else ❌ Ditolak @endif
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Simple Map Script -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// Simple map - hanya menampilkan lokasi sekolah
let map = L.map('map').setView([-6.9347, 107.7425], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Marker sekolah
L.marker([-6.9347, 107.7425])
    .addTo(map)
    .bindPopup(`
        <div class="p-3 text-center">
            <h4 class="font-bold text-lg">🏫 SMK BAKTI NUSANTARA 666</h4>
            <p class="text-gray-600">Jl. Percobaan No. 666, Cileunyi</p>
            <p class="text-gray-600">Kabupaten Bandung</p>
        </div>
    `)
    .openPopup();
</script>
@endsection
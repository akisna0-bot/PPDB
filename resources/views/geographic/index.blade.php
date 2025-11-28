@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-600 via-teal-600 to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="SMK Bakti Nusantara 666" class="w-16 h-16 rounded-xl mr-6 object-cover shadow-lg">
                    <div>
                        <h1 class="text-3xl font-bold">Peta Sebaran Geografis</h1>
                        <p class="text-blue-100 text-lg">Visualisasi Domisili Calon Siswa</p>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('executive.dashboard') }}" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                        📊 Dashboard
                    </a>
                    <button onclick="exportMapData()" class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition duration-200">
                        🗺️ Export Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Map Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Lokasi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $geographicData->count() }}</p>
                        <p class="text-sm text-green-600">🏙️ Kab/Kota</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🗺️</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Provinsi</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $provinceStats->count() }}</p>
                        <p class="text-sm text-blue-600">📍 Wilayah</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🌏</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kecamatan Terpopuler</p>
                        <p class="text-lg font-bold text-gray-900">{{ $geographicData->first()->kecamatan ?? 'N/A' }}</p>
                        <p class="text-sm text-purple-600">👑 {{ $geographicData->first()->count ?? 0 }} pendaftar</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🏆</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Jangkauan</p>
                        <p class="text-lg font-bold text-gray-900">Kab. Bandung</p>
                        <p class="text-sm text-orange-600">🎯 & Sekitarnya</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">📡</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Map -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-teal-500 to-blue-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Peta Interaktif Sebaran Pendaftar</h3>
                    <div class="flex space-x-2">
                        <button onclick="toggleHeatmap()" id="heatmapBtn" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded text-sm transition duration-200">
                            🔥 Heatmap
                        </button>
                        <button onclick="toggleMarkers()" id="markersBtn" class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded text-sm transition duration-200">
                            📍 Markers
                        </button>
                    </div>
                </div>
            </div>
            <div id="map" class="w-full h-96"></div>
        </div>

        <!-- Geographic Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Top Cities -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Top 10 Kecamatan</h3>
                    <span class="text-2xl">🏙️</span>
                </div>
                <div class="space-y-4">
                    @foreach($geographicData->take(10) as $index => $location)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-r from-teal-500 to-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-4">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $location->kecamatan }}</p>
                                    <p class="text-sm text-gray-600">{{ $location->kota }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-900">{{ $location->count }}</p>
                                <p class="text-sm text-gray-600">pendaftar</p>
                                <div class="flex items-center mt-1">
                                    <div class="w-16 bg-gray-200 rounded-full h-1 mr-2">
                                        <div class="bg-gradient-to-r from-teal-400 to-blue-400 h-1 rounded-full" 
                                             style="width: {{ ($location->count / $geographicData->max('count')) * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs text-green-600">{{ round($location->verification_rate ?? 0, 1) }}% ✅</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Province Statistics -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Statistik per Provinsi</h3>
                    <span class="text-2xl">📊</span>
                </div>
                <div class="space-y-4">
                    @foreach($provinceStats as $kecamatan)
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-teal-50 rounded-lg border border-blue-100">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-semibold text-gray-900">{{ $kecamatan->kecamatan }}</h4>
                                <span class="text-lg font-bold text-blue-600">{{ $kecamatan->total_applicants }}</span>
                            </div>
                            <div class="grid grid-cols-3 gap-2 text-sm">
                                <div class="text-center p-2 bg-green-100 rounded">
                                    <p class="font-semibold text-green-800">{{ $kecamatan->verified }}</p>
                                    <p class="text-green-600 text-xs">Verified</p>
                                </div>
                                <div class="text-center p-2 bg-yellow-100 rounded">
                                    <p class="font-semibold text-yellow-800">{{ $kecamatan->pending }}</p>
                                    <p class="text-yellow-600 text-xs">Pending</p>
                                </div>
                                <div class="text-center p-2 bg-red-100 rounded">
                                    <p class="font-semibold text-red-800">{{ $kecamatan->rejected }}</p>
                                    <p class="text-red-600 text-xs">Rejected</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Distance Analysis -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Analisis Jarak & Aksesibilitas</h3>
                <span class="text-2xl">🚗</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $nearKecamatan = ['Cileunyi', 'Cilengkrang', 'Rancaekek'];
                    $mediumKecamatan = ['Majalaya', 'Cicalengka', 'Ibun', 'Paseh', 'Bojongsoang', 'Dayeuhkolot'];
                    $nearCount = $geographicData->whereIn('kecamatan', $nearKecamatan)->sum('count');
                    $mediumCount = $geographicData->whereIn('kecamatan', $mediumKecamatan)->sum('count');
                    $farCount = $geographicData->whereNotIn('kecamatan', array_merge($nearKecamatan, $mediumKecamatan))->sum('count');
                @endphp
                <div class="text-center p-6 bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg border border-green-200">
                    <div class="text-3xl mb-3">🟢</div>
                    <h4 class="font-semibold text-gray-900 mb-2">Dekat (< 10km)</h4>
                    <p class="text-2xl font-bold text-green-600">{{ $nearCount }}</p>
                    <p class="text-sm text-gray-600">pendaftar</p>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                    <div class="text-3xl mb-3">🟡</div>
                    <h4 class="font-semibold text-gray-900 mb-2">Sedang (10-25km)</h4>
                    <p class="text-2xl font-bold text-yellow-600">{{ $mediumCount }}</p>
                    <p class="text-sm text-gray-600">pendaftar</p>
                </div>
                <div class="text-center p-6 bg-gradient-to-br from-red-50 to-pink-50 rounded-lg border border-red-200">
                    <div class="text-3xl mb-3">🔴</div>
                    <h4 class="font-semibold text-gray-900 mb-2">Jauh (> 25km)</h4>
                    <p class="text-2xl font-bold text-red-600">{{ $farCount }}</p>
                    <p class="text-sm text-gray-600">pendaftar</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Performance Optimizer -->
<x-performance-optimizer />

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
<script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js" defer></script>

<script>
// Lazy load map initialization
function initMap() {
    if (typeof L === 'undefined') {
        setTimeout(initMap, 100);
        return;
    }
    
    // Initialize map - Fokus ke Cileunyi, Kabupaten Bandung
    let map = L.map('map').setView([-6.9347, 107.7425], 11);

// Add tile layer
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Map data
const heatmapData = @json($heatmapData);
const markersData = @json($heatmapData);

// Heatmap layer
let heatmapLayer = null;
let markersLayer = L.layerGroup().addTo(map);

// Initialize markers
function initializeMarkers() {
    markersLayer.clearLayers();
    
    if (markersData && markersData.length > 0) {
        markersData.forEach(function(point) {
            if (point.lat && point.lng) {
                const marker = L.marker([point.lat, point.lng])
                    .bindPopup(`
                        <div class="p-3">
                            <h4 class="font-bold text-lg">${point.city || 'Unknown'}</h4>
                            <p class="text-gray-600">${point.province || 'Unknown'}</p>
                            <div class="mt-2">
                                <p class="text-blue-600 font-semibold">👥 ${point.count} pendaftar</p>
                            </div>
                        </div>
                    `);
                
                markersLayer.addLayer(marker);
            }
        });
    }
}

// Initialize heatmap
function initializeHeatmap() {
    if (!heatmapData || heatmapData.length === 0) {
        console.log('No heatmap data available');
        return;
    }
    
    const heatPoints = heatmapData
        .filter(point => point.lat && point.lng && point.count > 0)
        .map(point => [point.lat, point.lng, Math.min(point.count * 0.2, 1)]);
    
    if (heatmapLayer) {
        map.removeLayer(heatmapLayer);
    }
    
    if (heatPoints.length > 0) {
        heatmapLayer = L.heatLayer(heatPoints, {
            radius: 30,
            blur: 20,
            maxZoom: 17,
            gradient: {
                0.0: 'blue',
                0.4: 'cyan',
                0.6: 'lime',
                0.8: 'yellow',
                1.0: 'red'
            }
        });
    }
}

// Toggle functions
function toggleHeatmap() {
    if (map.hasLayer(heatmapLayer)) {
        map.removeLayer(heatmapLayer);
        document.getElementById('heatmapBtn').classList.remove('bg-white/40');
    } else {
        initializeHeatmap();
        map.addLayer(heatmapLayer);
        document.getElementById('heatmapBtn').classList.add('bg-white/40');
    }
}

function toggleMarkers() {
    if (map.hasLayer(markersLayer)) {
        map.removeLayer(markersLayer);
        document.getElementById('markersBtn').classList.remove('bg-white/40');
    } else {
        map.addLayer(markersLayer);
        document.getElementById('markersBtn').classList.add('bg-white/40');
    }
}

function exportMapData() {
    const geographicData = @json($geographicData);
    const csvContent = "data:text/csv;charset=utf-8," 
        + "Kecamatan,Kota,Jumlah Pendaftar,Tingkat Verifikasi\n"
        + geographicData.map(row => 
            `"${row.kecamatan}","${row.kota}",${row.count},${Math.round(row.verification_rate || 0)}%`
        ).join("\n");
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "sebaran_geografis_" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

    // Initialize map with markers
    initializeMarkers();
    document.getElementById('markersBtn').classList.add('bg-white/40');
    
    // Add school location marker - SMK Bakti Nusantara 666 di Cileunyi
    const schoolMarker = L.marker([-6.9347, 107.7425], {
        icon: L.icon({
            iconUrl: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEyIDJMMTMuMDkgOC4yNkwyMCA5TDEzLjA5IDE1Ljc0TDEyIDIyTDEwLjkxIDE1Ljc0TDQgOUwxMC45MSA4LjI2TDEyIDJaIiBmaWxsPSIjRUY0NDQ0Ii8+Cjwvc3ZnPgo=',
            iconSize: [30, 30],
            iconAnchor: [15, 30]
        })
    })
    .bindPopup(`
        <div class="p-3 text-center">
            <h4 class="font-bold text-lg text-red-600">🏫 SMK BAKTI NUSANTARA 666</h4>
            <p class="text-gray-600">Lokasi Sekolah</p>
        </div>
    `)
    .addTo(map);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMap);
} else {
    initMap();
}


</script>
@endsection
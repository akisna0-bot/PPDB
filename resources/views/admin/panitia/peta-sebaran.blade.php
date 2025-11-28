@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Peta Sebaran</h1>
                        <p class="text-blue-100">Sebaran geografis pendaftar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Map Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🗺️</span>
                Peta Sebaran Pendaftar
            </h3>
            <!-- Filter Controls -->
            <div class="mb-4 flex flex-wrap gap-4 items-center">
                <div class="flex items-center gap-2">
                    <label class="text-sm font-medium">Mode Tampilan:</label>
                    <select id="displayMode" class="px-3 py-2 border rounded-lg">
                        <option value="major">Berdasarkan Jurusan</option>
                        <option value="status">Berdasarkan Status</option>
                    </select>
                </div>
                <select id="majorFilter" class="px-3 py-2 border rounded-lg">
                    <option value="">Semua Jurusan</option>
                    @foreach($majors ?? [] as $major)
                        <option value="{{ $major->id }}">{{ $major->code }} - {{ $major->name }}</option>
                    @endforeach
                </select>
                <select id="statusFilter" class="px-3 py-2 border rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="SUBMIT">Menunggu</option>
                    <option value="VERIFIED">Diverifikasi</option>
                    <option value="REJECTED">Ditolak</option>
                    <option value="PAID">Terbayar</option>
                </select>
                <button onclick="refreshMap()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    🔄 Refresh
                </button>
            </div>
            
            <!-- Interactive Map -->
            <div id="map" class="h-96 rounded-lg border"></div>
            
            <!-- Map Legend -->
            <div class="mt-4">
                <h4 class="font-semibold text-sm mb-2">Legend Status:</h4>
                <div class="flex flex-wrap gap-4 text-sm mb-4">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                        <span>Diverifikasi (VERIFIED)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                        <span>Menunggu (SUBMIT)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-500 rounded-full mr-2"></div>
                        <span>Terbayar (PAID)</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                        <span>Ditolak (REJECTED)</span>
                    </div>
                </div>
                
                <h4 class="font-semibold text-sm mb-2">Legend Jurusan:</h4>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-blue-600 rounded mr-2"></div>
                        <span>PPLG</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-600 rounded mr-2"></div>
                        <span>AKT</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-purple-600 rounded mr-2"></div>
                        <span>ANM</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-pink-600 rounded mr-2"></div>
                        <span>DKV</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-orange-600 rounded mr-2"></div>
                        <span>PMS</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Total Pendaftar</p>
                        <p class="text-2xl font-bold">{{ $stats['total_pendaftar'] ?? 0 }}</p>
                    </div>
                    <div class="text-3xl opacity-80">👥</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Dengan Alamat</p>
                        <p class="text-2xl font-bold">{{ $stats['dengan_alamat'] ?? 0 }}</p>
                    </div>
                    <div class="text-3xl opacity-80">📍</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Total Provinsi</p>
                        <p class="text-2xl font-bold">{{ $stats['total_provinsi'] ?? 0 }}</p>
                    </div>
                    <div class="text-3xl opacity-80">🗺️</div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-red-500 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Total Kab/Kota</p>
                        <p class="text-2xl font-bold">{{ $stats['total_kabupaten'] ?? 0 }}</p>
                    </div>
                    <div class="text-3xl opacity-80">🏙️</div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Top Provinces -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">📍</span>
                    Top Provinsi
                </h3>
                <div class="space-y-4">
                    @php
                        $provinces = collect($sebaranData ?? [])->groupBy('provinsi')->map(function($items) {
                            return $items->sum('total');
                        })->sortDesc()->take(10);
                    @endphp
                    
                    @forelse($provinces as $province => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $province ?: 'Tidak Diketahui' }}</span>
                                    <span class="text-sm text-gray-500">{{ $count }} pendaftar</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $provinces->max() > 0 ? ($count / $provinces->max()) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Tidak ada data sebaran</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Cities -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <span class="mr-2">🏙️</span>
                    Top Kabupaten/Kota
                </h3>
                <div class="space-y-4">
                    @php
                        $cities = collect($sebaranData ?? [])->groupBy('kabupaten')->map(function($items) {
                            return $items->sum('total');
                        })->sortDesc()->take(10);
                    @endphp
                    
                    @forelse($cities as $city => $count)
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $city ?: 'Tidak Diketahui' }}</span>
                                    <span class="text-sm text-gray-500">{{ $count }} pendaftar</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-teal-500 h-2 rounded-full transition-all duration-500" 
                                         style="width: {{ $cities->max() > 0 ? ($count / $cities->max()) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Tidak ada data sebaran</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Summary Statistics -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">📊</span>
                Ringkasan Sebaran
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format((($stats['dengan_alamat'] ?? 0) / max($stats['total_pendaftar'] ?? 1, 1)) * 100, 1) }}%</div>
                    <p class="text-gray-600 text-sm">Pendaftar dengan alamat lengkap</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $stats['total_provinsi'] ?? 0 }}</div>
                    <p class="text-gray-600 text-sm">Provinsi terwakili</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $stats['total_kabupaten'] ?? 0 }}</div>
                    <p class="text-gray-600 text-sm">Kabupaten/Kota terwakili</p>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Detail Sebaran Geografis</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provinsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kabupaten/Kota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kecamatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($sebaranData ?? [] as $index => $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $data->provinsi ?: 'Tidak Diketahui' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $data->kabupaten ?: 'Tidak Diketahui' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $data->kecamatan ?: 'Tidak Diketahui' }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $data->total }} pendaftar</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data sebaran geografis
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

<script>
let map;
let markersCluster;
let allApplicants = [];

// Initialize map
function initMap() {
    // Center on Indonesia
    map = L.map('map').setView([-2.5489, 118.0149], 5);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    // Initialize marker cluster
    markersCluster = L.markerClusterGroup({
        chunkedLoading: true,
        maxClusterRadius: 50
    });
    
    // Load applicant data
    loadApplicantData();
}

// Load applicant data from server
function loadApplicantData() {
    const loadingIndicator = document.createElement('div');
    loadingIndicator.innerHTML = '<div class="text-center p-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-sm text-gray-600">Memuat data peta...</p></div>';
    document.getElementById('map').appendChild(loadingIndicator);
    
    fetch('/admin/panitia/map-data')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            allApplicants = data.applicants || [];
            updateMapMarkers();
            console.log('Map data loaded:', data);
            if (loadingIndicator.parentNode) {
                loadingIndicator.remove();
            }
        })
        .catch(error => {
            console.error('Error loading map data:', error);
            if (loadingIndicator.parentNode) {
                loadingIndicator.innerHTML = '<div class="text-center p-4 text-red-600"><p>Gagal memuat data peta</p><button onclick="loadApplicantData()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Coba Lagi</button></div>';
            }
        });
}

// Update map markers based on filters
function updateMapMarkers() {
    // Clear existing markers
    markersCluster.clearLayers();
    
    // Get filter values
    const majorFilter = document.getElementById('majorFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    
    // Filter applicants
    let filteredApplicants = allApplicants.filter(applicant => {
        if (majorFilter && applicant.major_id != majorFilter) return false;
        if (statusFilter && applicant.status !== statusFilter) return false;
        return applicant.latitude && applicant.longitude;
    });
    
    // Add markers
    filteredApplicants.forEach(applicant => {
        const marker = createMarker(applicant);
        if (marker) {
            markersCluster.addLayer(marker);
        }
    });
    
    // Add cluster to map
    map.addLayer(markersCluster);
    
    // Update statistics
    updateStatistics(filteredApplicants);
}

// Create marker for applicant
function createMarker(applicant) {
    if (!applicant.latitude || !applicant.longitude) return null;
    
    const displayMode = document.getElementById('displayMode').value;
    
    // Color palettes
    const majorColors = {
        'PPLG': '#2563eb', // blue-600
        'AKT': '#16a34a',  // green-600
        'ANM': '#9333ea',  // purple-600
        'DKV': '#db2777',  // pink-600
        'PMS': '#ea580c'   // orange-600
    };
    
    const statusColors = {
        'VERIFIED': '#16a34a', // green
        'SUBMIT': '#f59e0b',   // yellow
        'PAID': '#2563eb',     // blue
        'REJECTED': '#dc2626', // red
        'DRAFT': '#6b7280'     // gray
    };
    
    let color, borderColor, shape;
    
    if (displayMode === 'major') {
        // Major-based coloring with status border
        color = majorColors[applicant.major_code] || '#6b7280';
        borderColor = statusColors[applicant.status] || '#6b7280';
        shape = 'border-radius: 3px;'; // Square for major
    } else {
        // Status-based coloring
        color = statusColors[applicant.status] || '#6b7280';
        borderColor = '#ffffff';
        shape = 'border-radius: 50%;'; // Circle for status
    }
    
    const icon = L.divIcon({
        className: 'custom-marker',
        html: `<div style="background-color: ${color}; width: 14px; height: 14px; ${shape} border: 2px solid ${borderColor}; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });
    
    // Create marker
    const marker = L.marker([applicant.latitude, applicant.longitude], { icon });
    
    // Add popup
    const popupContent = `
        <div class="p-3 min-w-48">
            <h4 class="font-bold text-sm text-gray-800">${applicant.name}</h4>
            <p class="text-xs text-gray-600 mb-2">${applicant.no_pendaftaran}</p>
            <div class="space-y-1">
                <p class="text-xs"><strong>Jurusan:</strong> <span class="px-2 py-1 rounded text-white text-xs" style="background-color: ${majorColors[applicant.major_code] || '#6b7280'}">${applicant.major_code}</span> ${applicant.major_name}</p>
                <p class="text-xs"><strong>Status:</strong> <span class="px-2 py-1 rounded text-white text-xs" style="background-color: ${statusColors[applicant.status] || '#6b7280'}">${getStatusText(applicant.status)}</span></p>
                <p class="text-xs"><strong>Alamat:</strong> ${applicant.alamat || 'Tidak ada'}</p>
                <p class="text-xs"><strong>Kecamatan:</strong> ${applicant.kecamatan || 'Tidak ada'}</p>
            </div>
        </div>
    `;
    
    marker.bindPopup(popupContent);
    
    return marker;
}

// Get status text
function getStatusText(status) {
    const statusTexts = {
        'DRAFT': 'Draft',
        'SUBMIT': 'Menunggu Verifikasi',
        'VERIFIED': 'Diverifikasi',
        'REJECTED': 'Ditolak',
        'PAID': 'Sudah Bayar'
    };
    return statusTexts[status] || status;
}

// Update statistics display
function updateStatistics(applicants) {
    const stats = {
        total: applicants.length,
        byStatus: {},
        byMajor: {}
    };
    
    applicants.forEach(applicant => {
        // Count by status
        stats.byStatus[applicant.status] = (stats.byStatus[applicant.status] || 0) + 1;
        
        // Count by major
        stats.byMajor[applicant.major_code] = (stats.byMajor[applicant.major_code] || 0) + 1;
    });
    
    console.log('Map Statistics:', stats);
}

// Refresh map
function refreshMap() {
    loadApplicantData();
}

// Add event listeners
document.addEventListener('DOMContentLoaded', function() {
    initMap();
    
    // Add filter event listeners
    document.getElementById('displayMode').addEventListener('change', updateMapMarkers);
    document.getElementById('majorFilter').addEventListener('change', updateMapMarkers);
    document.getElementById('statusFilter').addEventListener('change', updateMapMarkers);
});
</script>
@endsection
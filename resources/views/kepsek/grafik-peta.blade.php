@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <span class="mr-3">🗺️</span> Peta Sebaran Pendaftar
                        </h1>
                        <p class="text-blue-100">Distribusi geografis calon siswa</p>
                    </div>
                    <a href="{{ route('kepsek.dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Grafik Sebaran -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <span class="mr-2">📊</span> Grafik Sebaran per Kabupaten
                </h3>
                <canvas id="sebaranChart" width="400" height="300"></canvas>
            </div>

            <!-- Tabel Detail -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold">📋 Detail Sebaran</h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="text-left py-3 px-4 font-semibold">Kabupaten</th>
                                <th class="text-center py-3 px-4 font-semibold">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($sebaranData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    <p class="font-medium">{{ $data->kabupaten }}</p>
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-bold">
                                        {{ $data->total }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="py-8 text-center text-gray-500">
                                    <div class="text-4xl mb-2">🗺️</div>
                                    <p>Belum ada data sebaran</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Ringkas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">🏙️</div>
                <h3 class="text-2xl font-bold text-blue-600">{{ $sebaranData->count() }}</h3>
                <p class="text-gray-600">Total Kabupaten</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">👑</div>
                <h3 class="text-lg font-bold text-green-600">{{ $sebaranData->first()->kabupaten ?? 'N/A' }}</h3>
                <p class="text-gray-600">Terbanyak</p>
                <p class="text-sm text-green-600">{{ $sebaranData->first()->total ?? 0 }} pendaftar</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="text-2xl font-bold text-purple-600">{{ number_format($sebaranData->avg('total'), 1) }}</h3>
                <p class="text-gray-600">Rata-rata</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📈</div>
                <h3 class="text-2xl font-bold text-orange-600">{{ $sebaranData->sum('total') }}</h3>
                <p class="text-gray-600">Total Pendaftar</p>
            </div>
        </div>

        <!-- Peta Interaktif -->
        <div class="bg-white rounded-xl shadow-lg p-6 mt-8">
            <h3 class="text-lg font-semibold mb-4 flex items-center">
                <span class="mr-2">🗺️</span> Peta Sebaran Interaktif
            </h3>
            <div id="map" style="height: 500px; border-radius: 8px;"></div>
            
            <!-- Legend -->
            <div class="mt-4 flex items-center justify-center space-x-6 text-sm">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                    <span>1-5 Pendaftar</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded-full mr-2"></div>
                    <span>6-10 Pendaftar</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                    <span>11+ Pendaftar</span>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Grafik Sebaran
const ctx = document.getElementById('sebaranChart').getContext('2d');
const sebaranChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($sebaranData->pluck('kabupaten')->toArray()) !!},
        datasets: [{
            label: 'Jumlah Pendaftar',
            data: {!! json_encode($sebaranData->pluck('total')->toArray()) !!},
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true
            },
            x: {
                ticks: {
                    maxRotation: 45
                }
            }
        }
    }
});

// Peta Interaktif
const map = L.map('map').setView([-6.2088, 106.8456], 10); // Jakarta sebagai center

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

// Data sebaran dengan koordinat berdasarkan siswa yang mendaftar
const sebaranLocations = [
    @foreach($sebaranData as $data)
    @php
        $coordinates = [
            'Bandung' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Jakarta Selatan' => ['lat' => -6.2615, 'lng' => 106.8106],
            'Jakarta Pusat' => ['lat' => -6.1745, 'lng' => 106.8227],
            'Jakarta Timur' => ['lat' => -6.2250, 'lng' => 106.9004],
            'Depok' => ['lat' => -6.4025, 'lng' => 106.7942],
            'Tangerang' => ['lat' => -6.1783, 'lng' => 106.6319],
            'Bogor' => ['lat' => -6.5971, 'lng' => 106.8060],
            'default' => ['lat' => -6.2088, 'lng' => 106.8456]
        ];
        $coord = $coordinates[$data->kabupaten] ?? $coordinates['default'];
    @endphp
    {
        name: '{{ $data->kabupaten }}',
        province: '{{ $data->provinsi }}',
        count: {{ $data->total }},
        lat: {{ $coord['lat'] }},
        lng: {{ $coord['lng'] }}
    },
    @endforeach
];

// Tambahkan marker untuk setiap lokasi
sebaranLocations.forEach(location => {
    if (location.name && location.count > 0) {
        const color = location.count <= 5 ? 'green' : location.count <= 10 ? 'orange' : 'red';
        
        const marker = L.circleMarker([location.lat, location.lng], {
            color: color,
            fillColor: color,
            fillOpacity: 0.7,
            radius: Math.max(8, location.count * 2)
        }).addTo(map);
        
        marker.bindPopup(`
            <div class="text-center p-2">
                <h4 class="font-bold text-lg">${location.name}</h4>
                <p class="text-gray-600 text-sm">${location.province}</p>
                <p class="text-blue-600 font-semibold">${location.count} Pendaftar</p>
                <div class="mt-2 text-xs text-gray-500">
                    📍 Koordinat: ${location.lat.toFixed(4)}, ${location.lng.toFixed(4)}
                </div>
            </div>
        `);
    }
});

// Auto fit bounds jika ada data
if (sebaranLocations.length > 0) {
    const group = new L.featureGroup(map._layers);
    if (Object.keys(group._layers).length > 0) {
        map.fitBounds(group.getBounds().pad(0.1));
    }
}
</script>
@endsection
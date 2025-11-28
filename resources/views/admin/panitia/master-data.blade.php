@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                    @elseif(auth()->user()->role === 'keuangan')
                        <a href="{{ route('keuangan.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                    @elseif(auth()->user()->role === 'verifikator')
                        <a href="{{ route('verifikator.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                    @elseif(auth()->user()->role === 'kepsek')
                        <a href="{{ route('kepsek.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold">Master Data</h1>
                        <p class="text-blue-100">Kelola data jurusan dan gelombang</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Jurusan Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🎓</span>
                Data Jurusan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($majors ?? [] as $major)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-gray-900">{{ $major->code }}</h4>
                            <span class="text-sm text-gray-500">Kuota: {{ $major->kuota }}</span>
                        </div>
                        <p class="text-sm text-gray-700 mb-3">{{ $major->name }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500">ID: {{ $major->id }}</span>
                            <button onclick="editMajor({{ $major->id }}, '{{ $major->code }}', '{{ $major->name }}', {{ $major->kuota }})" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Edit
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Gelombang Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">🌊</span>
                Data Gelombang
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($waves ?? [] as $wave)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $wave->nama }}</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Mulai:</span>
                                <span>{{ \Carbon\Carbon::parse($wave->tgl_mulai)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Selesai:</span>
                                <span>{{ \Carbon\Carbon::parse($wave->tgl_selesai)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya:</span>
                                <span class="font-medium text-green-600">Rp {{ number_format($wave->biaya_daftar, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">ID: {{ $wave->id }}</span>
                            <button onclick="editWave({{ $wave->id }}, '{{ $wave->nama }}', '{{ $wave->tgl_mulai }}', '{{ $wave->tgl_selesai }}', {{ $wave->biaya_daftar }})" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                Edit
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Document Types Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">📄</span>
                Jenis Dokumen
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @if(isset($documentTypes))
                    @foreach($documentTypes as $key => $name)
                        <div class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-900">{{ $name }}</span>
                                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $key }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Jurusan -->
<div id="editMajorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Edit Jurusan</h3>
            <form id="editMajorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Kode Jurusan</label>
                    <input type="text" id="majorCode" name="code" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama Jurusan</label>
                    <input type="text" id="majorName" name="name" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Kuota</label>
                    <input type="number" id="majorKuota" name="kuota" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editMajorModal')" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Gelombang -->
<div id="editWaveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Edit Gelombang</h3>
            <form id="editWaveForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nama Gelombang</label>
                    <input type="text" id="waveName" name="nama" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Tanggal Mulai</label>
                    <input type="date" id="waveStart" name="tgl_mulai" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Tanggal Selesai</label>
                    <input type="date" id="waveEnd" name="tgl_selesai" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Biaya Daftar</label>
                    <input type="number" id="waveFee" name="biaya_daftar" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal('editWaveModal')" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMajor(id, code, name, kuota) {
    document.getElementById('majorCode').value = code;
    document.getElementById('majorName').value = name;
    document.getElementById('majorKuota').value = kuota;
    document.getElementById('editMajorForm').action = `/admin/majors/${id}`;
    document.getElementById('editMajorModal').classList.remove('hidden');
}

function editWave(id, nama, tglMulai, tglSelesai, biaya) {
    document.getElementById('waveName').value = nama;
    document.getElementById('waveStart').value = tglMulai;
    document.getElementById('waveEnd').value = tglSelesai;
    document.getElementById('waveFee').value = biaya;
    document.getElementById('editWaveForm').action = `/admin/waves/${id}`;
    document.getElementById('editWaveModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>

@endsection
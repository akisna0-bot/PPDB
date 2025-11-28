@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/verifikator-fix.css') }}">
<script src="{{ asset('js/image-preview.js') }}" defer></script>
@php
    use Illuminate\Support\Facades\Storage;
@endphp
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <span class="mr-3">👤</span> Detail Pendaftar
                    </h2>
                    <x-back-button url="{{ route('verifikator.daftar-pendaftar') }}" text="Kembali" class="bg-white text-purple-600 px-4 py-2 font-medium hover:bg-gray-100" />
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <span class="text-2xl font-bold text-purple-600">{{ substr($applicant->user->name, 0, 2) }}</span>
                        </div>
                        <h3 class="font-bold text-lg">{{ $applicant->user->name }}</h3>
                        <p class="text-gray-600">{{ $applicant->no_pendaftaran }}</p>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-2">Informasi Dasar</h4>
                        <div class="space-y-1 text-sm">
                            <p><span class="font-medium">Jurusan:</span> {{ $applicant->major->name ?? 'Belum dipilih' }}</p>
                            <p><span class="font-medium">Gelombang:</span> {{ $applicant->wave->name ?? 'Belum dipilih' }}</p>
                            <p><span class="font-medium">Email:</span> {{ $applicant->user->email }}</p>
                            <p><span class="font-medium">Tanggal Daftar:</span> {{ $applicant->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold mb-2">Status Verifikasi</h4>
                        <x-status-badge :status="$applicant->status" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pribadi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">📋 Data Pribadi Pendaftar</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-semibold mb-3 text-purple-600">Identitas</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium w-32 inline-block">Nama Lengkap:</span> {{ $applicant->nama_lengkap ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">NIK:</span> {{ $applicant->nik ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Tempat Lahir:</span> {{ $applicant->tempat_lahir ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Tanggal Lahir:</span> {{ $applicant->tanggal_lahir ? $applicant->tanggal_lahir->format('d/m/Y') : 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Jenis Kelamin:</span> {{ $applicant->jenis_kelamin == 'L' ? 'Laki-laki' : ($applicant->jenis_kelamin == 'P' ? 'Perempuan' : 'Belum diisi') }}</p>
                            <p><span class="font-medium w-32 inline-block">Agama:</span> {{ $applicant->agama ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">No. HP:</span> {{ $applicant->no_hp ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3 text-purple-600">Alamat & Sekolah</h4>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium w-32 inline-block">Alamat:</span> {{ $applicant->alamat_lengkap ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Kabupaten:</span> {{ $applicant->kabupaten ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Provinsi:</span> {{ $applicant->provinsi ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Asal Sekolah:</span> {{ $applicant->asal_sekolah ?? 'Belum diisi' }}</p>
                            <p><span class="font-medium w-32 inline-block">Tahun Lulus:</span> {{ $applicant->tahun_lulus ?? 'Belum diisi' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Berkas -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">📄 Berkas Pendaftaran</h3>
            </div>
            <div class="p-6">
                @if($applicant->files && $applicant->files->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($applicant->files as $file)
                        <div class="border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium">{{ ucfirst(str_replace('_', ' ', $file->document_type)) }}</h4>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                                    {{ $file->size_kb ? number_format($file->size_kb, 0) . ' KB' : 'File' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">{{ $file->original_name ?? 'Dokumen ' . ucfirst(str_replace('_', ' ', $file->document_type)) }}</p>
                            
                            @if($file->path && Storage::exists('public/applicant_files/' . $file->filename))
                                @php
                                    $extension = strtolower(pathinfo($file->original_name ?? $file->filename, PATHINFO_EXTENSION));
                                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                                    $fileUrl = Storage::url('applicant_files/' . $file->filename);
                                @endphp
                                
                                @if($isImage)
                                    <div class="mb-3">
                                        <img src="{{ $fileUrl }}" 
                                             alt="{{ $file->original_name }}" 
                                             class="w-full max-w-xs rounded border cursor-pointer hover:opacity-80 transition" 
                                             onclick="openImageModal('{{ $fileUrl }}', '{{ ucfirst(str_replace('_', ' ', $file->document_type)) }}')" 
                                             title="Klik untuk memperbesar">
                                    </div>
                                @endif
                                
                                <div class="flex space-x-2">
                                    <a href="{{ $fileUrl }}" target="_blank"
                                       class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition">
                                        👁️ Lihat
                                    </a>
                                    <a href="{{ $fileUrl }}" download="{{ $file->original_name }}"
                                       class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700 transition">
                                        📥 Download
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    <div class="text-3xl mb-2">📄</div>
                                    <p class="text-sm">Dokumen tersedia untuk diverifikasi</p>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <span class="text-4xl mb-2 block">📄</span>
                        <p>Belum ada berkas yang diupload</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Verifikasi -->
        @if($applicant->status == 'SUBMIT')
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">✅ Verifikasi Administrasi</h3>
            </div>
            <div class="p-6">
                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                        <h4 class="font-semibold text-red-800 mb-2">Terjadi Kesalahan:</h4>
                        <ul class="list-disc list-inside text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                        <p class="text-red-700">{{ session('error') }}</p>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('verifikator.verify', $applicant->id) }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Status Verifikasi</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="VERIFIED" class="mr-2" required>
                                    <span class="text-green-600">✅ Lulus Administrasi - Data & berkas lengkap dan valid</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="status" value="REJECTED" class="mr-2" required>
                                    <span class="text-red-600">❌ Ditolak Administrasi - Tidak memenuhi syarat</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-2">Catatan Verifikasi (Opsional)</label>
                            <textarea name="catatan_verifikasi" rows="4" 
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500" 
                                      placeholder="Berikan catatan untuk pendaftar (opsional)..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">Contoh: "Semua berkas lengkap dan valid" atau "Ijazah tidak jelas, mohon upload ulang"</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-4">
                        <button type="submit" onclick="return confirmSubmit()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition">
                            💾 Simpan Verifikasi
                        </button>
                        <a href="{{ route('verifikator.daftar-pendaftar') }}" 
                           class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                            ❌ Batal
                        </a>
                    </div>
                    
                    <script>
                    function confirmSubmit() {
                        const status = document.querySelector('input[name="status"]:checked');
                        const catatan = document.querySelector('textarea[name="catatan_verifikasi"]').value;
                        
                        if (!status) {
                            alert('Pilih status verifikasi terlebih dahulu!');
                            return false;
                        }
                        
                        const statusText = status.value === 'VERIFIED' ? 'MENERIMA' : 'MENOLAK';
                        const confirmText = catatan ? `Apakah Anda yakin ingin ${statusText} pendaftar ini?\n\nCatatan: ${catatan}` : `Apakah Anda yakin ingin ${statusText} pendaftar ini?`;
                        return confirm(confirmText);
                    }
                    </script>
                </form>
            </div>
        </div>
        @endif

        <!-- Log Verifikasi -->
        @if($applicant->catatan_verifikasi || $applicant->user_verifikasi_adm || $applicant->verified_by)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold">📋 Log Verifikasi</h3>
            </div>
            <div class="p-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-medium">
                            Verifikator: {{ $applicant->verifier->name ?? $applicant->user_verifikasi_adm ?? 'Belum diverifikasi' }}
                        </span>
                        <span class="text-sm text-gray-600">
                            @if($applicant->verified_at)
                                {{ $applicant->verified_at->format('d/m/Y H:i') }}
                            @elseif($applicant->tgl_verifikasi_adm)
                                @if(is_string($applicant->tgl_verifikasi_adm))
                                    {{ \Carbon\Carbon::parse($applicant->tgl_verifikasi_adm)->format('d/m/Y H:i') }}
                                @else
                                    {{ $applicant->tgl_verifikasi_adm->format('d/m/Y H:i') }}
                                @endif
                            @endif
                        </span>
                    </div>
                    
                    <div class="flex items-center mb-2">
                        <span class="px-3 py-1 rounded-full text-sm font-medium
                            @if($applicant->status == 'VERIFIED' || $applicant->status == 'PAYMENT_PENDING') bg-green-100 text-green-800
                            @elseif($applicant->status == 'REJECTED') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            @if($applicant->status == 'VERIFIED' || $applicant->status == 'PAYMENT_PENDING')
                                ✅ Diverifikasi
                            @elseif($applicant->status == 'REJECTED')
                                ❌ Ditolak
                            @else
                                ⏳ {{ $applicant->status }}
                            @endif
                        </span>
                    </div>
                    
                    @if($applicant->catatan_verifikasi)
                        <p class="text-sm text-gray-700 bg-white p-3 rounded border-l-4 border-purple-500">
                            <strong>Catatan:</strong> {{ $applicant->catatan_verifikasi }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal untuk preview gambar -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-90 hidden z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="max-w-5xl max-h-full relative">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded shadow-2xl">
        <div class="text-center mt-4">
            <p id="modalTitle" class="text-white text-xl font-semibold mb-3"></p>
            <button onclick="closeImageModal()" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-medium">
                ✕ Tutup
            </button>
        </div>
        <button onclick="closeImageModal()" class="absolute top-4 right-4 bg-red-600 text-white w-10 h-10 rounded-full hover:bg-red-700 transition">
            ✕
        </button>
    </div>
</div>

<script>
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Tutup modal dengan ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

@endsection
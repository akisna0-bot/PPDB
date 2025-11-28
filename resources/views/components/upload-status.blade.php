@props(['files', 'documentType', 'label', 'compact' => false])

@php
    $existingFile = null;
    if ($files && $files->count() > 0) {
        $existingFile = $files->where('document_type', $documentType)->first();
    }
@endphp

@if($compact)
    <!-- Versi Ringkas untuk Dashboard -->
    @if($existingFile)
        <div class="flex items-center gap-2">
            <span class="text-green-600">✅</span>
            <span class="text-sm text-green-600 font-medium">Sudah diupload</span>
        </div>
    @else
        <div class="flex items-center gap-2">
            <span class="text-red-600">❌</span>
            <span class="text-sm text-red-600">Belum upload</span>
        </div>
    @endif
@else
    <!-- Versi Lengkap untuk Halaman Upload -->
    @if($existingFile)
        <!-- 🔹 Setelah Berhasil Upload -->
        <div class="border-2 border-green-300 bg-green-50 rounded-lg p-4 text-center">
            <div class="text-3xl mb-2">✅</div>
            <h4 class="font-semibold text-green-800 mb-2">{{ $label }}</h4>
            
            <div class="bg-white border border-green-200 rounded-lg p-3 mb-3">
                <p class="text-sm text-green-700 font-medium">✅ File berhasil diupload!</p>
                <p class="text-xs text-green-600 mt-1">{{ $existingFile->original_name ?? 'File tersimpan' }}</p>
                @if($existingFile->size_kb)
                    <p class="text-xs text-green-500">{{ number_format($existingFile->size_kb, 1) }} KB</p>
                @endif
                <p class="text-xs text-gray-500 mt-1">
                    Status: 
                    <span class="px-1 py-0.5 rounded text-xs
                        @if($existingFile->status == 'approved') bg-green-100 text-green-800
                        @elseif($existingFile->status == 'rejected') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        @if($existingFile->status == 'approved') Disetujui
                        @elseif($existingFile->status == 'rejected') Ditolak
                        @else Menunggu Verifikasi @endif
                    </span>
                </p>
            </div>
            
            <div class="flex gap-2 justify-center">
                <a href="{{ asset('storage/' . $existingFile->path) }}" target="_blank" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm transition flex items-center gap-1">
                    📄 Lihat file
                </a>
                <form action="{{ route('dokumen.upload') }}" method="POST" enctype="multipart/form-data" class="inline">
                    @csrf
                    <input type="hidden" name="document_type" value="{{ $documentType }}">
                    <label class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded text-sm transition cursor-pointer flex items-center gap-1">
                        🔄 Ganti file
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="this.form.submit()">
                    </label>
                </form>
            </div>
        </div>
    @else
        <!-- 🔹 Saat Belum Upload -->
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors">
            <div class="text-3xl mb-2">❌</div>
            <h4 class="font-semibold text-gray-700 mb-2">{{ $label }}</h4>
            
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-3">
                <p class="text-sm text-red-700 font-medium">❌ Belum ada file yang diupload.</p>
                <p class="text-xs text-red-600 mt-1">📎 Silakan unggah dokumen resmi (PDF/JPG/PNG, maks. 5 MB).</p>
                <p class="text-xs text-gray-600 mt-1">Dokumen ini wajib diupload untuk melengkapi pendaftaran.</p>
            </div>
            
            <form action="{{ route('dokumen.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="document_type" value="{{ $documentType }}">
                <label class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg font-medium transition cursor-pointer inline-block">
                    📎 Upload {{ $label }}
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
        </div>
    @endif
@endif
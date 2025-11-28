@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-slate-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Upload Dokumen - SMK BAKTI NUSANTARA 666</h1>
                    <p class="text-slate-200 text-sm">No. Pendaftaran: {{ $applicant->no_pendaftaran }}</p>
                </div>
                <x-back-button url="{{ route('dashboard') }}" text="Kembali ke Dashboard" class="bg-white/20 hover:bg-white/30 text-white" />
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Progress Info -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-slate-800">Progress Upload Dokumen</h3>
                <span class="text-sm text-slate-600">{{ $files->count() }}/{{ count($documentTypes) }} dokumen</span>
            </div>
            @php
                $progress = count($documentTypes) > 0 ? ($files->count() / count($documentTypes)) * 100 : 0;
            @endphp
            <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                <div class="bg-blue-600 h-3 rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
            </div>
            <p class="text-sm text-slate-600">{{ round($progress) }}% dokumen telah diupload</p>
        </div>

        <!-- Upload Area - Simplified -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8 border border-gray-200">
            <div class="flex items-center mb-6">
                <span class="text-2xl mr-3">📎</span>
                <h3 class="text-xl font-bold text-slate-800">Upload Dokumen</h3>
            </div>
            
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="text-green-700">✅ {{ session('success') }}</span>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <span class="text-red-700">❌ {{ session('error') }}</span>
                    </div>
                </div>
            @endif
            

            
            <!-- Document Upload Cards - Simplified -->
            <div class="space-y-4">
                @foreach($documentTypes as $key => $label)
                    @php
                        $file = $files->where('document_type', $key)->first();
                        $hasFile = !is_null($file);
                    @endphp
                    
                    <div class="border rounded-lg p-4 {{ $hasFile ? 'border-green-300 bg-green-50' : 'border-red-300 bg-red-50' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="text-2xl">{{ $hasFile ? '✅' : '❌' }}</div>
                                <div>
                                    <h4 class="font-semibold {{ $hasFile ? 'text-green-800' : 'text-red-800' }}">{{ $label }}</h4>
                                    @if($hasFile)
                                        <p class="text-sm text-green-600">{{ $file->original_name }}</p>
                                        <p class="text-xs text-green-500">
                                            {{ number_format($file->size_kb ?? 0, 1) }} KB - 
                                            <span class="px-1 py-0.5 rounded
                                                @if($file->status == 'approved') bg-green-100 text-green-800
                                                @elseif($file->status == 'rejected') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                @if($file->status == 'approved') Disetujui
                                                @elseif($file->status == 'rejected') Ditolak
                                                @else Menunggu @endif
                                            </span>
                                        </p>
                                    @else
                                        <p class="text-sm text-red-600">Belum diupload</p>
                                        <p class="text-xs text-red-500">PDF/JPG/PNG, maks. 5 MB</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex space-x-2">
                                @if($hasFile)
                                    <a href="{{ asset('storage/' . $file->path) }}" target="_blank" 
                                       class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded text-sm">
                                        👁️ Lihat
                                    </a>
                                @endif
                                
                                <form action="{{ route('dokumen.upload') }}" method="POST" enctype="multipart/form-data" class="inline">
                                    @csrf
                                    <input type="hidden" name="document_type" value="{{ $key }}">
                                    <label class="{{ $hasFile ? 'bg-orange-500 hover:bg-orange-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white px-3 py-2 rounded text-sm cursor-pointer transition">
                                        {{ $hasFile ? '🔄 Ganti' : '📎 Upload' }}
                                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" 
                                               onchange="uploadFile(this, '{{ $key }}')">
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>💡 Tips:</strong> Format yang diterima: PDF, JPG, PNG. Maksimal ukuran file 5MB.
                </p>
            </div>
        </div>
        


        <!-- Documents List -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <div class="bg-slate-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <span class="text-2xl mr-3">📋</span>
                    <h3 class="text-xl font-bold text-slate-800">Dokumen yang Sudah Diupload</h3>
                </div>
            </div>
            
            <div id="documentsList" class="divide-y divide-gray-200">
                @forelse($files as $file)
                    <div class="p-6 hover:bg-gray-50 transition-colors" data-file-id="{{ $file->id }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    @if(str_contains($file->file_type, 'pdf'))
                                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                            <span class="text-2xl">📄</span>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <span class="text-2xl">🖼️</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-semibold text-slate-800">{{ $file->document_type_name }}</h4>
                                    <p class="text-sm text-slate-600">{{ $file->original_name }}</p>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="text-xs text-slate-500">{{ number_format($file->size_kb, 1) }} KB</span>
                                        <span class="text-xs px-2 py-1 rounded-full
                                            @if($file->status == 'approved') bg-green-100 text-green-800
                                            @elseif($file->status == 'rejected') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            @if($file->status == 'approved') ✅ Disetujui
                                            @elseif($file->status == 'rejected') ❌ Ditolak
                                            @else ⏳ Menunggu Verifikasi @endif
                                        </span>
                                        <span class="text-xs text-slate-500">{{ $file->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-3 py-2 rounded-lg text-sm transition duration-200">
                                    👁️ Lihat
                                </a>
                                <form action="{{ route('dokumen.delete', $file->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus dokumen ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded-lg text-sm transition duration-200">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($file->notes)
                            <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-slate-600"><strong>Catatan:</strong> {{ $file->notes }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <div class="text-6xl mb-4">📁</div>
                        <h4 class="text-xl font-semibold text-slate-800 mb-2">Belum Ada Dokumen</h4>
                        <p class="text-slate-600">Mulai upload dokumen persyaratan pendaftaran Anda</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Required Documents Info - Versi Ringkas -->
        <div class="mt-8 bg-amber-50 rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center mb-4">
                <span class="text-2xl mr-3">📋</span>
                <h3 class="text-lg font-semibold text-slate-800">Ringkasan Status Dokumen</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($documentTypes as $key => $label)
                    <div class="flex items-center justify-between p-3 bg-white rounded-lg border">
                        <span class="text-slate-700 text-sm">{{ $label }}</span>
                        @if($files->where('document_type', $key)->count())
                            <span class="text-green-600 font-semibold text-sm flex items-center gap-1">
                                ✅ Sudah diupload
                            </span>
                        @else
                            <span class="text-red-600 font-semibold text-sm">
                                ❌ Belum upload
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <!-- Summary Progress -->
            <div class="mt-4 p-3 bg-white rounded-lg border">
                @php
                    $uploadedCount = $files->count();
                    $totalCount = count($documentTypes);
                    $percentage = $totalCount > 0 ? round(($uploadedCount / $totalCount) * 100) : 0;
                @endphp
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-slate-700">Progress Upload:</span>
                    <span class="text-sm font-bold {{ $percentage == 100 ? 'text-green-600' : 'text-blue-600' }}">
                        {{ $uploadedCount }}/{{ $totalCount }} dokumen ({{ $percentage }}%)
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="{{ $percentage == 100 ? 'bg-green-500' : 'bg-blue-500' }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                </div>
                @if($percentage == 100)
                    <p class="text-xs text-green-600 mt-2 font-medium">✅ Semua dokumen sudah diupload!</p>
                @else
                    <p class="text-xs text-blue-600 mt-2">Masih ada {{ $totalCount - $uploadedCount }} dokumen yang perlu diupload</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Performance Optimizer -->
<x-performance-optimizer />

<script>
function uploadFile(input, docType) {
    if (input.files.length > 0) {
        const form = input.closest('form');
        const fileName = input.files[0].name;
        const fileSize = (input.files[0].size / 1024).toFixed(1);
        
        // Validasi ukuran file
        if (input.files[0].size > 5120 * 1024) {
            alert('File terlalu besar! Maksimal 5MB.');
            input.value = '';
            return;
        }
        
        // Validasi tipe file
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(input.files[0].type)) {
            alert('Tipe file tidak didukung! Gunakan PDF, JPG, atau PNG.');
            input.value = '';
            return;
        }
        
        // Submit langsung
        form.submit();
    }
}
</script>

@endsection
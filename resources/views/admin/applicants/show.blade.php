@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <x-breadcrumb :items="[
        ['name' => 'Data Pendaftar', 'url' => route('admin.applicants.index')],
        ['name' => 'Detail Pendaftar']
    ]" />
    
    <h2 class="text-2xl font-bold mb-4">Detail Pendaftar</h2>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gray-50 p-4 rounded">
            <p class="mb-2"><strong>No Pendaftaran:</strong> {{ $applicant->no_pendaftaran }}</p>
            <p class="mb-2"><strong>Nama:</strong> {{ $applicant->user->name }}</p>
            <p class="mb-2"><strong>Email:</strong> {{ $applicant->user->email }}</p>
            <p class="mb-2"><strong>Jurusan:</strong> {{ $applicant->major->name ?? 'Tidak ada' }}</p>
            <p><strong>Gelombang:</strong> {{ $applicant->wave->name ?? 'Tidak ada' }}</p>
        </div>
        <div class="bg-gray-50 p-4 rounded">
            <p class="mb-2"><strong>Status Saat Ini:</strong> 
                <span class="px-2 py-1 rounded text-sm
                    @if($applicant->status == 'VERIFIED') bg-green-100 text-green-800
                    @elseif($applicant->status == 'REJECTED') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $applicant->status }}
                </span>
            </p>
            <p class="mb-2"><strong>Tanggal Daftar:</strong> {{ $applicant->created_at->format('d/m/Y H:i') }}</p>
            @if($applicant->updated_at != $applicant->created_at)
                <p><strong>Terakhir Update:</strong> {{ $applicant->updated_at->format('d/m/Y H:i') }}</p>
            @endif
        </div>
    </div>

    <div class="bg-blue-50 p-4 rounded mb-6">
        <h3 class="text-lg font-semibold mb-3">Update Status Verifikasi</h3>
        <form method="POST" action="{{ route('admin.applicants.verify', $applicant->id) }}">
            @csrf
            @method('PATCH')
            <div class="flex flex-col sm:flex-row gap-3">
                <select name="status" class="border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Pilih Status --</option>
                    <option value="SUBMIT" {{ $applicant->status == 'SUBMIT' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="VERIFIED" {{ $applicant->status == 'VERIFIED' ? 'selected' : '' }}>Terverifikasi</option>
                    <option value="REJECTED" {{ $applicant->status == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                </select>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded transition-colors">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    <x-back-button url="{{ route('admin.applicants.index') }}" text="Kembali ke Daftar Pendaftar" />
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Daftar Pendaftar</h2>
        <div class="flex space-x-2">
            <a href="{{ route('admin.applicants.export.excel') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm transition flex items-center">
                📊 Export Excel
            </a>
            <a href="{{ route('admin.applicants.export.pdf') }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded text-sm transition flex items-center">
                📄 Export PDF
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No Pendaftaran</th>
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Email</th>
                    <th class="border p-2">Jurusan</th>
                    <th class="border p-2">Status</th>
                    <th class="border p-2">Tanggal</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applicants as $applicant)
                <tr>
                    <td class="border p-2">{{ $applicant->no_pendaftaran }}</td>
                    <td class="border p-2">{{ $applicant->user->name }}</td>
                    <td class="border p-2">{{ $applicant->user->email }}</td>
                    <td class="border p-2">{{ $applicant->major->name ?? 'Tidak ada' }}</td>
                    <td class="border p-2">
                        <span class="px-2 py-1 rounded text-xs
                            @if($applicant->status == 'VERIFIED') bg-green-100 text-green-800
                            @elseif($applicant->status == 'REJECTED') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ $applicant->status }}
                        </span>
                    </td>
                    <td class="border p-2 text-sm">{{ $applicant->created_at->format('d/m/Y') }}</td>
                    <td class="border p-2">
                        <a href="{{ route('admin.applicants.show', $applicant->id) }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm transition-colors">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="border p-4 text-center text-gray-500">
                        Belum ada pendaftar
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
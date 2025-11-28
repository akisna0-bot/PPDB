@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h1 class="text-3xl font-bold">✅ Dashboard Verifikator</h1>
            <p class="text-purple-100">Verifikasi Administrasi Calon Siswa</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Menu Aksi -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('verifikator.daftar-pendaftar') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📋</div>
                    <h3 class="font-semibold text-lg mb-2">Daftar Pendaftar</h3>
                    <p class="text-gray-600 text-sm">Verifikasi berkas pendaftar</p>
                </div>
            </a>
            
            <a href="{{ route('verifikator.master-data') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📄</div>
                    <h3 class="font-semibold text-lg mb-2">Master Data</h3>
                    <p class="text-gray-600 text-sm">Data jurusan & gelombang</p>
                </div>
            </a>
            
            <a href="{{ route('verifikator.laporan') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="text-center">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="font-semibold text-lg mb-2">Laporan</h3>
                    <p class="text-gray-600 text-sm">Laporan verifikasi</p>
                </div>
            </a>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-indigo-500 px-6 py-4">
                <h3 class="text-xl font-bold text-white">📋 Daftar Pendaftar Menunggu Verifikasi</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">No. Pendaftaran</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Nama</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Jurusan</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Tanggal Daftar</th>
                            <th class="text-left py-3 px-6 font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($applicants as $applicant)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 font-medium">{{ $applicant->no_pendaftaran }}</td>
                            <td class="py-4 px-6">{{ $applicant->user->name }}</td>
                            <td class="py-4 px-6">{{ $applicant->major->nama }}</td>
                            <td class="py-4 px-6">{{ $applicant->created_at->format('d M Y') }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('verifikator.show', $applicant->id) }}" 
                                   class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg text-sm transition">
                                    👁️ Verifikasi
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $applicants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
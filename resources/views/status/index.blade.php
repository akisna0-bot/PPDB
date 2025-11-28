@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        @php
            $applicant = App\Models\Applicant::with(['major', 'wave', 'files', 'payments'])
                ->where('user_id', auth()->id())->first();
        @endphp

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-8 text-center">
                <h1 class="text-3xl font-bold text-white mb-2">📊 Status Pendaftaran</h1>
                <p class="text-blue-100">Pantau perkembangan pendaftaran Anda</p>
            </div>
        </div>

        @if(!$applicant)
            <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                <div class="text-6xl mb-4">📝</div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Pendaftaran</h3>
                <p class="text-gray-600 mb-4">Silakan lengkapi formulir pendaftaran terlebih dahulu</p>
                <a href="{{ route('pendaftaran.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                    📝 Mulai Pendaftaran
                </a>
            </div>
        @else
            <!-- Status Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Status Utama -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="mr-2">🎯</span> Status Saat Ini
                    </h3>
                    
                    @if($applicant->final_status == 'ACCEPTED')
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">🎉</div>
                            <h4 class="text-xl font-bold text-green-600 mb-2">SELAMAT!</h4>
                            <p class="text-green-700 font-medium">ANDA DITERIMA</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $applicant->final_notes ?? 'Selamat bergabung!' }}</p>
                        </div>
                    @elseif($applicant->final_status == 'REJECTED')
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">😔</div>
                            <h4 class="text-xl font-bold text-red-600 mb-2">MOHON MAAF</h4>
                            <p class="text-red-700 font-medium">Belum berhasil kali ini</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $applicant->final_notes ?? 'Tetap semangat!' }}</p>
                        </div>
                    @elseif($applicant->status == 'PAID')
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">⏳</div>
                            <h4 class="text-xl font-bold text-blue-600 mb-2">MENUNGGU PENGUMUMAN</h4>
                            <p class="text-blue-700 font-medium">Pembayaran telah dikonfirmasi</p>
                            <p class="text-sm text-gray-600 mt-2">Menunggu keputusan akhir dari sekolah</p>
                        </div>
                    @elseif($applicant->status == 'VERIFIED')
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">💰</div>
                            <h4 class="text-xl font-bold text-green-600 mb-2">BERKAS DIVERIFIKASI</h4>
                            <p class="text-green-700 font-medium">Silakan lakukan pembayaran</p>
                            <a href="{{ route('payment.index') }}" class="inline-block mt-3 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
                                💳 Bayar Sekarang
                            </a>
                        </div>
                    @elseif($applicant->status == 'REJECTED')
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">❌</div>
                            <h4 class="text-xl font-bold text-red-600 mb-2">BERKAS DITOLAK</h4>
                            <p class="text-red-700 font-medium">Perlu perbaikan berkas</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $applicant->catatan_verifikasi ?? 'Silakan perbaiki berkas' }}</p>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-5xl mb-3">🔍</div>
                            <h4 class="text-xl font-bold text-yellow-600 mb-2">MENUNGGU VERIFIKASI</h4>
                            <p class="text-yellow-700 font-medium">Berkas sedang diproses</p>
                            <p class="text-sm text-gray-600 mt-2">Tim panitia sedang memeriksa berkas Anda</p>
                        </div>
                    @endif
                </div>

                <!-- Info Pendaftaran -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <span class="mr-2">📋</span> Info Pendaftaran
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">No. Pendaftaran:</span>
                            <span class="font-medium text-blue-600">{{ $applicant->no_pendaftaran }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Nama:</span>
                            <span class="font-medium">{{ $applicant->nama_lengkap }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Jurusan:</span>
                            <span class="font-medium">{{ $applicant->major->name ?? 'Belum dipilih' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Gelombang:</span>
                            <span class="font-medium">{{ $applicant->wave->nama ?? 'Belum dipilih' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tanggal Daftar:</span>
                            <span class="font-medium">{{ $applicant->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Timeline -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-6 flex items-center">
                    <span class="mr-2">📈</span> Timeline Proses
                </h3>
                
                <div class="relative">
                    <!-- Progress Line -->
                    <div class="absolute left-4 top-8 bottom-0 w-0.5 bg-gray-200"></div>
                    
                    <!-- Steps -->
                    <div class="space-y-6">
                        <!-- Step 1: Pendaftaran -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white text-sm font-bold relative z-10">
                                ✓
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold text-green-700">Pendaftaran Selesai</h4>
                                <p class="text-sm text-gray-600">{{ $applicant->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Step 2: Verifikasi -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ $applicant->status == 'VERIFIED' || $applicant->status == 'PAID' || $applicant->final_status ? 'bg-green-500' : ($applicant->status == 'REJECTED' ? 'bg-red-500' : 'bg-yellow-500') }} flex items-center justify-center text-white text-sm font-bold relative z-10">
                                @if($applicant->status == 'VERIFIED' || $applicant->status == 'PAID' || $applicant->final_status)
                                    ✓
                                @elseif($applicant->status == 'REJECTED')
                                    ✗
                                @else
                                    ⏳
                                @endif
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold {{ $applicant->status == 'VERIFIED' || $applicant->status == 'PAID' || $applicant->final_status ? 'text-green-700' : ($applicant->status == 'REJECTED' ? 'text-red-700' : 'text-yellow-700') }}">
                                    @if($applicant->status == 'VERIFIED' || $applicant->status == 'PAID' || $applicant->final_status)
                                        Berkas Diverifikasi
                                    @elseif($applicant->status == 'REJECTED')
                                        Berkas Ditolak
                                    @else
                                        Menunggu Verifikasi
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-600">
                                    @if($applicant->tgl_verifikasi_adm)
                                        {{ \Carbon\Carbon::parse($applicant->tgl_verifikasi_adm)->format('d M Y, H:i') }}
                                    @else
                                        Belum diverifikasi
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Step 3: Pembayaran -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ $applicant->status == 'PAID' || $applicant->final_status ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white text-sm font-bold relative z-10">
                                @if($applicant->status == 'PAID' || $applicant->final_status)
                                    ✓
                                @else
                                    3
                                @endif
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold {{ $applicant->status == 'PAID' || $applicant->final_status ? 'text-green-700' : 'text-gray-500' }}">
                                    @if($applicant->status == 'PAID' || $applicant->final_status)
                                        Pembayaran Selesai
                                    @else
                                        Menunggu Pembayaran
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-600">
                                    @if($applicant->tgl_verifikasi_payment)
                                        {{ \Carbon\Carbon::parse($applicant->tgl_verifikasi_payment)->format('d M Y, H:i') }}
                                    @else
                                        Belum bayar
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Step 4: Pengumuman -->
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-full {{ $applicant->final_status ? 'bg-green-500' : 'bg-gray-300' }} flex items-center justify-center text-white text-sm font-bold relative z-10">
                                @if($applicant->final_status)
                                    ✓
                                @else
                                    4
                                @endif
                            </div>
                            <div class="ml-4">
                                <h4 class="font-semibold {{ $applicant->final_status ? 'text-green-700' : 'text-gray-500' }}">
                                    @if($applicant->final_status)
                                        Pengumuman Keluar
                                    @else
                                        Menunggu Pengumuman
                                    @endif
                                </h4>
                                <p class="text-sm text-gray-600">
                                    @if($applicant->decided_at)
                                        {{ \Carbon\Carbon::parse($applicant->decided_at)->format('d M Y, H:i') }}
                                    @else
                                        Belum diumumkan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('pendaftaran.create') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition text-center">
                    <div class="text-3xl mb-2">📝</div>
                    <h4 class="font-semibold">Edit Data</h4>
                    <p class="text-sm text-gray-600">Ubah data pendaftaran</p>
                </a>
                
                <a href="{{ route('dokumen.index') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition text-center">
                    <div class="text-3xl mb-2">📄</div>
                    <h4 class="font-semibold">Kelola Dokumen</h4>
                    <p class="text-sm text-gray-600">Upload berkas persyaratan</p>
                </a>
                
                @if($applicant->status == 'VERIFIED')
                <a href="{{ route('payment.index') }}" class="bg-white rounded-xl shadow-lg p-4 hover:shadow-xl transition text-center">
                    <div class="text-3xl mb-2">💳</div>
                    <h4 class="font-semibold">Pembayaran</h4>
                    <p class="text-sm text-gray-600">Lakukan pembayaran</p>
                </a>
                @else
                <div class="bg-gray-100 rounded-xl shadow-lg p-4 opacity-50 text-center">
                    <div class="text-3xl mb-2">🔒</div>
                    <h4 class="font-semibold">Pembayaran</h4>
                    <p class="text-sm text-gray-600">Tersedia setelah verifikasi</p>
                </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
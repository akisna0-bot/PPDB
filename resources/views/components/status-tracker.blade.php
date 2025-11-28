@props(['applicant'])

<div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-800 mb-6">Status Pendaftaran</h3>
    
    <div class="relative">
        <!-- Progress Line -->
        <div class="absolute left-4 top-8 bottom-8 w-0.5 bg-gray-200"></div>
        
        <!-- Steps -->
        <div class="space-y-6">
            <!-- Step 1: Pendaftaran -->
            <div class="relative flex items-center">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center {{ $applicant && in_array($applicant->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'MENUNGGU_BAYAR', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']) ? 'bg-green-500 text-white' : 'bg-gray-300 text-gray-600' }}">
                    @if($applicant && in_array($applicant->status, ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'MENUNGGU_BAYAR', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']))
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        1
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900">Pendaftaran Submitted</h4>
                    <p class="text-sm text-gray-500">
                        @if($applicant && $applicant->created_at)
                            {{ $applicant->created_at->format('d M Y, H:i') }}
                        @else
                            Belum submit
                        @endif
                    </p>
                </div>
            </div>

            <!-- Step 2: Verifikasi Administrasi -->
            <div class="relative flex items-center">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $applicant && $applicant->status === 'ADM_PASS' ? 'bg-green-500 text-white' : 
                       ($applicant && $applicant->status === 'ADM_REJECT' ? 'bg-red-500 text-white' : 
                       ($applicant && in_array($applicant->status, ['MENUNGGU_BAYAR', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']) ? 'bg-green-500 text-white' : 
                       ($applicant && $applicant->status === 'SUBMIT' ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600'))) }}">
                    @if($applicant && $applicant->status === 'ADM_PASS')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && $applicant->status === 'ADM_REJECT')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && in_array($applicant->status, ['MENUNGGU_BAYAR', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']))
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && $applicant->status === 'SUBMIT')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        2
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900">Verifikasi Administrasi</h4>
                    <p class="text-sm text-gray-500">
                        @if($applicant && $applicant->status === 'ADM_PASS')
                            Berkas diverifikasi: {{ $applicant->tgl_verifikasi_adm ? $applicant->tgl_verifikasi_adm->format('d M Y, H:i') : '-' }}
                        @elseif($applicant && $applicant->status === 'ADM_REJECT')
                            Berkas ditolak: {{ $applicant->tgl_verifikasi_adm ? $applicant->tgl_verifikasi_adm->format('d M Y, H:i') : '-' }}
                        @elseif($applicant && $applicant->status === 'SUBMIT')
                            Sedang diverifikasi...
                        @elseif($applicant && in_array($applicant->status, ['MENUNGGU_BAYAR', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']))
                            Berkas diverifikasi: {{ $applicant->tgl_verifikasi_adm ? $applicant->tgl_verifikasi_adm->format('d M Y, H:i') : '-' }}
                        @else
                            Menunggu submit pendaftaran
                        @endif
                    </p>
                </div>
            </div>

            <!-- Step 3: Pembayaran -->
            <div class="relative flex items-center">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $applicant && $applicant->status === 'PAID' ? 'bg-green-500 text-white' : 
                       ($applicant && in_array($applicant->status, ['LULUS', 'TIDAK_LULUS', 'CADANGAN']) ? 'bg-green-500 text-white' : 
                       ($applicant && $applicant->status === 'MENUNGGU_BAYAR' ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600')) }}">
                    @if($applicant && in_array($applicant->status, ['PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']))
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && $applicant->status === 'MENUNGGU_BAYAR')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        3
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900">Pembayaran</h4>
                    <p class="text-sm text-gray-500">
                        @if($applicant && in_array($applicant->status, ['PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']))
                            Pembayaran terverifikasi
                        @elseif($applicant && $applicant->status === 'MENUNGGU_BAYAR')
                            Menunggu pembayaran
                        @else
                            Belum sampai tahap pembayaran
                        @endif
                    </p>
                </div>
            </div>

            <!-- Step 4: Hasil Seleksi -->
            <div class="relative flex items-center">
                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center 
                    {{ $applicant && $applicant->status === 'LULUS' ? 'bg-green-500 text-white' : 
                       ($applicant && $applicant->status === 'TIDAK_LULUS' ? 'bg-red-500 text-white' : 
                       ($applicant && $applicant->status === 'CADANGAN' ? 'bg-yellow-500 text-white' : 'bg-gray-300 text-gray-600')) }}">
                    @if($applicant && $applicant->status === 'LULUS')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && $applicant->status === 'TIDAK_LULUS')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    @elseif($applicant && $applicant->status === 'CADANGAN')
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    @else
                        4
                    @endif
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900">Hasil Seleksi</h4>
                    <p class="text-sm text-gray-500">
                        @if($applicant && $applicant->status === 'LULUS')
                            🎉 Selamat! Anda LULUS
                        @elseif($applicant && $applicant->status === 'TIDAK_LULUS')
                            Mohon maaf, belum berhasil kali ini
                        @elseif($applicant && $applicant->status === 'CADANGAN')
                            Anda masuk daftar cadangan
                        @else
                            Menunggu pengumuman hasil
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if($applicant && $applicant->catatan_verifikasi)
        <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <h4 class="text-sm font-medium text-yellow-800 mb-2">Catatan Verifikasi:</h4>
            <p class="text-sm text-yellow-700">{{ $applicant->catatan_verifikasi }}</p>
        </div>
    @endif
</div>
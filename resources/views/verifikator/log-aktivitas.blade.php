@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-indigo-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 flex items-center">
                <span class="mr-3">📝</span> Log Aktivitas Verifikasi
            </h2>
            <p class="text-gray-600">Riwayat semua aktivitas verifikasi yang telah dilakukan</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <span class="mr-2">🕒</span> Riwayat Aktivitas
                </h3>
            </div>
            
            <div class="p-6">
                @if($logs->count() > 0)
                    <div class="space-y-4">
                        @foreach($logs as $log)
                        <div class="flex items-start p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4
                                @if(str_contains($log->keterangan, 'verified')) bg-green-100
                                @elseif(str_contains($log->keterangan, 'rejected')) bg-red-100
                                @else bg-yellow-100 @endif">
                                @if(str_contains($log->keterangan, 'verified'))
                                    <span class="text-green-600">✅</span>
                                @elseif(str_contains($log->keterangan, 'rejected'))
                                    <span class="text-red-600">❌</span>
                                @else
                                    <span class="text-yellow-600">🔄</span>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="font-semibold text-gray-800">
                                        {{ $log->applicant->user->name ?? 'Pendaftar' }}
                                    </h4>
                                    <span class="text-xs text-gray-500">
                                        {{ $log->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                
                                <p class="text-sm text-gray-600 mb-2">
                                    <span class="font-medium">{{ $log->applicant->no_pendaftaran ?? '-' }}</span>
                                    - {{ $log->applicant->major->name ?? 'Jurusan tidak diketahui' }}
                                </p>
                                
                                <p class="text-sm text-gray-700 mb-2">
                                    <strong>Aktivitas:</strong> {{ $log->keterangan }}
                                </p>
                                
                                @if($log->data_baru && isset($log->data_baru['catatan']))
                                <div class="bg-white p-3 rounded border-l-4 border-purple-500">
                                    <p class="text-sm text-gray-600">
                                        <strong>Catatan:</strong> {{ $log->data_baru['catatan'] }}
                                    </p>
                                </div>
                                @endif
                                
                                <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                    @if($log->data_lama && isset($log->data_lama['status']))
                                    <span>Status Lama: {{ $log->data_lama['status'] }}</span>
                                    @endif
                                    @if($log->data_baru && isset($log->data_baru['status']))
                                    <span>Status Baru: {{ $log->data_baru['status'] }}</span>
                                    @endif
                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <span class="text-6xl mb-4 block">📝</span>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Aktivitas</h4>
                        <p class="text-gray-600">Log aktivitas verifikasi akan muncul di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
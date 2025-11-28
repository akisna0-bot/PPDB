@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">📋 Status Pendaftaran</h1>
                    <p class="text-gray-600 mt-2">Timeline proses pendaftaran Anda</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">No. Pendaftaran</p>
                    <p class="text-lg font-bold text-blue-600">{{ $applicant->no_pendaftaran ?? 'Belum ada' }}</p>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Progress Pendaftaran</h2>
                <span class="text-sm text-gray-500">{{ $progressPercentage }}% Selesai</span>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500" 
                     style="width: {{ $progressPercentage }}%"></div>
            </div>
            
            <!-- Status Steps -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($statusSteps as $step)
                <div class="text-center">
                    <div class="w-12 h-12 mx-auto mb-2 rounded-full flex items-center justify-center
                        {{ $step['completed'] ? 'bg-green-500 text-white' : 
                           ($step['current'] ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                        <span class="text-lg">{!! $step['icon'] !!}</span>
                    </div>
                    <p class="text-xs font-medium {{ $step['completed'] ? 'text-green-600' : 
                                                   ($step['current'] ? 'text-blue-600' : 'text-gray-400') }}">
                        {{ $step['title'] }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Timeline Detail -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">🕒 Timeline Detail</h2>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    @foreach($timeline as $item)
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center
                                {{ $item['status'] == 'completed' ? 'bg-green-100 text-green-600' : 
                                   ($item['status'] == 'current' ? 'bg-blue-100 text-blue-600' : 
                                   ($item['status'] == 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-gray-100 text-gray-400')) }}">
                                <span class="text-sm">{!! $item['icon'] !!}</span>
                            </div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">{{ $item['title'] }}</h3>
                                @if($item['date'])
                                    <span class="text-sm text-gray-500">{{ $item['date'] }}</span>
                                @endif
                            </div>
                            
                            <p class="text-gray-600 mt-1">{{ $item['description'] }}</p>
                            
                            @if($item['status'] == 'completed')
                                <div class="mt-2 flex items-center text-sm text-green-600">
                                    <span class="mr-1">✅</span> Selesai
                                    @if($item['verified_by'])
                                        <span class="ml-2 text-gray-500">oleh {{ $item['verified_by'] }}</span>
                                    @endif
                                </div>
                            @elseif($item['status'] == 'current')
                                <div class="mt-2 flex items-center text-sm text-blue-600">
                                    <span class="mr-1">🔄</span> Sedang Diproses
                                </div>
                            @elseif($item['status'] == 'pending')
                                <div class="mt-2 flex items-center text-sm text-yellow-600">
                                    <span class="mr-1">⏳</span> Menunggu
                                </div>
                            @endif
                            
                            @if($item['notes'])
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-700"><strong>Catatan:</strong> {{ $item['notes'] }}</p>
                                </div>
                            @endif
                            
                            @if($item['action_needed'])
                                <div class="mt-3">
                                    <a href="{{ $item['action_url'] }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                                        {{ $item['action_text'] }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @if(!$loop->last)
                        <div class="ml-5 w-px h-6 bg-gray-200"></div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('pendaftaran.create') }}" 
               class="bg-blue-600 text-white p-4 rounded-xl text-center hover:bg-blue-700 transition">
                <div class="text-2xl mb-2">📝</div>
                <div class="font-medium">Edit Data</div>
            </a>
            
            <a href="{{ route('dokumen.index') }}" 
               class="bg-green-600 text-white p-4 rounded-xl text-center hover:bg-green-700 transition">
                <div class="text-2xl mb-2">📄</div>
                <div class="font-medium">Upload Berkas</div>
            </a>
            
            <a href="{{ route('payment.index') }}" 
               class="bg-purple-600 text-white p-4 rounded-xl text-center hover:bg-purple-700 transition">
                <div class="text-2xl mb-2">💰</div>
                <div class="font-medium">Pembayaran</div>
            </a>
        </div>
    </div>
</div>
@endsection
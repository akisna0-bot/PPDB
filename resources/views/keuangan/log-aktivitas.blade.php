@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <span class="mr-3">📝</span> Log Aktivitas Keuangan
                        </h1>
                        <p class="text-green-100">Riwayat semua aktivitas pembayaran</p>
                    </div>
                    <a href="{{ route('keuangan.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="text-lg font-semibold flex items-center">
                    <span class="mr-2">🔔</span> Aktivitas Pembayaran
                </h3>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @php
                        $activities = App\Models\Applicant::with(['user', 'major'])
                            ->where('status', 'PAID')
                            ->orderBy('updated_at', 'desc')
                            ->limit(20)
                            ->get();
                    @endphp
                    
                    @forelse($activities as $activity)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                                <span class="text-sm font-bold text-green-600">💰</span>
                            </div>
                            <div>
                                <p class="font-medium">Pembayaran Diterima</p>
                                <p class="text-sm text-gray-600">{{ $activity->user->name }} - {{ $activity->no_pendaftaran }}</p>
                                <p class="text-xs text-gray-500">{{ $activity->major->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">Rp {{ number_format(150000, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">{{ $activity->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        <div class="text-4xl mb-2">📝</div>
                        <p>Belum ada aktivitas pembayaran</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Statistik Aktivitas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📊</div>
                <h3 class="text-2xl font-bold text-green-600">{{ $activities->count() }}</h3>
                <p class="text-gray-600">Total Aktivitas</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">💰</div>
                <h3 class="text-lg font-bold text-blue-600">Rp {{ number_format($activities->count() * 150000, 0, ',', '.') }}</h3>
                <p class="text-gray-600">Total Transaksi</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📅</div>
                <h3 class="text-2xl font-bold text-purple-600">{{ $activities->where('updated_at', '>=', today())->count() }}</h3>
                <p class="text-gray-600">Hari Ini</p>
            </div>
            
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-3xl mb-2">📈</div>
                <h3 class="text-lg font-bold text-orange-600">{{ number_format(($activities->count() / max(1, App\Models\Applicant::count())) * 100, 1) }}%</h3>
                <p class="text-gray-600">Tingkat Bayar</p>
            </div>
        </div>
    </div>
</div>
@endsection
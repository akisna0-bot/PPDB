@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-indigo-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('admin.dashboard') }}" class="mr-4 bg-white/20 hover:bg-white/30 p-2 rounded-lg transition">
                        ← Kembali
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold">Manajemen Akun</h1>
                        <p class="text-blue-100">Kelola semua akun pengguna sistem</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">👑</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Admin</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userStats['admin'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">✅</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Verifikator</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userStats['verifikator'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">💰</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Keuangan</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userStats['keuangan'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🏫</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kepsek</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userStats['kepsek'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Siswa</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $userStats['user'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Create User Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <span class="mr-2">➕</span>
                Buat Akun Baru
            </h3>
            
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.panitia.create-user') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                           required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                        <option value="">Pilih Role</option>
                        <option value="admin">Admin</option>
                        <option value="kepsek">Kepala Sekolah</option>
                        <option value="verifikator_adm">Verifikator</option>
                        <option value="verifikator">Verifikator (Legacy)</option>
                        <option value="keuangan">Keuangan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                           required>
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                        Buat Akun
                    </button>
                </div>
            </form>
        </div>

        <!-- Users List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Daftar Akun</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Info Tambahan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($users ?? [] as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->role == 'user' && $user->applicant)
                                        <div class="text-xs text-gray-500">{{ $user->applicant->no_pendaftaran ?? 'Belum daftar' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role == 'admin')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            👑 Admin
                                        </span>
                                    @elseif($user->role == 'kepsek')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            🏫 Kepala Sekolah
                                        </span>
                                    @elseif($user->role == 'verifikator_adm' || $user->role == 'verifikator')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Verifikator
                                        </span>
                                    @elseif($user->role == 'keuangan')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            💰 Keuangan
                                        </span>
                                    @elseif($user->role == 'user')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            🎓 Siswa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($user->role == 'user' && $user->applicant)
                                        <div class="text-xs">
                                            <div><strong>Jurusan:</strong> {{ $user->applicant->major->name ?? 'Belum pilih' }}</div>
                                            <div><strong>Status:</strong> 
                                                <span class="px-1 py-0.5 rounded text-xs
                                                    @if($user->applicant->status == 'VERIFIED') bg-green-100 text-green-800
                                                    @elseif($user->applicant->status == 'REJECTED') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    {{ $user->applicant->status }}
                                                </span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        @if($user->role != 'admin' || $user->id != auth()->id())
                                            <button onclick="resetPassword({{ $user->id }}, '{{ $user->name }}')" 
                                                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-xs transition">
                                                🔑 Reset Password
                                            </button>
                                        @endif
                                        @if($user->role == 'user' && $user->applicant)
                                            <button onclick="viewDetails({{ $user->applicant->id }})" 
                                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition">
                                                👁️ Detail
                                            </button>
                                        @elseif($user->role == 'user')
                                            <span class="text-gray-400 text-xs">Belum daftar</span>
                                        @endif
                                        @if($user->id != auth()->id())
                                            <button onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" 
                                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs transition">
                                                🗑️ Hapus
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    Tidak ada data akun
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function resetPassword(userId, userName) {
    if (confirm(`Apakah Anda yakin ingin reset password untuk ${userName}?\n\nPassword baru akan dikirim via email.`)) {
        fetch('/admin/panitia/reset-password', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Password berhasil direset!\n\nPassword baru: ${data.new_password}\n\nSilakan catat dan berikan kepada user.`);
            } else {
                alert('Gagal reset password: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
}

function viewDetails(applicantId) {
    // Redirect ke halaman detail applicant
    window.open(`/admin/applicants/${applicantId}`, '_blank');
}

function deleteUser(userId, userName) {
    if (confirm(`PERINGATAN!\n\nApakah Anda yakin ingin menghapus akun:\n${userName}?\n\nTindakan ini tidak dapat dibatalkan!`)) {
        fetch(`/admin/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Akun berhasil dihapus!');
                location.reload();
            } else {
                alert('Gagal menghapus akun: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan: ' + error.message);
        });
    }
}
</script>
@endsection
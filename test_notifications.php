<?php

// Test script untuk memastikan notifikasi berfungsi
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
);

// Test 1: Buat notifikasi test
echo "=== TEST NOTIFIKASI PPDB ===\n\n";

try {
    // Ambil user pertama (siswa)
    $user = App\Models\User::where('role', 'siswa')->first();
    
    if (!$user) {
        echo "❌ Tidak ada user dengan role siswa\n";
        exit;
    }
    
    echo "✅ User ditemukan: {$user->name} (ID: {$user->id})\n";
    
    // Test 2: Buat notifikasi verifikasi berkas
    $notification1 = App\Models\Notification::create([
        'user_id' => $user->id,
        'title' => '✅ Berkas Diverifikasi - Silakan Bayar',
        'message' => 'Selamat! Berkas administrasi Anda telah diverifikasi oleh tim verifikator. Silakan lakukan pembayaran untuk melanjutkan proses pendaftaran.',
        'type' => 'success',
        'is_read' => false
    ]);
    
    echo "✅ Notifikasi verifikasi berkas dibuat (ID: {$notification1->id})\n";
    
    // Test 3: Buat notifikasi pembayaran
    $notification2 = App\Models\Notification::create([
        'user_id' => $user->id,
        'title' => '✅ Pembayaran Terverifikasi',
        'message' => 'Selamat! Pembayaran Anda telah diverifikasi oleh tim keuangan. Menunggu pengumuman hasil seleksi.',
        'type' => 'success',
        'is_read' => false
    ]);
    
    echo "✅ Notifikasi pembayaran dibuat (ID: {$notification2->id})\n";
    
    // Test 4: Buat notifikasi keputusan final
    $notification3 = App\Models\Notification::create([
        'user_id' => $user->id,
        'title' => '🎉 Selamat! Anda DITERIMA',
        'message' => 'Selamat! Anda dinyatakan DITERIMA dalam seleksi PPDB SMK Bakti Nusantara 666. Silakan ikuti instruksi daftar ulang.',
        'type' => 'success',
        'is_read' => false
    ]);
    
    echo "✅ Notifikasi keputusan final dibuat (ID: {$notification3->id})\n";
    
    // Test 5: Hitung total notifikasi
    $totalNotifications = App\Models\Notification::where('user_id', $user->id)->count();
    $unreadNotifications = App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
    
    echo "\n=== STATISTIK NOTIFIKASI ===\n";
    echo "Total notifikasi: {$totalNotifications}\n";
    echo "Belum dibaca: {$unreadNotifications}\n";
    
    // Test 6: Tampilkan semua notifikasi
    $notifications = App\Models\Notification::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();
    
    echo "\n=== DAFTAR NOTIFIKASI ===\n";
    foreach ($notifications as $notif) {
        $status = $notif->is_read ? '✓' : '●';
        echo "{$status} [{$notif->type}] {$notif->title}\n";
        echo "   {$notif->message}\n";
        echo "   {$notif->created_at->format('d M Y H:i')}\n\n";
    }
    
    echo "✅ Test notifikasi selesai!\n";
    echo "\n=== CARA MENGGUNAKAN ===\n";
    echo "1. Login sebagai siswa\n";
    echo "2. Lihat notifikasi di navigation bar (ikon lonceng)\n";
    echo "3. Atau kunjungi /notifications untuk melihat semua notifikasi\n";
    echo "4. Notifikasi juga muncul di dashboard\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
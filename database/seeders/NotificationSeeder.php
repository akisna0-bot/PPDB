<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('role', 'admin')->first();
        $keuangan = User::where('role', 'keuangan')->first();
        $verifikator = User::where('role', 'verifikator')->first();
        $kepsek = User::where('role', 'kepsek')->first();

        $notifications = [
            ['user_id' => $admin->id, 'title' => 'Pendaftar Baru', 'message' => '15 pendaftar baru minggu ini', 'type' => 'info', 'is_read' => 0],
            ['user_id' => $admin->id, 'title' => 'Sistem Update', 'message' => 'Sistem berhasil diperbarui', 'type' => 'success', 'is_read' => 0],
            ['user_id' => $keuangan->id, 'title' => 'Pembayaran Pending', 'message' => '8 pembayaran menunggu verifikasi', 'type' => 'warning', 'is_read' => 0],
            ['user_id' => $keuangan->id, 'title' => 'Laporan Keuangan', 'message' => 'Laporan bulan ini siap diunduh', 'type' => 'info', 'is_read' => 1],
            ['user_id' => $verifikator->id, 'title' => 'Berkas Baru', 'message' => '12 berkas menunggu verifikasi', 'type' => 'warning', 'is_read' => 0],
            ['user_id' => $verifikator->id, 'title' => 'Deadline Verifikasi', 'message' => 'Batas waktu 3 hari lagi', 'type' => 'danger', 'is_read' => 0],
            ['user_id' => $kepsek->id, 'title' => 'Laporan Mingguan', 'message' => 'Laporan PPDB tersedia untuk review', 'type' => 'info', 'is_read' => 1],
            ['user_id' => $kepsek->id, 'title' => 'Target Tercapai', 'message' => 'Target pendaftar 80% tercapai', 'type' => 'success', 'is_read' => 0],
        ];

        foreach ($notifications as $notif) {
            Notification::create($notif);
        }

        echo "✅ Notifikasi berhasil dibuat untuk semua role!\n";
    }
}
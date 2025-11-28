<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SimpleDummySeeder extends Seeder
{
    public function run()
    {
        // Create test users
        for ($i = 1; $i <= 8; $i++) {
            $user = User::create([
                'name' => 'Siswa Test ' . $i,
                'email' => 'siswa' . $i . '@test.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);

            $majors = Major::all();
            $waves = Wave::all();
            
            $statuses = ['DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID'];
            $status = $statuses[array_rand($statuses)];
            
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad(1000 + $i, 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'user_verifikasi_adm' => $status === 'ADM_PASS' ? 'verifikator@ppdb.com' : null,
                'tgl_verifikasi_adm' => $status === 'ADM_PASS' ? Carbon::now()->subDays(rand(1, 10)) : null,
            ]);

            // Create payment
            $paymentStatuses = ['pending', 'paid', 'verified'];
            $pStatus = $paymentStatuses[array_rand($paymentStatuses)];
            
            Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => 'INV' . date('Y') . str_pad(1000 + $i, 4, '0', STR_PAD_LEFT),
                'amount' => rand(0, 1) ? 150000 : 200000,
                'payment_type' => 'registration',
                'status' => $pStatus,
                'payment_method' => ['bank_transfer', 'virtual_account', 'e_wallet'][array_rand(['bank_transfer', 'virtual_account', 'e_wallet'])],
                'paid_at' => $pStatus !== 'pending' ? Carbon::now()->subDays(rand(1, 15)) : null,
                'verified_by' => $pStatus === 'verified' ? User::where('role', 'keuangan')->first()->id : null,
                'verified_at' => $pStatus === 'verified' ? Carbon::now()->subDays(rand(1, 10)) : null,
            ]);
        }

        // Create notifications for staff
        $adminUser = User::where('role', 'admin')->first();
        $keuanganUser = User::where('role', 'keuangan')->first();
        $verifikatorUser = User::where('role', 'verifikator')->first();
        $kepsekUser = User::where('role', 'kepsek')->first();

        $notifications = [
            ['user_id' => $adminUser->id, 'title' => 'Pendaftar Baru', 'message' => '8 pendaftar baru minggu ini', 'type' => 'info'],
            ['user_id' => $adminUser->id, 'title' => 'Sistem Update', 'message' => 'Sistem berhasil diperbarui ke versi terbaru', 'type' => 'success'],
            ['user_id' => $keuanganUser->id, 'title' => 'Pembayaran Pending', 'message' => '5 pembayaran menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $keuanganUser->id, 'title' => 'Laporan Keuangan', 'message' => 'Laporan keuangan bulan ini siap diunduh', 'type' => 'info'],
            ['user_id' => $verifikatorUser->id, 'title' => 'Berkas Baru', 'message' => '12 berkas menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $verifikatorUser->id, 'title' => 'Deadline Verifikasi', 'message' => 'Batas waktu verifikasi berkas 3 hari lagi', 'type' => 'danger'],
            ['user_id' => $kepsekUser->id, 'title' => 'Laporan Mingguan', 'message' => 'Laporan PPDB minggu ini tersedia untuk review', 'type' => 'info'],
            ['user_id' => $kepsekUser->id, 'title' => 'Target Tercapai', 'message' => 'Target pendaftar 75% sudah tercapai', 'type' => 'success'],
        ];

        foreach ($notifications as $notif) {
            Notification::create([
                'user_id' => $notif['user_id'],
                'title' => $notif['title'],
                'message' => $notif['message'],
                'type' => $notif['type'],
                'is_read' => rand(0, 1),
                'created_at' => Carbon::now()->subDays(rand(1, 7)),
            ]);
        }

        echo "✅ Data dummy berhasil dibuat!\n";
        echo "👥 " . User::where('role', 'user')->count() . " siswa pendaftar\n";
        echo "📝 " . Applicant::count() . " data pendaftaran\n";
        echo "💰 " . Payment::count() . " data pembayaran\n";
        echo "🔔 " . Notification::count() . " notifikasi untuk staff\n";
        echo "\n📋 Dashboard sekarang sudah berisi data dummy yang bagus!\n";
    }
}
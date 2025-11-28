<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\Notification;
use App\Models\LogAktivitas;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QuickDummySeeder extends Seeder
{
    public function run()
    {
        // Get existing users
        $existingUsers = User::where('role', 'user')->get();
        
        // If no users exist, create some
        if ($existingUsers->count() == 0) {
            for ($i = 1; $i <= 5; $i++) {
                $user = User::create([
                    'name' => 'User Test ' . $i,
                    'email' => 'user' . $i . '@test.com',
                    'password' => bcrypt('password'),
                    'role' => 'user',
                ]);
                $existingUsers->push($user);
            }
        }

        $majors = Major::all();
        $waves = Wave::all();
        
        // Create applicants for existing users
        foreach ($existingUsers->take(5) as $user) {
            if (!$user->applicant) {
                $statuses = ['DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID'];
                $status = $statuses[array_rand($statuses)];
                
                $applicant = Applicant::create([
                    'user_id' => $user->id,
                    'major_id' => $majors->random()->id,
                    'wave_id' => $waves->random()->id,
                    'no_pendaftaran' => 'PPDB' . date('Y') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'user_verifikasi_adm' => $status === 'ADM_PASS' ? 'verifikator@ppdb.com' : null,
                    'tgl_verifikasi_adm' => $status === 'ADM_PASS' ? Carbon::now()->subDays(rand(1, 10)) : null,
                ]);

                // Create payment
                $paymentStatuses = ['pending', 'paid', 'verified'];
                $pStatus = $paymentStatuses[array_rand($paymentStatuses)];
                
                Payment::create([
                    'applicant_id' => $applicant->id,
                    'invoice_number' => 'INV' . date('Y') . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'amount' => rand(0, 1) ? 150000 : 200000,
                    'payment_type' => 'registration',
                    'status' => $pStatus,
                    'payment_method' => ['bank_transfer', 'virtual_account', 'e_wallet'][array_rand(['bank_transfer', 'virtual_account', 'e_wallet'])],
                    'paid_at' => $pStatus !== 'pending' ? Carbon::now()->subDays(rand(1, 15)) : null,
                    'verified_by' => $pStatus === 'verified' ? User::where('role', 'keuangan')->first()->id : null,
                    'verified_at' => $pStatus === 'verified' ? Carbon::now()->subDays(rand(1, 10)) : null,
                ]);
            }
        }

        // Create notifications for staff
        $adminUser = User::where('role', 'admin')->first();
        $keuanganUser = User::where('role', 'keuangan')->first();
        $verifikatorUser = User::where('role', 'verifikator')->first();
        $kepsekUser = User::where('role', 'kepsek')->first();

        $notifications = [
            ['user_id' => $adminUser->id, 'title' => 'Pendaftar Baru', 'message' => '5 pendaftar baru hari ini', 'type' => 'info'],
            ['user_id' => $keuanganUser->id, 'title' => 'Pembayaran Pending', 'message' => '3 pembayaran menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $verifikatorUser->id, 'title' => 'Berkas Baru', 'message' => '8 berkas menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $kepsekUser->id, 'title' => 'Laporan Mingguan', 'message' => 'Laporan PPDB minggu ini tersedia', 'type' => 'info'],
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

        // Create activity logs
        $activities = [
            ['user_id' => $adminUser->id, 'activity' => 'Login ke sistem', 'description' => 'Admin login dari IP 192.168.1.1'],
            ['user_id' => $keuanganUser->id, 'activity' => 'Verifikasi pembayaran', 'description' => 'Memverifikasi pembayaran INV2025001'],
            ['user_id' => $verifikatorUser->id, 'activity' => 'Verifikasi berkas', 'description' => 'Memverifikasi berkas pendaftar'],
            ['user_id' => $kepsekUser->id, 'activity' => 'Review laporan', 'description' => 'Mereview laporan PPDB mingguan'],
        ];

        foreach ($activities as $activity) {
            LogAktivitas::create([
                'user_id' => $activity['user_id'],
                'activity' => $activity['activity'],
                'description' => $activity['description'],
                'ip_address' => '192.168.1.' . rand(1, 255),
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);
        }

        echo "✅ Data dummy berhasil dibuat!\n";
        echo "👥 " . User::count() . " total users\n";
        echo "📝 " . Applicant::count() . " pendaftar\n";
        echo "💰 " . Payment::count() . " pembayaran\n";
        echo "🔔 " . Notification::count() . " notifikasi\n";
    }
}
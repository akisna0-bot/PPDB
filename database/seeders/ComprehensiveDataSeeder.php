<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\ApplicantFile;
use App\Models\Major;
use App\Models\Wave;
use App\Models\Notification;
use App\Models\LogAktivitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ComprehensiveDataSeeder extends Seeder
{
    public function run()
    {
        // Create more users with different statuses
        $users = [
            ['name' => 'Ahmad Rizki', 'email' => 'ahmad.rizki@gmail.com', 'role' => 'user'],
            ['name' => 'Siti Nurhaliza', 'email' => 'siti.nurhaliza@gmail.com', 'role' => 'user'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com', 'role' => 'user'],
            ['name' => 'Dewi Sartika', 'email' => 'dewi.sartika@gmail.com', 'role' => 'user'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@gmail.com', 'role' => 'user'],
            ['name' => 'Fitri Handayani', 'email' => 'fitri.handayani@gmail.com', 'role' => 'user'],
            ['name' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@gmail.com', 'role' => 'user'],
            ['name' => 'Hani Safitri', 'email' => 'hani.safitri@gmail.com', 'role' => 'user'],
            ['name' => 'Indra Gunawan', 'email' => 'indra.gunawan@gmail.com', 'role' => 'user'],
            ['name' => 'Joko Widodo', 'email' => 'joko.widodo@gmail.com', 'role' => 'user'],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'role' => $userData['role'],
            ]);

            // Create applicant for each user
            $majors = Major::all();
            $waves = Wave::all();
            
            $statuses = ['DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID'];
            $status = $statuses[array_rand($statuses)];
            
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'status' => $status,
                'user_verifikasi_adm' => $status === 'ADM_PASS' ? 'verifikator@ppdb.com' : null,
                'tgl_verifikasi_adm' => $status === 'ADM_PASS' ? Carbon::now()->subDays(rand(1, 10)) : null,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Create payment for each applicant
            $paymentStatus = ['pending', 'paid', 'verified'];
            $pStatus = $paymentStatus[array_rand($paymentStatus)];
            
            $payment = Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => 'INV' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'amount' => rand(0, 1) ? 150000 : 200000,
                'payment_type' => 'registration',
                'status' => $pStatus,
                'payment_method' => ['bank_transfer', 'virtual_account', 'e_wallet'][array_rand(['bank_transfer', 'virtual_account', 'e_wallet'])],
                'paid_at' => $pStatus !== 'pending' ? Carbon::now()->subDays(rand(1, 15)) : null,
                'verified_by' => $pStatus === 'verified' ? User::where('role', 'keuangan')->first()->id : null,
                'verified_at' => $pStatus === 'verified' ? Carbon::now()->subDays(rand(1, 10)) : null,
                'created_at' => Carbon::now()->subDays(rand(1, 25)),
            ]);

            // Create applicant files
            $fileTypes = ['photo', 'birth_certificate', 'family_card', 'school_certificate', 'report_card'];
            foreach ($fileTypes as $type) {
                if (rand(0, 1)) { // 50% chance to have each file
                    ApplicantFile::create([
                        'applicant_id' => $applicant->id,
                        'filename' => $type . '_' . $applicant->id . '.pdf',
                        'original_name' => $type . '_original.pdf',
                        'file_type' => $type,
                        'path' => 'documents/' . $type . '_' . $applicant->id . '.pdf',
                        'size_kb' => rand(100, 2000),
                        'is_valid' => rand(0, 1),
                        'notes' => rand(0, 1) ? 'File valid' : null,
                        'created_at' => Carbon::now()->subDays(rand(1, 20)),
                    ]);
                }
            }
        }

        // Create notifications for different roles
        $adminUser = User::where('role', 'admin')->first();
        $keuanganUser = User::where('role', 'keuangan')->first();
        $verifikatorUser = User::where('role', 'verifikator')->first();
        $kepsekUser = User::where('role', 'kepsek')->first();

        $notifications = [
            // Admin notifications
            ['user_id' => $adminUser->id, 'title' => 'Pendaftar Baru', 'message' => '5 pendaftar baru hari ini', 'type' => 'info'],
            ['user_id' => $adminUser->id, 'title' => 'Sistem Update', 'message' => 'Sistem berhasil diperbarui', 'type' => 'success'],
            
            // Keuangan notifications
            ['user_id' => $keuanganUser->id, 'title' => 'Pembayaran Pending', 'message' => '3 pembayaran menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $keuanganUser->id, 'title' => 'Laporan Keuangan', 'message' => 'Laporan bulanan siap diunduh', 'type' => 'info'],
            
            // Verifikator notifications
            ['user_id' => $verifikatorUser->id, 'title' => 'Berkas Baru', 'message' => '8 berkas menunggu verifikasi', 'type' => 'warning'],
            ['user_id' => $verifikatorUser->id, 'title' => 'Deadline Verifikasi', 'message' => 'Batas waktu verifikasi 2 hari lagi', 'type' => 'danger'],
            
            // Kepsek notifications
            ['user_id' => $kepsekUser->id, 'title' => 'Laporan Mingguan', 'message' => 'Laporan PPDB minggu ini tersedia', 'type' => 'info'],
            ['user_id' => $kepsekUser->id, 'title' => 'Target Tercapai', 'message' => 'Target pendaftar 80% tercapai', 'type' => 'success'],
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
            ['user_id' => $adminUser->id, 'activity' => 'Export data pendaftar', 'description' => 'Export 50 data pendaftar ke Excel'],
            ['user_id' => $keuanganUser->id, 'activity' => 'Verifikasi pembayaran', 'description' => 'Memverifikasi pembayaran INV2025001'],
            ['user_id' => $keuanganUser->id, 'activity' => 'Generate laporan', 'description' => 'Generate laporan keuangan bulanan'],
            ['user_id' => $verifikatorUser->id, 'activity' => 'Verifikasi berkas', 'description' => 'Memverifikasi berkas pendaftar PPDB20250001'],
            ['user_id' => $verifikatorUser->id, 'activity' => 'Reject berkas', 'description' => 'Menolak berkas karena tidak lengkap'],
            ['user_id' => $kepsekUser->id, 'activity' => 'Review laporan', 'description' => 'Mereview laporan PPDB mingguan'],
            ['user_id' => $kepsekUser->id, 'activity' => 'Approve kebijakan', 'description' => 'Menyetujui kebijakan penerimaan baru'],
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

        echo "✅ Data dummy lengkap berhasil dibuat!\n";
        echo "📊 " . User::where('role', 'user')->count() . " pendaftar\n";
        echo "💰 " . Payment::count() . " pembayaran\n";
        echo "📄 " . ApplicantFile::count() . " berkas\n";
        echo "🔔 " . Notification::count() . " notifikasi\n";
        echo "📝 " . LogAktivitas::count() . " log aktivitas\n";
    }
}
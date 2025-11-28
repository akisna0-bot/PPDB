<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VerifiableDataSeeder extends Seeder
{
    public function run()
    {
        $majors = Major::all();
        $waves = Wave::all();
        
        // Create users with SUBMIT status (ready for verification)
        $names = [
            'Ahmad Fauzi',
            'Siti Rahayu', 
            'Budi Hartono',
            'Dewi Lestari',
            'Eko Susanto'
        ];
        
        foreach ($names as $index => $name) {
            $user = User::create([
                'name' => $name,
                'email' => 'pendaftar' . ($index + 1) . '@gmail.com',
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);

            $applicant = Applicant::create([
                'user_id' => $user->id,
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad(2000 + $index, 4, '0', STR_PAD_LEFT),
                'status' => 'SUBMIT', // Ready for verification
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);

            // Create payment with paid status
            Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => 'INV' . date('Y') . str_pad(2000 + $index, 4, '0', STR_PAD_LEFT),
                'amount' => 150000,
                'payment_type' => 'registration',
                'status' => 'paid',
                'payment_method' => 'bank_transfer',
                'paid_at' => Carbon::now()->subDays(rand(1, 3)),
            ]);
        }

        echo "✅ Data pendaftar siap verifikasi berhasil dibuat!\n";
        echo "📝 " . count($names) . " pendaftar dengan status SUBMIT\n";
        echo "💰 Semua sudah bayar dan siap diverifikasi\n";
        echo "\n🔍 Login sebagai verifikator untuk mulai verifikasi!\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpleDataSeeder extends Seeder
{
    public function run()
    {
        $majors = Major::all();
        $waves = Wave::all();
        
        // Data siswa sederhana
        $siswa = [
            'Ahmad Budi', 'Siti Aisyah', 'Dedi Kurniawan', 'Maya Sari', 'Rizki Pratama',
            'Indah Permata', 'Eko Susanto', 'Fitri Rahayu', 'Gilang Ramadan', 'Nisa Aulia',
            'Bayu Setiawan', 'Dewi Lestari', 'Fajar Nugraha', 'Rina Safitri', 'Andi Wijaya',
            'Sari Maharani', 'Doni Kusuma', 'Lina Anggraini', 'Rudi Hermawan', 'Tika Sari'
        ];
        
        // Kecamatan di Bandung
        $kecamatan = [
            'Cileunyi', 'Rancaekek', 'Majalaya', 'Soreang', 'Banjaran',
            'Cicalengka', 'Nagreg', 'Ibun', 'Paseh', 'Katapang'
        ];
        
        for ($i = 1; $i <= 60; $i++) {
            $nama = $siswa[array_rand($siswa)] . ' ' . $i;
            
            $user = User::create([
                'name' => $nama,
                'email' => "siswa{$i}@test.com",
                'password' => Hash::make('password'),
                'role' => 'user'
            ]);
            
            Applicant::create([
                'user_id' => $user->id,
                'no_pendaftaran' => 'REG2025' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'status' => ['SUBMIT', 'ADM_PASS', 'ADM_REJECT'][rand(0, 2)]
            ]);
        }
    }
}
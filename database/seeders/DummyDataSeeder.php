<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        $majors = Major::all();
        $waves = Wave::all();
        
        // Daftar kecamatan di Kabupaten Bandung
        $kecamatans = [
            'Cileunyi', 'Rancaekek', 'Majalaya', 'Solokan Jeruk', 'Paseh',
            'Ibun', 'Soreang', 'Katapang', 'Banjaran', 'Arjasari',
            'Pangalengan', 'Kertasari', 'Pacet', 'Ciwidey', 'Pasirjambu',
            'Cimaung', 'Margaasih', 'Margahayu', 'Dayeuhkolot', 'Bojongsoang',
            'Cicalengka', 'Nagreg', 'Rancabali', 'Cikancung', 'Cilengkrang'
        ];
        
        $kelurahans = [
            'Cileunyi Kulon', 'Cileunyi Wetan', 'Cinunuk', 'Cibiru Hilir', 'Cibiru Wetan',
            'Rancaekek Kulon', 'Rancaekek Wetan', 'Linggar', 'Bojongloa', 'Jelegong',
            'Majalaya', 'Padamukti', 'Neglasari', 'Wangisagara', 'Sukamaju',
            'Cikuya', 'Bojongemas', 'Paseh', 'Cijagra', 'Drawati',
            'Ibun', 'Dukuh', 'Lampegan', 'Mekarjaya', 'Pangguh',
            'Soreang', 'Panyileukan', 'Pamekaran', 'Parungponteng', 'Ciluncat'
        ];
        
        $namaDepan = ['Ahmad', 'Budi', 'Citra', 'Dina', 'Eko', 'Fitri', 'Gilang', 'Hani', 'Indra', 'Joko', 'Kiki', 'Lina', 'Maya', 'Nanda', 'Oki'];
        $namaBelakang = ['Pratama', 'Sari', 'Putra', 'Dewi', 'Wijaya', 'Lestari', 'Santoso', 'Maharani', 'Kusuma', 'Anggraini', 'Permana', 'Safitri', 'Nugraha', 'Rahayu', 'Setiawan'];
        
        $sekolahAsal = [
            'SMPN 1 Cileunyi', 'SMPN 2 Cileunyi', 'SMPN 3 Cileunyi', 'SMP Pasundan Cileunyi',
            'SMPN 1 Rancaekek', 'SMPN 2 Rancaekek', 'SMP Al-Azhar Rancaekek',
            'SMPN 1 Majalaya', 'SMPN 2 Majalaya', 'SMP Muhammadiyah Majalaya',
            'SMPN 1 Soreang', 'SMPN 2 Soreang', 'SMP PGRI Soreang',
            'SMPN 1 Banjaran', 'SMPN 2 Banjaran', 'SMP Daarut Tauhiid Banjaran'
        ];
        
        for ($i = 1; $i <= 60; $i++) {
            $namaLengkap = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            $kecamatan = $kecamatans[array_rand($kecamatans)];
            $kelurahan = $kelurahans[array_rand($kelurahans)];
            
            // Koordinat random di sekitar Kabupaten Bandung (Cileunyi area)
            $lat = -6.9 + (rand(-50, 50) / 1000); // Sekitar -6.9 (Cileunyi)
            $lng = 107.7 + (rand(-50, 50) / 1000); // Sekitar 107.7 (Cileunyi)
            
            $user = User::create([
                'name' => $namaLengkap,
                'email' => "siswa{$i}@test.com",
                'password' => Hash::make('password'),
                'role' => 'user'
            ]);
            
            Applicant::create([
                'user_id' => $user->id,
                'no_pendaftaran' => 'REG' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'status' => ['DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT'][rand(0, 3)]
            ]);
        }
    }
}
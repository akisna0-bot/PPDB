<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use App\Models\Applicant;

class CompleteSetupSeeder extends Seeder
{
    public function run()
    {
        // Users
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ppdb.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Verifikator Admin',
            'email' => 'verifikator@ppdb.com',
            'password' => Hash::make('password'),
            'role' => 'verifikator_adm'
        ]);

        User::create([
            'name' => 'Staff Keuangan',
            'email' => 'keuangan@ppdb.com',
            'password' => Hash::make('password'),
            'role' => 'keuangan'
        ]);

        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@ppdb.com',
            'password' => Hash::make('password'),
            'role' => 'kepsek'
        ]);

        // Majors
        Major::create(['code' => 'TKJ', 'name' => 'Teknik Komputer dan Jaringan', 'kuota' => 36]);
        Major::create(['code' => 'RPL', 'name' => 'Rekayasa Perangkat Lunak', 'kuota' => 36]);
        Major::create(['code' => 'MM', 'name' => 'Multimedia', 'kuota' => 36]);
        Major::create(['code' => 'TKR', 'name' => 'Teknik Kendaraan Ringan', 'kuota' => 36]);
        Major::create(['code' => 'TSM', 'name' => 'Teknik Sepeda Motor', 'kuota' => 36]);
        Major::create(['code' => 'AKL', 'name' => 'Akuntansi dan Keuangan Lembaga', 'kuota' => 36]);

        // Waves
        Wave::create([
            'name' => 'Gelombang 1',
            'start_date' => '2024-01-01',
            'end_date' => '2024-03-31',
            'is_active' => true
        ]);

        Wave::create([
            'name' => 'Gelombang 2', 
            'start_date' => '2024-04-01',
            'end_date' => '2024-06-30',
            'is_active' => false
        ]);

        // Sample Users & Applicants
        for ($i = 1; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Siswa Test $i",
                'email' => "siswa$i@test.com",
                'password' => Hash::make('password'),
                'role' => 'user'
            ]);

            Applicant::create([
                'user_id' => $user->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'wave_id' => 1,
                'major_id' => rand(1, 6),
                'nama_lengkap' => "Siswa Test Lengkap $i",
                'status' => ['DRAFT', 'SUBMIT', 'VERIFIED', 'REJECTED'][rand(0, 3)]
            ]);
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class QuickSeeder extends Seeder
{
    public function run()
    {
        // Users dengan role sesuai spesifikasi
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@smkbn666.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'kepsek',
        ]);
        
        User::create([
            'name' => 'Verifikator Administrasi',
            'email' => 'verifikator@smkbn666.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'verifikator_adm',
        ]);
        
        User::create([
            'name' => 'Staff Keuangan',
            'email' => 'keuangan@smkbn666.sch.id',
            'password' => Hash::make('password123'),
            'role' => 'keuangan',
        ]);

        // Majors
        Major::create(['code' => 'PPLG', 'name' => 'Pengembangan Perangkat Lunak dan Gim', 'kuota' => 72]);
        Major::create(['code' => 'AKT', 'name' => 'Akuntansi', 'kuota' => 72]);
        Major::create(['code' => 'ANM', 'name' => 'Animasi', 'kuota' => 36]);
        Major::create(['code' => 'DKV', 'name' => 'Desain Komunikasi Visual', 'kuota' => 36]);
        Major::create(['code' => 'PMS', 'name' => 'Pemasaran', 'kuota' => 72]);

        // Waves
        Wave::create([
            'nama' => 'Gelombang 1 - 2025',
            'tgl_mulai' => '2025-01-01',
            'tgl_selesai' => '2025-03-31',
            'biaya_daftar' => 150000
        ]);
        
        Wave::create([
            'nama' => 'Gelombang 2 - 2025',
            'tgl_mulai' => '2025-04-01',
            'tgl_selesai' => '2025-06-30',
            'biaya_daftar' => 200000
        ]);

        echo "✅ Sistem PPDB siap digunakan!\n";
        echo "🔑 kepsek@smkbn666.sch.id / password123\n";
        echo "🔑 verifikator@smkbn666.sch.id / password123\n";
        echo "🔑 keuangan@smkbn666.sch.id / password123\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SimpleSeeder extends Seeder
{
    public function run()
    {
        // Users
        User::create(['name' => 'Kepala Sekolah', 'email' => 'kepsek@ppdb.com', 'password' => Hash::make('password'), 'role' => 'kepsek']);
        User::create(['name' => 'Verifikator', 'email' => 'verifikator@ppdb.com', 'password' => Hash::make('password'), 'role' => 'verifikator']);
        User::create(['name' => 'Keuangan', 'email' => 'keuangan@ppdb.com', 'password' => Hash::make('password'), 'role' => 'keuangan']);

        // Majors
        Major::create(['kode' => 'PPLG', 'code' => 'PPLG', 'name' => 'Pengembangan Perangkat Lunak dan Gim', 'kuota' => 72]);
        Major::create(['kode' => 'AKT', 'code' => 'AKT', 'name' => 'Akuntansi', 'kuota' => 72]);
        Major::create(['kode' => 'ANM', 'code' => 'ANM', 'name' => 'Animasi', 'kuota' => 36]);

        // Waves
        Wave::create(['nama' => 'Gelombang 1', 'tgl_mulai' => '2024-01-01', 'tgl_selesai' => '2024-03-31', 'biaya_daftar' => 150000]);
        Wave::create(['nama' => 'Gelombang 2', 'tgl_mulai' => '2024-04-01', 'tgl_selesai' => '2024-06-30', 'biaya_daftar' => 200000]);
    }
}
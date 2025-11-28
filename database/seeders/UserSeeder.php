<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@ppdb.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Kepala Sekolah
        User::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@ppdb.com',
            'password' => Hash::make('kepsek123'),
            'role' => 'kepsek'
        ]);

        // Bagian Keuangan
        User::create([
            'name' => 'Bagian Keuangan',
            'email' => 'keuangan@ppdb.com',
            'password' => Hash::make('keuangan123'),
            'role' => 'keuangan'
        ]);

        // Verifikator Administrasi
        User::create([
            'name' => 'Verifikator Administrasi',
            'email' => 'verifikator@ppdb.com',
            'password' => Hash::make('verifikator123'),
            'role' => 'verifikator'
        ]);
    }
}
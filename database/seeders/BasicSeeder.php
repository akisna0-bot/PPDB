<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BasicSeeder extends Seeder
{
    public function run()
    {
        // Create admin user if not exists
        if (!DB::table('users')->where('email', 'admin@ppdb.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Administrator',
                'email' => 'admin@ppdb.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create majors if not exists
        if (DB::table('majors')->count() == 0) {
            DB::table('majors')->insert([
                [
                    'name' => 'Teknik Komputer dan Jaringan',
                    'code' => 'TKJ',
                    'kode' => 'TKJ',
                    'kuota' => 36,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Multimedia',
                    'code' => 'MM', 
                    'kode' => 'MM',
                    'kuota' => 36,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Akuntansi dan Keuangan Lembaga',
                    'code' => 'AKL',
                    'kode' => 'AKL',
                    'kuota' => 36,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // Create waves if not exists
        if (DB::table('waves')->count() == 0) {
            DB::table('waves')->insert([
                [
                    'nama' => 'Gelombang 1',
                    'tgl_mulai' => '2025-01-01',
                    'tgl_selesai' => '2025-03-31',
                    'biaya_daftar' => 150000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'nama' => 'Gelombang 2',
                    'tgl_mulai' => '2025-04-01',
                    'tgl_selesai' => '2025-06-30',
                    'biaya_daftar' => 200000,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}
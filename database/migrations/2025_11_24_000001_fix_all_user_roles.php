<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // 1. Update role yang tidak konsisten
        DB::table('users')
            ->where('role', 'verifikator_adm')
            ->update(['role' => 'verifikator']);

        // 2. Hapus user duplikat berdasarkan email
        $duplicates = DB::select("
            SELECT email, MIN(id) as keep_id 
            FROM users 
            GROUP BY email 
            HAVING COUNT(*) > 1
        ");

        foreach ($duplicates as $duplicate) {
            DB::table('users')
                ->where('email', $duplicate->email)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        // 3. Pastikan semua user admin ada dengan data yang benar
        $adminUsers = [
            [
                'name' => 'Administrator',
                'email' => 'admin@ppdb.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin'
            ],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@ppdb.com',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepsek'
            ],
            [
                'name' => 'Bagian Keuangan',
                'email' => 'keuangan@ppdb.com',
                'password' => Hash::make('keuangan123'),
                'role' => 'keuangan'
            ],
            [
                'name' => 'Verifikator Administrasi',
                'email' => 'verifikator@ppdb.com',
                'password' => Hash::make('verifikator123'),
                'role' => 'verifikator'
            ]
        ];

        foreach ($adminUsers as $userData) {
            DB::table('users')->updateOrInsert(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }
    }

    public function down()
    {
        // Rollback: ubah kembali ke verifikator_adm jika diperlukan
        DB::table('users')
            ->where('role', 'verifikator')
            ->update(['role' => 'verifikator_adm']);
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CompleteUserSeeder extends Seeder
{
    public function run()
    {
        $users = [
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

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
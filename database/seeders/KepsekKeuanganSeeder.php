<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KepsekKeuanganSeeder extends Seeder
{
    public function run()
    {
        // Create Kepala Sekolah
        User::updateOrCreate(
            ['email' => 'kepsek@smk.com'],
            [
                'name' => 'Kepala Sekolah',
                'email' => 'kepsek@smk.com',
                'password' => Hash::make('password'),
                'role' => 'kepsek',
                'email_verified_at' => now()
            ]
        );

        // Create Keuangan
        User::updateOrCreate(
            ['email' => 'keuangan@smk.com'],
            [
                'name' => 'Staff Keuangan',
                'email' => 'keuangan@smk.com',
                'password' => Hash::make('password'),
                'role' => 'keuangan',
                'email_verified_at' => now()
            ]
        );

        echo "✅ User kepsek dan keuangan berhasil dibuat!\n";
        echo "📧 Login kepsek: kepsek@smk.com / password\n";
        echo "💰 Login keuangan: keuangan@smk.com / password\n";
    }
}
<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create test users
$users = [
    [
        'name' => 'Administrator',
        'email' => 'admin@ppdb.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
    ],
    [
        'name' => 'Verifikator Admin',
        'email' => 'verifikator@ppdb.com',
        'password' => Hash::make('verifikator123'),
        'role' => 'verifikator',
    ],
    [
        'name' => 'Bagian Keuangan',
        'email' => 'keuangan@ppdb.com',
        'password' => Hash::make('keuangan123'),
        'role' => 'keuangan',
    ],
    [
        'name' => 'Kepala Sekolah',
        'email' => 'kepsek@ppdb.com',
        'password' => Hash::make('kepsek123'),
        'role' => 'kepsek',
    ]
];

foreach ($users as $user) {
    DB::table('users')->updateOrInsert(
        ['email' => $user['email']],
        array_merge($user, [
            'created_at' => now(),
            'updated_at' => now(),
        ])
    );
    echo "User {$user['email']} created successfully!\n";
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Admin: admin@ppdb.com / admin123\n";
echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
echo "Kepala Sekolah: kepsek@ppdb.com / kepsek123\n";
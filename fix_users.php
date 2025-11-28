<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update all users with proper data
$users = [
    [
        'name' => 'Administrator',
        'email' => 'admin@ppdb.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ],
    [
        'name' => 'Verifikator Admin',
        'email' => 'verifikator@ppdb.com',
        'password' => Hash::make('verifikator123'),
        'role' => 'verifikator',
        'email_verified_at' => now(),
    ],
    [
        'name' => 'Bagian Keuangan',
        'email' => 'keuangan@ppdb.com',
        'password' => Hash::make('keuangan123'),
        'role' => 'keuangan',
        'email_verified_at' => now(),
    ],
    [
        'name' => 'Kepala Sekolah',
        'email' => 'kepsek@ppdb.com',
        'password' => Hash::make('kepsek123'),
        'role' => 'kepsek',
        'email_verified_at' => now(),
    ]
];

// Clear existing users first
DB::table('users')->truncate();

foreach ($users as $user) {
    DB::table('users')->insert(array_merge($user, [
        'created_at' => now(),
        'updated_at' => now(),
    ]));
    echo "User {$user['email']} created/updated successfully!\n";
}

// Test password verification
echo "\n=== TESTING PASSWORD VERIFICATION ===\n";
foreach ($users as $user) {
    $dbUser = DB::table('users')->where('email', $user['email'])->first();
    $password = str_replace(['admin123', 'verifikator123', 'keuangan123', 'kepsek123'], 
                           ['admin123', 'verifikator123', 'keuangan123', 'kepsek123'], 
                           $user['email']);
    $password = explode('@', $password)[0] . '123';
    
    if (Hash::check($password, $dbUser->password)) {
        echo "✅ {$user['email']} password verification: OK\n";
    } else {
        echo "❌ {$user['email']} password verification: FAILED\n";
    }
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Admin: admin@ppdb.com / admin123\n";
echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
echo "Kepala Sekolah: kepsek@ppdb.com / kepsek123\n";
<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Update users safely
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
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ])
    );
    echo "User {$user['email']} updated successfully!\n";
}

// Test login for each user
echo "\n=== TESTING LOGIN ===\n";
foreach ($users as $user) {
    $dbUser = DB::table('users')->where('email', $user['email'])->first();
    if ($dbUser) {
        echo "✅ User {$user['email']} exists in database\n";
        echo "   - ID: {$dbUser->id}\n";
        echo "   - Name: {$dbUser->name}\n";
        echo "   - Role: {$dbUser->role}\n";
        echo "   - Email Verified: " . ($dbUser->email_verified_at ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ User {$user['email']} NOT found\n";
    }
}

echo "\n=== LOGIN CREDENTIALS ===\n";
echo "Admin: admin@ppdb.com / admin123\n";
echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
echo "Kepala Sekolah: kepsek@ppdb.com / kepsek123\n";
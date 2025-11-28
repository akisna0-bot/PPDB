<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create admin user
DB::table('users')->updateOrInsert(
    ['email' => 'admin@ppdb.com'],
    [
        'name' => 'Administrator',
        'email' => 'admin@ppdb.com',
        'password' => Hash::make('admin123'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now(),
    ]
);

echo "Admin user created successfully!\n";
echo "Email: admin@ppdb.com\n";
echo "Password: admin123\n";
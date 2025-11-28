<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MEMPERBAIKI LOGIN VERIFIKATOR ===\n\n";

try {
    // 1. Update role verifikator_adm menjadi verifikator
    echo "1. Mengupdate role verifikator_adm menjadi verifikator...\n";
    $updated = DB::table('users')
        ->where('role', 'verifikator_adm')
        ->update(['role' => 'verifikator']);
    echo "   Updated {$updated} users\n\n";

    // 2. Pastikan user verifikator ada
    echo "2. Memastikan user verifikator ada...\n";
    $verifikator = DB::table('users')->where('email', 'verifikator@ppdb.com')->first();
    
    if (!$verifikator) {
        echo "   User verifikator tidak ditemukan, membuat user baru...\n";
        DB::table('users')->insert([
            'name' => 'Verifikator Administrasi',
            'email' => 'verifikator@ppdb.com',
            'password' => Hash::make('verifikator123'),
            'role' => 'verifikator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "   User verifikator berhasil dibuat!\n";
    } else {
        echo "   User verifikator sudah ada\n";
        // Update role jika masih verifikator_adm
        if ($verifikator->role !== 'verifikator') {
            DB::table('users')
                ->where('id', $verifikator->id)
                ->update(['role' => 'verifikator']);
            echo "   Role user verifikator berhasil diupdate!\n";
        }
    }

    // 3. Tampilkan semua user admin/verifikator
    echo "\n3. Daftar user admin dan verifikator:\n";
    $adminUsers = DB::table('users')
        ->whereIn('role', ['admin', 'verifikator', 'kepsek', 'keuangan'])
        ->get();
    
    foreach ($adminUsers as $user) {
        echo "   - {$user->name} ({$user->email}) - Role: {$user->role}\n";
    }

    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "Sekarang Anda bisa login dengan:\n";
    echo "Email: verifikator@ppdb.com\n";
    echo "Password: verifikator123\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
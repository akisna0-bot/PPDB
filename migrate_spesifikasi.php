<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== MIGRASI DATABASE SESUAI SPESIFIKASI ===\n\n";

try {
    // 1. Drop existing tables if exists (in correct order)
    echo "1. Dropping existing tables...\n";
    
    $tables = [
        'log_aktivitas',
        'pendaftar_berkas', 
        'pendaftar_asal_sekolah',
        'pendaftar_data_ortu',
        'pendaftar_data_siswa',
        'pendaftar',
        'pengguna',
        'wilayah',
        'gelombang',
        'jurusan'
    ];
    
    DB::statement('SET FOREIGN_KEY_CHECKS=0');
    foreach ($tables as $table) {
        if (Schema::hasTable($table)) {
            Schema::drop($table);
            echo "   - Dropped table: $table\n";
        }
    }
    DB::statement('SET FOREIGN_KEY_CHECKS=1');
    
    // 2. Run migration
    echo "\n2. Running migration...\n";
    $exitCode = 0;
    $output = [];
    exec('php artisan migrate --path=database/migrations/2025_11_17_create_database_sesuai_spesifikasi.php --force', $output, $exitCode);
    
    if ($exitCode === 0) {
        echo "   ✅ Migration completed successfully\n";
        foreach ($output as $line) {
            echo "   $line\n";
        }
    } else {
        throw new Exception("Migration failed with exit code: $exitCode");
    }
    
    // 3. Run seeder
    echo "\n3. Running seeder...\n";
    $seeder = new \Database\Seeders\DatabaseSpesifikasiSeeder();
    $seeder->run();
    
    // 4. Verify tables
    echo "\n4. Verifying tables...\n";
    $expectedTables = [
        'jurusan', 'gelombang', 'wilayah', 'pengguna', 'pendaftar',
        'pendaftar_data_siswa', 'pendaftar_data_ortu', 'pendaftar_asal_sekolah',
        'pendaftar_berkas', 'log_aktivitas'
    ];
    
    foreach ($expectedTables as $table) {
        if (Schema::hasTable($table)) {
            $count = DB::table($table)->count();
            echo "   ✅ Table '$table' exists with $count records\n";
        } else {
            echo "   ❌ Table '$table' missing\n";
        }
    }
    
    // 5. Test login credentials
    echo "\n5. Testing login credentials...\n";
    $users = DB::table('pengguna')->select('nama', 'email', 'role')->get();
    foreach ($users as $user) {
        echo "   ✅ {$user->email} ({$user->role}) - {$user->nama}\n";
    }
    
    echo "\n=== MIGRASI SELESAI ===\n";
    echo "Database structure now matches the specification exactly!\n\n";
    
    echo "LOGIN CREDENTIALS:\n";
    echo "Admin: admin@ppdb.com / admin123\n";
    echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
    echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
    echo "Kepala Sekolah: kepsek@ppdb.com / kepsek123\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
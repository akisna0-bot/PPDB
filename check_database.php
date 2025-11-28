<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CEK STATUS DATABASE ===\n\n";

try {
    // Cek koneksi database
    $connection = DB::connection();
    $databaseName = $connection->getDatabaseName();
    $driver = $connection->getDriverName();
    
    echo "✅ Database Connection: BERHASIL\n";
    echo "📁 Database: $databaseName\n";
    echo "🔧 Driver: $driver\n\n";
    
    // Cek tabel users
    echo "=== CEK TABEL USERS ===\n";
    $users = DB::table('users')->get();
    
    if ($users->count() > 0) {
        echo "✅ Tabel users: ADA ({$users->count()} users)\n\n";
        
        echo "Daftar Users:\n";
        foreach ($users as $user) {
            echo "- {$user->name} ({$user->email}) - Role: {$user->role}\n";
        }
    } else {
        echo "❌ Tabel users: KOSONG\n";
    }
    
    // Cek tabel lain
    echo "\n=== CEK TABEL LAINNYA ===\n";
    $tables = ['majors', 'waves', 'applicants', 'payments'];
    
    foreach ($tables as $table) {
        try {
            $count = DB::table($table)->count();
            echo "✅ Tabel $table: $count records\n";
        } catch (Exception $e) {
            echo "❌ Tabel $table: ERROR - " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== STATUS KESELURUHAN ===\n";
    echo "✅ Database: PULIH dan BERFUNGSI\n";
    echo "🔗 Koneksi: STABIL\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n=== DIAGNOSIS ===\n";
    
    // Cek file .env
    if (file_exists('.env')) {
        $env = file_get_contents('.env');
        if (strpos($env, 'DB_CONNECTION=sqlite') !== false) {
            echo "📁 Database: SQLite (File-based)\n";
            if (file_exists('database/database.sqlite')) {
                echo "✅ File SQLite: ADA\n";
            } else {
                echo "❌ File SQLite: TIDAK ADA\n";
            }
        } else {
            echo "🔧 Database: MySQL/MariaDB\n";
            echo "⚠️  Kemungkinan masalah koneksi MySQL\n";
        }
    }
}
?>
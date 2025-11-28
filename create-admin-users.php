<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Hapus user admin lama jika ada
    DB::table('users')->whereIn('email', [
        'admin@ppdb.com', 
        'verifikator@ppdb.com', 
        'keuangan@ppdb.com', 
        'kepsek@ppdb.com'
    ])->delete();
    
    // Buat user admin baru
    $users = [
        [
            'name' => 'Administrator',
            'email' => 'admin@ppdb.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Verifikator Administrasi',
            'email' => 'verifikator@ppdb.com',
            'password' => Hash::make('verifikator123'),
            'role' => 'verifikator_adm',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Bagian Keuangan',
            'email' => 'keuangan@ppdb.com',
            'password' => Hash::make('keuangan123'),
            'role' => 'keuangan',
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@ppdb.com',
            'password' => Hash::make('kepsek123'),
            'role' => 'kepsek',
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    foreach ($users as $user) {
        DB::table('users')->insert($user);
    }
    
    echo "<h2>✅ Admin users berhasil dibuat!</h2>";
    echo "<h3>🔐 Login Credentials:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@ppdb.com / admin123</li>";
    echo "<li><strong>Verifikator:</strong> verifikator@ppdb.com / verifikator123</li>";
    echo "<li><strong>Keuangan:</strong> keuangan@ppdb.com / keuangan123</li>";
    echo "<li><strong>Kepsek:</strong> kepsek@ppdb.com / kepsek123</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
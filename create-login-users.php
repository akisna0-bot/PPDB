<?php
// Script untuk membuat user login yang benar
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    // Hapus user lama
    DB::table('pengguna')->whereIn('email', [
        'admin@smk.com', 
        'verifikator@smk.com', 
        'keuangan@smk.com', 
        'kepsek@smk.com'
    ])->delete();
    
    // Buat user baru dengan password hash yang benar
    $users = [
        [
            'nama' => 'Admin SMK',
            'email' => 'admin@smk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'admin',
            'aktif' => 1
        ],
        [
            'nama' => 'Verifikator SMK',
            'email' => 'verifikator@smk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'verifikator_adm',
            'aktif' => 1
        ],
        [
            'nama' => 'Keuangan SMK',
            'email' => 'keuangan@smk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'keuangan',
            'aktif' => 1
        ],
        [
            'nama' => 'Kepala Sekolah',
            'email' => 'kepsek@smk.com',
            'password_hash' => Hash::make('password'),
            'role' => 'kepsek',
            'aktif' => 1
        ]
    ];
    
    foreach ($users as $user) {
        DB::table('pengguna')->insert($user);
    }
    
    echo "<h2>✅ User berhasil dibuat dengan password hash Laravel!</h2>";
    echo "<h3>🔐 Login dengan:</h3>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> admin@smk.com / password</li>";
    echo "<li><strong>Verifikator:</strong> verifikator@smk.com / password</li>";
    echo "<li><strong>Keuangan:</strong> keuangan@smk.com / password</li>";
    echo "<li><strong>Kepsek:</strong> kepsek@smk.com / password</li>";
    echo "</ul>";
    echo "<p>Password untuk semua: <strong>password</strong></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
<?php
// Script untuk membuat user test langsung
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Hapus user lama jika ada
    User::whereIn('email', ['admin@smk.com', 'verifikator@smk.com', 'keuangan@smk.com', 'kepsek@smk.com'])->delete();
    
    // Buat user baru
    $users = [
        ['name' => 'Admin SMK', 'email' => 'admin@smk.com', 'role' => 'admin'],
        ['name' => 'Verifikator SMK', 'email' => 'verifikator@smk.com', 'role' => 'verifikator'],
        ['name' => 'Keuangan SMK', 'email' => 'keuangan@smk.com', 'role' => 'keuangan'],
        ['name' => 'Kepala Sekolah', 'email' => 'kepsek@smk.com', 'role' => 'kepsek']
    ];
    
    foreach ($users as $userData) {
        User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'password' => Hash::make('password'),
            'role' => $userData['role'],
            'email_verified_at' => now()
        ]);
    }
    
    echo "<h2>✅ User berhasil dibuat!</h2>";
    echo "<p><strong>Login dengan:</strong></p>";
    echo "<ul>";
    echo "<li>Admin: admin@smk.com / password</li>";
    echo "<li>Verifikator: verifikator@smk.com / password</li>";
    echo "<li>Keuangan: keuangan@smk.com / password</li>";
    echo "<li>Kepsek: kepsek@smk.com / password</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
<?php

// Script sederhana untuk membuat user verifikator
// Jalankan dengan: php create_verifikator_simple.php

$host = '127.0.0.1';
$dbname = 'ppdb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MEMPERBAIKI LOGIN VERIFIKATOR ===\n\n";
    
    // 1. Update role verifikator_adm menjadi verifikator
    echo "1. Mengupdate role verifikator_adm menjadi verifikator...\n";
    $stmt = $pdo->prepare("UPDATE users SET role = 'verifikator' WHERE role = 'verifikator_adm'");
    $stmt->execute();
    echo "   Updated " . $stmt->rowCount() . " users\n\n";
    
    // 2. Cek apakah user verifikator sudah ada
    echo "2. Mengecek user verifikator...\n";
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'verifikator@ppdb.com'");
    $stmt->execute();
    $user = $stmt->fetch();
    
    if (!$user) {
        echo "   User verifikator tidak ditemukan, membuat user baru...\n";
        // Password hash untuk 'verifikator123'
        $passwordHash = password_hash('verifikator123', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
        $stmt->execute([
            'Verifikator Administrasi',
            'verifikator@ppdb.com',
            $passwordHash,
            'verifikator'
        ]);
        echo "   User verifikator berhasil dibuat!\n";
    } else {
        echo "   User verifikator sudah ada\n";
        // Update role jika perlu
        if ($user['role'] !== 'verifikator') {
            $stmt = $pdo->prepare("UPDATE users SET role = 'verifikator' WHERE id = ?");
            $stmt->execute([$user['id']]);
            echo "   Role user verifikator berhasil diupdate!\n";
        }
        
        // Update password jika perlu
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'verifikator@ppdb.com'");
        $passwordHash = password_hash('verifikator123', PASSWORD_DEFAULT);
        $stmt->execute([$passwordHash]);
        echo "   Password user verifikator berhasil diupdate!\n";
    }
    
    // 3. Tampilkan semua user admin
    echo "\n3. Daftar user admin dan verifikator:\n";
    $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan')");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    foreach ($users as $user) {
        echo "   - {$user['name']} ({$user['email']}) - Role: {$user['role']}\n";
    }
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "Sekarang Anda bisa login dengan:\n";
    echo "Email: verifikator@ppdb.com\n";
    echo "Password: verifikator123\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nPastikan:\n";
    echo "1. XAMPP MySQL/MariaDB sudah berjalan\n";
    echo "2. Database 'ppdb' sudah ada\n";
    echo "3. User 'root' bisa mengakses database\n";
}
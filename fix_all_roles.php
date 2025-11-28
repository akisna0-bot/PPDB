<?php

// Script untuk memperbaiki semua role login
$host = '127.0.0.1';
$dbname = 'ppdb';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== MEMPERBAIKI SEMUA ROLE LOGIN ===\n\n";
    
    // Data user yang akan dibuat/diupdate
    $users = [
        [
            'name' => 'Administrator',
            'email' => 'admin@ppdb.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin'
        ],
        [
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@ppdb.com',
            'password' => password_hash('kepsek123', PASSWORD_DEFAULT),
            'role' => 'kepsek'
        ],
        [
            'name' => 'Bagian Keuangan',
            'email' => 'keuangan@ppdb.com',
            'password' => password_hash('keuangan123', PASSWORD_DEFAULT),
            'role' => 'keuangan'
        ],
        [
            'name' => 'Verifikator Administrasi',
            'email' => 'verifikator@ppdb.com',
            'password' => password_hash('verifikator123', PASSWORD_DEFAULT),
            'role' => 'verifikator'
        ]
    ];
    
    // 1. Update role yang salah
    echo "1. Mengupdate role yang tidak konsisten...\n";
    $stmt = $pdo->prepare("UPDATE users SET role = 'verifikator' WHERE role = 'verifikator_adm'");
    $stmt->execute();
    echo "   Updated verifikator_adm -> verifikator: " . $stmt->rowCount() . " users\n\n";
    
    // 2. Buat/update semua user
    echo "2. Membuat/mengupdate semua user admin...\n";
    foreach ($users as $userData) {
        // Cek apakah user sudah ada
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$userData['email']]);
        $existingUser = $stmt->fetch();
        
        if ($existingUser) {
            // Update user yang sudah ada
            $stmt = $pdo->prepare("UPDATE users SET name = ?, password = ?, role = ?, updated_at = NOW() WHERE email = ?");
            $stmt->execute([
                $userData['name'],
                $userData['password'],
                $userData['role'],
                $userData['email']
            ]);
            echo "   Updated: {$userData['name']} ({$userData['email']})\n";
        } else {
            // Buat user baru
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([
                $userData['name'],
                $userData['email'],
                $userData['password'],
                $userData['role']
            ]);
            echo "   Created: {$userData['name']} ({$userData['email']})\n";
        }
    }
    
    // 3. Tampilkan semua user admin
    echo "\n3. Daftar semua user admin:\n";
    $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE role IN ('admin', 'verifikator', 'kepsek', 'keuangan') ORDER BY role");
    $stmt->execute();
    $adminUsers = $stmt->fetchAll();
    
    foreach ($adminUsers as $user) {
        echo "   - {$user['name']} ({$user['email']}) - Role: {$user['role']}\n";
    }
    
    echo "\n=== PERBAIKAN SELESAI ===\n";
    echo "\nKredensial Login:\n";
    echo "Admin: admin@ppdb.com / admin123\n";
    echo "Kepsek: kepsek@ppdb.com / kepsek123\n";
    echo "Keuangan: keuangan@ppdb.com / keuangan123\n";
    echo "Verifikator: verifikator@ppdb.com / verifikator123\n";
    
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nPastikan XAMPP MySQL/MariaDB sudah berjalan!\n";
}
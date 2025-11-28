<?php
// Script langsung tanpa Laravel untuk membuat user admin

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ppdb";

// Coba berbagai cara koneksi
$connections = [
    "mysql:host=localhost;dbname=$dbname",
    "mysql:host=127.0.0.1;dbname=$dbname",
    "mysql:host=localhost;port=3306;dbname=$dbname",
    "mysql:host=127.0.0.1;port=3306;dbname=$dbname"
];

foreach ($connections as $dsn) {
    try {
        echo "Mencoba koneksi: $dsn\n";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "Koneksi berhasil!\n";
        
        // Buat user admin
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (name, email, password, role, created_at, updated_at) 
                VALUES (?, ?, ?, ?, NOW(), NOW()) 
                ON DUPLICATE KEY UPDATE 
                password = VALUES(password), role = VALUES(role)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'Administrator',
            'admin@ppdb.com', 
            $adminPassword,
            'admin'
        ]);
        
        echo "User admin berhasil dibuat/diupdate!\n";
        echo "Login: admin@ppdb.com / admin123\n";
        break;
        
    } catch(PDOException $e) {
        echo "Gagal: " . $e->getMessage() . "\n\n";
        continue;
    }
}
?>
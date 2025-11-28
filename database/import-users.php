<?php
// Script untuk import user via browser
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ppdb';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Baca file SQL
    $sql = file_get_contents(__DIR__ . '/create-users.sql');
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "<h2>✅ User berhasil dibuat!</h2>";
    echo "<p><strong>Login dengan akun berikut:</strong></p>";
    echo "<ul>";
    echo "<li>Admin: admin@smk.com / password</li>";
    echo "<li>Verifikator: verifikator@smk.com / password</li>";
    echo "<li>Keuangan: keuangan@smk.com / password</li>";
    echo "<li>Kepsek: kepsek@smk.com / password</li>";
    echo "</ul>";
    echo "<p>Password untuk semua akun: <strong>password</strong></p>";
    
} catch(PDOException $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
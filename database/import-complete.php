<?php
// Script import database lengkap
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Baca dan execute SQL
    $sql = file_get_contents(__DIR__ . '/ppdb-complete.sql');
    $pdo->exec($sql);
    
    echo "<h2>✅ Database PPDB berhasil dibuat!</h2>";
    echo "<h3>📊 Struktur Database:</h3>";
    echo "<ul>";
    echo "<li><strong>Referensi:</strong> jurusan, gelombang, wilayah, pengguna</li>";
    echo "<li><strong>Pendaftaran:</strong> pendaftar, data_siswa, data_ortu, asal_sekolah, berkas</li>";
    echo "<li><strong>Audit:</strong> log_aktivitas</li>";
    echo "</ul>";
    
    echo "<h3>🔐 Akun Login:</h3>";
    echo "<ul>";
    echo "<li>Admin: admin@smk.com / password</li>";
    echo "<li>Verifikator: verifikator@smk.com / password</li>";
    echo "<li>Keuangan: keuangan@smk.com / password</li>";
    echo "<li>Kepsek: kepsek@smk.com / password</li>";
    echo "<li>Siswa Test: siswa@test.com / password</li>";
    echo "</ul>";
    
    echo "<h3>📋 Data Sample:</h3>";
    echo "<ul>";
    echo "<li>4 Jurusan (TKJ, RPL, MM, OTKP)</li>";
    echo "<li>2 Gelombang pendaftaran</li>";
    echo "<li>5 Wilayah Bandung & Cimahi</li>";
    echo "<li>1 Pendaftar sample lengkap</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<h2>❌ Error: " . $e->getMessage() . "</h2>";
}
?>
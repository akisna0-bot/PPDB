<?php
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    
    echo "=== MEMBUAT DATABASE PPDB COMPLETE ===\n\n";
    
    // Baca file SQL
    $sql = file_get_contents('database_design_complete.sql');
    
    // Split berdasarkan ; dan jalankan satu per satu
    $statements = explode(';', $sql);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement)) {
            try {
                $pdo->exec($statement);
                echo "✅ Executed: " . substr($statement, 0, 50) . "...\n";
            } catch (Exception $e) {
                if (strpos($e->getMessage(), 'already exists') === false) {
                    echo "⚠️  Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "\n🎉 DATABASE BERHASIL DIBUAT!\n";
    echo "📊 Semua tabel dan relasi sudah siap digunakan\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
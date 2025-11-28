<?php
// Database Import Helper
// Run this file in browser: http://localhost/ppdb/database/import-helper.php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'ppdb';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Import Helper</h2>";
    
    // Read and execute SQL file
    $sqlFile = __DIR__ . '/create-fresh-db.sql';
    
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into individual queries
    $queries = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "<p>Executing " . count($queries) . " queries...</p>";
    
    foreach ($queries as $query) {
        if (!empty($query)) {
            try {
                $pdo->exec($query);
                echo "<div style='color: green;'>✓ Query executed successfully</div>";
            } catch (PDOException $e) {
                echo "<div style='color: red;'>✗ Error: " . $e->getMessage() . "</div>";
                echo "<div style='color: gray; font-size: 12px;'>Query: " . substr($query, 0, 100) . "...</div>";
            }
        }
    }
    
    echo "<h3 style='color: green;'>Database setup completed!</h3>";
    echo "<p>Default login credentials:</p>";
    echo "<ul>";
    echo "<li>Admin: admin@smkbn666.sch.id / password</li>";
    echo "<li>Verifikator: verifikator@smkbn666.sch.id / password</li>";
    echo "<li>Keuangan: keuangan@smkbn666.sch.id / password</li>";
    echo "<li>Kepsek: kepsek@smkbn666.sch.id / password</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='color: red; padding: 10px; border: 1px solid red;'>";
    echo "<h3>Error:</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "</div>";
}
?>
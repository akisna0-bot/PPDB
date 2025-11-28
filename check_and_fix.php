<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

$capsule = new DB;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'ppdb',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== CEK DAN PERBAIKI DATA APPLICANTS ===\n\n";

try {
    // Cek semua data
    echo "1. Data applicants saat ini:\n";
    $all = DB::table('applicants')->get();
    foreach ($all as $app) {
        echo "   ID: {$app->id}, Status: '{$app->status}', User: {$app->user_id}\n";
    }
    
    // Perbaiki status kosong
    echo "\n2. Memperbaiki status kosong...\n";
    $fixed = DB::table('applicants')
        ->where('status', '')
        ->orWhereNull('status')
        ->update(['status' => 'SUBMIT']);
    echo "   - Fixed $fixed records dengan status kosong\n";
    
    // Cek hasil akhir
    echo "\n3. Status setelah perbaikan:\n";
    $statuses = DB::table('applicants')
        ->select('status', DB::raw('COUNT(*) as jumlah'))
        ->groupBy('status')
        ->get();
    
    foreach ($statuses as $status) {
        echo "   - '{$status->status}': {$status->jumlah} records\n";
    }
    
    echo "\n✅ SELESAI!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
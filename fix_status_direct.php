<?php
require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as DB;

// Setup database connection
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

echo "=== MEMPERBAIKI STATUS ENUM PPDB ===\n\n";

try {
    // Update status lama ke format baru
    echo "1. Mengupdate status lama ke format baru...\n";
    
    $updated1 = DB::table('applicants')
        ->whereIn('status', ['submitted', 'DRAFT'])
        ->update(['status' => 'SUBMIT']);
    echo "   - Updated $updated1 records to SUBMIT\n";
    
    $updated2 = DB::table('applicants')
        ->whereIn('status', ['verified', 'ADM_PASS'])
        ->update(['status' => 'VERIFIED']);
    echo "   - Updated $updated2 records to VERIFIED\n";
    
    $updated3 = DB::table('applicants')
        ->whereIn('status', ['rejected', 'need_revision', 'ADM_REJECT'])
        ->update(['status' => 'REJECTED']);
    echo "   - Updated $updated3 records to REJECTED\n";
    
    // Cek hasil
    echo "\n2. Status setelah perbaikan:\n";
    $statuses = DB::table('applicants')
        ->select('status', DB::raw('COUNT(*) as jumlah'))
        ->groupBy('status')
        ->get();
    
    foreach ($statuses as $status) {
        echo "   - {$status->status}: {$status->jumlah} records\n";
    }
    
    echo "\n✅ PERBAIKAN SELESAI!\n";
    echo "Status yang digunakan sekarang: SUBMIT, VERIFIED, REJECTED\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
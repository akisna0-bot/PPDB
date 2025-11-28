<?php

require_once 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'ppdb',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();

echo "=== CLEANING DUPLICATE USERS ===\n\n";

try {
    // Check for duplicates by email
    $duplicates = Capsule::select("
        SELECT email, COUNT(*) as count 
        FROM users 
        GROUP BY email 
        HAVING COUNT(*) > 1
    ");
    
    if (count($duplicates) > 0) {
        echo "Found duplicates:\n";
        foreach ($duplicates as $dup) {
            echo "- {$dup->email}: {$dup->count} entries\n";
            
            // Keep only the latest one
            $users = Capsule::table('users')
                ->where('email', $dup->email)
                ->orderBy('id', 'desc')
                ->get();
            
            // Delete older duplicates
            for ($i = 1; $i < count($users); $i++) {
                Capsule::table('users')->where('id', $users[$i]->id)->delete();
                echo "  Deleted duplicate ID: {$users[$i]->id}\n";
            }
        }
    } else {
        echo "✅ No duplicates found\n";
    }
    
    echo "\n=== FINAL USER LIST ===\n";
    $users = Capsule::table('users')->orderBy('role')->get(['id', 'name', 'email', 'role']);
    
    foreach ($users as $user) {
        echo "ID: {$user->id} | {$user->role} | {$user->email} | {$user->name}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
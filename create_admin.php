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

echo "Creating admin user...\n";

try {
    // Check if admin exists
    $existingAdmin = Capsule::table('users')->where('email', 'admin@ppdb.com')->first();
    
    if ($existingAdmin) {
        echo "Admin already exists, updating...\n";
        Capsule::table('users')
            ->where('email', 'admin@ppdb.com')
            ->update([
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => password_hash('password', PASSWORD_DEFAULT),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    } else {
        echo "Creating new admin...\n";
        Capsule::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@ppdb.com',
            'role' => 'admin',
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    
    echo "✅ Admin user created/updated successfully!\n";
    echo "Email: admin@ppdb.com\n";
    echo "Password: password\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
<?php
// Script untuk mengatasi error 419 Page Expired

// 1. Clear semua cache
echo "Clearing cache...\n";
exec('php artisan cache:clear');
exec('php artisan config:clear');
exec('php artisan route:clear');
exec('php artisan view:clear');

// 2. Generate APP_KEY baru
echo "Generating new APP_KEY...\n";
exec('php artisan key:generate');

// 3. Set session lifetime lebih lama
$envFile = '.env';
$envContent = file_get_contents($envFile);

// Update session lifetime
$envContent = preg_replace('/SESSION_LIFETIME=\d+/', 'SESSION_LIFETIME=1440', $envContent);

// Pastikan session driver file
if (strpos($envContent, 'SESSION_DRIVER=') === false) {
    $envContent .= "\nSESSION_DRIVER=file\n";
} else {
    $envContent = preg_replace('/SESSION_DRIVER=\w+/', 'SESSION_DRIVER=file', $envContent);
}

file_put_contents($envFile, $envContent);

echo "✅ CSRF fix completed!\n";
echo "Sekarang coba login lagi.\n";
?>
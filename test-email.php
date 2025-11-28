<?php
require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\Mail;

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Test kirim email
try {
    Mail::raw('Test email dari Laravel PPDB', function ($message) {
        $message->to('emailtujuan@gmail.com')
                ->subject('Test Email PPDB');
    });
    
    echo "✅ Email berhasil dikirim!";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
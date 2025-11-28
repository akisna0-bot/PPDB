<?php
require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Applicant;
use App\Models\User;

echo "<h2>Debug Data PPDB</h2>";

echo "<h3>Total Users:</h3>";
$users = User::all();
echo "Total: " . $users->count() . "<br>";
foreach($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}<br>";
}

echo "<h3>Total Applicants:</h3>";
$applicants = Applicant::with('user')->get();
echo "Total: " . $applicants->count() . "<br>";
foreach($applicants as $app) {
    echo "ID: {$app->id}, No: {$app->no_pendaftaran}, Nama: {$app->nama_lengkap}, User: " . ($app->user->name ?? 'NULL') . ", Status: {$app->status}<br>";
}

echo "<h3>Applicants with User relation:</h3>";
$applicantsWithUser = Applicant::whereHas('user')->with('user')->get();
echo "Total with user: " . $applicantsWithUser->count() . "<br>";
foreach($applicantsWithUser as $app) {
    echo "ID: {$app->id}, No: {$app->no_pendaftaran}, Nama: {$app->nama_lengkap}, User: {$app->user->name}, Status: {$app->status}<br>";
}
?>
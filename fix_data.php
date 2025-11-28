<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Major;
use App\Models\Wave;

// Hapus majors yang kosong
Major::where('code', '')->delete();

// Tambah majors
$majors = [
    ['code' => 'PPLG', 'name' => 'Pengembangan Perangkat Lunak dan Gim', 'kuota' => 72],
    ['code' => 'AKT', 'name' => 'Akuntansi', 'kuota' => 72],
    ['code' => 'ANM', 'name' => 'Animasi', 'kuota' => 36],
    ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual', 'kuota' => 36],
    ['code' => 'PMS', 'name' => 'Pemasaran', 'kuota' => 72],
];

foreach ($majors as $major) {
    Major::updateOrCreate(['code' => $major['code']], $major);
}

echo "Data berhasil diperbaiki!\n";
echo "Majors: " . Major::count() . "\n";
echo "Waves: " . Wave::count() . "\n";
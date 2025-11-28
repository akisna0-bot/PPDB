<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Wave;

class WaveSeeder extends Seeder
{
    public function run()
    {
        Wave::create([
            'nama' => 'Gelombang 1',
            'tgl_mulai' => '2025-01-01',
            'tgl_selesai' => '2025-03-31',
            'biaya_daftar' => 150000.00
        ]);

        Wave::create([
            'nama' => 'Gelombang 2',
            'tgl_mulai' => '2025-04-01',
            'tgl_selesai' => '2025-06-30',
            'biaya_daftar' => 200000.00
        ]);
    }
}
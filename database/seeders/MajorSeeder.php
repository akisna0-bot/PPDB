<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Major;

class MajorSeeder extends Seeder
{
    public function run()
    {
        Major::create([
            'code' => 'PPLG',
            'kode' => 'PPLG',
            'name' => 'Pengembangan Perangkat Lunak dan Gim',
            'kuota' => 72
        ]);

        Major::create([
            'code' => 'AKT',
            'kode' => 'AKT',
            'name' => 'Akuntansi dan Keuangan Lembaga',
            'kuota' => 72
        ]);

        Major::create([
            'code' => 'ANM',
            'kode' => 'ANM',
            'name' => 'Animasi',
            'kuota' => 36
        ]);

        Major::create([
            'code' => 'DKV',
            'kode' => 'DKV',
            'name' => 'Desain Komunikasi Visual',
            'kuota' => 36
        ]);

        Major::create([
            'code' => 'PMS',
            'kode' => 'PMS',
            'name' => 'Pemasaran',
            'kuota' => 72
        ]);
    }
}
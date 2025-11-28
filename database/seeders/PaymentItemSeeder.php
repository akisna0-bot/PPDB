<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentItem;

class PaymentItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'Biaya Pendaftaran',
                'description' => 'Biaya administrasi pendaftaran PPDB',
                'price' => 150000,
                'category' => 'registration',
                'is_required' => true,
                'is_active' => true
            ],
            [
                'name' => 'Seragam Sekolah',
                'description' => 'Paket seragam lengkap (putih abu-abu, olahraga, pramuka)',
                'price' => 500000,
                'category' => 'uniform',
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Buku Paket',
                'description' => 'Buku pelajaran semester 1',
                'price' => 300000,
                'category' => 'books',
                'is_required' => false,
                'is_active' => true
            ],
            [
                'name' => 'Praktikum',
                'description' => 'Biaya praktikum dan alat-alat praktik',
                'price' => 200000,
                'category' => 'practical',
                'is_required' => false,
                'is_active' => true
            ]
        ];

        foreach ($items as $item) {
            PaymentItem::updateOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
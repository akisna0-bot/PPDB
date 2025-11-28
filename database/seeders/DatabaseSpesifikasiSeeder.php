<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSpesifikasiSeeder extends Seeder
{
    public function run()
    {
        // 1. Seeder jurusan
        DB::table('jurusan')->insert([
            [
                'kode' => 'TKJ',
                'nama' => 'Teknik Komputer dan Jaringan',
                'kuota' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'MM',
                'nama' => 'Multimedia',
                'kuota' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'AKL',
                'nama' => 'Akuntansi dan Keuangan Lembaga',
                'kuota' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Seeder gelombang
        DB::table('gelombang')->insert([
            [
                'nama' => 'Gelombang 1',
                'tahun' => 2025,
                'tgl_mulai' => '2025-01-01',
                'tgl_selesai' => '2025-03-31',
                'biaya_daftar' => 150000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Gelombang 2',
                'tahun' => 2025,
                'tgl_mulai' => '2025-04-01',
                'tgl_selesai' => '2025-06-30',
                'biaya_daftar' => 200000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Seeder wilayah (contoh data Bandung)
        DB::table('wilayah')->insert([
            [
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Kabupaten Bandung',
                'kecamatan' => 'Cileunyi',
                'kelurahan' => 'Cileunyi Kulon',
                'kodepos' => '40622',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Kabupaten Bandung',
                'kecamatan' => 'Cileunyi',
                'kelurahan' => 'Cileunyi Wetan',
                'kodepos' => '40623',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'provinsi' => 'Jawa Barat',
                'kabupaten' => 'Kabupaten Bandung',
                'kecamatan' => 'Rancaekek',
                'kelurahan' => 'Rancaekek Kulon',
                'kodepos' => '40394',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 4. Seeder pengguna
        DB::table('pengguna')->insert([
            [
                'nama' => 'Administrator',
                'email' => 'admin@ppdb.com',
                'hp' => '081234567890',
                'password_hash' => Hash::make('admin123'),
                'role' => 'admin',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Verifikator Administrasi',
                'email' => 'verifikator@ppdb.com',
                'hp' => '081234567891',
                'password_hash' => Hash::make('verifikator123'),
                'role' => 'verifikator_adm',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Bagian Keuangan',
                'email' => 'keuangan@ppdb.com',
                'hp' => '081234567892',
                'password_hash' => Hash::make('keuangan123'),
                'role' => 'keuangan',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Kepala Sekolah',
                'email' => 'kepsek@ppdb.com',
                'hp' => '081234567893',
                'password_hash' => Hash::make('kepsek123'),
                'role' => 'kepsek',
                'aktif' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        echo "Database seeded successfully with spesifikasi structure!\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class FinalSeeder extends Seeder
{
    public function run()
    {
        // Hapus data dummy dengan foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        DB::table('applicant_files')->delete();
        DB::table('payments')->delete();
        DB::table('applicants')->delete();
        
        if (DB::getSchemaBuilder()->hasTable('log_aktivitas')) {
            DB::table('log_aktivitas')->delete();
        }
        
        // Bersihkan majors yang kosong
        DB::table('majors')->where('code', '')->delete();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Users dengan role sesuai spesifikasi
        User::updateOrCreate(
            ['email' => 'kepsek@smkbn666.sch.id'],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('password123'),
                'role' => 'kepsek',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'verifikator@smkbn666.sch.id'],
            [
                'name' => 'Verifikator Administrasi',
                'password' => Hash::make('password123'),
                'role' => 'verifikator_adm',
            ]
        );
        
        User::updateOrCreate(
            ['email' => 'keuangan@smkbn666.sch.id'],
            [
                'name' => 'Staff Keuangan',
                'password' => Hash::make('password123'),
                'role' => 'keuangan',
            ]
        );

        // Update majors yang sudah ada
        $majors = [
            ['code' => 'PPLG', 'name' => 'Pengembangan Perangkat Lunak dan Gim', 'kuota' => 72],
            ['code' => 'AKT', 'name' => 'Akuntansi', 'kuota' => 72],
            ['code' => 'ANM', 'name' => 'Animasi', 'kuota' => 36],
            ['code' => 'DKV', 'name' => 'Desain Komunikasi Visual', 'kuota' => 36],
            ['code' => 'PMS', 'name' => 'Pemasaran', 'kuota' => 72],
        ];
        
        foreach ($majors as $major) {
            Major::updateOrCreate(
                ['code' => $major['code']],
                $major
            );
        }

        // Gelombang pendaftaran aktif
        Wave::updateOrCreate(
            ['nama' => 'Gelombang 1 - 2025'],
            [
                'tgl_mulai' => '2025-01-01',
                'tgl_selesai' => '2025-03-31',
                'biaya_daftar' => 150000
            ]
        );
        
        Wave::updateOrCreate(
            ['nama' => 'Gelombang 2 - 2025'],
            [
                'tgl_mulai' => '2025-04-01',
                'tgl_selesai' => '2025-06-30',
                'biaya_daftar' => 200000
            ]
        );

        echo "✅ Sistem PPDB siap digunakan!\n";
        echo "📋 Data dummy dihapus, workflow terintegrasi\n";
        echo "🔑 kepsek@smkbn666.sch.id / password123\n";
        echo "🔑 verifikator@smkbn666.sch.id / password123\n";
        echo "🔑 keuangan@smkbn666.sch.id / password123\n";
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SPMBSeeder extends Seeder
{
    public function run()
    {
        // Hapus data dummy yang ada
        DB::table('applicants')->delete();
        DB::table('payments')->delete();
        DB::table('log_aktivitas')->delete();
        DB::table('applicant_files')->delete();
        
        // Users dengan role sesuai spesifikasi
        User::firstOrCreate(
            ['email' => 'kepsek@smkbn666.sch.id'],
            [
                'name' => 'Kepala Sekolah',
                'password' => Hash::make('kepsek123'),
                'role' => 'kepsek',
            ]
        );
        
        User::firstOrCreate(
            ['email' => 'verifikator@smkbn666.sch.id'],
            [
                'name' => 'Verifikator Administrasi',
                'password' => Hash::make('verif123'),
                'role' => 'verifikator_adm',
            ]
        );
        
        User::firstOrCreate(
            ['email' => 'keuangan@smkbn666.sch.id'],
            [
                'name' => 'Staff Keuangan',
                'password' => Hash::make('keuangan123'),
                'role' => 'keuangan',
            ]
        );

        // Jurusan sesuai spesifikasi
        Major::updateOrCreate(
            ['code' => 'PPLG'], 
            ['name' => 'Pengembangan Perangkat Lunak dan Gim', 'kuota' => 72]
        );
        Major::updateOrCreate(
            ['code' => 'AKT'], 
            ['name' => 'Akuntansi', 'kuota' => 72]
        );
        Major::updateOrCreate(
            ['code' => 'ANM'], 
            ['name' => 'Animasi', 'kuota' => 36]
        );
        Major::updateOrCreate(
            ['code' => 'DKV'], 
            ['name' => 'Desain Komunikasi Visual', 'kuota' => 36]
        );
        Major::updateOrCreate(
            ['code' => 'PMS'], 
            ['name' => 'Pemasaran', 'kuota' => 72]
        );

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

        // Wilayah Kabupaten Bandung (data master)
        DB::table('wilayah')->insertOrIgnore([
            ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Cileunyi', 'kelurahan' => 'Cileunyi Kulon', 'kodepos' => '40622'],
            ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Cileunyi', 'kelurahan' => 'Cileunyi Wetan', 'kodepos' => '40623'],
            ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Rancaekek', 'kelurahan' => 'Rancaekek Kulon', 'kodepos' => '40394'],
            ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Rancaekek', 'kelurahan' => 'Rancaekek Wetan', 'kodepos' => '40395'],
            ['provinsi' => 'Jawa Barat', 'kabupaten' => 'Kabupaten Bandung', 'kecamatan' => 'Majalaya', 'kelurahan' => 'Majalaya', 'kodepos' => '40382'],
        ]);
        
        echo "✅ Data dummy dihapus, sistem siap untuk pendaftaran siswa baru\n";
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Applicant;
use App\Models\Major;
use App\Models\Wave;

class FixAllRoles extends Command
{
    protected $signature = 'system:fix-all';
    protected $description = 'Perbaiki semua fitur dan data sistem';

    public function handle()
    {
        $this->info('🔧 Memperbaiki sistem PPDB...');
        
        // 1. Pastikan major dan wave ada
        $this->ensureMasterData();
        
        // 2. Perbaiki user roles
        $this->fixUserRoles();
        
        // 3. Perbaiki data applicant
        $this->fixApplicantData();
        
        $this->info('✅ Sistem berhasil diperbaiki!');
        $this->displayCredentials();
    }
    
    private function ensureMasterData()
    {
        // Update major yang sudah ada
        Major::where('code', 'AKT')->update(['name' => 'Akuntansi dan Keuangan Lembaga']);
        Major::where('code', 'PMS')->update(['code' => 'PM', 'name' => 'Pemasaran']);
        
        // Pastikan semua major punya kuota
        Major::whereNull('kuota')->update(['kuota' => 36]);
        
        // Pastikan ada wave
        if (!Wave::find(1)) {
            Wave::create([
                'name' => 'Gelombang 1',
                'nama' => 'Gelombang 1',
                'tgl_mulai' => '2025-01-01',
                'tgl_selesai' => '2025-03-31',
                'biaya' => 150000,
                'biaya_daftar' => 150000
            ]);
        }
        
        if (!Wave::find(2)) {
            Wave::create([
                'name' => 'Gelombang 2', 
                'nama' => 'Gelombang 2',
                'tgl_mulai' => '2025-04-01',
                'tgl_selesai' => '2025-06-30',
                'biaya' => 200000,
                'biaya_daftar' => 200000
            ]);
        }
        
        $this->info('✅ Master data diperbaiki');
    }
    
    private function fixUserRoles()
    {
        // Perbaiki user dengan role yang salah
        User::where('role', 'siswa')->update(['role' => 'user']);
        
        // Pastikan user utama ada
        $mainUsers = [
            ['email' => 'admin@ppdb.com', 'name' => 'Administrator', 'role' => 'admin'],
            ['email' => 'verifikator@ppdb.com', 'name' => 'Verifikator Administrasi', 'role' => 'verifikator'],
            ['email' => 'kepsek@smk.com', 'name' => 'Kepala Sekolah', 'role' => 'kepsek'],
            ['email' => 'keuangan@smk.com', 'name' => 'Staff Keuangan', 'role' => 'keuangan']
        ];
        
        foreach ($mainUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                array_merge($userData, [
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ])
            );
        }
        
        $this->info('✅ User roles diperbaiki');
    }
    
    private function fixApplicantData()
    {
        // Perbaiki applicant tanpa major_id atau wave_id
        $applicants = Applicant::whereNull('major_id')->orWhereNull('wave_id')->get();
        
        foreach ($applicants as $applicant) {
            $applicant->update([
                'major_id' => $applicant->major_id ?? 1,
                'wave_id' => $applicant->wave_id ?? 1
            ]);
        }
        
        $this->info('✅ Data applicant diperbaiki');
    }
    
    private function displayCredentials()
    {
        $this->info("\n📋 KREDENSIAL LOGIN:");
        $this->info("👤 Admin: admin@ppdb.com / password");
        $this->info("🔍 Verifikator: verifikator@ppdb.com / password");
        $this->info("🏫 Kepsek: kepsek@smk.com / password");
        $this->info("💰 Keuangan: keuangan@smk.com / password");
    }
}
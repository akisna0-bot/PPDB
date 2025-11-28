<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Applicant;

class CleanDuplicateData extends Command
{
    protected $signature = 'data:clean-duplicates';
    protected $description = 'Bersihkan data duplikat di database';

    public function handle()
    {
        $this->info('🧹 Membersihkan data duplikat...');
        
        // Hapus user duplikat berdasarkan email
        $duplicateUsers = User::select('email')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');
            
        foreach ($duplicateUsers as $email) {
            $users = User::where('email', $email)->orderBy('id')->get();
            $keepUser = $users->first();
            
            for ($i = 1; $i < $users->count(); $i++) {
                $duplicateUser = $users[$i];
                
                // Update applicant yang terkait
                Applicant::where('user_id', $duplicateUser->id)
                    ->update(['user_id' => $keepUser->id]);
                
                // Hapus user duplikat
                $duplicateUser->delete();
                $this->info("✅ Hapus user duplikat: {$email}");
            }
        }
        
        // Bersihkan user tanpa role yang jelas
        $invalidUsers = User::whereNotIn('role', ['admin', 'verifikator', 'kepsek', 'keuangan', 'siswa', 'user'])->get();
        foreach ($invalidUsers as $user) {
            $user->update(['role' => 'user']);
            $this->info("✅ Perbaiki role user: {$user->email}");
        }
        
        // Pastikan user utama ada
        $this->ensureMainUsers();
        
        $this->info('🎉 Pembersihan data selesai!');
    }
    
    private function ensureMainUsers()
    {
        $mainUsers = [
            ['email' => 'admin@ppdb.com', 'name' => 'Administrator', 'role' => 'admin'],
            ['email' => 'verifikator@ppdb.com', 'name' => 'Verifikator', 'role' => 'verifikator'],
            ['email' => 'kepsek@smk.com', 'name' => 'Kepala Sekolah', 'role' => 'kepsek'],
            ['email' => 'keuangan@smk.com', 'name' => 'Staff Keuangan', 'role' => 'keuangan']
        ];
        
        foreach ($mainUsers as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'role' => $userData['role'],
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ]
            );
            $this->info("✅ Pastikan user: {$userData['email']}");
        }
    }
}
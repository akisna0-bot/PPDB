<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAllPasswords extends Command
{
    protected $signature = 'users:reset-passwords';
    protected $description = 'Reset semua password user ke "password"';

    public function handle()
    {
        $users = User::all();
        
        foreach ($users as $user) {
            $user->update(['password' => Hash::make('password')]);
            $this->info("✅ Password {$user->name} ({$user->role}) berhasil direset");
        }
        
        $this->info("\n🎉 Semua password berhasil direset ke 'password'");
        $this->info("\n📋 Daftar Login:");
        $this->info("👤 Admin: admin@ppdb.com / password");
        $this->info("🔍 Verifikator: verifikator@ppdb.com / password");
        $this->info("🏫 Kepsek: kepsek@smk.com / password");
        $this->info("💰 Keuangan: keuangan@smk.com / password");
    }
}
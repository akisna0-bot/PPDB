<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $email = $this->argument('email');
        
        try {
            Mail::raw('Test email dari PPDB SMK Bakti Nusantara 666', function($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email PPDB');
            });
            
            $this->info("Email berhasil dikirim ke: {$email}");
        } catch (\Exception $e) {
            $this->error("Gagal mengirim email: " . $e->getMessage());
        }
    }
}
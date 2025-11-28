<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;

class SendNotifications extends Command
{
    protected $signature = 'ppdb:send-notifications';
    protected $description = 'Kirim notifikasi email/SMS untuk sistem PPDB';

    public function handle()
    {
        $this->info('Memulai pengiriman notifikasi...');
        
        // Reminder pembayaran yang akan expired
        $this->sendPaymentReminders();
        
        // Notifikasi status update
        $this->sendStatusUpdates();
        
        $this->info('Pengiriman notifikasi selesai.');
    }

    private function sendPaymentReminders()
    {
        $expiringSoon = Payment::where('status', 'pending')
            ->whereBetween('expired_at', [now(), now()->addDays(2)])
            ->with(['applicant.user'])
            ->get();

        foreach ($expiringSoon as $payment) {
            $user = $payment->applicant->user;
            
            // Create notification
            Notification::create_notification(
                $user->id,
                'Reminder Pembayaran',
                "Pembayaran Anda akan expired pada {$payment->expired_at->format('d/m/Y')}. Segera lakukan pembayaran.",
                'warning',
                route('payment.show', $payment->id)
            );

            // Send email (jika email service dikonfigurasi)
            try {
                Mail::send('emails.payment-reminder', [
                    'user' => $user,
                    'payment' => $payment
                ], function($message) use ($user) {
                    $message->to($user->email)
                           ->subject('Reminder Pembayaran PPDB - SMK BAKTI NUSANTARA 666');
                });
            } catch (\Exception $e) {
                $this->warn("Failed to send email to {$user->email}: " . $e->getMessage());
            }
        }

        $this->info("Sent payment reminders to {$expiringSoon->count()} users");
    }

    private function sendStatusUpdates()
    {
        // Notifikasi untuk status yang baru diupdate hari ini
        $recentUpdates = Applicant::with(['user'])
            ->where('updated_at', '>=', now()->startOfDay())
            ->whereIn('status', ['verified', 'rejected', 'accepted'])
            ->get();

        foreach ($recentUpdates as $applicant) {
            $statusMessages = [
                'verified' => 'Dokumen Anda telah diverifikasi. Silakan lakukan pembayaran.',
                'rejected' => 'Maaf, dokumen Anda tidak memenuhi syarat. Silakan perbaiki dan daftar ulang.',
                'accepted' => 'Selamat! Anda resmi diterima di SMK BAKTI NUSANTARA 666.'
            ];

            $message = $statusMessages[$applicant->status] ?? 'Status pendaftaran Anda telah diupdate.';

            Notification::create_notification(
                $applicant->user_id,
                'Update Status Pendaftaran',
                $message,
                $applicant->status === 'accepted' ? 'success' : ($applicant->status === 'rejected' ? 'error' : 'info'),
                route('dashboard')
            );
        }

        $this->info("Sent status updates to {$recentUpdates->count()} users");
    }
}
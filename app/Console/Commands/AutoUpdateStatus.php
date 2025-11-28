<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\LogAktivitas;
use App\Models\Notification;

class AutoUpdateStatus extends Command
{
    protected $signature = 'ppdb:auto-update';
    protected $description = 'Update status otomatis untuk sistem PPDB';

    public function handle()
    {
        $this->info('Memulai update status otomatis...');
        
        // Update status pembayaran expired
        $this->updateExpiredPayments();
        
        // Update status dari verified ke payment_pending
        $this->updateVerifiedToPaymentPending();
        
        // Update status dari payment_verified ke accepted
        $this->updatePaymentVerifiedToAccepted();
        
        $this->info('Update status otomatis selesai.');
    }

    private function updateExpiredPayments()
    {
        $expiredPayments = Payment::where('status', 'pending')
            ->where('expired_at', '<', now())
            ->get();

        foreach ($expiredPayments as $payment) {
            $payment->update(['status' => 'expired']);
            
            LogAktivitas::log(
                null,
                $payment->applicant_id,
                'auto_update_payment',
                'Pembayaran expired otomatis',
                ['status' => 'pending'],
                ['status' => 'expired']
            );
        }

        $this->info("Updated {$expiredPayments->count()} expired payments");
    }

    private function updateVerifiedToPaymentPending()
    {
        $verifiedApplicants = Applicant::where('status', 'verified')
            ->whereDoesntHave('payments', function($query) {
                $query->where('payment_type', 'registration');
            })
            ->get();

        foreach ($verifiedApplicants as $applicant) {
            // Create payment invoice
            Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => Payment::generateInvoiceNumber(),
                'amount' => $applicant->wave->biaya_daftar,
                'payment_type' => 'registration',
                'status' => 'pending',
                'expired_at' => now()->addDays(7)
            ]);

            $applicant->update(['status' => 'payment_pending']);

            LogAktivitas::log(
                null,
                $applicant->id,
                'auto_create_invoice',
                'Invoice pembayaran dibuat otomatis',
                ['status' => 'verified'],
                ['status' => 'payment_pending']
            );

            // Send notification
            Notification::create_notification(
                $applicant->user_id,
                'Invoice Pembayaran Dibuat',
                'Silakan lakukan pembayaran dalam 7 hari',
                'info',
                route('payment.index')
            );
        }

        $this->info("Created invoices for {$verifiedApplicants->count()} verified applicants");
    }

    private function updatePaymentVerifiedToAccepted()
    {
        $paidApplicants = Applicant::where('status', 'payment_verified')
            ->whereHas('payments', function($query) {
                $query->where('status', 'paid')->where('payment_type', 'registration');
            })
            ->get();

        foreach ($paidApplicants as $applicant) {
            $applicant->update(['status' => 'accepted']);

            LogAktivitas::log(
                null,
                $applicant->id,
                'auto_accept',
                'Status diubah ke diterima otomatis setelah pembayaran',
                ['status' => 'payment_verified'],
                ['status' => 'accepted']
            );

            // Send notification
            Notification::create_notification(
                $applicant->user_id,
                'Selamat! Anda Diterima',
                'Pembayaran telah diverifikasi, Anda resmi diterima di SMK BAKTI NUSANTARA 666',
                'success',
                route('dashboard')
            );
        }

        $this->info("Auto-accepted {$paidApplicants->count()} paid applicants");
    }
}
<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Applicant;
use App\Models\Payment;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Kirim notifikasi selamat datang
     */
    public function sendRegistrationWelcome($userId)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => '🎉 Selamat Datang!',
            'message' => 'Akun Anda berhasil dibuat. Silakan lengkapi formulir pendaftaran untuk melanjutkan proses PPDB.',
            'type' => 'success'
        ]);
    }
    
    /**
     * Kirim notifikasi hasil verifikasi administrasi
     */
    public function sendVerificationNotification(Applicant $applicant, $status)
    {
        $messages = [
            'ADM_PASS' => [
                'title' => '✅ Verifikasi Administrasi Berhasil',
                'message' => 'Selamat! Berkas administrasi Anda telah diverifikasi. Silakan lakukan pembayaran untuk melanjutkan.',
                'type' => 'success'
            ],
            'ADM_REJECT' => [
                'title' => '❌ Verifikasi Administrasi Ditolak',
                'message' => 'Berkas administrasi Anda ditolak. Catatan: ' . ($applicant->catatan_verifikasi ?? 'Silakan perbaiki berkas dan submit ulang.'),
                'type' => 'error'
            ],
            'REQUEST_CORRECTION' => [
                'title' => '🔄 Berkas Perlu Perbaikan',
                'message' => 'Berkas Anda perlu diperbaiki. Catatan: ' . ($applicant->catatan_verifikasi ?? 'Silakan periksa dan perbaiki berkas.'),
                'type' => 'warning'
            ]
        ];
        
        $message = $messages[$status] ?? $messages['REQUEST_CORRECTION'];
        
        return Notification::create([
            'user_id' => $applicant->user_id,
            'title' => $message['title'],
            'message' => $message['message'],
            'type' => $message['type']
        ]);
    }
    
    /**
     * Kirim notifikasi hasil verifikasi pembayaran
     */
    public function sendPaymentNotification(Payment $payment, $status)
    {
        $messages = [
            'PAID' => [
                'title' => '✅ Pembayaran Terverifikasi',
                'message' => 'Selamat! Pembayaran Anda telah diverifikasi. Menunggu pengumuman hasil seleksi.',
                'type' => 'success'
            ],
            'PAYMENT_REJECT' => [
                'title' => '❌ Pembayaran Ditolak',
                'message' => 'Bukti pembayaran Anda ditolak. Catatan: ' . ($payment->catatan_verifikasi ?? 'Silakan upload bukti pembayaran yang valid.'),
                'type' => 'error'
            ]
        ];
        
        $message = $messages[$status] ?? $messages['PAYMENT_REJECT'];
        
        return Notification::create([
            'user_id' => $payment->applicant->user_id,
            'title' => $message['title'],
            'message' => $message['message'],
            'type' => $message['type']
        ]);
    }
    
    /**
     * Kirim notifikasi keputusan final
     */
    public function sendFinalDecisionNotification(Applicant $applicant, $status)
    {
        $messages = [
            'LULUS' => [
                'title' => '🎉 Selamat! Anda LULUS',
                'message' => 'Selamat! Anda dinyatakan LULUS dalam seleksi PPDB. Silakan ikuti instruksi daftar ulang.',
                'type' => 'success'
            ],
            'TIDAK_LULUS' => [
                'title' => '😔 Mohon Maaf, Anda Tidak Lulus',
                'message' => 'Mohon maaf, Anda belum berhasil dalam seleksi kali ini. Tetap semangat untuk kesempatan berikutnya!',
                'type' => 'error'
            ],
            'CADANGAN' => [
                'title' => '⏳ Anda Masuk Daftar Cadangan',
                'message' => 'Anda masuk dalam daftar cadangan. Harap menunggu pengumuman lebih lanjut.',
                'type' => 'warning'
            ]
        ];
        
        $message = $messages[$status] ?? $messages['CADANGAN'];
        
        return Notification::create([
            'user_id' => $applicant->user_id,
            'title' => $message['title'],
            'message' => $message['message'],
            'type' => $message['type']
        ]);
    }
    
    /**
     * Kirim email notifikasi (opsional, untuk integrasi SES)
     */
    public function sendEmailNotification($user, $subject, $message)
    {
        // Implementasi email dengan SES akan ditambahkan nanti
        // Mail::to($user->email)->send(new NotificationMail($subject, $message));
        
        return true;
    }
    
    /**
     * Kirim notifikasi reminder pembayaran
     */
    public function sendPaymentReminder(Applicant $applicant)
    {
        return Notification::create([
            'user_id' => $applicant->user_id,
            'title' => '⏰ Reminder Pembayaran',
            'message' => 'Jangan lupa untuk melakukan pembayaran pendaftaran. Batas waktu pembayaran sesuai gelombang yang dipilih.',
            'type' => 'warning'
        ]);
    }
}
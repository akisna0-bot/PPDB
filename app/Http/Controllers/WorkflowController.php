<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowController extends Controller
{
    // Verifikator verifikasi berkas pendaftar
    public function verifyApplicant(Request $request, Applicant $applicant)
    {
        $request->validate([
            'status' => 'required|in:VERIFIED,REJECTED',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $applicant->update([
                'status' => $request->status,
                'catatan_verifikasi' => $request->notes,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
                'user_verifikasi_adm' => auth()->user()->name,
                'tgl_verifikasi_adm' => now()
            ]);

            // Jika diverifikasi, buat payment otomatis dan ubah status
            if ($request->status === 'VERIFIED') {
                $this->createInitialPayment($applicant);
                $applicant->update(['status' => 'PAYMENT_PENDING']);
            }

            // Log aktivitas
            Log::info("Verifikator " . auth()->user()->name . " {$request->status} applicant {$applicant->no_pendaftaran}");

            // Kirim notifikasi ke siswa
            $this->sendNotificationToStudent($applicant, $request->status, $request->notes);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Verifikasi berhasil']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal verifikasi: ' . $e->getMessage()]);
        }
    }

    // Keuangan verifikasi pembayaran
    public function verifyPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:PAID,REJECTED',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $payment->update([
                'status' => $request->status,
                'verification_notes' => $request->notes,
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);

            // Update status applicant berdasarkan verifikasi pembayaran
            if ($request->status === 'PAID') {
                // Hanya update jika belum PAYMENT_VERIFIED
                if ($payment->applicant->status !== 'PAYMENT_VERIFIED') {
                    $payment->applicant->update(['status' => 'PAYMENT_VERIFIED']);
                }
            } else {
                $payment->applicant->update(['status' => 'PAYMENT_PENDING']);
            }

            // Kirim notifikasi pembayaran ke siswa
            $this->sendPaymentNotificationToStudent($payment, $request->status, $request->notes);

            Log::info("Keuangan {auth()->user()->name} {$request->status} payment {$payment->id}");

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Verifikasi pembayaran berhasil']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal verifikasi: ' . $e->getMessage()]);
        }
    }

    // Admin/Kepsek keputusan akhir
    public function finalDecision(Request $request, Applicant $applicant)
    {
        $request->validate([
            'final_status' => 'required|in:ACCEPTED,REJECTED',
            'notes' => 'nullable|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $applicant->update([
                'final_status' => $request->final_status,
                'final_notes' => $request->notes,
                'decided_by' => auth()->id(),
                'decided_at' => now()
            ]);

            // Kirim notifikasi keputusan final ke siswa
            $this->sendFinalDecisionNotificationToStudent($applicant, $request->final_status, $request->notes);

            Log::info("Admin/Kepsek {auth()->user()->name} final decision {$request->final_status} for {$applicant->no_pendaftaran}");

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Keputusan akhir berhasil disimpan']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    private function sendNotificationToStudent($applicant, $status, $notes)
    {
        try {
            $title = $status === 'VERIFIED' 
                ? '✅ Berkas Diverifikasi - Silakan Bayar' 
                : '❌ Berkas Ditolak';
                
            $message = $status === 'VERIFIED'
                ? 'Selamat! Berkas administrasi Anda telah diverifikasi oleh tim verifikator. Silakan lakukan pembayaran untuk melanjutkan proses pendaftaran.'
                : 'Berkas administrasi Anda ditolak. ' . ($notes ? 'Catatan: ' . $notes : 'Silakan perbaiki berkas dan submit ulang.');
                
            \App\Models\Notification::create([
                'user_id' => $applicant->user_id,
                'title' => $title,
                'message' => $message,
                'type' => $status === 'VERIFIED' ? 'success' : 'error',
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification notification: ' . $e->getMessage());
        }
    }
    
    private function sendPaymentNotificationToStudent($payment, $status, $notes)
    {
        try {
            $title = $status === 'PAID' 
                ? '✅ Pembayaran Terverifikasi' 
                : '❌ Pembayaran Ditolak';
                
            $message = $status === 'PAID'
                ? 'Selamat! Pembayaran Anda telah diverifikasi oleh tim keuangan. Menunggu pengumuman hasil seleksi.'
                : 'Bukti pembayaran Anda ditolak. ' . ($notes ? 'Catatan: ' . $notes : 'Silakan upload bukti pembayaran yang valid.');
                
            \App\Models\Notification::create([
                'user_id' => $payment->applicant->user_id,
                'title' => $title,
                'message' => $message,
                'type' => $status === 'PAID' ? 'success' : 'error',
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment notification: ' . $e->getMessage());
        }
    }
    
    private function sendFinalDecisionNotificationToStudent($applicant, $status, $notes)
    {
        try {
            $title = $status === 'ACCEPTED' 
                ? '🎉 Selamat! Anda DITERIMA' 
                : '😔 Mohon Maaf, Anda Tidak Diterima';
                
            $message = $status === 'ACCEPTED'
                ? 'Selamat! Anda dinyatakan DITERIMA dalam seleksi PPDB SMK Bakti Nusantara 666. ' . ($notes ? $notes : 'Silakan ikuti instruksi daftar ulang.')
                : 'Mohon maaf, Anda belum berhasil dalam seleksi kali ini. ' . ($notes ? $notes : 'Tetap semangat untuk kesempatan berikutnya!');
                
            \App\Models\Notification::create([
                'user_id' => $applicant->user_id,
                'title' => $title,
                'message' => $message,
                'type' => $status === 'ACCEPTED' ? 'success' : 'error',
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send final decision notification: ' . $e->getMessage());
        }
    }
    
    private function createInitialPayment($applicant)
    {
        try {
            // Cek apakah sudah ada payment untuk applicant ini
            $existingPayment = Payment::where('applicant_id', $applicant->id)
                ->where('payment_type', 'registration')
                ->first();
                
            if (!$existingPayment) {
                Payment::create([
                    'applicant_id' => $applicant->id,
                    'invoice_number' => Payment::generateInvoiceNumber(),
                    'amount' => 150000, // Biaya pendaftaran default
                    'payment_type' => 'registration',
                    'status' => 'pending',
                    'payment_method' => 'qris',
                    'expired_at' => now()->addDays(7), // 7 hari untuk bayar
                    'notes' => 'Biaya pendaftaran PPDB'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to create initial payment: ' . $e->getMessage());
        }
    }
}
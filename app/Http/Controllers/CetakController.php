<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CetakController extends Controller
{
    public function kartuPendaftaran()
    {
        $applicant = Applicant::with(['user', 'major', 'wave', 'files', 'payments'])->where('user_id', auth()->id())->firstOrFail();

        // Validasi kelengkapan data sebelum cetak
        if ($applicant->status !== 'VERIFIED' && $applicant->status !== 'PAID' && !$applicant->final_status) {
            return redirect()->route('status.index')->with('error', 'Kartu pendaftaran hanya dapat dicetak setelah berkas diverifikasi. Silakan lengkapi dan tunggu verifikasi terlebih dahulu.');
        }

        // Cek dokumen lengkap (semua 6 dokumen harus diupload dan disetujui)
        $requiredDocs = ['ijazah', 'skhun', 'rapor', 'akta_kelahiran', 'kartu_keluarga', 'pas_foto'];
        $uploadedDocs = $applicant->files->where('status', 'approved')->pluck('document_type')->toArray();

        $missingDocs = array_diff($requiredDocs, $uploadedDocs);
        if (!empty($missingDocs)) {
            $docNames = array_map(function($doc) {
                return ApplicantFile::getDocumentTypes()[$doc] ?? $doc;
            }, $missingDocs);
            return redirect()->route('status.index')->with('error', 'Kartu pendaftaran hanya dapat dicetak setelah semua dokumen disetujui. Dokumen yang belum lengkap: ' . implode(', ', $docNames));
        }

        // Cek pembayaran (minimal satu pembayaran sudah dibayar)
        $hasPaidPayment = $applicant->payments->where('status', 'paid')->count() > 0;
        if (!$hasPaidPayment) {
            return redirect()->route('status.index')->with('error', 'Kartu pendaftaran hanya dapat dicetak setelah melakukan pembayaran. Silakan lakukan pembayaran terlebih dahulu.');
        }

        $pdf = Pdf::loadView('cetak.kartu', compact('applicant'));
        return $pdf->download('kartu-pendaftaran-' . $applicant->no_pendaftaran . '.pdf');
    }
    
    public function buktiPembayaran($paymentId)
    {
        $payment = \App\Models\Payment::with(['applicant.user', 'applicant.major'])
            ->whereHas('applicant', function($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($paymentId);
            
        $pdf = Pdf::loadView('cetak.bukti-bayar', compact('payment'));
        return $pdf->download('bukti-bayar-' . $payment->invoice_number . '.pdf');
    }
}
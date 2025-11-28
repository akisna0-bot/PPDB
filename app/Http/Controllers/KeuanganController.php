<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KeuanganController extends Controller
{
    public function dashboard()
    {
        try {
            $stats = [
                'pending_payment' => \App\Models\Payment::where('status', 'pending')->count(),
                'paid' => \App\Models\Payment::where('status', 'paid')->count(),
                'total_revenue' => \App\Models\Payment::where('status', 'paid')->sum('amount') ?? 0,
                'today_payments' => \App\Models\Payment::where('status', 'paid')
                    ->whereDate('created_at', today())->count()
            ];
        
        // Tren pembayaran 7 hari terakhir
        $paymentTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $paymentTrend[] = [
                'date' => $date->format('d/m'),
                'count' => Applicant::where('status', 'PAID')
                    ->whereDate('updated_at', $date->format('Y-m-d'))->count()
            ];
        }
        
        // Pembayaran terbaru
        $recentPayments = \App\Models\Payment::with(['applicant.user', 'applicant.major'])
            ->where('status', 'paid')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
            return view('keuangan.dashboard', compact('stats', 'paymentTrend', 'recentPayments'));
        } catch (\Exception $e) {
            \Log::error('Keuangan Dashboard Error: ' . $e->getMessage());
            $stats = ['pending_payment' => 0, 'paid' => 0, 'total_revenue' => 0, 'today_payments' => 0];
            $paymentTrend = [];
            $recentPayments = collect([]);
            return view('keuangan.dashboard', compact('stats', 'paymentTrend', 'recentPayments'));
        }
    }

    public function daftarPembayaran()
    {
        try {
            // Ambil data dari applicants yang sudah verified dan punya payment
            $applicants = Applicant::with(['user', 'major', 'wave', 'payments'])
                ->whereIn('status', ['PAYMENT_PENDING', 'PAYMENT_VERIFIED'])
                ->whereHas('payments')
                ->orderBy('updated_at', 'desc')
                ->paginate(20);
                
            return view('keuangan.daftar-pembayaran', compact('applicants'));
        } catch (\Exception $e) {
            \Log::error('Keuangan Daftar Pembayaran Error: ' . $e->getMessage());
            $applicants = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            return view('keuangan.daftar-pembayaran', compact('applicants'));
        }
    }

    public function payments()
    {
        return $this->daftarPembayaran();
    }

    public function verifyPayment(Request $request, $id)
    {
        try {
            $applicant = Applicant::findOrFail($id);
            $payment = $applicant->payments()->latest()->first();
            
            if (!$payment) {
                return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
            }
            
            // Update payment status
            $payment->update([
                'status' => 'paid',
                'verified_by' => auth()->id(),
                'verified_at' => now()
            ]);
            
            // Update applicant status
            $applicant->update(['status' => 'PAYMENT_VERIFIED']);
            
            // Kirim notifikasi ke siswa
            \App\Models\Notification::create([
                'user_id' => $applicant->user_id,
                'title' => '✅ Pembayaran Diverifikasi!',
                'message' => 'Pembayaran Anda telah diverifikasi oleh tim keuangan. Menunggu pengumuman hasil seleksi.',
                'type' => 'success',
                'is_read' => false
            ]);
            
            return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi!');
        } catch (\Exception $e) {
            \Log::error('Verify Payment Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat verifikasi pembayaran.');
        }
    }
    
    private function notifyKepsekForFinalDecision($applicant)
    {
        // Log untuk kepsek
        \Log::info('Keuangan verified payment for applicant', [
            'applicant_id' => $applicant->id,
            'no_pendaftaran' => $applicant->no_pendaftaran,
            'nama' => $applicant->nama_lengkap ?? $applicant->user->name,
            'verified_by' => auth()->user()->name,
            'next_step' => 'Menunggu keputusan akhir dari Kepsek'
        ]);
    }

    public function rekapKeuangan()
    {
        $paidApplicants = Applicant::with(['user', 'major', 'wave'])->where('status', 'PAID')->get();
        
        $stats = [
            'total_pendapatan' => $paidApplicants->sum(function($app) {
                return $app->wave->biaya ?? 150000;
            }),
            'gelombang_1' => $paidApplicants->where('wave_id', 1)->count(),
            'gelombang_2' => $paidApplicants->where('wave_id', 2)->count(),
            'lunas' => $paidApplicants->count(),
            'pending' => Applicant::where('status', 'VERIFIED')->count()
        ];
        
        return view('keuangan.rekap', compact('stats', 'paidApplicants'));
    }

    public function exportExcel()
    {
        $applicants = Applicant::with(['user', 'major', 'wave'])
            ->whereIn('status', ['VERIFIED', 'PAID'])
            ->get();
            
        return Excel::download(new \App\Exports\PaymentExport($applicants), 'laporan-keuangan-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $applicants = Applicant::with(['user', 'major', 'wave'])
            ->whereIn('status', ['VERIFIED', 'PAID'])
            ->get();
            
        $stats = [
            'total_pendapatan' => $applicants->where('status', 'PAID')->sum(function($app) {
                return $app->wave->biaya ?? 150000;
            }),
            'lunas' => $applicants->where('status', 'PAID')->count(),
            'pending' => $applicants->where('status', 'VERIFIED')->count()
        ];
        
        $pdf = Pdf::loadView('keuangan.export-pdf', compact('applicants', 'stats'));
        return $pdf->download('laporan-keuangan-' . date('Y-m-d') . '.pdf');
    }

    public function logAktivitas()
    {
        return view('keuangan.log-aktivitas');
    }
}
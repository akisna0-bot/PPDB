<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Major;
use App\Models\Wave;
use App\Models\Payment;
use Illuminate\Http\Request;

class KepsekDashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $stats = [
            'total_pendaftar' => Applicant::count(),
            'sudah_bayar' => Applicant::where('status', 'PAYMENT_VERIFIED')->count(),
            'menunggu_keputusan' => Applicant::where('status', 'PAYMENT_VERIFIED')->whereNull('final_status')->count(),
            'diterima' => Applicant::where('final_status', 'ACCEPTED')->count(),
            'ditolak' => Applicant::where('final_status', 'REJECTED')->count()
        ];
        
        // Siswa yang sudah diterima oleh admin
        $acceptedStudents = Applicant::with(['user', 'major', 'wave'])
            ->where('final_status', 'ACCEPTED')
            ->orderBy('decided_at', 'desc')
            ->limit(10)
            ->get();
        
        // Pendaftar yang perlu keputusan akhir
        $pendingDecision = Applicant::with(['user', 'major', 'wave'])
            ->where('status', 'PAYMENT_VERIFIED')
            ->whereNull('final_status')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get();
        
        // Data per jurusan
        $dataJurusan = Major::withCount(['applicants as total_pendaftar'])
            ->withCount(['applicants as diterima' => function($q) {
                $q->where('status', 'PAID');
            }])
            ->get();
        
        // Tren pendaftaran 7 hari terakhir
        $trendData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $trendData[] = [
                'date' => $date->format('d/m'),
                'count' => Applicant::whereDate('created_at', $date->format('Y-m-d'))->count()
            ];
        }
        
        // Aktivitas terbaru
        $recentActivities = Applicant::with(['user', 'major'])
            ->whereIn('status', ['VERIFIED', 'REJECTED', 'PAID'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('kepsek.dashboard', compact('stats', 'dataJurusan', 'trendData', 'recentActivities', 'pendingDecision', 'acceptedStudents'));
    }

    public function laporanRekapitulasi()
    {
        $stats = [
            'total_pendaftar' => Applicant::count(),
            'diterima' => Applicant::where('status', 'PAID')->count(),
            'diverifikasi' => Applicant::where('status', 'VERIFIED')->count(),
            'pending' => Applicant::where('status', 'SUBMIT')->count(),
            'ditolak' => Applicant::where('status', 'REJECTED')->count()
        ];
        
        // Data per jurusan
        $dataJurusan = Major::withCount(['applicants as total'])
            ->withCount(['applicants as diterima' => function($q) {
                $q->where('status', 'PAID');
            }])
            ->get();
        
        // Data per gelombang
        $dataGelombang = Wave::withCount(['applicants as total'])
            ->withCount(['applicants as diterima' => function($q) {
                $q->where('status', 'PAID');
            }])
            ->get();
        
        return view('kepsek.laporan-rekapitulasi', compact('stats', 'dataJurusan', 'dataGelombang'));
    }

    public function grafikPetaSebaran()
    {
        // Data sebaran per kabupaten dari siswa yang benar-benar mendaftar
        $sebaranData = Applicant::selectRaw('kabupaten, provinsi, COUNT(*) as total')
            ->whereNotNull('kabupaten')
            ->groupBy('kabupaten', 'provinsi')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('kepsek.grafik-peta', compact('sebaranData'));
    }

    public function exportLaporanPdf()
    {
        return redirect()->back()->with('success', 'Export PDF berhasil diunduh');
    }

    public function exportLaporanExcel()
    {
        return redirect()->back()->with('success', 'Export Excel berhasil diunduh');
    }

    public function riwayatAktivitas()
    {
        $activities = Applicant::with(['user', 'major'])
            ->whereNotNull('tgl_verifikasi_adm')
            ->orderBy('tgl_verifikasi_adm', 'desc')
            ->paginate(20);
        
        return view('kepsek.riwayat-aktivitas', compact('activities'));
    }
    
    public function finalDecision(Request $request, $id)
    {
        $applicant = Applicant::with('user')->findOrFail($id);
        
        // Pastikan sudah bayar sebelum bisa diputuskan
        if ($applicant->status !== 'PAYMENT_VERIFIED') {
            return redirect()->back()->with('error', 'Pendaftar belum melakukan pembayaran atau belum diverifikasi!');
        }
        
        $request->validate([
            'final_status' => 'required|in:ACCEPTED,REJECTED',
            'final_notes' => 'nullable|string|max:500'
        ]);
        
        $applicant->update([
            'final_status' => $request->final_status,
            'final_notes' => $request->final_notes,
            'decided_by' => auth()->user()->name,
            'decided_at' => now()
        ]);
        
        $message = $request->final_status == 'ACCEPTED' ? 'Pendaftar berhasil DITERIMA!' : 'Pendaftar DITOLAK!';
        
        return redirect()->back()->with('success', $message);
    }
}
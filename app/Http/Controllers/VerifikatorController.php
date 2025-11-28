<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use App\Http\Controllers\WorkflowController;

class VerifikatorController extends Controller
{
    public function index()
    {
        try {
            // Statistik verifikasi
            $stats = [
                'pending' => Applicant::where('status', 'SUBMIT')->count(),
                'verified' => Applicant::where('status', 'VERIFIED')->count(),
                'rejected' => Applicant::where('status', 'REJECTED')->count(),
                'total' => Applicant::count()
            ];
        
        // Tren verifikasi 7 hari terakhir
        $verificationTrend = [];
        $rejectionTrend = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $verified = Applicant::where('status', 'VERIFIED')
                ->whereDate('tgl_verifikasi_adm', $date)
                ->count();
            $rejected = Applicant::where('status', 'REJECTED')
                ->whereDate('tgl_verifikasi_adm', $date)
                ->count();
                
            $verificationTrend[] = $verified;
            $rejectionTrend[] = $rejected;
        }
        
        // Aktivitas terbaru
        $recentActivities = Applicant::with(['user', 'major'])
            ->whereIn('status', ['VERIFIED', 'REJECTED'])
            ->whereNotNull('tgl_verifikasi_adm')
            ->orderBy('tgl_verifikasi_adm', 'desc')
            ->limit(5)
            ->get();
        
            return view('verifikator.dashboard', compact('stats', 'verificationTrend', 'rejectionTrend', 'recentActivities'));
        } catch (\Exception $e) {
            \Log::error('Verifikator Dashboard Error: ' . $e->getMessage());
            $stats = ['pending' => 0, 'verified' => 0, 'rejected' => 0, 'total' => 0];
            $verificationTrend = [0,0,0,0,0,0,0];
            $rejectionTrend = [0,0,0,0,0,0,0];
            $recentActivities = collect([]);
            return view('verifikator.dashboard', compact('stats', 'verificationTrend', 'rejectionTrend', 'recentActivities'));
        }
    }

    public function daftarPendaftar(Request $request)
    {
        $query = Applicant::with(['user', 'major', 'wave', 'files'])
            ->whereHas('user'); // Pastikan hanya ambil yang punya user
        
        // Filter berdasarkan status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $applicants = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Statistik untuk dashboard
        $stats = [
            'total' => Applicant::count(),
            'pending' => Applicant::where('status', 'SUBMIT')->count(),
            'verified' => Applicant::where('status', 'VERIFIED')->count(),
            'rejected' => Applicant::where('status', 'REJECTED')->count(),
            'paid' => Applicant::where('status', 'PAID')->count()
        ];
        
        // Debug info
        \Log::info('Verifikator Data Debug:', [
            'total_applicants' => $applicants->total(),
            'current_page_count' => $applicants->count(),
            'stats' => $stats,
            'filter_status' => $request->status,
            'sample_data' => $applicants->take(3)->map(function($app) {
                return [
                    'id' => $app->id,
                    'no_pendaftaran' => $app->no_pendaftaran,
                    'nama_lengkap' => $app->nama_lengkap,
                    'user_name' => $app->user->name ?? null,
                    'status' => $app->status
                ];
            })
        ]);
            
        return view('verifikator.daftar-pendaftar', compact('applicants', 'stats'));
    }

    public function show($id)
    {
        $applicant = Applicant::with(['user', 'major', 'wave', 'files'])
            ->findOrFail($id);
            
        return view('verifikator.show', compact('applicant'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:VERIFIED,REJECTED',
            'catatan_verifikasi' => 'nullable|string|max:500'
        ]);
        
        $applicant = Applicant::findOrFail($id);
        
        // Gunakan WorkflowController untuk integrasi antar role
        $workflowRequest = new Request([
            'status' => $request->status,
            'notes' => $request->catatan_verifikasi
        ]);
        
        $result = app('App\Http\Controllers\WorkflowController')->verifyApplicant($workflowRequest, $applicant);
        
        if ($result->getData()->success) {
            $message = $request->status == 'VERIFIED' 
                ? 'Pendaftar berhasil diverifikasi! Payment otomatis dibuat untuk siswa.' 
                : 'Pendaftar berhasil ditolak!';
            return redirect()->route('verifikator.daftar-pendaftar')->with('success', $message);
        } else {
            return redirect()->back()->with('error', $result->getData()->message)->withInput();
        }
    }

    public function laporanVerifikasi()
    {
        $stats = [
            'pending' => Applicant::where('status', 'SUBMIT')->count(),
            'approved' => Applicant::where('status', 'VERIFIED')->count(),
            'rejected' => Applicant::where('status', 'REJECTED')->count()
        ];
        
        return view('verifikator.laporan', compact('stats'));
    }

    public function logAktivitas()
    {
        return view('verifikator.log-aktivitas');
    }
    
    public function debugData()
    {
        $applicants = Applicant::with(['user', 'major', 'wave'])->get();
        
        $debug = [
            'total_applicants' => $applicants->count(),
            'applicants_data' => $applicants->map(function($app) {
                return [
                    'id' => $app->id,
                    'no_pendaftaran' => $app->no_pendaftaran,
                    'user_name' => $app->user->name ?? 'No User',
                    'major_name' => $app->major->name ?? 'No Major',
                    'status' => $app->status,
                    'created_at' => $app->created_at
                ];
            })
        ];
        
        return response()->json($debug);
    }
}
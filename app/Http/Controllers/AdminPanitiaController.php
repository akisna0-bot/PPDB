<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Major;
use App\Models\Wave;
use App\Models\User;
use App\Models\Payment;
use App\Models\ApplicantFile;
use Carbon\Carbon;
use App\Exports\ApplicantsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminPanitiaController extends Controller
{
    public function dashboard()
    {
        $totalApplicants = Applicant::count();
        $pendingApplicants = Applicant::where('status', 'SUBMIT')->count();
        $verifiedApplicants = Applicant::where('status', 'VERIFIED')->count();
        $paidApplicants = Applicant::where('status', 'PAID')->count();
        
        // Payment stats
        $paymentStats = [
            'pending_verification' => Payment::where('status', 'pending')->count(),
            'verified_by_finance' => Payment::where('status', 'paid')->count(),
            'rejected_by_finance' => Payment::where('status', 'failed')->count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount')
        ];
        
        // Data untuk grafik tren pendaftaran (7 hari terakhir)
        $registrationTrend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Applicant::whereDate('created_at', $date)->count();
            $registrationTrend[] = $count;
        }
        
        // Data untuk grafik status pendaftar
        $statusData = [
            'submit' => Applicant::where('status', 'SUBMIT')->count(),
            'verified' => Applicant::where('status', 'VERIFIED')->count(),
            'rejected' => Applicant::where('status', 'REJECTED')->count(),
            'paid' => Applicant::where('status', 'PAID')->count()
        ];
        
        // Data untuk grafik jurusan
        $majorData = [];
        $majors = Major::all();
        foreach ($majors as $major) {
            $majorData[$major->code] = Applicant::where('major_id', $major->id)->count();
        }
        
        // Aktivitas terbaru dari database real
        $recentActivities = collect();
        
        // Pendaftar baru (5 terbaru)
        $newApplicants = Applicant::with(['user', 'major'])
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($applicant) {
                return [
                    'type' => 'new_registration',
                    'message' => '📝 Pendaftar baru: <strong>' . ($applicant->user->name ?? 'N/A') . '</strong> (' . ($applicant->registration_number ?? 'N/A') . ') - Mendaftar jurusan ' . ($applicant->major->code ?? 'N/A'),
                    'time' => $applicant->created_at,
                    'color' => 'yellow'
                ];
            });
        
        // Verifikasi terbaru (3 terbaru)
        $recentVerifications = Applicant::with(['user'])
            ->where('status', 'VERIFIED')
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get()
            ->map(function($applicant) {
                return [
                    'type' => 'verification',
                    'message' => '✅ Verifikasi: <strong>' . ($applicant->user->name ?? 'N/A') . '</strong> (' . ($applicant->registration_number ?? 'N/A') . ') - Berkas telah diverifikasi admin',
                    'time' => $applicant->updated_at,
                    'color' => 'blue'
                ];
            });
        
        // Pembayaran terbaru (3 terbaru)
        $recentPayments = Payment::with(['applicant.user'])
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->limit(1)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => 'payment',
                    'message' => '💰 Pembayaran: <strong>' . ($payment->applicant->user->name ?? 'N/A') . '</strong> (' . ($payment->applicant->registration_number ?? 'N/A') . ') - Pembayaran berhasil diverifikasi',
                    'time' => $payment->updated_at,
                    'color' => 'green'
                ];
            });
        
        // Gabungkan semua aktivitas dan urutkan berdasarkan waktu
        $recentActivities = $newApplicants
            ->concat($recentVerifications)
            ->concat($recentPayments)
            ->sortByDesc('time')
            ->take(5);
        
        return view('admin.dashboard', compact(
            'totalApplicants',
            'pendingApplicants', 
            'verifiedApplicants',
            'paidApplicants',
            'paymentStats',
            'registrationTrend',
            'statusData',
            'majorData',
            'recentActivities'
        ));
    }

    public function masterData()
    {
        $majors = Major::all();
        $waves = Wave::all();
        $documentTypes = ApplicantFile::getDocumentTypes();
        
        return view('admin.panitia.master-data', compact('majors', 'waves', 'documentTypes'));
    }

    public function dataPendaftar(Request $request)
    {
        $query = Applicant::with(['user', 'major', 'wave', 'files', 'payments'])
            ->whereHas('user'); // Pastikan hanya ambil yang punya user
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->major_id) {
            $query->where('major_id', $request->major_id);
        }
        if ($request->wave_id) {
            $query->where('wave_id', $request->wave_id);
        }

        $applicants = $query->latest()->paginate(20);
        $majors = Major::all();
        $waves = Wave::all();
        
        return view('admin.panitia.data-pendaftar', compact('applicants', 'majors', 'waves'));
    }

    public function monitoringBerkas()
    {
        $berkasStats = ApplicantFile::select('document_type', 'status', DB::raw('count(*) as total'))
            ->groupBy('document_type', 'status')->get();
            
        $applicantsWithFiles = Applicant::with(['files', 'user', 'major'])
            ->whereHas('files')->paginate(20);
            
        return view('admin.panitia.monitoring-berkas', compact('berkasStats', 'applicantsWithFiles'));
    }

    public function monitoringPembayaran()
    {
        $paymentStats = Payment::select('status', DB::raw('count(*) as total'), DB::raw('sum(amount) as total_amount'))
            ->groupBy('status')->get();
            
        $payments = Payment::with(['applicant.user', 'applicant.major'])
            ->latest()->paginate(20);
            
        return view('admin.panitia.monitoring-pembayaran', compact('paymentStats', 'payments'));
    }

    public function petaSebaran()
    {
        // Data sebaran dengan kecamatan
        $sebaranData = Applicant::select(
                'provinsi', 
                'kabupaten', 
                'kecamatan',
                DB::raw('count(*) as total')
            )
            ->whereNotNull('provinsi')
            ->groupBy('provinsi', 'kabupaten', 'kecamatan')
            ->orderBy('total', 'desc')
            ->get();
            
        $majors = Major::all();
        
        // Statistik tambahan
        $stats = [
            'total_pendaftar' => Applicant::count(),
            'dengan_alamat' => Applicant::whereNotNull('provinsi')->count(),
            'tanpa_alamat' => Applicant::whereNull('provinsi')->count(),
            'total_provinsi' => Applicant::whereNotNull('provinsi')->distinct('provinsi')->count(),
            'total_kabupaten' => Applicant::whereNotNull('kabupaten')->distinct('kabupaten')->count()
        ];
            
        return view('admin.panitia.peta-sebaran', compact('sebaranData', 'majors', 'stats'));
    }
    
    public function testMapData()
    {
        // Method untuk testing data peta
        $applicants = Applicant::with(['user', 'major'])->limit(10)->get();
        
        $testData = $applicants->map(function($applicant) {
            return [
                'id' => $applicant->id,
                'name' => $applicant->user->name ?? $applicant->nama_lengkap ?? 'Test User',
                'kabupaten' => $applicant->kabupaten ?? 'Bandung',
                'provinsi' => $applicant->provinsi ?? 'Jawa Barat',
                'major' => $applicant->major->name ?? 'Test Major',
                'status' => $applicant->status
            ];
        });
        
        return response()->json([
            'success' => true,
            'test_data' => $testData,
            'total_applicants' => Applicant::count()
        ]);
    }

    public function manajemenAkun()
    {
        // Ambil semua user dengan relasi applicant untuk siswa
        $users = User::with('applicant')->get();
        
        // Statistik user berdasarkan role
        $userStats = [
            'admin' => User::where('role', 'admin')->count(),
            'verifikator' => User::where('role', 'verifikator')->count(),
            'keuangan' => User::where('role', 'keuangan')->count(),
            'kepsek' => User::where('role', 'kepsek')->count(),
            'user' => User::where('role', 'user')->count(),
        ];
        
        return view('admin.panitia.manajemen-akun', compact('users', 'userStats'));
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:kepsek,verifikator_adm,verifikator,keuangan,admin',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Akun berhasil dibuat!');
    }

    private function getBerkasStats()
    {
        $totalApplicants = Applicant::count();
        $applicantsWithFiles = Applicant::whereHas('files')->count();
        
        return [
            'total_applicants' => $totalApplicants,
            'with_files' => $applicantsWithFiles,
            'percentage' => $totalApplicants > 0 ? round(($applicantsWithFiles / $totalApplicants) * 100) : 0
        ];
    }

    private function getVerifikasiStats()
    {
        return [
            'pending' => Applicant::where('status', 'submitted')->count(),
            'verified' => Applicant::where('status', 'verified')->count(),
            'rejected' => Applicant::where('status', 'rejected')->count()
        ];
    }

    private function getPembayaranStats()
    {
        return [
            'pending' => Payment::where('status', 'pending')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
            'failed' => Payment::where('status', 'failed')->count()
        ];
    }

    private function getTrenHarian()
    {
        return Applicant::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['status', 'major_id', 'wave_id']);
        return Excel::download(new ApplicantsExport($filters), 'data-pendaftar-' . date('Y-m-d') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Applicant::with(['user', 'major', 'wave']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->major_id) {
            $query->where('major_id', $request->major_id);
        }
        if ($request->wave_id) {
            $query->where('wave_id', $request->wave_id);
        }

        $applicants = $query->get();
        $majors = Major::all();
        $waves = Wave::all();
        
        $pdf = Pdf::loadView('admin.panitia.export-pdf', compact('applicants', 'majors', 'waves', 'request'));
        return $pdf->download('data-pendaftar-' . date('Y-m-d') . '.pdf');
    }
    
    public function getMapData(Request $request)
    {
        try {
            $query = Applicant::with(['user', 'major']);
                
            if ($request->major_id) {
                $query->where('major_id', $request->major_id);
            }
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            $applicants = $query->get()->map(function($applicant) {
                $coordinates = $this->getCoordinatesForLocation(
                    $applicant->kabupaten ?? 'Bandung',
                    $applicant->provinsi ?? 'Jawa Barat'
                );
                
                return [
                    'id' => $applicant->id,
                    'name' => $applicant->user->name ?? $applicant->nama_lengkap ?? 'N/A',
                    'no_pendaftaran' => $applicant->no_pendaftaran,
                    'major_id' => $applicant->major_id,
                    'major_code' => $applicant->major->code ?? 'N/A',
                    'major_name' => $applicant->major->name ?? 'N/A',
                    'status' => $applicant->status,
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng'],
                    'alamat' => $applicant->alamat_lengkap ?? 'Alamat tidak tersedia',
                    'kecamatan' => $applicant->kecamatan ?? 'Tidak diketahui',
                    'kabupaten' => $applicant->kabupaten ?? 'Tidak diketahui',
                    'provinsi' => $applicant->provinsi ?? 'Tidak diketahui'
                ];
            });
            
            return response()->json([
                'success' => true,
                'applicants' => $applicants,
                'total' => $applicants->count(),
                'by_status' => $applicants->groupBy('status')->map->count(),
                'by_major' => $applicants->groupBy('major_code')->map->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data peta: ' . $e->getMessage(),
                'applicants' => [],
                'total' => 0
            ], 500);
        }
    }
    
    private function getCoordinatesForLocation($kabupaten, $provinsi)
    {
        // Database koordinat untuk wilayah Indonesia
        $locations = [
            // Jawa Barat
            'Bandung' => ['lat' => -6.9175, 'lng' => 107.6191],
            'Bekasi' => ['lat' => -6.2383, 'lng' => 107.0039],
            'Bogor' => ['lat' => -6.5971, 'lng' => 106.8060],
            'Depok' => ['lat' => -6.4025, 'lng' => 106.7942],
            'Cimahi' => ['lat' => -6.8721, 'lng' => 107.5420],
            'Bandung Barat' => ['lat' => -6.8615, 'lng' => 107.4962],
            'Sumedang' => ['lat' => -6.8595, 'lng' => 107.9239],
            'Subang' => ['lat' => -6.5627, 'lng' => 107.7539],
            'Purwakarta' => ['lat' => -6.5569, 'lng' => 107.4431],
            'Karawang' => ['lat' => -6.3015, 'lng' => 107.3020],
            'Cirebon' => ['lat' => -6.7063, 'lng' => 108.5571],
            'Indramayu' => ['lat' => -6.3274, 'lng' => 108.3199],
            'Majalengka' => ['lat' => -6.8364, 'lng' => 108.2274],
            'Kuningan' => ['lat' => -6.9759, 'lng' => 108.4836],
            'Cianjur' => ['lat' => -6.8174, 'lng' => 107.1425],
            'Sukabumi' => ['lat' => -6.9278, 'lng' => 106.9571],
            'Garut' => ['lat' => -7.2253, 'lng' => 107.8986],
            'Tasikmalaya' => ['lat' => -7.3274, 'lng' => 108.2207],
            'Ciamis' => ['lat' => -7.3257, 'lng' => 108.3534],
            'Pangandaran' => ['lat' => -7.6840, 'lng' => 108.6500],
            'Banjar' => ['lat' => -7.3953, 'lng' => 108.5492],
            
            // DKI Jakarta
            'Jakarta Pusat' => ['lat' => -6.1805, 'lng' => 106.8284],
            'Jakarta Utara' => ['lat' => -6.1384, 'lng' => 106.8759],
            'Jakarta Barat' => ['lat' => -6.1352, 'lng' => 106.7606],
            'Jakarta Selatan' => ['lat' => -6.2615, 'lng' => 106.8106],
            'Jakarta Timur' => ['lat' => -6.2250, 'lng' => 106.9004],
            
            // Jawa Tengah
            'Semarang' => ['lat' => -7.0051, 'lng' => 110.4381],
            'Solo' => ['lat' => -7.5755, 'lng' => 110.8243],
            'Yogyakarta' => ['lat' => -7.7956, 'lng' => 110.3695],
            
            // Jawa Timur
            'Surabaya' => ['lat' => -7.2575, 'lng' => 112.7521],
            'Malang' => ['lat' => -7.9666, 'lng' => 112.6326],
            
            // Default untuk Bandung
            'default' => ['lat' => -6.9175, 'lng' => 107.6191]
        ];
        
        // Cari berdasarkan kabupaten dulu, lalu provinsi
        $key = $kabupaten;
        if (!isset($locations[$key])) {
            // Jika tidak ada, coba cari berdasarkan provinsi
            $provinsiDefaults = [
                'Jawa Barat' => ['lat' => -6.9175, 'lng' => 107.6191],
                'DKI Jakarta' => ['lat' => -6.2088, 'lng' => 106.8456],
                'Jawa Tengah' => ['lat' => -7.0051, 'lng' => 110.4381],
                'Jawa Timur' => ['lat' => -7.2575, 'lng' => 112.7521],
                'Banten' => ['lat' => -6.4058, 'lng' => 106.0640]
            ];
            $coords = $provinsiDefaults[$provinsi] ?? $locations['default'];
        } else {
            $coords = $locations[$key];
        }
        
        // Tambahkan sedikit random untuk menghindari overlap
        return [
            'lat' => $coords['lat'] + (rand(-50, 50) / 10000),
            'lng' => $coords['lng'] + (rand(-50, 50) / 10000)
        ];
    }

    public function pengaturanSistem()
    {
        return view('admin.panitia.pengaturan-sistem');
    }

    public function logAktivitas()
    {
        // Simulasi log aktivitas - bisa diganti dengan model Log yang sebenarnya
        $logs = collect([
            ['action' => 'Login', 'user' => 'Admin', 'time' => now()->subMinutes(5), 'ip' => '192.168.1.1'],
            ['action' => 'Verifikasi Pendaftar', 'user' => 'Verifikator', 'time' => now()->subMinutes(10), 'ip' => '192.168.1.2'],
            ['action' => 'Export Data', 'user' => 'Admin', 'time' => now()->subMinutes(15), 'ip' => '192.168.1.1'],
        ]);
        
        return view('admin.panitia.log-aktivitas', compact('logs'));
    }

    public function backupData(Request $request)
    {
        try {
            $filename = 'backup_ppdb_' . date('Y-m-d_H-i-s') . '.sql';
            $path = storage_path('app/backups/');
            
            // Buat direktori jika belum ada
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
            
            // Simulasi backup - dalam implementasi nyata gunakan mysqldump
            $backupContent = "-- PPDB Backup " . date('Y-m-d H:i:s') . "\n";
            $backupContent .= "-- Total Applicants: " . Applicant::count() . "\n";
            $backupContent .= "-- Generated by Admin: " . auth()->user()->name . "\n";
            
            file_put_contents($path . $filename, $backupContent);
            
            return response()->json([
                'success' => true,
                'filename' => $filename,
                'message' => 'Backup berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function sendNotification(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:500'
            ]);
            
            $users = User::where('role', 'user')->get();
            $count = 0;
            
            foreach ($users as $user) {
                // Simulasi kirim notifikasi - bisa diganti dengan email/SMS service
                // Mail::to($user->email)->send(new NotificationMail($request->message));
                $count++;
            }
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Notifikasi berhasil dikirim'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id'
            ]);
            
            $user = User::findOrFail($request->user_id);
            
            // Generate password baru (8 karakter random)
            $newPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
            
            // Update password
            $user->update([
                'password' => Hash::make($newPassword)
            ]);
            
            return response()->json([
                'success' => true,
                'new_password' => $newPassword,
                'message' => 'Password berhasil direset'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function updateMajor(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'kuota' => 'required|integer|min:1'
        ]);
        
        $major = Major::findOrFail($id);
        $major->update($request->all());
        
        return redirect()->route('admin.panitia.master-data')->with('success', 'Data jurusan berhasil diupdate!');
    }
    
    public function updateWave(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date|after:tgl_mulai',
            'biaya_daftar' => 'required|integer|min:0'
        ]);
        
        $wave = Wave::findOrFail($id);
        $wave->update($request->all());
        
        return redirect()->route('admin.panitia.master-data')->with('success', 'Data gelombang berhasil diupdate!');
    }
    
    public function workflowStatus()
    {
        $stats = [
            'submit' => Applicant::where('status', 'SUBMIT')->count(),
            'verified' => Applicant::where('status', 'VERIFIED')->count(),
            'payment_pending' => Applicant::where('status', 'PAYMENT_PENDING')->count(),
            'payment_verified' => Applicant::where('status', 'PAYMENT_VERIFIED')->count(),
            'accepted' => Applicant::where('final_status', 'ACCEPTED')->count(),
            'rejected' => Applicant::where('final_status', 'REJECTED')->count()
        ];
        
        $recentApplicants = Applicant::with(['user', 'major'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
            
        return view('admin.workflow-status', compact('stats', 'recentApplicants'));
    }
    
    public function finalDecision(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        
        // Gunakan WorkflowController untuk konsistensi
        $workflowRequest = new Request([
            'final_status' => $request->final_status,
            'notes' => $request->notes
        ]);
        
        $result = app('App\Http\Controllers\WorkflowController')->finalDecision($workflowRequest, $applicant);
        
        if ($result->getData()->success) {
            return redirect()->back()->with('success', 'Keputusan akhir berhasil disimpan! Siswa akan mendapat notifikasi.');
        } else {
            return redirect()->back()->with('error', $result->getData()->message);
        }
    }
    
    public function keputusanAkhir()
    {
        // Ambil siswa yang menunggu keputusan akhir (sudah bayar tapi belum ada final_status)
        $pendingDecision = Applicant::with(['user', 'major'])
            ->where('status', 'PAYMENT_VERIFIED')
            ->whereNull('final_status')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
            
        $stats = [
            'menunggu_keputusan' => Applicant::where('status', 'PAYMENT_VERIFIED')->whereNull('final_status')->count(),
            'diterima' => Applicant::where('final_status', 'ACCEPTED')->count(),
            'ditolak' => Applicant::where('final_status', 'REJECTED')->count()
        ];
        
        return view('admin.keputusan-akhir', compact('pendingDecision', 'stats'));
    }
}
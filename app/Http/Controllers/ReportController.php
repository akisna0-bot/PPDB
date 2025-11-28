<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Payment;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        $reports = [
            'applicants' => Applicant::count(),
            'payments' => Payment::sum('amount'),
            'majors' => Major::count(),
            'latest_export' => now()->format('d M Y H:i')
        ];
        
        return view('reports.index', compact('reports'));
    }
    
    public function exportApplicantsPdf(Request $request)
    {
        $query = Applicant::with(['user', 'major', 'wave']);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->major_id) {
            $query->where('major_id', $request->major_id);
        }
        
        $applicants = $query->get();
        $title = 'Laporan Data Pendaftar PPDB';
        
        $pdf = Pdf::loadView('reports.applicants-pdf', compact('applicants', 'title'));
        
        return $pdf->download('laporan-pendaftar-' . date('Y-m-d') . '.pdf');
    }
    
    public function exportApplicantsExcel(Request $request)
    {
        return Excel::download(new \App\Exports\ApplicantsExport($request->all()), 'laporan-pendaftar-' . date('Y-m-d') . '.xlsx');
    }
    
    public function exportPaymentsPdf()
    {
        $payments = Payment::with(['applicant.user', 'applicant.major'])->get();
        $title = 'Laporan Pembayaran PPDB';
        
        $pdf = Pdf::loadView('reports.payments-pdf', compact('payments', 'title'));
        
        return $pdf->download('laporan-pembayaran-' . date('Y-m-d') . '.pdf');
    }
    
    public function exportStatisticsPdf()
    {
        $statistics = [
            'total_applicants' => Applicant::count(),
            'verified_applicants' => Applicant::where('status', 'VERIFIED')->count(),
            'pending_applicants' => Applicant::where('status', 'SUBMIT')->count(),
            'rejected_applicants' => Applicant::where('status', 'REJECTED')->count(),
            'total_payments' => Payment::sum('amount'),
            'paid_payments' => Payment::where('status', 'paid')->sum('amount'),
            'major_stats' => Major::withCount('applicants')->get(),
            'geographic_stats' => Applicant::select('kabupaten', DB::raw('COUNT(*) as count'))
                ->whereNotNull('kabupaten')
                ->groupBy('kabupaten')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
        ];
        
        $title = 'Laporan Statistik PPDB';
        
        $pdf = Pdf::loadView('reports.statistics-pdf', compact('statistics', 'title'));
        
        return $pdf->download('laporan-statistik-' . date('Y-m-d') . '.pdf');
    }
}
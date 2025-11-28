<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Major;
use App\Models\Wave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExecutiveDashboardController extends Controller
{
    public function index()
    {
        // Basic Statistics
        $totalApplicants = Applicant::count();
        $verifiedApplicants = Applicant::where('status', 'VERIFIED')->count();
        $pendingApplicants = Applicant::where('status', 'SUBMIT')->count();
        $rejectedApplicants = Applicant::where('status', 'REJECTED')->count();
        
        // Registration by Major
        $majorStats = Major::withCount('applicants')
            ->get()
            ->map(function($major) {
                return [
                    'name' => $major->name,
                    'code' => $major->code,
                    'total' => $major->applicants_count,
                    'kuota' => $major->kuota,
                    'percentage' => $major->kuota > 0 ? round(($major->applicants_count / $major->kuota) * 100, 1) : 0
                ];
            });
        
        // Registration by Wave
        $waveStats = Wave::withCount('applicants')->get();
        
        // Daily Registration (Last 30 days)
        $dailyRegistrations = Applicant::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Monthly Registration (Last 12 months)
        $monthlyRegistrations = Applicant::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'period' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'count' => $item->count
                ];
            });
        
        // Status Distribution
        $statusStats = [
            ['status' => 'Terverifikasi', 'count' => $verifiedApplicants, 'color' => '#10B981'],
            ['status' => 'Menunggu', 'count' => $pendingApplicants, 'color' => '#F59E0B'],
            ['status' => 'Ditolak', 'count' => $rejectedApplicants, 'color' => '#EF4444']
        ];
        
        // School Origin Analysis
        $schoolOrigins = Applicant::select('asal_sekolah', DB::raw('COUNT(*) as count'))
            ->whereNotNull('asal_sekolah')
            ->groupBy('asal_sekolah')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Geographic Distribution
        $geographicStats = Applicant::select('kabupaten', DB::raw('COUNT(*) as count'))
            ->whereNotNull('kabupaten')
            ->groupBy('kabupaten')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        // Recent Activities
        $recentActivities = Applicant::with('user', 'major')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('executive.dashboard', compact(
            'totalApplicants', 'verifiedApplicants', 'pendingApplicants', 'rejectedApplicants',
            'majorStats', 'waveStats', 'dailyRegistrations', 'monthlyRegistrations',
            'statusStats', 'schoolOrigins', 'geographicStats', 'recentActivities'
        ));
    }
    
    public function analytics()
    {
        // Advanced Analytics Data
        $data = [
            'conversion_rate' => $this->getConversionRate(),
            'peak_hours' => $this->getPeakRegistrationHours(),
            'completion_rate' => $this->getDocumentCompletionRate(),
            'demographic_analysis' => $this->getDemographicAnalysis()
        ];
        
        return response()->json($data);
    }
    
    private function getConversionRate()
    {
        $totalUsers = User::where('role', 'user')->count();
        $applicants = Applicant::count();
        
        return $totalUsers > 0 ? round(($applicants / $totalUsers) * 100, 1) : 0;
    }
    
    private function getPeakRegistrationHours()
    {
        return Applicant::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('hour')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();
    }
    
    private function getDocumentCompletionRate()
    {
        $totalApplicants = Applicant::count();
        $completedDocs = Applicant::whereHas('files', function($q) {
            $q->havingRaw('COUNT(*) >= 4'); // At least 4 documents
        })->count();
        
        return $totalApplicants > 0 ? round(($completedDocs / $totalApplicants) * 100, 1) : 0;
    }
    
    private function getDemographicAnalysis()
    {
        return [
            'gender' => Applicant::select('jenis_kelamin', DB::raw('COUNT(*) as count'))
                ->whereNotNull('jenis_kelamin')
                ->groupBy('jenis_kelamin')
                ->get(),
            'age_groups' => Applicant::select(
                    DB::raw('CASE 
                        WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 16 THEN "< 16 tahun"
                        WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 16 AND 17 THEN "16-17 tahun"
                        WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 18 AND 19 THEN "18-19 tahun"
                        ELSE "> 19 tahun"
                    END as age_group'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereNotNull('tanggal_lahir')
                ->groupBy('age_group')
                ->get()
        ];
    }
}
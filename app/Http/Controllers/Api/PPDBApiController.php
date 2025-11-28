<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Major;
use App\Models\Wave;
use App\Models\Payment;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Validator;

class PPDBApiController extends Controller
{
    /**
     * Get dashboard summary untuk executive dashboard
     */
    public function dashboardSummary()
    {
        $summary = [
            'total_pendaftar' => Applicant::count(),
            'total_terverifikasi' => Applicant::where('status', 'ADM_PASS')->count(),
            'total_bayar' => Applicant::where('status', 'PAID')->count(),
            'total_lulus' => Applicant::where('status', 'LULUS')->count(),
            'pendaftar_hari_ini' => Applicant::whereDate('created_at', today())->count(),
            'kuota_tersedia' => Major::sum('kuota'),
            'kuota_terisi' => Applicant::where('status', 'LULUS')->count(),
            'persentase_terisi' => Major::sum('kuota') > 0 ? 
                round((Applicant::where('status', 'LULUS')->count() / Major::sum('kuota')) * 100, 2) : 0
        ];

        return response()->json([
            'success' => true,
            'data' => $summary
        ]);
    }

    /**
     * Get data untuk peta sebaran
     */
    public function mapData()
    {
        $applicants = Applicant::select('id', 'nama_lengkap', 'alamat_lengkap', 'latitude', 'longitude', 'status')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($applicant) {
                return [
                    'id' => $applicant->id,
                    'nama' => $applicant->nama_lengkap,
                    'alamat' => $applicant->alamat_lengkap,
                    'lat' => (float) $applicant->latitude,
                    'lng' => (float) $applicant->longitude,
                    'status' => $applicant->status,
                    'color' => $this->getStatusColor($applicant->status)
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $applicants
        ]);
    }

    /**
     * Health check untuk monitoring AWS
     */
    public function healthCheck()
    {
        try {
            $dbStatus = \DB::connection()->getPdo() ? 'connected' : 'disconnected';
            
            return response()->json([
                'status' => 'healthy',
                'timestamp' => now()->toISOString(),
                'database' => $dbStatus,
                'version' => '1.0.0'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'unhealthy',
                'timestamp' => now()->toISOString(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function getStatusColor($status)
    {
        $colors = [
            'DRAFT' => '#gray',
            'SUBMIT' => '#blue',
            'ADM_PASS' => '#green',
            'ADM_REJECT' => '#red',
            'MENUNGGU_BAYAR' => '#yellow',
            'PAID' => '#purple',
            'LULUS' => '#emerald',
            'TIDAK_LULUS' => '#red',
            'CADANGAN' => '#orange'
        ];

        return $colors[$status] ?? '#gray';
    }
}
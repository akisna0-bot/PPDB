<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeographicController extends Controller
{
    public function index()
    {
        // Get geographic distribution data
        $geographicData = $this->getGeographicData();
        $heatmapData = $this->getHeatmapData();
        $provinceStats = $this->getProvinceStats();
        $distanceStats = $this->getDistanceStats();
        
        return view('geographic.index', compact('geographicData', 'heatmapData', 'provinceStats', 'distanceStats'));
    }
    
    public function mapData()
    {
        // API endpoint for map data
        return response()->json([
            'markers' => $this->getMarkerData(),
            'heatmap' => $this->getHeatmapData(),
            'statistics' => $this->getMapStatistics()
        ]);
    }
    
    private function getGeographicData()
    {
        return Applicant::select(
                'kecamatan',
                'kota', 
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(CASE WHEN status = "verified" THEN 1 ELSE 0 END) * 100 as verification_rate')
            )
            ->whereNotNull('kecamatan')
            ->whereNotNull('kota')
            ->groupBy('kecamatan', 'kota')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    private function getHeatmapData()
    {
        // Koordinat kecamatan di Kabupaten Bandung
        $kecamatanCoords = [
            'Cileunyi' => [-6.9347, 107.7425],
            'Rancaekek' => [-6.9697, 107.7581],
            'Majalaya' => [-7.0478, 107.7581],
            'Solokan Jeruk' => [-7.0833, 107.7167],
            'Paseh' => [-7.1000, 107.7667],
            'Ibun' => [-6.9833, 107.8167],
            'Soreang' => [-7.0333, 107.5167],
            'Katapang' => [-7.0167, 107.5333],
            'Banjaran' => [-7.0500, 107.5833],
            'Arjasari' => [-7.1167, 107.4833],
            'Pangalengan' => [-7.1833, 107.5833],
            'Kertasari' => [-7.3167, 107.6000],
            'Pacet' => [-6.9500, 107.5500],
            'Ciwidey' => [-7.1500, 107.4833],
            'Pasirjambu' => [-7.1167, 107.4167],
            'Cimaung' => [-7.0833, 107.5500],
            'Margaasih' => [-6.9167, 107.5833],
            'Margahayu' => [-6.9333, 107.6167],
            'Dayeuhkolot' => [-6.9500, 107.6333],
            'Bojongsoang' => [-6.9667, 107.6500],
            'Cicalengka' => [-6.8833, 107.7833],
            'Nagreg' => [-7.0167, 107.8833],
            'Rancabali' => [-7.1333, 107.3833],
            'Cikancung' => [-6.8667, 107.8167],
            'Cilengkrang' => [-6.9000, 107.7167]
        ];
        
        $heatmapPoints = [];
        
        // Get real data from applicants with coordinates
        $applicants = Applicant::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('kecamatan', 'kota', 'latitude', 'longitude', DB::raw('COUNT(*) as count'))
            ->groupBy('kecamatan', 'kota', 'latitude', 'longitude')
            ->get();
        
        // If no real coordinates, use default coordinates with actual counts
        if ($applicants->isEmpty()) {
            $kecamatanCounts = Applicant::select('kecamatan', 'kota', DB::raw('COUNT(*) as count'))
                ->whereNotNull('kecamatan')
                ->groupBy('kecamatan', 'kota')
                ->get();
                
            foreach ($kecamatanCounts as $data) {
                if (isset($kecamatanCoords[$data->kecamatan])) {
                    $coords = $kecamatanCoords[$data->kecamatan];
                    $heatmapPoints[] = [
                        'lat' => $coords[0],
                        'lng' => $coords[1],
                        'count' => $data->count,
                        'city' => $data->kecamatan,
                        'province' => $data->kota
                    ];
                }
            }
        } else {
            foreach ($applicants as $applicant) {
                $heatmapPoints[] = [
                    'lat' => (float) $applicant->latitude,
                    'lng' => (float) $applicant->longitude,
                    'count' => $applicant->count,
                    'city' => $applicant->kecamatan,
                    'province' => $applicant->kota
                ];
            }
        }
        
        return $heatmapPoints;
    }
    
    private function getMarkerData()
    {
        $markers = [];
        
        // Get applicant data grouped by kecamatan
        $kecamatanData = Applicant::select(
                'kecamatan',
                DB::raw('COUNT(*) as count'),
                DB::raw('AVG(latitude) as avg_lat'),
                DB::raw('AVG(longitude) as avg_lng'),
                DB::raw('SUM(CASE WHEN status = "verified" THEN 1 ELSE 0 END) as verified'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('kecamatan')
            ->orderBy('count', 'desc')
            ->get();
        
        foreach ($kecamatanData as $data) {
            $markers[] = [
                'lat' => (float) $data->avg_lat,
                'lng' => (float) $data->avg_lng,
                'title' => $data->kecamatan,
                'count' => $data->count,
                'verified' => $data->verified,
                'pending' => $data->pending,
                'rejected' => $data->rejected,
                'verification_rate' => $data->count > 0 ? round(($data->verified / $data->count) * 100, 1) : 0
            ];
        }
        
        return $markers;
    }
    
    private function getProvinceStats()
    {
        return Applicant::select(
                'kecamatan',
                DB::raw('COUNT(*) as total_applicants'),
                DB::raw('SUM(CASE WHEN status = "verified" THEN 1 ELSE 0 END) as verified'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderBy('total_applicants', 'desc')
            ->get();
    }
    
    private function getMapStatistics()
    {
        return [
            'total_locations' => Applicant::whereNotNull('kecamatan')->distinct('kecamatan')->count(),
            'total_kecamatan' => Applicant::whereNotNull('kecamatan')->distinct('kecamatan')->count(),
            'most_popular_kecamatan' => Applicant::select('kecamatan', DB::raw('COUNT(*) as count'))
                ->whereNotNull('kecamatan')
                ->groupBy('kecamatan')
                ->orderBy('count', 'desc')
                ->first(),
            'coverage_area' => 'Kabupaten Bandung'
        ];
    }
    
    private function getDistanceStats()
    {
        // Definisi kecamatan berdasarkan jarak dari sekolah (Cileunyi)
        $nearKecamatan = ['Cileunyi', 'Cilengkrang', 'Rancaekek'];
        $mediumKecamatan = ['Majalaya', 'Cicalengka', 'Ibun', 'Paseh', 'Bojongsoang', 'Dayeuhkolot'];
        
        $nearCount = Applicant::whereIn('kecamatan', $nearKecamatan)->count();
        $mediumCount = Applicant::whereIn('kecamatan', $mediumKecamatan)->count();
        $farCount = Applicant::whereNotIn('kecamatan', array_merge($nearKecamatan, $mediumKecamatan))
            ->whereNotNull('kecamatan')
            ->count();
            
        return [
            'near' => $nearCount,
            'medium' => $mediumCount,
            'far' => $farCount
        ];
    }
}
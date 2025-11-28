<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;

class GeocodeAddresses extends Command
{
    protected $signature = 'geocode:addresses {--limit=10}';
    protected $description = 'Geocode applicant addresses to get coordinates';

    public function handle()
    {
        $limit = $this->option('limit');
        
        $applicants = Applicant::whereNull('latitude')
            ->whereNotNull('alamat_lengkap')
            ->limit($limit)
            ->get();

        if ($applicants->isEmpty()) {
            $this->info('No addresses to geocode.');
            return;
        }

        $this->info("Geocoding {$applicants->count()} addresses...");
        
        $bar = $this->output->createProgressBar($applicants->count());
        $bar->start();

        foreach ($applicants as $applicant) {
            $coordinates = $this->geocodeAddress($applicant);
            
            if ($coordinates) {
                $applicant->update([
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng'],
                    'full_address' => $applicant->alamat_lengkap . ', ' . $applicant->kabupaten . ', ' . $applicant->provinsi
                ]);
            }
            
            $bar->advance();
            
            // Rate limiting - sleep for 1 second between requests
            sleep(1);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Geocoding completed!');
    }

    private function geocodeAddress($applicant)
    {
        // Simple geocoding using Nominatim (OpenStreetMap)
        $address = urlencode($applicant->alamat_lengkap . ', ' . $applicant->kabupaten . ', ' . $applicant->provinsi . ', Indonesia');
        $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}&limit=1";
        
        try {
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if (!empty($data)) {
                return [
                    'lat' => (float) $data[0]['lat'],
                    'lng' => (float) $data[0]['lon']
                ];
            }
        } catch (\Exception $e) {
            $this->error("Error geocoding address for applicant {$applicant->id}: " . $e->getMessage());
        }
        
        return null;
    }
}
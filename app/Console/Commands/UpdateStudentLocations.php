<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Applicant;

class UpdateStudentLocations extends Command
{
    protected $signature = 'students:update-locations';
    protected $description = 'Update lokasi siswa dengan data yang realistis';

    public function handle()
    {
        $this->info('🗺️ Mengupdate lokasi siswa...');
        
        $locations = [
            ['alamat' => 'Jl. Raya Cinunuk No. 123', 'kabupaten' => 'Bandung', 'provinsi' => 'Jawa Barat'],
            ['alamat' => 'Jl. Permata Biru No. 45', 'kabupaten' => 'Jakarta Selatan', 'provinsi' => 'DKI Jakarta'],
            ['alamat' => 'Jl. Sudirman No. 67', 'kabupaten' => 'Jakarta Pusat', 'provinsi' => 'DKI Jakarta'],
            ['alamat' => 'Jl. Margonda Raya No. 89', 'kabupaten' => 'Depok', 'provinsi' => 'Jawa Barat'],
            ['alamat' => 'Jl. BSD Raya No. 12', 'kabupaten' => 'Tangerang', 'provinsi' => 'Banten'],
            ['alamat' => 'Jl. Kemang Raya No. 34', 'kabupaten' => 'Jakarta Selatan', 'provinsi' => 'DKI Jakarta'],
            ['alamat' => 'Jl. Cibubur No. 56', 'kabupaten' => 'Jakarta Timur', 'provinsi' => 'DKI Jakarta'],
            ['alamat' => 'Jl. Bogor Raya No. 78', 'kabupaten' => 'Bogor', 'provinsi' => 'Jawa Barat']
        ];
        
        $applicants = Applicant::all();
        
        foreach ($applicants as $index => $applicant) {
            $location = $locations[$index % count($locations)];
            
            $applicant->update([
                'alamat_lengkap' => $location['alamat'],
                'kabupaten' => $location['kabupaten'],
                'provinsi' => $location['provinsi']
            ]);
            
            $this->info("✅ Update {$applicant->user->name}: {$location['kabupaten']}, {$location['provinsi']}");
        }
        
        $this->info('🎉 Lokasi siswa berhasil diupdate!');
    }
}
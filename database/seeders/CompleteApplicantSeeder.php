<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Payment;
use App\Models\ApplicantFile;
use App\Models\Major;
use App\Models\Wave;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompleteApplicantSeeder extends Seeder
{
    public function run()
    {
        // First, let's check if we need to add more columns to applicants table
        $majors = Major::all();
        $waves = Wave::all();
        
        $applicantsData = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki.new@gmail.com',
                'nik' => '3201012005010001',
                'birth_place' => 'Jakarta',
                'birth_date' => '2005-01-20',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, RT 001/RW 005',
                'village' => 'Kebayoran Baru',
                'district' => 'Jakarta Selatan',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12110',
                'father_name' => 'Budi Pratama',
                'father_job' => 'PNS',
                'father_phone' => '081234567891',
                'mother_name' => 'Siti Nurhaliza',
                'mother_job' => 'Guru',
                'mother_phone' => '081234567892',
                'previous_school' => 'SMP Negeri 15 Jakarta',
                'nisn' => '0012345678'
            ],
            [
                'name' => 'Siti Rahayu Ningrum',
                'email' => 'siti.rahayu.new@gmail.com',
                'nik' => '3201012006020002',
                'birth_place' => 'Bandung',
                'birth_date' => '2006-02-15',
                'gender' => 'P',
                'religion' => 'Islam',
                'phone' => '082345678901',
                'address' => 'Jl. Sudirman No. 456, RT 002/RW 003',
                'village' => 'Menteng',
                'district' => 'Jakarta Pusat',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10310',
                'father_name' => 'Agus Ningrum',
                'father_job' => 'Wiraswasta',
                'father_phone' => '082345678902',
                'mother_name' => 'Dewi Sartika',
                'mother_job' => 'IRT',
                'mother_phone' => '082345678903',
                'previous_school' => 'SMP Swasta Al-Azhar',
                'nisn' => '0012345679'
            ],
            [
                'name' => 'Budi Hartono Wijaya',
                'email' => 'budi.hartono.new@gmail.com',
                'nik' => '3201012005030003',
                'birth_place' => 'Surabaya',
                'birth_date' => '2005-03-10',
                'gender' => 'L',
                'religion' => 'Kristen',
                'phone' => '083456789012',
                'address' => 'Jl. Gatot Subroto No. 789, RT 003/RW 007',
                'village' => 'Kuningan',
                'district' => 'Jakarta Selatan',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12950',
                'father_name' => 'Hartono Wijaya',
                'father_job' => 'TNI',
                'father_phone' => '083456789013',
                'mother_name' => 'Maria Magdalena',
                'mother_job' => 'Perawat',
                'mother_phone' => '083456789014',
                'previous_school' => 'SMP Katolik Santo Yusup',
                'nisn' => '0012345680'
            ],
            [
                'name' => 'Dewi Lestari Putri',
                'email' => 'dewi.lestari.new@gmail.com',
                'nik' => '3201012006040004',
                'birth_place' => 'Medan',
                'birth_date' => '2006-04-25',
                'gender' => 'P',
                'religion' => 'Islam',
                'phone' => '084567890123',
                'address' => 'Jl. Thamrin No. 321, RT 004/RW 002',
                'village' => 'Tanah Abang',
                'district' => 'Jakarta Pusat',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '10230',
                'father_name' => 'Lestari Gunawan',
                'father_job' => 'Dokter',
                'father_phone' => '084567890124',
                'mother_name' => 'Putri Handayani',
                'mother_job' => 'Bidan',
                'mother_phone' => '084567890125',
                'previous_school' => 'SMP Negeri 8 Jakarta',
                'nisn' => '0012345681'
            ],
            [
                'name' => 'Eko Susanto Putra',
                'email' => 'eko.susanto.new@gmail.com',
                'nik' => '3201012005050005',
                'birth_place' => 'Semarang',
                'birth_date' => '2005-05-12',
                'gender' => 'L',
                'religion' => 'Islam',
                'phone' => '085678901234',
                'address' => 'Jl. Rasuna Said No. 654, RT 005/RW 001',
                'village' => 'Setiabudi',
                'district' => 'Jakarta Selatan',
                'city' => 'Jakarta',
                'province' => 'DKI Jakarta',
                'postal_code' => '12920',
                'father_name' => 'Susanto Putra',
                'father_job' => 'Polri',
                'father_phone' => '085678901235',
                'mother_name' => 'Indah Permata',
                'mother_job' => 'Guru',
                'mother_phone' => '085678901236',
                'previous_school' => 'SMP Negeri 22 Jakarta',
                'nisn' => '0012345682'
            ]
        ];

        foreach ($applicantsData as $index => $data) {
            // Create user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
                'role' => 'user',
            ]);

            // Create applicant with complete data
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'major_id' => $majors->random()->id,
                'wave_id' => $waves->random()->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad(3000 + $index, 4, '0', STR_PAD_LEFT),
                'status' => 'SUBMIT',
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);

            // Create payment
            Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => 'INV' . date('Y') . str_pad(3000 + $index, 4, '0', STR_PAD_LEFT),
                'amount' => 150000,
                'payment_type' => 'registration',
                'status' => 'paid',
                'payment_method' => 'bank_transfer',
                'paid_at' => Carbon::now()->subDays(rand(1, 3)),
            ]);

            // Create applicant files (berkas)
            $fileTypes = [
                ['type' => 'photo', 'name' => 'Pas Foto 3x4'],
                ['type' => 'birth_certificate', 'name' => 'Akta Kelahiran'],
                ['type' => 'family_card', 'name' => 'Kartu Keluarga'],
                ['type' => 'school_certificate', 'name' => 'Ijazah SMP'],
                ['type' => 'report_card', 'name' => 'Rapor Semester 5-6']
            ];

            foreach ($fileTypes as $fileType) {
                ApplicantFile::create([
                    'applicant_id' => $applicant->id,
                    'filename' => strtolower(str_replace(' ', '_', $fileType['name'])) . '_' . $applicant->id . '.pdf',
                    'original_name' => $fileType['name'] . '_' . $data['name'] . '.pdf',
                    'file_type' => $fileType['type'],
                    'path' => 'documents/' . $applicant->id . '/' . $fileType['type'] . '.pdf',
                    'size_kb' => rand(200, 1500),
                    'is_valid' => true,
                    'notes' => 'File berkas ' . $fileType['name'] . ' lengkap dan sesuai',
                    'created_at' => Carbon::now()->subDays(rand(1, 4)),
                ]);
            }
        }

        echo "✅ Data pendaftar lengkap berhasil dibuat!\n";
        echo "👥 " . count($applicantsData) . " pendaftar dengan data pribadi lengkap\n";
        echo "📄 " . (count($applicantsData) * 5) . " berkas pendaftaran\n";
        echo "💰 " . count($applicantsData) . " pembayaran lunas\n";
        echo "\n🔍 Sekarang login sebagai verifikator untuk melihat data lengkap!\n";
    }
}
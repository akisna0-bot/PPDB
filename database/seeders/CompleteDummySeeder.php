<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Applicant;
use App\Models\ApplicantFile;
use App\Models\Payment;
use App\Models\Major;
use App\Models\Wave;

class CompleteDummySeeder extends Seeder
{
    public function run()
    {
        // Clear existing student data
        $studentUsers = User::where('role', 'user')->get();
        foreach ($studentUsers as $user) {
            if ($user->applicant) {
                $user->applicant->files()->delete();
                $user->applicant->payments()->delete();
                $user->applicant->delete();
            }
            $user->delete();
        }

        $majors = Major::all();
        $wave = Wave::first();

        // Create 5 complete dummy applicants
        for ($i = 1; $i <= 5; $i++) {
            // Create user
            $user = User::create([
                'name' => "Siswa Test $i",
                'email' => "siswa$i@test.com",
                'password' => Hash::make('password'),
                'role' => 'user'
            ]);

            // Create applicant with complete data
            $applicant = Applicant::create([
                'user_id' => $user->id,
                'no_pendaftaran' => 'PPDB' . date('Y') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'wave_id' => $wave->id,
                'major_id' => $majors->random()->id,
                'nama_lengkap' => "Siswa Test Lengkap $i",
                'nik' => '327' . str_pad($i, 13, '0', STR_PAD_LEFT),
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '2006-01-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'jenis_kelamin' => $i % 2 == 0 ? 'P' : 'L',
                'agama' => 'Islam',
                'no_hp' => '08123456789' . $i,
                'alamat_lengkap' => "Jl. Test No. $i, Jakarta Selatan",
                'provinsi' => 'DKI Jakarta',
                'kabupaten' => 'Jakarta Selatan',
                'kecamatan' => 'Kebayoran Baru',
                'kode_pos' => '12560',
                'latitude' => -6.2088 + ($i * 0.01),
                'longitude' => 106.8456 + ($i * 0.01),
                'asal_sekolah' => "SMP Negeri $i Jakarta",
                'tahun_lulus' => 2024,
                'nama_ayah' => "Ayah Siswa $i",
                'nama_ibu' => "Ibu Siswa $i",
                'no_hp_ortu' => '08987654321' . $i,
                'status' => 'SUBMIT'
            ]);

            // Create complete files for each applicant
            $documentTypes = ['ijazah', 'ktp', 'kartu_keluarga', 'foto', 'rapor'];
            foreach ($documentTypes as $docType) {
                ApplicantFile::create([
                    'applicant_id' => $applicant->id,
                    'filename' => "$docType-siswa-$i.pdf",
                    'original_name' => "$docType-siswa-$i.pdf",
                    'file_type' => $docType,
                    'path' => "documents/$docType-siswa-$i.pdf",
                    'size_kb' => rand(100, 500),
                    'is_valid' => false,
                    'notes' => null
                ]);
            }

            // Create payment for each applicant
            Payment::create([
                'applicant_id' => $applicant->id,
                'invoice_number' => 'INV' . date('Ymd') . str_pad($i, 3, '0', STR_PAD_LEFT),
                'amount' => 350000,
                'payment_type' => 'registration',
                'payment_method' => 'bank_transfer',
                'status' => 'pending',
                'notes' => "Pembayaran pendaftaran siswa $i"
            ]);
        }

        // Set different statuses for workflow demonstration
        $applicants = Applicant::where('user_id', '>', 4)->get();
        
        if ($applicants->count() >= 5) {
            // Applicant 1: Complete workflow - PAID
            $applicant1 = $applicants[0];
            $applicant1->update([
                'status' => 'PAID',
                'user_verifikasi_adm' => 'Verifikator Admin',
                'tgl_verifikasi_adm' => now(),
                'catatan_verifikasi' => 'Semua berkas lengkap dan valid'
            ]);
            $applicant1->files()->update(['is_valid' => true, 'notes' => 'Berkas valid']);
            $applicant1->payments()->update([
                'status' => 'paid',
                'paid_at' => now(),
                'verified_by' => 3, // ID user keuangan
                'verified_at' => now()
            ]);

            // Applicant 2: Verified admin, pending payment
            $applicant2 = $applicants[1];
            $applicant2->update([
                'status' => 'ADM_PASS',
                'user_verifikasi_adm' => 'Verifikator Admin',
                'tgl_verifikasi_adm' => now(),
                'catatan_verifikasi' => 'Berkas sudah diverifikasi, silakan lakukan pembayaran'
            ]);
            $applicant2->files()->update(['is_valid' => true, 'notes' => 'Berkas valid']);

            // Applicant 3: Rejected by admin
            $applicant3 = $applicants[2];
            $applicant3->update([
                'status' => 'ADM_REJECT',
                'user_verifikasi_adm' => 'Verifikator Admin',
                'tgl_verifikasi_adm' => now(),
                'catatan_verifikasi' => 'Foto tidak jelas, mohon upload ulang'
            ]);
            $applicant3->files()->where('file_type', 'foto')->update(['is_valid' => false, 'notes' => 'Foto tidak jelas']);

            // Applicant 4: Pending verification
            $applicant4 = $applicants[3];
            $applicant4->update(['status' => 'SUBMIT']);

            // Applicant 5: Payment rejected
            $applicant5 = $applicants[4];
            $applicant5->update([
                'status' => 'ADM_PASS',
                'user_verifikasi_adm' => 'Verifikator Admin',
                'tgl_verifikasi_adm' => now(),
                'catatan_verifikasi' => 'Berkas lengkap'
            ]);
            $applicant5->files()->update(['is_valid' => true, 'notes' => 'Berkas valid']);
            $applicant5->payments()->update([
                'status' => 'failed',
                'notes' => 'Bukti transfer tidak valid'
            ]);
        }

        echo "✅ 5 Data dummy lengkap berhasil dibuat!\n";
        echo "📊 Status: 1 Selesai, 1 Lulus Admin, 1 Ditolak, 1 Pending, 1 Bayar Ditolak\n";
        echo "📄 Semua berkas lengkap (5 dokumen per siswa)\n";
        echo "💰 Semua pembayaran terhubung dengan workflow\n";
    }
}
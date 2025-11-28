<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\ApplicantFile;
use App\Models\Major;
use App\Models\Wave;

class ApplicantController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Redirect berdasarkan role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kepsek':
                return redirect()->route('kepsek.dashboard');
            case 'keuangan':
                return redirect()->route('keuangan.dashboard');
            case 'verifikator_adm':
                return redirect()->route('verifikator.dashboard');
            default:
                // User biasa (siswa)
                $applicant = Applicant::with(['major', 'wave', 'files'])
                    ->where('user_id', auth()->id())->first();
                $notifications = collect();
                $unreadCount = 0;
                
                return view('dashboard', compact('applicant', 'notifications', 'unreadCount'));
        }
    }

    public function create()
    {
        $majors = Major::all();
        $waves = Wave::all();
        return view('pendaftaran.create', compact('majors', 'waves'));
    }

    public function store(Request $request)
    {
        // Set default wave jika tidak ada
        if (!$request->wave_id) {
            $defaultWave = Wave::first();
            if ($defaultWave) {
                $request->merge(['wave_id' => $defaultWave->id]);
            }
        }
        
        $request->validate([
            'major_id' => 'required|exists:majors,id',
            'wave_id' => 'required|exists:waves,id',
            'nik' => 'required|digits:16|unique:applicants,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'no_hp' => 'required|string|min:10|max:15',
            'alamat_lengkap' => 'required|string',
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|digits:4|integer|min:2020|max:' . (date('Y') + 1),
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'no_hp_ortu' => 'required|string|min:10|max:15',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180'
        ]);

        $noPendaftaran = 'PPDB-' . date('Y') . '-' . str_pad(Applicant::count() + 1, 4, '0', STR_PAD_LEFT);

        $applicant = Applicant::create(array_merge($request->all(), [
            'user_id' => auth()->id(),
            'no_pendaftaran' => $noPendaftaran,
            'status' => 'SUBMIT'
        ]));

        // Log aktivitas
        \App\Models\LogAktivitas::create([
            'user_id' => auth()->id(),
            'aksi' => 'PENDAFTARAN_BARU',
            'objek' => 'APPLICANT',
            'objek_data' => [
                'applicant_id' => $applicant->id,
                'no_pendaftaran' => $noPendaftaran,
                'major_id' => $request->major_id,
                'wave_id' => $request->wave_id
            ],
            'waktu' => now(),
            'ip' => $request->ip()
        ]);

        // Kirim notifikasi
        $notificationService = new \App\Services\NotificationService();
        \App\Models\Notification::create([
            'user_id' => auth()->id(),
            'title' => '✅ Pendaftaran Berhasil!',
            'message' => "Pendaftaran dengan nomor {$noPendaftaran} berhasil disimpan. Silakan lengkapi dokumen dan lakukan pembayaran.",
            'type' => 'success'
        ]);

        return redirect()->route('dashboard')->with('success', "Pendaftaran berhasil! Nomor pendaftaran Anda: {$noPendaftaran}");
    }
    
    public function update(Request $request, $id)
    {
        $applicant = Applicant::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'major_id' => 'required',
            'nik' => 'required|digits:16|unique:applicants,nik,' . $applicant->id,
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required',
            'no_hp' => 'required|digits_between:10,15',
            'alamat_lengkap' => 'required',
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|digits:4',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'no_hp_ortu' => 'required|digits_between:10,15'
        ]);
        
        $applicant->update($request->all());
        
        // Reset status ke SUBMIT jika data diubah setelah ditolak
        if (in_array($applicant->status, ['REJECTED', 'ADM_REJECT'])) {
            $applicant->update([
                'status' => 'SUBMIT',
                'catatan_verifikasi' => null,
                'tgl_verifikasi_adm' => null,
                'user_verifikasi_adm' => null
            ]);
        }
        
        return redirect()->route('dashboard')->with('success', 'Data berhasil diperbaiki!');
    }

    public function documents()
    {
        $applicant = Applicant::where('user_id', auth()->id())->first();
        
        if (!$applicant) {
            return redirect()->route('pendaftaran.create')->with('error', 'Silakan lengkapi pendaftaran terlebih dahulu.');
        }
        
        // Ambil semua file dengan relasi yang benar
        $files = ApplicantFile::where('applicant_id', $applicant->id)
            ->orderBy('document_type')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $documentTypes = ApplicantFile::getDocumentTypes();
        
        return view('dokumen.index', compact('applicant', 'files', 'documentTypes'));
    }
    
    public function uploadFile(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
                'document_type' => 'required|in:ijazah,skhun,rapor,akta_kelahiran,kartu_keluarga,pas_foto'
            ]);

            $applicant = Applicant::where('user_id', auth()->id())->firstOrFail();
            
            // Hapus file lama jika ada
            ApplicantFile::where('applicant_id', $applicant->id)
                ->where('document_type', $request->document_type)
                ->delete();
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $request->document_type . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('applicant_files', $filename, 'public');
                
                ApplicantFile::create([
                    'applicant_id' => $applicant->id,
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'document_type' => $request->document_type,
                    'path' => $path,
                    'size_kb' => round($file->getSize() / 1024, 2),
                    'is_required' => true,
                    'status' => 'pending'
                ]);
                
                // Reset status ke SUBMIT jika upload file setelah ditolak
                if (in_array($applicant->status, ['REJECTED', 'ADM_REJECT'])) {
                    $applicant->update([
                        'status' => 'SUBMIT',
                        'catatan_verifikasi' => null,
                        'tgl_verifikasi_adm' => null,
                        'user_verifikasi_adm' => null
                    ]);
                }
                
                return redirect()->route('dokumen.index')->with('success', 'File berhasil diupload!');
            }

            return redirect()->route('dokumen.index')->with('error', 'File tidak ditemukan');
            
        } catch (\Exception $e) {
            return redirect()->route('dokumen.index')->with('error', 'Gagal upload: ' . $e->getMessage());
        }
    }
    
    public function deleteFile($id)
    {
        $file = ApplicantFile::whereHas('applicant', function($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        
        if (\Storage::disk('public')->exists($file->path)) {
            \Storage::disk('public')->delete($file->path);
        }
        
        $file->delete();
        
        return redirect()->route('dokumen.index')->with('success', 'File berhasil dihapus!');
    }
}

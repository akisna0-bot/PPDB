<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Hapus data lama yang tidak sesuai
        DB::table('applicant_files')->whereNotIn('document_type', [
            'ijazah', 'skhun', 'rapor', 'akta_kelahiran', 'kartu_keluarga', 'pas_foto'
        ])->delete();
        
        // Update document_type yang salah
        DB::table('applicant_files')->where('document_type', 'kk')->update(['document_type' => 'kartu_keluarga']);
        DB::table('applicant_files')->where('document_type', 'ktp_ortu')->update(['document_type' => 'kartu_keluarga']);
        DB::table('applicant_files')->where('document_type', 'foto')->update(['document_type' => 'pas_foto']);
    }

    public function down()
    {
        // Tidak ada rollback
    }
};
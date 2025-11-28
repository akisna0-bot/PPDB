<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicant_files', function (Blueprint $table) {
            $table->enum('document_type', [
                'ijazah', 'skhun', 'rapor', 'akta_kelahiran', 'kartu_keluarga', 
                'pas_foto', 'surat_sehat', 'surat_kelakuan_baik', 'sertifikat_prestasi', 'lainnya'
            ])->after('file_type');
            $table->boolean('is_required')->default(true)->after('document_type');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_required');
            $table->timestamp('verified_at')->nullable()->after('notes');
            $table->string('verified_by')->nullable()->after('verified_at');
        });
    }

    public function down()
    {
        Schema::table('applicant_files', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'is_required', 'status', 'verified_at', 'verified_by']);
        });
    }
};
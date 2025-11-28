<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Update status enum sesuai spesifikasi
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('DRAFT', 'SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID') DEFAULT 'DRAFT'");
        
        // Tambah kolom catatan verifikasi jika belum ada
        Schema::table('applicants', function (Blueprint $table) {
            if (!Schema::hasColumn('applicants', 'catatan_verifikasi')) {
                $table->text('catatan_verifikasi')->nullable();
            }
        });
    }

    public function down()
    {
        DB::statement("ALTER TABLE applicants MODIFY COLUMN status ENUM('DRAFT', 'SUBMIT', 'VERIFIED', 'REJECTED') DEFAULT 'DRAFT'");
    }
};
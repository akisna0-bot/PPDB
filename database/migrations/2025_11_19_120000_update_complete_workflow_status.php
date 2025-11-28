<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('status', [
                'SUBMIT',           // Siswa submit berkas
                'VERIFIED',         // Verifikator approve berkas
                'REJECTED',         // Verifikator reject berkas
                'PAYMENT_PENDING',  // Menunggu pembayaran
                'PAYMENT_VERIFIED', // Keuangan sudah verifikasi pembayaran
                'FINAL_REVIEW'      // Menunggu keputusan akhir admin
            ])->default('SUBMIT');
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('status', ['SUBMIT', 'VERIFIED', 'REJECTED', 'PAID'])->default('SUBMIT');
        });
    }
};
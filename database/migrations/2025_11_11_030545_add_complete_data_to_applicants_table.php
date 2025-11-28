<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            // Data Pribadi
            $table->string('nik', 16)->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'])->nullable();
            $table->string('no_hp', 15)->nullable();
            
            // Alamat
            $table->text('alamat_lengkap')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos', 5)->nullable();
            
            // Data Sekolah Asal
            $table->string('asal_sekolah')->nullable();
            $table->string('npsn_asal', 8)->nullable();
            $table->string('alamat_sekolah_asal')->nullable();
            $table->year('tahun_lulus')->nullable();
            
            // Data Orang Tua/Wali
            $table->string('nama_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('hubungan_wali')->nullable();
            $table->string('no_hp_ortu', 15)->nullable();
            
            // Data Tambahan
            $table->decimal('tinggi_badan', 5, 2)->nullable();
            $table->decimal('berat_badan', 5, 2)->nullable();
            $table->string('golongan_darah', 2)->nullable();
            $table->text('riwayat_penyakit')->nullable();
            $table->boolean('beasiswa')->default(false);
            $table->string('jenis_beasiswa')->nullable();
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'no_hp',
                'alamat_lengkap', 'provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'kode_pos',
                'asal_sekolah', 'npsn_asal', 'alamat_sekolah_asal', 'tahun_lulus',
                'nama_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'nama_ibu', 'pekerjaan_ibu', 'penghasilan_ibu',
                'nama_wali', 'hubungan_wali', 'no_hp_ortu',
                'tinggi_badan', 'berat_badan', 'golongan_darah', 'riwayat_penyakit', 'beasiswa', 'jenis_beasiswa'
            ]);
        });
    }
};
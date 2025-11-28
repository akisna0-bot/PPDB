<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel wilayah
        if (!Schema::hasTable('wilayah')) {
            Schema::create('wilayah', function (Blueprint $table) {
                $table->id();
                $table->string('provinsi', 100);
                $table->string('kabupaten', 100);
                $table->string('kecamatan', 100);
                $table->string('kelurahan', 100);
                $table->string('kodepos', 10);
                $table->timestamps();
            });
        }

        // Tambah kolom ke applicants
        if (Schema::hasTable('applicants')) {
            Schema::table('applicants', function (Blueprint $table) {
                if (!Schema::hasColumn('applicants', 'user_verifikasi_payment')) {
                    $table->string('user_verifikasi_payment', 100)->nullable();
                }
                if (!Schema::hasColumn('applicants', 'tgl_verifikasi_payment')) {
                    $table->datetime('tgl_verifikasi_payment')->nullable();
                }
            });
        }

        // Tabel pendaftar_data_siswa
        if (!Schema::hasTable('pendaftar_data_siswa')) {
            Schema::create('pendaftar_data_siswa', function (Blueprint $table) {
                $table->bigInteger('pendaftar_id')->primary();
                $table->string('nik', 20)->nullable();
                $table->string('nisn', 20)->nullable();
                $table->string('nama', 120);
                $table->enum('jk', ['L', 'P']);
                $table->string('tmp_lahir', 60);
                $table->date('tgl_lahir');
                $table->text('alamat');
                $table->bigInteger('wilayah_id')->nullable();
                $table->decimal('lat', 10, 7)->nullable();
                $table->decimal('lng', 10, 7)->nullable();
                $table->timestamps();
            });
        }

        // Tabel pendaftar_data_ortu
        if (!Schema::hasTable('pendaftar_data_ortu')) {
            Schema::create('pendaftar_data_ortu', function (Blueprint $table) {
                $table->bigInteger('pendaftar_id')->primary();
                $table->string('nama_ayah', 120)->nullable();
                $table->string('pekerjaan_ayah', 100)->nullable();
                $table->string('hp_ayah', 20)->nullable();
                $table->string('nama_ibu', 120)->nullable();
                $table->string('pekerjaan_ibu', 100)->nullable();
                $table->string('hp_ibu', 20)->nullable();
                $table->string('wali_nama', 120)->nullable();
                $table->string('wali_hp', 20)->nullable();
                $table->timestamps();
            });
        }

        // Tabel pendaftar_asal_sekolah
        if (!Schema::hasTable('pendaftar_asal_sekolah')) {
            Schema::create('pendaftar_asal_sekolah', function (Blueprint $table) {
                $table->bigInteger('pendaftar_id')->primary();
                $table->string('npsn', 20)->nullable();
                $table->string('nama_sekolah', 150);
                $table->string('kabupaten', 100);
                $table->decimal('nilai_rata', 5, 2)->nullable();
                $table->timestamps();
            });
        }

        // Tabel log_aktivitas
        if (!Schema::hasTable('log_aktivitas')) {
            Schema::create('log_aktivitas', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('user_id');
                $table->string('aksi', 100);
                $table->string('objek', 100);
                $table->json('objek_data')->nullable();
                $table->datetime('waktu');
                $table->string('ip', 45)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('pendaftar_asal_sekolah');
        Schema::dropIfExists('pendaftar_data_ortu');
        Schema::dropIfExists('pendaftar_data_siswa');
        Schema::dropIfExists('wilayah');
    }
};
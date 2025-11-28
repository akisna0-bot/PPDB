<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('applicants', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('no_pendaftaran')->unique();
        $table->foreignId('wave_id')->constrained('waves');
        $table->foreignId('major_id')->constrained('majors');
        $table->enum('status', ['DRAFT','SUBMIT','ADM_PASS','ADM_REJECT','PAID'])->default('DRAFT');
        $table->string('user_verifikasi_adm')->nullable();
        $table->timestamp('tgl_verifikasi_adm')->nullable();
        $table->timestamps();
    });
}
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('aksi');
            $table->string('objek');
            $table->json('objek_data')->nullable();
            $table->timestamp('waktu');
            $table->string('ip')->nullable();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'waktu']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
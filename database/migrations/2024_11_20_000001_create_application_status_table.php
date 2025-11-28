<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('application_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'SUBMIT',           // Baru submit
                'VERIFIED',         // Sudah diverifikasi admin
                'PAYMENT_VERIFIED', // Pembayaran sudah diverifikasi
                'ACCEPTED',         // Diterima
                'REJECTED'          // Ditolak
            ])->default('SUBMIT');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->timestamp('decision_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_status');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_type', ['registration', 'uniform', 'book', 'other'])->default('registration');
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->enum('payment_method', ['bank_transfer', 'virtual_account', 'e_wallet', 'qris', 'cash'])->nullable();
            $table->string('payment_gateway')->nullable(); // midtrans, xendit, etc
            $table->string('transaction_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->json('payment_details')->nullable(); // Store gateway response
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_path')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            
            $table->index(['status', 'payment_type']);
            $table->index('invoice_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
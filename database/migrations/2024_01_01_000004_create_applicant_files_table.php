<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_id')
                  ->constrained('applicants')
                  ->onDelete('cascade');
            $table->string('filename');
            $table->string('original_name');
            $table->string('file_type')->default('document');
            $table->string('path');
            $table->decimal('size_kb', 8, 2)->nullable();
            $table->boolean('is_valid')->default(false);
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_files');
    }
};

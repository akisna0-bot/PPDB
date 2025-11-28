<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->unsignedBigInteger('verified_by')->nullable()->after('catatan_verifikasi');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->enum('final_status', ['ACCEPTED', 'REJECTED'])->nullable()->after('verified_at');
            $table->text('final_notes')->nullable()->after('final_status');
            $table->unsignedBigInteger('decided_by')->nullable()->after('final_notes');
            $table->timestamp('decided_at')->nullable()->after('decided_by');
            
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('decided_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['decided_by']);
            $table->dropColumn([
                'verified_by', 'verified_at', 'final_status', 
                'final_notes', 'decided_by', 'decided_at'
            ]);
        });
    }
};
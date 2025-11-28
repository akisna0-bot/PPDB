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
            $table->enum('status', ['SUBMIT', 'VERIFIED', 'REJECTED'])->default('SUBMIT');
        });
    }

    public function down()
    {
        Schema::table('applicants', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('applicants', function (Blueprint $table) {
            $table->enum('status', ['DRAFT','SUBMIT','ADM_PASS','ADM_REJECT','PAID'])->default('DRAFT');
        });
    }
};
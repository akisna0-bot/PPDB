<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update role verifikator_adm menjadi verifikator untuk konsistensi
        \DB::table('users')
            ->where('role', 'verifikator_adm')
            ->update(['role' => 'verifikator']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Rollback: ubah kembali ke verifikator_adm
        \DB::table('users')
            ->where('role', 'verifikator')
            ->update(['role' => 'verifikator_adm']);
    }
};

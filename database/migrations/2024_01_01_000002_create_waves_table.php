<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
public function up()
{
    Schema::create('waves', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->date('tgl_mulai')->nullable();
        $table->date('tgl_selesai')->nullable();
        $table->decimal('biaya_daftar', 12, 2)->default(0);
        $table->timestamps();
    });
}
};
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    public $timestamps = false;
    
    protected $fillable = ['provinsi', 'kabupaten', 'kecamatan', 'kelurahan', 'kodepos'];
    
    public function pendaftarDataSiswa()
    {
        return $this->hasMany(PendaftarDataSiswa::class, 'wilayah_id');
    }
}
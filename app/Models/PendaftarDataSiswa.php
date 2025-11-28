<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftarDataSiswa extends Model
{
    protected $table = 'pendaftar_data_siswa';
    public $timestamps = false;
    protected $primaryKey = 'pendaftar_id';
    
    protected $fillable = [
        'pendaftar_id', 'nik', 'nisn', 'nama', 'jk', 'tmp_lahir', 
        'tgl_lahir', 'alamat', 'wilayah_id', 'lat', 'lng'
    ];
    
    protected $casts = [
        'tgl_lahir' => 'date',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7'
    ];
    
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
    
    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }
}
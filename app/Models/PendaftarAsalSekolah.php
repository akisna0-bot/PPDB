<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftarAsalSekolah extends Model
{
    protected $table = 'pendaftar_asal_sekolah';
    public $timestamps = false;
    protected $primaryKey = 'pendaftar_id';
    
    protected $fillable = [
        'pendaftar_id', 'npsn', 'nama_sekolah', 'kabupaten', 'nilai_rata'
    ];
    
    protected $casts = [
        'nilai_rata' => 'decimal:2'
    ];
    
    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}
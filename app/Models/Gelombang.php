<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gelombang extends Model
{
    protected $table = 'gelombang';
    public $timestamps = false;
    
    protected $fillable = ['nama', 'tahun', 'tgl_mulai', 'tgl_selesai', 'biaya_daftar'];
    
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'biaya_daftar' => 'decimal:2'
    ];
    
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'gelombang_id');
    }
}
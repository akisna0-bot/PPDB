<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wave extends Model
{
    protected $fillable = ['name', 'nama', 'tgl_mulai', 'tgl_selesai', 'biaya_daftar', 'biaya'];
    
    protected $casts = [
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'biaya_daftar' => 'decimal:2',
        'biaya' => 'decimal:2'
    ];
    
    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'wave_id');
    }
    
    // Accessor untuk nama
    public function getNameAttribute()
    {
        return $this->nama ?? $this->attributes['name'] ?? 'Gelombang ' . $this->id;
    }
    
    // Accessor untuk biaya
    public function getBiayaAttribute()
    {
        return $this->biaya_daftar ?? $this->attributes['biaya'] ?? 0;
    }
}
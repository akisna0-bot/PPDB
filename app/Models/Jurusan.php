<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';
    public $timestamps = false;
    
    protected $fillable = ['kode', 'nama', 'kuota'];
    
    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'jurusan_id');
    }
}
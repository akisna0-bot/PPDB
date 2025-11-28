<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'aksi', 'objek', 'objek_data', 'waktu', 'ip'
    ];
    
    protected $casts = [
        'objek_data' => 'array',
        'waktu' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
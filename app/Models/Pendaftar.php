<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    protected $table = 'pendaftar';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'tanggal_daftar', 'no_pendaftaran', 'gelombang_id', 
        'jurusan_id', 'status', 'user_verifikasi_adm', 'tgl_verifikasi_adm',
        'user_verifikasi_payment', 'tgl_verifikasi_payment'
    ];
    
    protected $casts = [
        'tanggal_daftar' => 'datetime',
        'tgl_verifikasi_adm' => 'datetime',
        'tgl_verifikasi_payment' => 'datetime'
    ];
    
    // Relasi ke User/Pengguna
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relasi ke Gelombang
    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class, 'gelombang_id');
    }
    
    // Relasi ke Jurusan
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    
    // Relasi ke Data Siswa (One-to-One)
    public function dataSiswa()
    {
        return $this->hasOne(PendaftarDataSiswa::class, 'pendaftar_id');
    }
    
    // Relasi ke Data Orang Tua (One-to-One)
    public function dataOrtu()
    {
        return $this->hasOne(PendaftarDataOrtu::class, 'pendaftar_id');
    }
    
    // Relasi ke Asal Sekolah (One-to-One)
    public function asalSekolah()
    {
        return $this->hasOne(PendaftarAsalSekolah::class, 'pendaftar_id');
    }
    
    // Relasi ke Berkas (One-to-Many)
    public function berkas()
    {
        return $this->hasMany(PendaftarBerkas::class, 'pendaftar_id');
    }
}
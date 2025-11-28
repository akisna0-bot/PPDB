<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Applicant extends Model
{
    protected $fillable = [
        'user_id', 'no_pendaftaran', 'wave_id', 'major_id', 'status',
        'user_verifikasi_adm', 'tgl_verifikasi_adm', 'nik', 'nama_lengkap',
        'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'no_hp',
        'alamat_lengkap', 'asal_sekolah', 'tahun_lulus', 'nama_ayah', 
        'nama_ibu', 'no_hp_ortu', 'catatan_verifikasi', 'verified_by', 
        'verified_at', 'final_status', 'final_notes', 'decided_by', 'decided_at'
    ];
    
    protected $casts = [
        'tanggal_lahir' => 'date',
        'tgl_verifikasi_adm' => 'datetime',
        'verified_at' => 'datetime',
        'decided_at' => 'datetime'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function major()
    {
        return $this->belongsTo(Major::class);
    }
    
    public function wave()
    {
        return $this->belongsTo(Wave::class);
    }
    
    public function files()
    {
        return $this->hasMany(ApplicantFile::class);
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    public function decider()
    {
        return $this->belongsTo(User::class, 'decided_by');
    }
    
    public function canMakePayment()
    {
        return in_array($this->status, ['VERIFIED', 'PAYMENT_PENDING']);
    }
    
    public function isVerified()
    {
        return in_array($this->status, ['VERIFIED', 'PAYMENT_PENDING', 'PAYMENT_VERIFIED', 'FINAL_REVIEW']);
    }
    
    public function isPaid()
    {
        return $this->status === 'PAYMENT_VERIFIED' || !empty($this->final_status);
    }
    
    public function hasPaymentVerified()
    {
        return $this->status === 'PAYMENT_VERIFIED' || !empty($this->final_status);
    }
    
    public function hasFinalDecision()
    {
        return !empty($this->final_status);
    }
}
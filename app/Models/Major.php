<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $fillable = ['code', 'name', 'kuota'];
    
    public function applicants()
    {
        return $this->hasMany(Applicant::class, 'major_id');
    }
    
    // Scope untuk statistik
    public function scopeWithApplicantStats($query)
    {
        return $query->withCount([
            'applicants as total_pendaftar',
            'applicants as diterima' => function($q) {
                $q->where('status', 'PAID');
            },
            'applicants as diverifikasi' => function($q) {
                $q->where('status', 'VERIFIED');
            }
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantFile extends Model
{
    protected $fillable = [
        'applicant_id', 'filename', 'original_name', 'file_type', 
        'document_type', 'path', 'size_kb', 'is_required', 'status'
    ];
    
    protected $casts = [
        'is_required' => 'boolean'
    ];
    
    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
    
    public static function getDocumentTypes()
    {
        return [
            'ijazah' => 'Ijazah/STTB',
            'skhun' => 'SKHUN', 
            'rapor' => 'Rapor Semester Akhir',
            'akta_kelahiran' => 'Akta Kelahiran',
            'kartu_keluarga' => 'Kartu Keluarga',
            'pas_foto' => 'Pas Foto 3x4'
        ];
    }
    
    public function getDocumentTypeNameAttribute()
    {
        $types = self::getDocumentTypes();
        return $types[$this->document_type] ?? $this->document_type;
    }
}
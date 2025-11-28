<?php

namespace App\Exports;

use App\Models\Applicant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ApplicantsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Applicant::with(['user', 'major', 'wave']);
        
        if (isset($this->filters['status']) && $this->filters['status']) {
            $query->where('status', $this->filters['status']);
        }
        
        if (isset($this->filters['major_id']) && $this->filters['major_id']) {
            $query->where('major_id', $this->filters['major_id']);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'No. Pendaftaran',
            'Nama Lengkap',
            'Email',
            'NIK',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'No. HP',
            'Alamat',
            'Kabupaten',
            'Provinsi',
            'Asal Sekolah',
            'Tahun Lulus',
            'Jurusan',
            'Gelombang',
            'Status',
            'Tanggal Daftar'
        ];
    }

    public function map($applicant): array
    {
        static $no = 1;
        
        return [
            $no++,
            $applicant->no_pendaftaran,
            $applicant->nama_lengkap ?? $applicant->user->name,
            $applicant->user->email,
            $applicant->nik,
            $applicant->tempat_lahir,
            $applicant->tanggal_lahir ? $applicant->tanggal_lahir->format('d/m/Y') : '',
            $applicant->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
            $applicant->agama,
            $applicant->no_hp,
            $applicant->alamat_lengkap,
            $applicant->kabupaten,
            $applicant->provinsi,
            $applicant->asal_sekolah,
            $applicant->tahun_lulus,
            $applicant->major->name ?? '',
            $applicant->wave->name ?? '',
            $applicant->status == 'VERIFIED' ? 'Terverifikasi' : ($applicant->status == 'REJECTED' ? 'Ditolak' : 'Menunggu'),
            $applicant->created_at->format('d/m/Y H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
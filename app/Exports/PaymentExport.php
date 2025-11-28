<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentExport implements FromCollection, WithHeadings, WithMapping
{
    protected $applicants;

    public function __construct($applicants)
    {
        $this->applicants = $applicants;
    }

    public function collection()
    {
        return $this->applicants;
    }

    public function headings(): array
    {
        return [
            'No Pendaftaran',
            'Nama',
            'Email',
            'Jurusan',
            'Gelombang',
            'Biaya',
            'Status Pembayaran',
            'Tanggal Daftar',
            'Tanggal Bayar'
        ];
    }

    public function map($applicant): array
    {
        return [
            $applicant->no_pendaftaran,
            $applicant->user->name,
            $applicant->user->email,
            $applicant->major->name ?? 'N/A',
            $applicant->wave->name ?? 'N/A',
            'Rp ' . number_format($applicant->wave->biaya ?? 150000, 0, ',', '.'),
            $applicant->status == 'PAID' ? 'Lunas' : 'Menunggu Bayar',
            $applicant->created_at->format('d/m/Y'),
            $applicant->status == 'PAID' ? ($applicant->updated_at->format('d/m/Y') ?? '-') : '-'
        ];
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'applicant_id', 'invoice_number', 'amount', 'payment_type', 'status',
        'payment_method', 'payment_gateway', 'transaction_id', 'reference_number',
        'payment_details', 'paid_at', 'expired_at', 'notes', 'receipt_path',
        'verified_by', 'verified_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'verified_at' => 'datetime'
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            'refunded' => 'bg-blue-100 text-blue-800'
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Sudah Dibayar',
            'failed' => 'Pembayaran Gagal',
            'expired' => 'Kadaluarsa',
            'refunded' => 'Dikembalikan'
        ];

        return $texts[$this->status] ?? 'Unknown';
    }

    public function getPaymentTypeTextAttribute()
    {
        $types = [
            'registration' => 'Biaya Pendaftaran',
            'uniform' => 'Seragam',
            'book' => 'Buku',
            'other' => 'Lainnya'
        ];

        return $types[$this->payment_type] ?? 'Unknown';
    }

    public function isExpired()
    {
        return $this->expired_at && Carbon::now()->gt($this->expired_at);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function canBePaid()
    {
        return in_array($this->status, ['pending']) && !$this->isExpired();
    }

    public static function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd') . '-';
        $lastInvoice = self::where('invoice_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice->invoice_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
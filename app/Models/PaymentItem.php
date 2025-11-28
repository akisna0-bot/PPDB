<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentItem extends Model
{
    protected $fillable = [
        'name', 'description', 'price', 'category', 'is_required', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_required' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function paymentDetails()
    {
        return $this->hasMany(PaymentDetail::class);
    }
}
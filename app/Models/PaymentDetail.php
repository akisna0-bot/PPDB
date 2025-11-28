<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentDetail extends Model
{
    protected $fillable = [
        'payment_id', 'payment_item_id', 'quantity', 'price', 'subtotal'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function paymentItem()
    {
        return $this->belongsTo(PaymentItem::class);
    }
}
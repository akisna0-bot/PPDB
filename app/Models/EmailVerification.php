<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class EmailVerification extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_verified'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_verified' => 'boolean'
    ];

    public function isExpired()
    {
        return $this->expires_at < now();
    }

    public function isValid($otp)
    {
        return $this->otp === $otp && !$this->isExpired() && !$this->is_verified;
    }

    public static function generateOTP($email)
    {
        // Hapus OTP lama
        self::where('email', $email)->delete();
        
        // Buat OTP baru
        return self::create([
            'email' => $email,
            'otp' => str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT),
            'expires_at' => now()->addMinutes(10)
        ]);
    }
}
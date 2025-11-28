<?php

namespace App\Http\Controllers;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPMail;

class EmailVerificationController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $email = $request->email;
            
            // Generate OTP
            $verification = EmailVerification::generateOTP($email);
            
            // Kirim email OTP
            Mail::to($email)->send(new OTPMail($verification->otp));
            
            return response()->json([
                'success' => true,
                'message' => 'OTP telah dikirim ke email Anda'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $verification = EmailVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid'
            ], 400);
        }

        if ($verification->isExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah kadaluarsa'
            ], 400);
        }

        if ($verification->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'OTP sudah digunakan'
            ], 400);
        }

        // Mark as verified
        $verification->update(['is_verified' => true]);
        
        // Update user email_verified_at jika user sudah ada
        User::where('email', $request->email)
            ->update(['email_verified_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diverifikasi'
        ]);
    }
}
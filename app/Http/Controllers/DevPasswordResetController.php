<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;

class DevPasswordResetController extends Controller
{
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem.']);
        }

        // Generate token
        $token = Str::random(64);
        
        // Simpan token ke database (untuk production gunakan password_resets table)
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => \Hash::make($token),
                'created_at' => now()
            ]
        );

        // Untuk development, tampilkan link langsung
        $resetUrl = url('/reset-password/' . $token . '?email=' . urlencode($request->email));
        
        return back()->with('status', 'Link reset password: ' . $resetUrl);
    }
}
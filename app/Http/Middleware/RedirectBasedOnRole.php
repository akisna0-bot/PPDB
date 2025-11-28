<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectBasedOnRole
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // If admin/staff accessing student pages, redirect to their dashboard
            if ($user->role !== 'user' && ($request->is('dashboard') || $request->is('pendaftaran*') || $request->is('dokumen*') || $request->is('payment*') || $request->is('status*'))) {
                switch ($user->role) {
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    case 'kepsek':
                        return redirect()->route('kepsek.dashboard');
                    case 'keuangan':
                        return redirect()->route('keuangan.dashboard');
                    case 'verifikator':
                        return redirect()->route('verifikator.dashboard');
                }
            }
            
            // If student accessing admin pages, redirect to student dashboard
            if ($user->role === 'user' && ($request->is('admin*') || $request->is('kepsek*') || $request->is('keuangan*') || $request->is('verifikator*'))) {
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}
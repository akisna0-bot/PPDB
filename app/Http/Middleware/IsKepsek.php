<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsKepsek
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->role === 'kepsek'){
            return $next($request);
        }
        abort(403);
    }
}
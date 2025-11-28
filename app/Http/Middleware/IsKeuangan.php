<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsKeuangan
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && auth()->user()->role === 'keuangan'){
            return $next($request);
        }
        abort(403);
    }
}
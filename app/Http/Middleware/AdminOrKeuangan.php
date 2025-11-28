<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminOrKeuangan
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check() && in_array(auth()->user()->role, ['admin', 'keuangan'])){
            return $next($request);
        }
        abort(403);
    }
}
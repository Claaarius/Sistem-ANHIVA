<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AutoAnonymousCode
{
    public function handle(Request $request, Closure $next)
    {
        // If user is logged in, use their kode_unik
        if (auth()->check()) {
            session(['kode_unik' => auth()->user()->kode_unik]);
        }
        // If not logged in and no code in session, generate one
        elseif (!session()->has('kode_unik')) {
            $code = 'ANH-' . strtoupper(Str::random(10));
            session(['kode_unik' => $code]);
        }

        return $next($request);
    }
}

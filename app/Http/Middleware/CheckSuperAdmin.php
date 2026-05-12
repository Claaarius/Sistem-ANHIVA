<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin')) {
            return redirect()->route('login');
        }
        if (session('admin')['role'] !== 'Super Admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses hanya untuk Super Admin.');
        }
        return $next($request);
    }
}
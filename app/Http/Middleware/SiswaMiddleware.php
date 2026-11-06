<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SiswaMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('siswa')->check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login sebagai Siswa.');
        }

        if (Auth::guard('siswa')->user()->trashed()) {
            Auth::guard('siswa')->logout();
            if ($request->hasSession()) {
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }
            return redirect()->route('login')->with('error', 'Akun Siswa Anda telah dinonaktifkan atau diarsipkan. Hubungi Administrator.');
        }

        return $next($request);
    }
}

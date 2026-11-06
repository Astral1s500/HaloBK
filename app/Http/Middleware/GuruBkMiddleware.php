<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class GuruBkMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('guru')->check()) {
            return redirect()->route('login')->with('error', 'Akses ditolak. Silakan login sebagai Guru BK.');
        }

        return $next($request);
    }
}

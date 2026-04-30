<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SysadminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || auth()->user()->role !== 'sysadmin') {
            abort(403, 'Akses ditolak. Hanya Sysadmin yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}

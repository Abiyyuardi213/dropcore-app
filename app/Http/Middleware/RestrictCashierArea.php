<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictCashierArea
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role?->isCashier()) {
            if ($request->is('kasir/logout') && $request->isMethod('post')) {
                return $next($request);
            }
            if ($request->user()->status_kepegawaian !== 'aktif' || !$request->user()->role->role_status) {
                abort(403, 'Akun atau role kasir sedang dinonaktifkan.');
            }
            if (!$request->is('kasir', 'kasir/*')) {
                abort(403, 'Akun kasir hanya dapat mengakses area kasir.');
            }
        }
        return $next($request);
    }
}

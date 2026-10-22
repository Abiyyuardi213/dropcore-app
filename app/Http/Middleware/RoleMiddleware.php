<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        Role::ensureSchemaAndDefaults();

        $user = $request->user();

        if (!$user || !$user->role) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if (!$user->role->role_status) {
            abort(403, 'Role akses Anda sedang dinonaktifkan.');
        }

        $roleName = strtolower(trim($user->role->role_name));
        $normalizedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);

        // 1. Jika route khusus portal distributor (role:distributor)
        if (in_array('distributor', $normalizedRoles, true) && !in_array('admin', $normalizedRoles, true)) {
            if ($roleName === 'distributor' || $user->role->hasPermission('portal_distributor')) {
                return $next($request);
            }
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        // 2. Jika route backoffice / admin (role:admin,staff)
        if (in_array('admin', $normalizedRoles, true) || in_array('staff', $normalizedRoles, true)) {
            if (!$user->role->isAdminPanelRole() && !in_array($roleName, $normalizedRoles, true)) {
                abort(403, 'Anda tidak memiliki akses ke halaman admin.');
            }

            // Jika mengakses dashboard utama namun role tidak diberi akses dashboard, arahkan ke modul pertama yang diizinkan
            if ($request->is('administrator/dashboard') && !$user->role->hasPermission('dashboard')) {
                $fallbackUrl = $user->role->getFirstAllowedAdminUrl();
                if ($fallbackUrl !== '/administrator/dashboard') {
                    return redirect($fallbackUrl);
                }
            }

            if (!$user->role->canAccessRequest($request)) {
                abort(403, 'Anda tidak memiliki hak akses untuk membuka modul ini.');
            }

            return $next($request);
        }

        if (!in_array($roleName, $normalizedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}


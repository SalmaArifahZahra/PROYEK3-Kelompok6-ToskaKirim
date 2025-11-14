<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  ...$roles  (Ini akan menangkap semua argumen, misal: 'admin', 'superadmin')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Jika user->peran adalah Enum, ambil value-nya
        $userRole = $user->peran instanceof \BackedEnum 
            ? $user->peran->value 
            : (string) $user->peran;

        if (!in_array($userRole, $roles, true)) {
            abort(403, "Akses ditolak. Role Anda (" . $userRole . ") tidak memiliki izin.");
        }

        return $next($request);
    }
}
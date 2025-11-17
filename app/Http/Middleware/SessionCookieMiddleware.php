<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class SessionCookieMiddleware
{
    /**
     * Set session cookie name early based on request path or existing cookies.
     * This helps support multiple simultaneous sessions (customer/admin/superadmin)
     * in the same browser by selecting the correct cookie for the incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If request is for admin area, prefer the superadmin/admin cookies if present
        if ($request->is('admin') || $request->is('admin/*') || $request->is('superadmin') || $request->is('superadmin/*')) {
            if ($request->cookies->has('toska_superadmin_session')) {
                Config::set('session.cookie', 'toska_superadmin_session');
            } elseif ($request->cookies->has('toska_admin_session')) {
                Config::set('session.cookie', 'toska_admin_session');
            } else {
                // default to admin cookie for admin area
                Config::set('session.cookie', 'toska_admin_session');
            }

            return $next($request);
        }

        // Default: customer area -> use customer session cookie
        Config::set('session.cookie', 'toska_customer_session');

        return $next($request);
    }
}

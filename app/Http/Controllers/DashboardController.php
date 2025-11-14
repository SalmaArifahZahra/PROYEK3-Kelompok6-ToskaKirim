<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Pintu Gerbang setelah login.
     * Redirect user ke dashboard yang sesuai berdasarkan peran.
     * Sesuai rute: GET /dashboard
     */
    public function index(): RedirectResponse
    {
        $user = Auth::user();
        
        // Debug: Log peran user
        \Log::info('User login - Role: ' . $user->peran->value);

        if ($user->peran === RoleEnum::SUPERADMIN) {
            return redirect()->route('superadmin.users.dashboard');
        } elseif ($user->peran === RoleEnum::ADMIN) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->peran === RoleEnum::CUSTOMER) {
            return redirect()->route('customer.dashboard');
        }
        
        // Fallback jika peran tidak dikenal
        Auth::logout();
        return redirect()->route('login')->withErrors([
            'email' => 'Peran tidak dikenali, silahkan hubungi administrator.'
        ]);
    }
}
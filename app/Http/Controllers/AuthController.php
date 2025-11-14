<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     * Sesuai rute: GET /
     */
    public function index_login(): View
    {
        return view('auth.login'); 
    }

    /**
     * Menampilkan halaman register.
     * Sesuai rute: GET /register
     */
    public function index_register(): View
    {
        return view('auth.register');
    }

    /**
     * Memproses data dari form register.
     * Sesuai rute: POST /register
     */
    public function action_register(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'peran' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('customer.profile.complete');
    }

    /**
     * Memproses data dari form login.
     * Sesuai rute: POST /login
     */
    public function action_login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->withInput($request->only('email'));
    }

    /**
     * Memproses logout user.
     * Sesuai rute: POST /logout
     */
    public function action_logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
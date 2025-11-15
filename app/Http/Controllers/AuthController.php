<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use App\Enums\RoleEnum;

class AuthController extends Controller
{
    // Menampilkan halaman login customer.
    public function index_login(): View
    {
        return view('auth.login'); 
    }

    // Menampilkan halaman register customer.
    public function index_register(): View
    {
        return view('auth.register');
    }

    // Menampilkan halaman login admin.
    public function index_admin_login(): View
    {
        return view('admin.auth.login');
    }

    // Memproses registrasi customer.
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

        // Store user ID and name in session for profile completion without auto-login
        session([
            'temp_user_id' => $user->id_user,
            'temp_user_name' => $user->nama,
            'temp_user_email' => $user->email,
        ]);

        return redirect()->route('customer.profile.complete')
                         ->with('success', 'Registrasi berhasil! Silakan lengkapi data diri Anda.');
    }

    // Memproses login admin dan superadmin.
    public function action_admin_login(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('nama'));
        }

        $nama = $request->input('nama');
        $password = $request->input('password');
        $remember = $request->boolean('remember');

        $user = User::where('nama', $nama)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return back()->withErrors([
                'nama' => 'Nama atau password yang dimasukkan salah.',
            ])->withInput($request->only('nama'));
        }

        // hanya admin & superadmin yang bisa login lewat route ini
        $roleValue = $user->peran instanceof \BackedEnum ? $user->peran->value : (string) $user->peran;
        if (!in_array($roleValue, [RoleEnum::ADMIN->value, RoleEnum::SUPERADMIN->value], true)) {
            return back()->withErrors([
                'nama' => 'Akun ini tidak memiliki akses admin.',
            ])->withInput($request->only('nama'));
        }

        $cookieName = $roleValue === RoleEnum::SUPERADMIN->value ? 'toska_superadmin_session' : 'toska_admin_session';
        Config::set('session.cookie', $cookieName);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // Memproses login customer.
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

        Config::set('session.cookie', 'toska_customer_session');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang dimasukkan salah.',
        ])->withInput($request->only('email'));
    }

    // Memproses logout.
    public function action_logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $roleValue = null;

        if ($user) {
            $roleValue = $user->peran instanceof \BackedEnum ? $user->peran->value : (string) $user->peran;
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cookie::queue(Cookie::forget('toska_customer_session'));
        Cookie::queue(Cookie::forget('toska_admin_session'));
        Cookie::queue(Cookie::forget('toska_superadmin_session'));

        if ($roleValue && in_array($roleValue, [RoleEnum::ADMIN->value, RoleEnum::SUPERADMIN->value], true)) {
            return redirect()->route('admin.login');
        }

        return redirect()->route('login');
    }
}
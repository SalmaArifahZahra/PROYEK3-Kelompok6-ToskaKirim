<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatUserController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Publik (Guest) ---
Route::middleware(['guest'])->group(function () {
    // Customer (public) login/register
    Route::get('/', [AuthController::class, 'index_login'])->name('login');
    Route::post('/login', [AuthController::class, 'action_login'])->name('login.action');
    Route::get('/register', [AuthController::class, 'index_register'])->name('register');
    Route::post('/register', [AuthController::class, 'action_register'])->name('register.action');

    // Admin / Superadmin login (separate URL and handler)
    Route::get('/admin/login', [AuthController::class, 'index_admin_login'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'action_admin_login'])->name('admin.login.action');

    // Profile completion untuk registrasi baru (tanpa auth)
    Route::get('/customer/profile/complete', [AlamatUserController::class, 'create'])
         ->name('customer.profile.complete');
    Route::post('/customer/alamat', [AlamatUserController::class, 'store'])
         ->name('customer.alamat.store');
});

// --- Rute Terproteksi (Autentikasi) ---
Route::middleware(['auth'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'action_logout'])->name('logout');

    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Rute Customer ---
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

        
        Route::resource('alamat', AlamatUserController::class)->except(['show']);
        Route::post('/alamat/{alamat}/set-utama', [AlamatUserController::class, 'setUtama'])
             ->name('alamat.setUtama');
    });

    // --- Rute Admin & Superadmin ---
    Route::middleware('role:admin,superadmin')->prefix('admin')->name('admin.')->group(function () {
        
        // Rute landing page Admin (Nama 'admin.dashboard' sudah benar)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rute Produk
        Route::get('/produk', [\App\Http\Controllers\Admin\ProdukController::class, 'index'])->name('produk.index');
    });

    // --- Rute Superadmin ---
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // Rute landing page Superadmin
        Route::get('/dashboard', [AdminUserController::class, 'dashboard'])->name('users.dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });

});

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
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index_login')->name('login');
        Route::get('/register', 'index_register')->name('register');
        Route::post('/login', 'action_login')->name('login.action');
        Route::post('/register', 'action_register')->name('register.action');
    });
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

        Route::get('/profile/complete', [AlamatUserController::class, 'create'])
             ->name('profile.complete');
        
        Route::resource('alamat', AlamatUserController::class)->except(['show']);
        Route::post('/alamat/{alamat}/set-utama', [AlamatUserController::class, 'setUtama'])
             ->name('alamat.setUtama');
    });

    // --- Rute Admin & Superadmin ---
    Route::middleware('role:admin,superadmin')->prefix('admin')->name('admin.')->group(function () {
        
        // Rute landing page Admin (Nama 'admin.dashboard' sudah benar)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // (Nanti kita tambahkan rute Kategori & Produk di sini)
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
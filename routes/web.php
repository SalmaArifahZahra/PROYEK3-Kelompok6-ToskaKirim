<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatUserController;
use App\Http\Controllers\DashboardController;
// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\ProdukDetailController as AdminProdukDetailController;
// Import Controller Customer
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- Rute Publik (Guest) ---
Route::middleware(['guest'])->group(function () {
    // Customer login/register
    Route::get('/', [AuthController::class, 'index_login'])->name('login');
    Route::post('/login', [AuthController::class, 'action_login'])->name('login.action');
    Route::get('/register', [AuthController::class, 'index_register'])->name('register');
    Route::post('/register', [AuthController::class, 'action_register'])->name('register.action');

    // Admin / Superadmin
    Route::get('/admin/login', [AuthController::class, 'index_admin_login'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'action_admin_login'])->name('admin.login.action');
});

// --- Rute Terproteksi (Autentikasi) ---
Route::middleware(['auth'])->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'action_logout'])->name('logout');

    // Dashboard Utama (Pintu Gerbang)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Rute Customer ---
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {

        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');

        // Alur 'Lengkapi Profil' setelah register
        Route::get('/profile/complete', [AlamatUserController::class, 'create'])
            ->name('profile.complete');

        // CRUD Alamat Lengkap
        Route::resource('alamat', AlamatUserController::class);
        Route::post('/alamat/{alamat}/set-utama', [AlamatUserController::class, 'setUtama'])
            ->name('alamat.setUtama');
    });

    // --- Rute Admin & Superadmin ---
    Route::middleware('role:admin,superadmin')->prefix('admin')->name('admin.')->group(function () {

        // Rute landing page Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rute Kategori (Full CRUD)
        Route::resource('kategori', AdminKategoriController::class);

        // Rute Produk Induk (Full CRUD)
        Route::resource('produk', AdminProdukController::class);

        // Rute Varian/Detail Produk (Nested)
        Route::prefix('produk/{produk}/detail')->name('produk.detail.')->group(function () {
            Route::get('/create', [AdminProdukDetailController::class, 'create'])->name('create');
            Route::post('/', [AdminProdukDetailController::class, 'store'])->name('store');
            Route::get('/{detail}/edit', [AdminProdukDetailController::class, 'edit'])->name('edit');
            Route::put('/{detail}', [AdminProdukDetailController::class, 'update'])->name('update');
            Route::delete('/{detail}', [AdminProdukDetailController::class, 'destroy'])->name('destroy');
        });
    });

    // --- Rute Superadmin ---
    Route::middleware('role:superadmin')->prefix('superadmin')->name('superadmin.')->group(function () {
        // Rute landing page Superadmin
        Route::get('/dashboard', [AdminUserController::class, 'dashboard'])->name('users.dashboard');

        // Rute CRUD User
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});

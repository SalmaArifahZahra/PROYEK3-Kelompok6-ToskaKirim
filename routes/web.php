<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlamatUserController;
use App\Http\Controllers\DashboardController;
// Import Controller Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\SubKategoriController as AdminSubKategoriController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\ProdukDetailController as AdminProdukDetailController;
use App\Http\Controllers\Admin\PesananController as AdminPesananController;
use App\Http\Controllers\Admin\PesananDetailController as AdminPesananDetailController;
// Import Controller Superadmin
use App\Http\Controllers\Superadmin\UserController as SuperAdminUserController;
use App\Http\Controllers\Superadmin\MetodePembayaranController;
// Import Controller Customer
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\KategoriController as CustomerKategoriController;
use App\Http\Controllers\Customer\ProdukController as CustomerProdukController;
use App\Http\Controllers\Customer\KeranjangController as CustomerKeranjangController;
use App\Http\Controllers\Customer\CheckoutController  as CheckoutCustomerController;

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

        Route::prefix('produk')->group(function () {
            Route::get('/{id}', [CustomerProdukController::class, 'detail'])->name('produk.detail');
        });

        // Alur 'Lengkapi Profil' setelah register
        Route::get('/profile/complete', [AlamatUserController::class, 'create'])
            ->name('profile.complete');

        // CRUD Alamat Lengkap
        Route::resource('alamat', AlamatUserController::class);
        Route::post('/alamat/{alamat}/set-utama', [AlamatUserController::class, 'setUtama'])
            ->name('alamat.setUtama');

        // Kategori & Sub-Kategori
        Route::get('/kategori/{kategori}', [CustomerKategoriController::class, 'index'])
            ->name('kategori.index');
        Route::get('/sub-kategori/{subKategori}', [CustomerKategoriController::class, 'show'])
            ->name('kategori.show');

        // Keranjang Belanja Customer
        Route::prefix('keranjang')->name('keranjang.')->group(function () {
            Route::get('/', [CustomerKeranjangController::class, 'index'])->name('index');
            Route::post('/add', [CustomerKeranjangController::class, 'add'])->name('add');
            Route::delete('/{id_produk_detail}', [CustomerKeranjangController::class, 'destroy'])->name('destroy');
            Route::post('/update-qty/{id_produk_detail}', [CustomerKeranjangController::class, 'updateQty'])->name('updateQty');
            Route::get('/checkout', [CheckoutCustomerController::class, 'index'])->name('checkout');
            
        });

        Route::post('/checkout', [CustomerKeranjangController::class, 'checkout'])
            ->name('checkout.store');
    });

    // --- Rute Admin & Superadmin ---
    Route::middleware('role:admin,superadmin')->prefix('admin')->name('admin.')->group(function () {

        // Rute landing page Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rute Kategori (Full CRUD)
        Route::resource('kategori', AdminKategoriController::class);

        // Rute Sub-Kategori Index
        Route::get('kategori/{kategori}/subkategori', [AdminSubKategoriController::class, 'index'])->name('kategori.subkategori.index');

        // Rute Sub-Kategori (Nested)
        Route::prefix('kategori/{kategori:id_kategori}/subkategori')->name('kategori.subkategori.')->group(function () {
            Route::get('/create', [AdminSubKategoriController::class, 'create'])->name('create');
            Route::post('/', [AdminSubKategoriController::class, 'store'])->name('store');
            Route::get('/{subkategori}/edit', [AdminSubKategoriController::class, 'edit'])->name('edit');
            Route::put('/{subkategori}', [AdminSubKategoriController::class, 'update'])->name('update');
            Route::delete('/{subkategori}', [AdminSubKategoriController::class, 'destroy'])->name('destroy');
        });

        // Rute Produk - Pilih Kategori
        Route::get('produk/select-kategori', [AdminProdukController::class, 'selectKategori'])->name('produk.selectKategori');

        // Rute Produk Induk (Full CRUD)
        Route::resource('produk', AdminProdukController::class);

        // Rute Produk Detail Index
        Route::get('produk/{produk}/detail', [AdminProdukDetailController::class, 'index'])->name('produk_detail.index');

        // Rute Varian/Detail Produk (Nested)
        Route::prefix('produk/{produk:id_produk}/detail')->name('produk.detail.')->group(function () {
            Route::get('/create', [AdminProdukDetailController::class, 'create'])->name('create');
            Route::post('/', action: [AdminProdukDetailController::class, 'store'])->name('store');
            Route::get('/{detail}/edit', [AdminProdukDetailController::class, 'edit'])->name('edit');
            Route::put('/{detail}', [AdminProdukDetailController::class, 'update'])->name('update');
            Route::delete('/{detail}', [AdminProdukDetailController::class, 'destroy'])->name('destroy');
        });

        // Rute Pesanan
        Route::get('pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');

        // Rute Pesanan Detail (nested)
        Route::prefix('pesanan/{pesanan:id_pesanan}')->name('pesanan_detail.')->group(function() {
            Route::get('/', [AdminPesananDetailController::class, 'index'])->name('index');
            Route::post('/verify', [AdminPesananDetailController::class, 'verify'])->name('verify');
            Route::post('/process', [AdminPesananDetailController::class, 'process'])->name('process');
            Route::post('/complete', [AdminPesananDetailController::class, 'complete'])->name('complete');
            Route::post('/cancel', [AdminPesananDetailController::class, 'cancel'])->name('cancel');
        });


    });

    Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {

        // Dashboard (Halaman Awal setelah Login)
        Route::get('/dashboard', [SuperAdminUserController::class, 'dashboard'])->name('dashboard');

        Route::resource('users', SuperAdminUserController::class);

        Route::resource('payments', MetodePembayaranController::class);

        // Rute CRUD User
        // Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        // Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        // Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        // Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        // Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});

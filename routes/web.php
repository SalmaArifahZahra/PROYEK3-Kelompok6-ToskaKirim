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
use App\Http\Controllers\Admin\RekapController as AdminRekapController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController;
// Import Controller Superadmin
use App\Http\Controllers\Superadmin\UserController as SuperAdminUserController;
use App\Http\Controllers\Superadmin\MetodePembayaranController;
use App\Http\Controllers\Superadmin\LayananPengirimanController;
use App\Http\Controllers\Superadmin\PromoOngkirController;
use App\Http\Controllers\Superadmin\WilayahPengirimanController;
use App\Http\Controllers\Superadmin\KontrolTokoController;
// Import Controller Customer
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\KategoriController as CustomerKategoriController;
use App\Http\Controllers\Customer\ProdukController as CustomerProdukController;
use App\Http\Controllers\Customer\KeranjangController as CustomerKeranjangController;
use App\Http\Controllers\Customer\PesananController  as CustomerPesananController;

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
            Route::get('/search', [CustomerProdukController::class, 'search'])->name('produk.search');
            Route::get('/{id}', [CustomerProdukController::class, 'detail'])->name('produk.detail');
        });

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
            Route::delete('/bulk/destroy', [CustomerKeranjangController::class, 'destroyBulk'])->name('destroyBulk');
            Route::post('/update-qty/{id_produk_detail}', [CustomerKeranjangController::class, 'updateQty'])->name('updateQty');
            Route::post('/checkout', [CustomerPesananController::class, 'checkoutFromCart'])->name('checkout');
        });

        // Checkout & Pesanan
        Route::prefix('pesanan')->name('pesanan.')->group(function () {
            Route::get('/', [CustomerPesananController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerPesananController::class, 'show'])->name('show');
            Route::post('/store', [CustomerPesananController::class, 'storeFromConfirm'])->name('store');
            Route::post('/calculate-ongkir', [CustomerPesananController::class, 'calculateOngkir'])->name('calculateOngkir');
            Route::post('/{id}/upload', [CustomerPesananController::class, 'uploadBukti'])->name('upload');
            Route::post('/{id}/cancel', [CustomerPesananController::class, 'cancel'])->name('cancel');
            Route::post('/{id}/selesai', [CustomerPesananController::class, 'selesai'])->name('selesai');
        });

        // Alamat APIs
        Route::prefix('alamat')->name('alamat.')->group(function () {
            // API Endpoints (AJAX) - Semua operasi alamat via modal checkout
            Route::post('/store', [AlamatUserController::class, 'store'])->name('store');
            Route::get('/api/all', [AlamatUserController::class, 'getAllUserAddresses'])->name('api.all');
            Route::get('/api/{alamat}', [AlamatUserController::class, 'getAddressDetail'])->name('api.detail');
            Route::put('/api/{alamat}', [AlamatUserController::class, 'updateAddress'])->name('api.update');
            Route::delete('/api/{alamat}', [AlamatUserController::class, 'deleteAddress'])->name('api.delete');
            Route::post('/api/{alamat}/utama', [AlamatUserController::class, 'setUtamaApi'])->name('api.setUtama');
        });
    });

    // --- Rute Admin Operasional (Role: admin & superadmin) ---
    // Prefix URL: /admin
    Route::middleware('role:admin,superadmin')->prefix('admin')->name('admin.')->group(function () {

        // Rute landing page Admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Rute Kategori (Full CRUD)
        Route::resource('kategori', AdminKategoriController::class);
        Route::post('kategori/batch-delete', [AdminKategoriController::class, 'batchDelete'])->name('kategori.batchDelete');

        // Rute Sub-Kategori Index
        Route::get('kategori/{kategori}/subkategori', [AdminSubKategoriController::class, 'index'])->name('kategori.subkategori.index');

        // Rute Sub-Kategori (Nested)
        Route::prefix('kategori/{kategori:id_kategori}/subkategori')->name('kategori.subkategori.')->group(function () {
            Route::get('/create', [AdminSubKategoriController::class, 'create'])->name('create');
            Route::post('/', [AdminSubKategoriController::class, 'store'])->name('store');
            Route::get('/{subkategori}/edit', [AdminSubKategoriController::class, 'edit'])->name('edit');
            Route::put('/{subkategori}', [AdminSubKategoriController::class, 'update'])->name('update');
            Route::delete('/{subkategori}', [AdminSubKategoriController::class, 'destroy'])->name('destroy');
            Route::post('/batch-delete', [AdminSubKategoriController::class, 'batchDelete'])->name('batchDelete');
        });

        // Rute Produk - Pilih Kategori
        Route::get('produk/select-kategori', [AdminProdukController::class, 'selectKategori'])->name('produk.selectKategori');

        // Rute Produk Induk (Full CRUD)
        Route::resource('produk', AdminProdukController::class);
        Route::post('produk/batch-delete', [AdminProdukController::class, 'batchDelete'])->name('produk.batchDelete');

        // Rute Produk Detail Index
        Route::get('produk/{produk}/detail', [AdminProdukDetailController::class, 'index'])->name('produk_detail.index');

        // Rute Varian/Detail Produk (Nested)
        Route::prefix('produk/{produk:id_produk}/detail')->name('produk.detail.')->group(function () {
            Route::get('/create', [AdminProdukDetailController::class, 'create'])->name('create');
            Route::post('/', action: [AdminProdukDetailController::class, 'store'])->name('store');
            Route::get('/{detail}/edit', [AdminProdukDetailController::class, 'edit'])->name('edit');
            Route::put('/{detail}', [AdminProdukDetailController::class, 'update'])->name('update');
            Route::delete('/{detail}', [AdminProdukDetailController::class, 'destroy'])->name('destroy');
            Route::post('/batch-delete', [AdminProdukDetailController::class, 'batchDelete'])->name('batchDelete');
        });

        // Rute Pesanan
        Route::get('pesanan', [AdminPesananController::class, 'index'])->name('pesanan.index');

        // Rute Pesanan Detail (nested)
        Route::prefix('pesanan/{pesanan:id_pesanan}')->name('pesanan_detail.')->group(function () {
            Route::get('/', [AdminPesananDetailController::class, 'index'])->name('index');
            Route::post('/verify', [AdminPesananDetailController::class, 'verify'])->name('verify');
            Route::post('/process', [AdminPesananDetailController::class, 'process'])->name('process');
            Route::post('/complete', [AdminPesananDetailController::class, 'complete'])->name('complete');
            Route::post('/cancel', [AdminPesananDetailController::class, 'cancel'])->name('cancel');
        });

        // Rute Rekap Laporan
        Route::get('/rekap', [AdminRekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/export/pdf', [AdminRekapController::class, 'exportPDF'])->name('rekap.export.pdf');
        Route::get('/rekap/export/excel', [AdminRekapController::class, 'exportExcel'])->name('rekap.export.excel');

        // Rute Pelanggan
        Route::get('pelanggan', [AdminPelangganController::class, 'index'])->name('pelanggan.index');
    });

    // --- Rute Logistik & Tarif (SHARED: Admin & Superadmin) ---
    Route::middleware(['auth', 'role:admin,superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        
        // --- 1. LAYANAN & PROMO ---
        Route::resource('layanan', LayananPengirimanController::class)->except(['create', 'show', 'edit']);
        Route::resource('promo', PromoOngkirController::class);

        // --- 2. WILAYAH PENGIRIMAN ---
        
        // A. Route Khusus (Harus didefinisikan SEBELUM resource)
        Route::prefix('wilayah')->name('wilayah.')->group(function () {
            // Fitur Hitung Jarak
            Route::post('/auto-calculate', [WilayahPengirimanController::class, 'hitungJarakOtomatis'])->name('auto');
            
            // Fitur Import & Export
            Route::get('/export', [WilayahPengirimanController::class, 'export'])->name('export');
            Route::post('/import', [WilayahPengirimanController::class, 'import'])->name('import');
        });

        // B. Route Resource (Menghandle Index, Create, Store, Edit, Update, Destroy sekaligus)
        Route::resource('wilayah', WilayahPengirimanController::class);

    });

    Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [SuperAdminUserController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', SuperAdminUserController::class);
        Route::resource('payments', MetodePembayaranController::class);
        Route::patch('payments/{payment}/toggle', [MetodePembayaranController::class, 'toggleActive'])->name('payments.toggle');
        Route::get('/kontrol-toko', [KontrolTokoController::class, 'index'])->name('kontrol_toko.index');
        Route::post('/kontrol-toko', [KontrolTokoController::class, 'update'])->name('kontrol_toko.update');

    });
});
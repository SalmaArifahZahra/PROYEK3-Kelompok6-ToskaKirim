<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\AlamatUser;
use App\Models\ProdukDetail;
use App\Policies\AlamatPolicy;
use Illuminate\Support\Facades\View;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    protected $policies = [
        AlamatUser::class => AlamatPolicy::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('detail', function ($value) {
            return ProdukDetail::where('id_produk_detail', $value)->firstOrFail();
        });

        if (Schema::hasTable('pengaturan')) {

            $globalAlamat = Pengaturan::where('key', 'alamat_toko')->value('value') ?? 'Alamat Default';
            $globalWA = Pengaturan::where('key', 'nomor_wa')->value('value') ?? '6280000000';

            View::share('toko_alamat', $globalAlamat);
            View::share('toko_wa', $globalWA);
        }
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\AlamatUser;
use App\Models\ProdukDetail;
use App\Policies\AlamatPolicy;

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
    }
}

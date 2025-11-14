<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\AlamatUser;
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
        //
    }
}

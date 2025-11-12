<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::middleware(['guest'])->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/', 'index_login')->name('login');
        Route::get('/register', 'index_register');
        Route::post('/login', 'action_login');
    });
});

//Testing Customer Profile
Route::get('/customer/profile/complete', function () {
    return view('customer.profile.complete_profile');
});

//Testing Footer Customer
Route::get('/customer/test_customer', function () {
    return view('customer.test_customer');
});



<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Auth Routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', function () {
            return view('admin.auth.login');
        });
        
        Route::post('/login', function () {
            // buat controller
        });
    });
    
    // Protected Admin Routes
    //Route::middleware('auth')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        });
        
        Route::post('/logout', function () {
            // buat controller
        });
    });
//});

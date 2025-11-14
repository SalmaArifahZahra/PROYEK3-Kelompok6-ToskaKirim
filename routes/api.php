<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WilayahController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('wilayah')->group(function () {
    Route::get('/provinces', [WilayahController::class, 'getProvinces']);
    Route::get('/regencies/{provinceId}', [WilayahController::class, 'getRegencies']);
    Route::get('/districts/{regencyId}', [WilayahController::class, 'getDistricts']);
    Route::get('/villages/{districtId}', [WilayahController::class, 'getVillages']);
});
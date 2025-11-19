<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard customer.
     * Sesuai rute: GET /customer/home
     */
    public function index(): View
    {
        // Asumsi Anda punya view di: resources/views/customer/dashboard.blade.php
        return view('customer.dashboard');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    // Menampilkan halaman dashboard customer.
    public function index(): View
    {
        return view('customer.dashboard');
    }
}
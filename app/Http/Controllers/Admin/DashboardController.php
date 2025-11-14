<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     * Sesuai rute: GET /admin/dashboard
     */
    public function index(): View
    {
        // Asumsi Anda punya view di: resources/views/admin/dashboard.blade.php
        return view('admin.dashboard');
    }
}
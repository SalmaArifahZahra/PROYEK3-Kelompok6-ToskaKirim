<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard customer.
     * Sesuai rute: GET /customer/home
     */
    // public function index(): View
    // {
    //     // Asumsi Anda punya view di: resources/views/customer/dashboard.blade.php
    //     return view('customer.dashboard');
    // }


    // Menampilkan halaman dashboard customer dengan data kategori yang sesuai sort(ini belum sesuai sort)
    public function index()
    {
        return view('customer.dashboard', [
            'kategori' => Kategori::orderBy('nama_kategori', 'asc')->get(),
            'produk'   => Produk::with('detail')
                ->take(12)
                ->get(),
        ]);
    }
}

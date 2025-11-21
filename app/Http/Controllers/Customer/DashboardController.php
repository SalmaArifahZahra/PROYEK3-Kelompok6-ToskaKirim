<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Kategori;
use App\Models\Produk;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard customer.
     */
    public function index(): View
    {
        // kategori card sesuai dengan sort nama kategori  -salma
        return view('customer.dashboard', [
            'kategori' => Kategori::orderBy('nama_kategori', 'asc')->whereNull('parent_id')->get(),
            'produk'   => Produk::with('detail')
                ->take(12)
                ->get(),
        ]);
    }
}

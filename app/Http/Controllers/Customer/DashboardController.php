<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\keranjang;

class DashboardController extends Controller
{

    //  Menampilkan halaman dashboard customer
    public function index(): View
    {
        $cartCount = Keranjang::where('id_user', Auth::id())->sum('quantity');
        $kategori = Kategori::orderBy('id_kategori', 'asc')
            ->whereNull('parent_id')
            ->get();

        $produk = Produk::with('detail')
            ->take(12)
            ->get();

        return view('customer.dashboard', compact('kategori', 'produk', 'cartCount'));
    }
}

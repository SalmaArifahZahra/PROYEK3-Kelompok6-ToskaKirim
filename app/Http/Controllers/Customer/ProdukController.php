<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    public function detail($id)
    {
        $produk = Produk::with('detail')->findOrFail($id);

        // Produk lainnya untuk ditampilkan di bagian bawah halaman detail
        $produkLainnya = Produk::with('detail')
            ->where('id_produk', '!=', $id)
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('customer.produk.detail', compact('produk', 'produkLainnya'));
    }
}

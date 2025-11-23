<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
class ProdukController extends Controller
{
    public function detail($id)
    {
        $produk = Produk::with('detail')->findOrFail($id);

        // Produk lainnya untuk rekomendasi
        $produkLainnya = Produk::with('detail')
            ->where('id_produk', '!=', $id)
            ->limit(10)
            ->get();

        return view('customer.produk.detail');
    }
}

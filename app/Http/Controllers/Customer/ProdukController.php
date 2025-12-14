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
    public function search(Request $request)
    {
        $keyword = strtolower($request->input('q', ''));

        $produk = Produk::with('detail')
            ->where(function ($q) use ($keyword) {
                $q->whereRaw('LOWER(nama) LIKE ?', ['%' . $keyword . '%'])
                    ->orWhereRaw('LOWER(deskripsi) LIKE ?', ['%' . $keyword . '%']);
            })
            ->paginate(12)
            ->appends(['q' => $keyword]);

        return view('customer.produk.search', compact('produk', 'keyword'));
    }
}

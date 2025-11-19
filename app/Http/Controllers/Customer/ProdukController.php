<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class ProdukController extends Controller
{
    // List semua produk
    public function index()
    {
        return view('customer.produk.index', [
            // 'produk' => Produk::with('detail')->paginate(20)
        ]);
    }

    // List produk berdasarkan kategori
    // public function byKategori($id_kategori)
    // {
    //     return view('customer.kategori.show', [
    //         'kategori' => Kategori::findOrFail($id_kategori),
    //         'produk'   => Produk::where('id_kategori', $id_kategori)
    //                             ->with('detail')
    //                             ->get(),
    //     ]);
    // }

    // // Detail produk + semua varian
    // public function show($id_produk)
    // {
    //     $produk = Produk::with('detail')->findOrFail($id_produk);

    //     return view('customer.produk.detail', [
    //         'produk' => $produk
    //     ]);
    // }
}

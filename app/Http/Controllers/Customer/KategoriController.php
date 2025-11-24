<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\View\View;

class KategoriController extends Controller
{
    // Halaman INDEX: Menampilkan kategori utama dan produk di dalamnya
    public function index(Kategori $kategori): View
    {
        if ($kategori->parent_id !== null) {
            return view ('customer.kategori.show', $kategori->id_kategori);
        }

        $subKategoris = $kategori->children;

        $kategoriIds = $subKategoris->pluck('id_kategori')->toArray();
        $kategoriIds[] = $kategori->id_kategori;

        $produkList = Produk::whereIn('id_kategori', $kategoriIds)
                            ->with('detail')
                            ->get();

        return view('customer.kategori.index', [
            'kategoriUtama' => $kategori,
            'subKategoris' => $subKategoris,
            'produkList' => $produkList
        ]);
    }

    // Halaman SHOW: Menampilkan sub-kategori tertentu dan produk di dalamnya
    public function show(Kategori $subKategori): View
    {
        $kategoriUtama = $subKategori->parent;
        $subKategoris = $kategoriUtama ? $kategoriUtama->children : collect([]);

        $produkList = Produk::where('id_kategori', $subKategori->id_kategori)
                            ->with('detail')
                            ->get();
        return view('customer.kategori.show', [
            'kategoriUtama' => $kategoriUtama,
            'activeSubKategori' => $subKategori,
            'subKategoris' => $subKategoris,
            'produkList' => $produkList
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produkDetails = ProdukDetail::with(['produk.kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.produk.index', compact('produkDetails'));
    }
}

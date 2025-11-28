<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan dengan filter.
     */
    public function index(Request $request): View
    {
        $query = Pesanan::with(['user', 'ongkir', 'detail.produkDetail.produk', 'pembayaran'])
                        ->orderBy('waktu_pesanan', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }

        $pesananList = $query->paginate(15);

        return view('admin.pesanan.index', [
            'pesananList' => $pesananList
        ]);
    }
}

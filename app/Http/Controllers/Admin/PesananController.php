<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananController extends Controller
{
    /**
     * Menampilkan daftar pesanan dengan filter dan search.
     */
    public function index(Request $request): View
    {
        $query = Pesanan::with(['user', 'ongkir', 'detail.produkDetail.produk', 'pembayaran'])
                        ->orderBy('waktu_pesanan', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }

        // Search berdasarkan ID pesanan atau nama penerima
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id_pesanan', 'like', "%{$search}%")
                  ->orWhere('penerima_nama', 'like', "%{$search}%");
            });
        }

        $pesananList = $query->paginate(15);

        return view('admin.pesanan.index', [
            'pesananList' => $pesananList
        ]);
    }
}

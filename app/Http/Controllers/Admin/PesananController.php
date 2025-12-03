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
        $query = Pesanan::with(['user', 'ongkir', 'detail.produkDetail.produk', 'pembayaran']);

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pesanan', $request->status);
        }
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Hilangkan # dan leading zeros untuk search ID
                $searchId = ltrim(str_replace('#', '', $search), '0');
                
                if (is_numeric($searchId) && $searchId != '') {
                    // Jika input adalah angka, search berdasarkan ID
                    $q->where('id_pesanan', '=', (int)$searchId);
                } else {
                    // Jika bukan angka, search berdasarkan nama penerima saja
                    $q->where('penerima_nama', 'ILIKE', "%{$search}%");
                }
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'waktu_pesanan');
        $sortOrder = $request->get('sort_order', 'desc');
        
        // Validasi sort_by untuk keamanan
        $allowedSorts = ['id_pesanan', 'penerima_nama', 'waktu_pesanan', 'grand_total'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'waktu_pesanan';
        }
        
        // Validasi sort_order
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        $pesananList = $query->paginate(15);

        return view('admin.pesanan.index', [
            'pesananList' => $pesananList,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
        ]);
    }
}

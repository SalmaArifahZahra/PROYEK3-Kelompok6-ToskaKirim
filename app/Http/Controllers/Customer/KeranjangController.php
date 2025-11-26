<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = Keranjang::with(['produkDetail.produk'])
            ->where('id_user', Auth::id())
            ->get();

        return view('customer.keranjang.index', compact('keranjang'));
    }

    public function add(Request $request)
    {
        $id_user = Auth::id();

        $request->validate([
            'id_produk_detail' => 'required|exists:produk_detail,id_produk_detail',
            'quantity' => 'required|integer|min:1'
        ]);

        $detail = ProdukDetail::find($request->id_produk_detail);

        $existing = Keranjang::where('id_user', $id_user)
            ->where('id_produk_detail', $request->id_produk_detail)
            ->first();

        if ($existing) {
            $existing->quantity += $request->quantity;
            $existing->save();
        }
        else {
            Keranjang::create([
                'id_user' => $id_user,
                'id_produk_detail' => $request->id_produk_detail,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Produk berhasil dimasukkan ke keranjang'
        ]);
    }
}

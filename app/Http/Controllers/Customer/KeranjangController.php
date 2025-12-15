<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    //Menampilkan halaman keranjang
    public function index()
    {
        $keranjang = Keranjang::with('produk.detail')
            ->get();

        $produks = $keranjang->map(function ($item) {
            return $item->produk;
        })->filter();

        $keranjang = Keranjang::with('produk.detail')
            ->where('id_user', Auth::id())
            ->get();

        $cartCount = $keranjang->sum('quantity');

        return view('customer.keranjang.index', compact('keranjang', 'produks', 'cartCount'));
    }

    //Tambah Item ke Keranjang
    public function add(Request $request)
    {
        $request->validate([
            'id_produk_detail' => 'required|exists:produk_detail,id_produk_detail',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        $keranjang = Keranjang::updateOrCreate(
            [
                'id_user' => $userId,
                'id_produk_detail' => $request->id_produk_detail,
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
        ]);
    }

    //Delete Item Keranjang
    public function destroy($id_produk_detail)
    {
        $item = Keranjang::where('id_produk_detail', $id_produk_detail)
            ->where('id_user', auth::id())
            ->first();

        if ($item) {
            $item->delete();
            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Produk tidak ditemukan.');
    }

    //Delete All Item Keranjang yang dipilih
    public function destroyBulk(Request $request)
    {
        $request->validate([
            'selected_ids' => 'required|string',
        ]);

        $ids = explode(',', $request->input('selected_ids'));

        $deleted = Keranjang::whereIn('id_produk_detail', $ids)
            ->where('id_user',  auth::id())
            ->delete();

        if ($deleted) {
            return back()->with('success', 'Produk terpilih berhasil dihapus.');
        }

        return back()->with('error', 'Gagal menghapus produk atau produk tidak ditemukan.');
    }

    //update quantity keranjang
    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item = Keranjang::findOrFail($id);
        $item->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantity berhasil diperbarui');
    }
}

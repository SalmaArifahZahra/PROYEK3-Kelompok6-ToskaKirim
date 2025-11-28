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
        $request->validate([
            'id_produk_detail' => 'required|exists:id_produk_detail',
            'quantity' => 'required|integer|min:1'
        ]);

        $id_user = Auth::id();

        $existingItem = Keranjang::where('id_user', $id_user)
            ->where('id_produk_detail', $request->id_produk_detail)
            ->first();

        if ($existingItem) {
            // Jika produk sudah di keranjang → tambahkan quantity-nya
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            // Jika belum ada → tambahkan baris baru
            Keranjang::create([
                'id_user' => $id_user,
                'id_produk_detail' => $request->id_produk_detail,
                'quantity' => $request->quantity
            ]);
        }

        return response()->json(['message' => 'Produk berhasil dimasukkan ke keranjang']);
    }

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

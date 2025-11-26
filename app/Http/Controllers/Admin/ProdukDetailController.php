<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProdukDetail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukDetailController extends Controller
{
    // Menampilkan tampilan daftar varian/detail untuk suatu produk.
    public function index(Produk $produk): View
    {
        $detailList = $produk->detail()->orderBy('nama_varian', 'asc')->get();
        return view('admin.produk_detail.index', [
            'produk' => $produk,
            'detailList' => $detailList
        ]);
    }
    
    // Menampilkan form untuk membuat varian/detail baru.
    public function create(Produk $produk): View
    {
        return view('admin.produk_detail.create', [
            'produk' => $produk
        ]);
    }

    // Menyimpan varian/detail baru.
    public function store(Request $request, Produk $produk): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_varian' => 'required|string|max:150',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('produk'), $filename);
            $data['foto'] = 'produk/' . $filename;
        }

        $data['id_produk'] = $produk->id_produk;

        ProdukDetail::create($data);

        return redirect()->route('admin.produk_detail.index', $produk->id_produk)
                         ->with('success', 'Varian produk baru berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit varian/detail.
    public function edit(Produk $produk, ProdukDetail $detail): View
    {
        return view('admin.produk_detail.edit', [
            'produk' => $produk,
            'detail' => $detail
        ]);
    }

    // Memperbarui varian/detail.
    public function update(Request $request, Produk $produk, ProdukDetail $detail): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_varian' => 'required|string|max:150',
            'harga_modal' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            if ($detail->foto && file_exists(public_path($detail->foto))) {
                unlink(public_path($detail->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('produk'), $filename);
            $data['foto'] = 'produk/' . $filename;
        }

        $detail->update($data);

        return redirect()->route('admin.produk_detail.index', $produk->id_produk)->with('success', 'Varian produk berhasil diperbarui.');
    }

    // Menghapus varian/detail.
    public function destroy(Produk $produk, ProdukDetail $detail): RedirectResponse
    {
        if ($detail->foto) {
            Storage::disk('public')->delete($detail->foto);
        }

        $detail->delete();

        return redirect()->route('admin.produk_detail.index', $produk->id_produk)->with('success', 'Varian produk berhasil dihapus.');
    }
}
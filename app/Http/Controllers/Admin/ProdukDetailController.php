<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\ProdukDetail;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukDetailController extends Controller
{
    // Menampilkan tampilan daftar varian/detail untuk suatu produk.
    public function index(Request $request, Produk $produk): View
    {
        $kategoriId = $request->get('kategori');
        $kategori = null;
        
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
        }
        
        $detailList = $produk->detail()->orderBy('nama_varian', 'asc')->get();
        return view('admin.produk_detail.index', [
            'produk' => $produk,
            'detailList' => $detailList,
            'kategori' => $kategori
        ]);
    }
    
    // Menampilkan form untuk membuat varian/detail baru.
    public function create(Request $request, Produk $produk): View
    {
        $kategoriId = $request->get('kategori');
        $kategori = null;
        
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
        }
        
        return view('admin.produk_detail.create', [
            'produk' => $produk,
            'kategori' => $kategori
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
        
        // Ambil kategori untuk redirect
        $kategoriParam = $request->get('kategori');
        $routeParams = ['produk' => $produk->id_produk];
        if ($kategoriParam) {
            $routeParams['kategori'] = $kategoriParam;
        }

        return redirect()->route('admin.produk_detail.index', $routeParams)
                         ->with('success', 'Varian produk baru berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit varian/detail.
    public function edit(Request $request, Produk $produk, ProdukDetail $detail): View
    {
        $kategoriId = $request->get('kategori');
        $kategori = null;
        
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
        }
        
        return view('admin.produk_detail.edit', [
            'produk' => $produk,
            'detail' => $detail,
            'kategori' => $kategori
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
        
        // Ambil kategori untuk redirect
        $kategoriParam = $request->get('kategori');
        $routeParams = ['produk' => $produk->id_produk];
        if ($kategoriParam) {
            $routeParams['kategori'] = $kategoriParam;
        }

        return redirect()->route('admin.produk_detail.index', $routeParams)
                         ->with('success', 'Varian produk berhasil diperbarui.');
    }

    // Menghapus varian/detail.
    public function destroy(Request $request, Produk $produk, ProdukDetail $detail): RedirectResponse
    {
        $detail->delete();
        
        // Ambil kategori untuk redirect
        $kategoriParam = $request->get('kategori');
        $routeParams = ['produk' => $produk->id_produk];
        if ($kategoriParam) {
            $routeParams['kategori'] = $kategoriParam;
        }

        return redirect()->route('admin.produk_detail.index', $routeParams)
                         ->with('success', 'Varian produk berhasil dihapus.');
    }
}
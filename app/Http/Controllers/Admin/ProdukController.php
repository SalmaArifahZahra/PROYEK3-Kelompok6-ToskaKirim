<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProdukController extends Controller
{
    // Menampilkan daftar produk (induk) beserta jumlah variannya.
    public function index(): View
    {
        $produkList = Produk::with('kategori', 'detail')
                            ->withCount('detail')
                            ->orderBy('nama', 'asc')
                            ->paginate(10);

        return view('admin.produk.index', [
            'produkList' => $produkList
        ]);
    }

    // Menampilkan form untuk membuat produk (induk) baru.
    public function create(): View
    {
        $kategoriList = Kategori::with('children')
                                ->whereNull('parent_id')
                                ->orderBy('nama_kategori', 'asc')
                                ->get();
        
        return view('admin.produk.create', [
            'kategoriList' => $kategoriList
        ]);
    }

    // Menyimpan produk (induk) baru ke database.
    public function store(Request $request): RedirectResponse
    {
        // Validasi data induk
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255|unique:produk,nama',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $produk = Produk::create($validator->validated());

        return redirect()->route('admin.produk.edit', $produk->id_produk)
                         ->with('success', 'Produk berhasil dibuat. Sekarang, tambahkan varian untuk produk ini.');
    }

    // Menampilkan form untuk mengedit produk (induk).
    public function edit(Produk $produk): View
    {
        $produk->load('kategori', 'detail');
        $kategoriList = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('admin.produk.edit', [
            'produk' => $produk,
            'kategoriList' => $kategoriList
        ]);
    }

    // Memperbarui data produk (induk) di database.
    public function update(Request $request, Produk $produk): RedirectResponse
    {
        // Validasi data induk
        $validator = Validator::make($request->all(), [
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('produk', 'nama')->ignore($produk->id_produk, 'id_produk')
            ],
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $produk->update($validator->validated());

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Data produk berhasil diperbarui.');
    }

    // Menghapus produk (induk) beserta semua variannya.
    public function destroy(Produk $produk): RedirectResponse
    {
        $produk->load('detail');
        foreach ($produk->detail as $detail) {
            if ($detail->foto) {
                Storage::disk('public')->delete($detail->foto);
            }
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')
                         ->with('success', 'Produk dan semua variannya berhasil dihapus.');
    }
}
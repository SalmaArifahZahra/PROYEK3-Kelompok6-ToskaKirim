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
    // Menampilkan halaman pilih kategori produk.
    public function selectKategori(): View
    {
        $kategoriList = Kategori::whereNull('parent_id')
                                ->withCount('produk')
                                ->orderBy('nama_kategori', 'asc')
                                ->get();

        return view('admin.produk.select_kategori', [
            'kategoriList' => $kategoriList
        ]);
    }

    // Menampilkan daftar produk (induk) beserta jumlah variannya.
    public function index(Request $request)
    {
        $kategoriId = $request->get('kategori');
        
        // Validasi kategori exists
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
            if (!$kategori) {
                return redirect()->route('admin.produk.selectKategori')
                                 ->with('error', 'Kategori tidak ditemukan.');
            }
        } else {
            return redirect()->route('admin.produk.selectKategori');
        }

        $query = Produk::with('kategori', 'detail')
                       ->withCount('detail');
        
        // Filter by kategori (parent atau child)
        if ($kategori->parent_id === null) {
            // Jika kategori parent, ambil semua produk dari parent dan child-nya
            $childIds = $kategori->children->pluck('id_kategori')->toArray();
            $allKategoriIds = array_merge([$kategori->id_kategori], $childIds);
            $query->whereIn('id_kategori', $allKategoriIds);
        } else {
            // Jika sub-kategori, ambil produk dari sub-kategori tersebut
            $query->where('id_kategori', $kategori->id_kategori);
        }
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'ILIKE', "%{$search}%")
                  ->orWhere('deskripsi', 'ILIKE', "%{$search}%");
            });
        }
        
        $produkList = $query->orderBy('nama', 'asc')->paginate(10);

        return view('admin.produk.index', [
            'produkList' => $produkList,
            'kategori' => $kategori
        ]);
    }

    // Menampilkan form untuk membuat produk (induk) baru.
    public function create(Request $request): View
    {
        $kategoriId = $request->get('kategori');
        $kategori = null;
        
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
        }
        
        $parentCategories = Kategori::whereNull('parent_id')
                            ->with('children') 
                            ->orderBy('nama_kategori', 'asc')
                            ->get();
        
        return view('admin.produk.create', [
            'parentCategories' => $parentCategories,
            'kategori' => $kategori
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
        
        // Ambil kategori untuk redirect ke konteks yang sama
        $kategori = Kategori::find($produk->id_kategori);
        $kategoriParam = $kategori->parent_id ?? $kategori->id_kategori;

        return redirect()->route('admin.produk.edit', ['produk' => $produk->id_produk, 'kategori' => $kategoriParam])
                         ->with('success', 'Produk berhasil dibuat. Sekarang, tambahkan varian untuk produk ini.');
    }

    // Menampilkan form untuk mengedit produk (induk).
    public function edit(Request $request, Produk $produk): View
    {
        $kategoriId = $request->get('kategori');
        $kategori = null;
        
        if ($kategoriId) {
            $kategori = Kategori::find($kategoriId);
        }
        
        $produk->load('kategori');
        $parentCategories = Kategori::whereNull('parent_id')
                            ->with('children') 
                            ->orderBy('nama_kategori', 'asc')
                            ->get();

        $currentSubId = old('id_kategori', $produk->id_kategori);
        $currentParentId = old('parent_id');

        if (!$currentParentId && $produk->kategori) {
            if ($produk->kategori->parent_id) {
                $currentParentId = $produk->kategori->parent_id;
            } else {
                $currentParentId = $produk->kategori->id_kategori;
            }
        }

        return view('admin.produk.edit', [
            'produk' => $produk,
            'parentCategories' => $parentCategories,
            'currentParentId' => $currentParentId,
            'currentSubId' => $currentSubId,
            'kategori' => $kategori,
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
        
        // Ambil kategori untuk redirect ke konteks yang sama
        $kategori = Kategori::find($produk->id_kategori);
        $kategoriParam = $kategori->parent_id ?? $kategori->id_kategori;

        return redirect()->route('admin.produk.index', ['kategori' => $kategoriParam])
                         ->with('success', 'Data produk berhasil diperbarui.');
    }

    // Menghapus produk (induk) beserta semua variannya.
    public function destroy(Produk $produk): RedirectResponse
    {
        // Simpan kategori sebelum delete untuk redirect
        $kategori = Kategori::find($produk->id_kategori);
        $kategoriParam = $kategori ? ($kategori->parent_id ?? $kategori->id_kategori) : null;
        
        $produk->load('detail');
        foreach ($produk->detail as $detail) {
            if ($detail->foto) {
                Storage::disk('public')->delete($detail->foto);
            }
        }

        $produk->delete();

        if ($kategoriParam) {
            return redirect()->route('admin.produk.index', ['kategori' => $kategoriParam])
                             ->with('success', 'Produk dan semua variannya berhasil dihapus.');
        }
        
        return redirect()->route('admin.produk.selectKategori')
                         ->with('success', 'Produk dan semua variannya berhasil dihapus.');
    }
}
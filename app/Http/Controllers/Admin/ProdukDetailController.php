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
        
        $query = $produk->detail();
        
        // Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_varian', 'ILIKE', "%{$search}%");
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'nama_varian');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['nama_varian', 'harga_modal', 'harga_jual', 'stok', 'created_at'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'nama_varian';
        }
        
        $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        
        $detailList = $query->orderBy($sortBy, $sortOrder)->paginate(15);
        
        return view('admin.produk_detail.index', [
            'produk' => $produk,
            'detailList' => $detailList,
            'kategori' => $kategori,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder
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
        // Cek apakah varian ini masih ada di pesanan
        if ($detail->pesananDetail()->count() > 0) {
            $kategoriParam = $request->get('kategori');
            $routeParams = ['produk' => $produk->id_produk];
            if ($kategoriParam) {
                $routeParams['kategori'] = $kategoriParam;
            }
            
            return redirect()->route('admin.produk_detail.index', $routeParams)
                           ->with('error', 'Varian produk ini tidak dapat dihapus karena masih ada di pesanan');
        }
        
        // Hapus foto jika ada
        if ($detail->foto) {
            $fotoPath = public_path($detail->foto);
            if (file_exists($fotoPath)) {
                unlink($fotoPath);
            }
        }
        
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

    // Menghapus banyak varian produk sekaligus
    public function batchDelete(Request $request, Produk $produk): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $kategoriId = $request->input('kategori');
        
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada varian produk yang dipilih.');
        }

        $deletedCount = 0;
        $errors = [];

        foreach ($ids as $id) {
            $detail = ProdukDetail::where('id_produk_detail', $id)
                                  ->where('id_produk', $produk->id_produk)
                                  ->first();
            
            if ($detail) {
                // Cek apakah varian ini masih ada di pesanan
                if ($detail->pesananDetail()->count() > 0) {
                    $errors[] = "Varian {$detail->nama_varian} masih ada di pesanan";
                    continue;
                }
                
                if ($detail->foto && file_exists(public_path($detail->foto))) {
                    unlink(public_path($detail->foto));
                }
                $detail->delete();
                $deletedCount++;
            }
        }

        $routeParams = ['produk' => $produk->id_produk];
        if ($kategoriId) {
            $routeParams['kategori'] = $kategoriId;
        }

        if ($deletedCount > 0 && empty($errors)) {
            return redirect()->route('admin.produk_detail.index', $routeParams)
                           ->with('success', "{$deletedCount} varian produk berhasil dihapus");
        } elseif ($deletedCount > 0 && !empty($errors)) {
            return redirect()->route('admin.produk_detail.index', $routeParams)
                           ->with('warning', "{$deletedCount} varian berhasil dihapus, namun beberapa gagal: " . implode(', ', $errors));
        } else {
            return redirect()->route('admin.produk_detail.index', $routeParams)
                           ->with('error', 'Gagal menghapus varian: ' . implode(', ', $errors));
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    // Menampilkan daftar kategori.
    public function index(): View
    {
        // Only list top-level categories (no parent)
        $kategoriList = Kategori::whereNull('parent_id')
                        ->orderBy('nama_kategori', 'asc')
                        ->get();

        return view('admin.kategori.index', [
            'kategoriList' => $kategoriList
        ]);
    }

    // Menampilkan form untuk membuat kategori baru.
    public function create(): View
    {
        return view('admin.kategori.create');
    }

    // Menyimpan kategori baru ke database.
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('kategori'), $filename);
            $data['foto'] = 'kategori/' . $filename;
        }

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    // Menampilkan detail kategori (redirect ke edit).
    public function show(Kategori $kategori)
    {
        return redirect()->route('admin.kategori.edit', $kategori);
    }

    // Menampilkan form untuk mengedit kategori.
    public function edit(Produk $produk): View
    {
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
        ]);
    }

    // Memperbarui data kategori di database.
    public function update(Request $request, Kategori $kategori): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori->id_kategori, 'id_kategori')
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            if ($kategori->foto) {
                Storage::disk('public')->delete($kategori->foto);
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('kategori'), $filename);
            $data['foto'] = 'kategori/' . $filename;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori dari database.
    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->foto) {
            Storage::disk('public')->delete($kategori->foto);
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
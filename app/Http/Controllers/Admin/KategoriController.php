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
        $kategoriList = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('admin.kategori.index', [
            'kategoriList' => $kategoriList
        ]);
    }

    // Menampilkan form untuk membuat kategori baru.
    public function create(): View
    {
        $parentKategori = Kategori::whereNull('parent_id')->get();
    
        return view('admin.kategori.create', [
            'parentKategori' => $parentKategori
        ]);
    }

    // Menyimpan kategori baru ke database.
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'parent_id' => 'nullable|exists:kategori,id_kategori',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->filled('parent_id') && $request->filled('id_kategori') && $request->input('parent_id') == $request->input('id_kategori')) {
            return back()->withErrors(['parent_id' => 'Parent tidak boleh sama dengan kategori itu sendiri.'])->withInput();
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kategori', 'public');
            $data['foto'] = $path;
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
    public function edit(Kategori $kategori): View
    {
        $parentKategori = Kategori::whereNull('parent_id')
                    ->where('id_kategori', '!=', $kategori->id_kategori)
                    ->orderBy('nama_kategori')
                    ->get();

        return view('admin.kategori.edit', [
            'kategori' => $kategori,
            'parentKategori' => $parentKategori
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
            'parent_id' => 'nullable|exists:kategori,id_kategori',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // Prevent self-parenting: parent_id cannot equal the category's own id
        if (isset($data['parent_id']) && $data['parent_id'] == $kategori->id_kategori) {
            return back()->withErrors(['parent_id' => 'Parent tidak boleh sama dengan kategori itu sendiri.'])->withInput();
        }

        if ($request->hasFile('foto')) {
            if ($kategori->foto) {
                Storage::disk('public')->delete($kategori->foto);
            }

            $path = $request->file('foto')->store('kategori', 'public');
            $data['foto'] = $path;
        }

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    // Menghapus kategori dari database.
    public function destroy(Kategori $kategori): RedirectResponse
    {
        if ($kategori->foto) {
            Storage::disk('public')->delete($kategori->foto);
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
                         ->with('success', 'Kategori berhasil dihapus.');
    }
}
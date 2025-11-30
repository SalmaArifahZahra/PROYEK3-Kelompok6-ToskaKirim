<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SubKategoriController extends Controller
{
    // Menampilkan daftar sub-kategori dari kategori tertentu.
    public function index(Kategori $kategori): View
    {
        $subKategoriList = Kategori::where('parent_id', $kategori->id_kategori)
                                    ->orderBy('nama_kategori', 'asc')
                                    ->get();

        return view('admin.subkategori.index', [
            'kategori' => $kategori,
            'subKategoriList' => $subKategoriList
        ]);
    }

    // Menampilkan form untuk membuat sub-kategori baru.
    public function create(Kategori $kategori): View
    {
        return view('admin.subkategori.create', [
            'kategori' => $kategori
        ]);
    }

    // Menyimpan sub-kategori baru ke database.
    public function store(Request $request, Kategori $kategori): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['parent_id'] = $kategori->id_kategori;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('kategori'), $filename);
            $data['foto'] = 'kategori/' . $filename;
        }

        Kategori::create($data);

        return redirect()->route('admin.kategori.subkategori.index', $kategori->id_kategori)

        ->with('success', 'Sub-kategori berhasil ditambahkan.');
    }

    // Menampilkan form untuk mengedit sub-kategori.
    public function edit(Kategori $kategori, Kategori $subkategori): View
    {
        return view('admin.subkategori.edit', [
            'kategori' => $kategori,
            'subkategori' => $subkategori
        ]);
    }

    // Memperbarui data sub-kategori di database.
    public function update(Request $request, Kategori $kategori, Kategori $subkategori): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => [
                'required',
                'string',
                'max:100',
                Rule::unique('kategori', 'nama_kategori')->ignore($subkategori->id_kategori, 'id_kategori')
            ],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('foto')) {
            if ($subkategori->foto && file_exists(public_path($subkategori->foto))) {
                unlink(public_path($subkategori->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('kategori'), $filename);
            $data['foto'] = 'kategori/' . $filename;
        }

        $subkategori->update($data);

        return redirect()->route('admin.kategori.subkategori.index', $kategori->id_kategori)
                         ->with('success', 'Sub-kategori berhasil diperbarui.');
    }

    // Menghapus sub-kategori dari database.
    public function destroy(Kategori $kategori, Kategori $subkategori): RedirectResponse
    {
        if ($subkategori->foto && file_exists(public_path($subkategori->foto))) {
            unlink(public_path($subkategori->foto));
        }

        $subkategori->delete();

        return redirect()->route('admin.kategori.subkategori.index', $kategori->id_kategori)
                         ->with('success', 'Sub-kategori berhasil dihapus.');
    }
}

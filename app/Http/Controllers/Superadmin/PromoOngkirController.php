<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PromoOngkir;
use Illuminate\Http\Request;

class PromoOngkirController extends Controller
{
    public function index()
    {
        $promos = PromoOngkir::orderBy('is_active', 'desc')
                             ->orderBy('tanggal_selesai', 'desc')
                             ->get();
        return view('superadmin.promo.index', compact('promos'));
    }

    public function create()
    {
        return view('superadmin.promo.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_promo' => 'required|string|max:255',
            'min_belanja' => 'required|numeric|min:0',
            'nilai_potongan' => 'required|numeric|min:0',
            'mekanisme' => 'required|in:flat,kelipatan',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        PromoOngkir::create($request->all());

        return redirect()->route('superadmin.promo.index')->with('success', 'Promo berhasil dibuat!');
    }

    public function edit($id)
    {
        $promo = PromoOngkir::findOrFail($id);
        return view('superadmin.promo.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        $promo = PromoOngkir::findOrFail($id);
        // Validasi sama seperti store...
        $promo->update($request->all());
        return redirect()->route('superadmin.promo.index')->with('success', 'Promo diperbarui!');
    }

    public function destroy($id)
    {
        PromoOngkir::findOrFail($id)->delete();
        return redirect()->route('superadmin.promo.index')->with('success', 'Promo dihapus!');
    }
}
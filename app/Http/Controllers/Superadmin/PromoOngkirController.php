<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use App\Models\PromoOngkir;
use Illuminate\Http\Request;

class PromoOngkirController extends Controller
{
    public function index() {
        $promos = PromoOngkir::all();
        // Kamu perlu buat view: admin.promo.index
        return view('superadmin.promo.index', compact('promos'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama_promo'=>'required', 
            'min_belanja'=>'required|numeric',
            'potongan_jarak'=>'required|numeric'
        ]);
        PromoOngkir::create($request->all());
        return back()->with('success', 'Promo berhasil ditambahkan');
    }

    public function update(Request $request, $id) {
        $promo = PromoOngkir::findOrFail($id);
        $promo->update($request->all());
        return back()->with('success', 'Promo berhasil diupdate');
    }

    public function destroy($id) {
        PromoOngkir::destroy($id);
        return back()->with('success', 'Promo dihapus');
    }
}
<?php
namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;
use App\Models\LayananPengiriman;
use Illuminate\Http\Request;

class LayananPengirimanController extends Controller
{
    public function index() {
        $layanan = LayananPengiriman::all();
        // Kamu perlu buat view: admin.layanan.index
        return view('superadmin.layanan.index', compact('layanan'));
    }

    public function store(Request $request) {
        $request->validate(['nama_layanan'=>'required', 'tarif_per_km'=>'required|numeric']);
        LayananPengiriman::create($request->all());
        return back()->with('success', 'Layanan berhasil ditambahkan');
    }

    public function update(Request $request, $id) {
        $layanan = LayananPengiriman::findOrFail($id);
        $layanan->update($request->all());
        return back()->with('success', 'Layanan berhasil diupdate');
    }

    public function destroy($id) {
        LayananPengiriman::destroy($id);
        return back()->with('success', 'Layanan dihapus');
    }
}
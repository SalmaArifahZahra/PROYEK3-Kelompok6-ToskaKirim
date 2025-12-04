<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan; // Model tetap Pengaturan (karena tabelnya 'pengaturan')

class KontrolTokoController extends Controller
{
    public function index()
    {
        $wa = Pengaturan::where('key', 'nomor_wa')->value('value') ?? '';
        $alamat = Pengaturan::where('key', 'alamat_toko')->value('value') ?? '';
        
        // Perhatikan: Folder view berubah jadi 'kontrol_toko'
        return view('superadmin.kontrol_toko.index', compact('wa', 'alamat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nomor_wa' => 'required|numeric',
            'alamat_toko' => 'required|string|max:500',
        ]);

        Pengaturan::updateOrCreate(['key' => 'nomor_wa'], ['value' => $request->nomor_wa]);
        Pengaturan::updateOrCreate(['key' => 'alamat_toko'], ['value' => $request->alamat_toko]);

        return redirect()->back()->with('success', 'Identitas toko berhasil diperbarui!');
    }
}
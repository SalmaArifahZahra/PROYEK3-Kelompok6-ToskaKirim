<?php

namespace App\Http\Controllers\Superadmin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran; 
use Illuminate\Support\Facades\Storage;

class MetodePembayaranController extends Controller
{
    // Halaman "Control" di Figma
    public function index()
    {
        $payments = MetodePembayaran::all();
        return view('superadmin.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('superadmin.payments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_bank' => 'required|string',
            'jenis' => 'required|string', // Transfer Bank / E-Wallet
            'nomor_rekening' => 'nullable|string',
            'atas_nama' => 'nullable|string',
            'gambar' => 'required|image|max:2048' // Max 2MB
        ]);

        // Upload Gambar (Logo Bank / QRIS)
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('payments', 'public');
        }

        MetodePembayaran::create($data);

        return redirect()->route('superadmin.payments.index')->with('success', 'Metode pembayaran berhasil ditambahkan');
    }

    // Tambahkan destroy/hapus untuk tombol tong sampah di Figma
    public function destroy($id)
    {
        $payment = MetodePembayaran::findOrFail($id);
        
        // Hapus gambar dari storage biar gak numpuk
        if ($payment->gambar) {
            Storage::delete('public/' . $payment->gambar);
        }
        
        $payment->delete();
        return redirect()->back()->with('success', 'Dihapus');
    }
}
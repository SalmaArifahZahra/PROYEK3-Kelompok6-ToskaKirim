<?php

namespace App\Http\Controllers\Superadmin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\DB; 

class MetodePembayaranController extends Controller
{
    public function index()
    {
        $payments = MetodePembayaran::latest()->get(); // Mengambil data terbaru
        return view('superadmin.payments.index', compact('payments'));
    }

    public function create()
    {
        return view('superadmin.payments.create');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input yang Lebih Ketat
        $request->validate([
            'nama_bank'      => 'required|string|max:100',
            'jenis'          => 'required|in:Bank Transfer,E-Wallet,QRIS',
            'nomor_rekening' => 'nullable|string|max:50',
            'atas_nama'      => 'nullable|string|max:100', 
            'gambar'         => 'required|image|mimes:jpeg,png,jpg,webp|max:2048' 
        ], [
            'nama_bank.required' => 'Nama Bank/E-Wallet wajib diisi.',
            'jenis.required'     => 'Jenis metode pembayaran wajib dipilih.',
            'gambar.required'    => 'Logo atau gambar QRIS wajib diupload.',
            'gambar.image'       => 'File yang diupload harus berupa gambar.',
            'gambar.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction();

            $data = $request->only(['nama_bank', 'jenis', 'nomor_rekening', 'atas_nama']);

            // Handle Upload File dengan Error Checking
            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')->store('payment_methods', 'public');
                $data['gambar'] = $path;
            }

            MetodePembayaran::create($data);

            DB::commit(); 

            return redirect()->route('superadmin.payments.index')
                             ->with('success', 'Metode pembayaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack(); 
            
            Log::error("Error create payment method: " . $e->getMessage());

            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }

            return back()->withInput()->withErrors(['msg' => 'Terjadi kesalahan sistem. Silahkan coba lagi.']);
        }
    }

    public function destroy($id)
    {
        try {
            $payment = MetodePembayaran::findOrFail($id);
            
            if ($payment->gambar) {
                if (Storage::disk('public')->exists($payment->gambar)) {
                    Storage::disk('public')->delete($payment->gambar);
                }
            }
            
            $payment->delete();

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error("Error delete payment method: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
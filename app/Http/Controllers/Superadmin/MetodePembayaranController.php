<?php

namespace App\Http\Controllers\Superadmin; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MetodePembayaran; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Untuk mencatat error
use Illuminate\Support\Facades\DB; // Untuk transaksi database

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
            'atas_nama'      => 'nullable|string|max:100', // Penting untuk transfer
            'gambar'         => 'required|image|mimes:jpeg,png,jpg,webp|max:2048' // Max 2MB, format spesifik
        ], [
            // Pesan Error Kustom (Bahasa Indonesia)
            'nama_bank.required' => 'Nama Bank/E-Wallet wajib diisi.',
            'jenis.required'     => 'Jenis metode pembayaran wajib dipilih.',
            'gambar.required'    => 'Logo atau gambar QRIS wajib diupload.',
            'gambar.image'       => 'File yang diupload harus berupa gambar.',
            'gambar.max'         => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            DB::beginTransaction(); // Mulai Transaksi

            $data = $request->only(['nama_bank', 'jenis', 'nomor_rekening', 'atas_nama']);

            // 2. Handle Upload File dengan Error Checking
            if ($request->hasFile('gambar')) {
                // Simpan ke folder: storage/app/public/payment_methods
                $path = $request->file('gambar')->store('payment_methods', 'public');
                $data['gambar'] = $path;
            }

            // 3. Simpan ke Database
            MetodePembayaran::create($data);

            DB::commit(); // Simpan permanen jika tidak ada error

            return redirect()->route('superadmin.payments.index')
                             ->with('success', 'Metode pembayaran berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua perubahan jika error
            
            // Catat error di storage/logs/laravel.log untuk developer
            Log::error("Error create payment method: " . $e->getMessage());

            // Hapus gambar jika sudah terlanjur ter-upload tapi DB gagal
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
            
            // 1. Hapus Gambar dari Storage
            if ($payment->gambar) {
                // Gunakan disk 'public' karena saat upload kita pakai disk 'public'
                if (Storage::disk('public')->exists($payment->gambar)) {
                    Storage::disk('public')->delete($payment->gambar);
                }
            }
            
            // 2. Hapus Data dari Database
            $payment->delete();

            return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus.');

        } catch (\Exception $e) {
            Log::error("Error delete payment method: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data.');
        }
    }
}
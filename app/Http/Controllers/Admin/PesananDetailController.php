<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PesananDetailController extends Controller
{
    /**
     * Menampilkan detail pesanan tertentu.
     */
    public function index(Pesanan $pesanan): View
    {
        // Eager load relasi yang dibutuhkan
        $pesanan->load([
            'user',
            'ongkir',
            'detail.produkDetail.produk',
            'pembayaran'
        ]);

        return view('admin.pesanan_detail.index', [
            'pesanan' => $pesanan
        ]);
    }

    /**
     * Verifikasi pembayaran pesanan.
     * TODO: Implementasi logic verifikasi pembayaran
     * - Update status pembayaran menjadi 'verified'
     * - Update status pesanan menjadi 'diproses'
     * - Validasi bukti transfer jika diperlukan
     */
    public function verify(Pesanan $pesanan): RedirectResponse
    {

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    /**
     * Proses pesanan (ubah status ke diproses/dikirim).
     * TODO: Implementasi logic pemrosesan pesanan
     * - Update status pesanan
     * - Validasi stok produk
     * - Generate nomor resi jika dikirim
     */
    public function process(Pesanan $pesanan): RedirectResponse
    {

        return back()->with('success', 'Status pesanan berhasil diupdate.');
    }

    /**
     * Selesaikan pesanan (ubah status ke selesai).
     * TODO: Implementasi logic penyelesaian pesanan
     * - Update status pesanan menjadi 'selesai'
     * - Validasi pesanan sudah dikirim
     */
    public function complete(Pesanan $pesanan): RedirectResponse
    {

        return back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    /**
     * Batalkan pesanan.
     * TODO: Implementasi logic pembatalan pesanan
     * - Update status pesanan menjadi 'dibatalkan'
     * - Kembalikan stok produk
     * - Update status pembayaran jika sudah dibayar
     */
    public function cancel(Pesanan $pesanan): RedirectResponse
    {

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

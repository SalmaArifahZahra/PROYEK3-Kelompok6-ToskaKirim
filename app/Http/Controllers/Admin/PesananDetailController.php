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
        // TODO: Implementasi logic verifikasi
        // Contoh:
        // if ($pesanan->status_pesanan->value !== 'menunggu_verifikasi') {
        //     return back()->with('error', 'Pesanan tidak dapat diverifikasi.');
        // }
        //
        // $pesanan->pembayaran->update(['status_pembayaran' => 'verified']);
        // $pesanan->update(['status_pesanan' => 'diproses']);

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
        // TODO: Implementasi logic proses pesanan
        // Contoh:
        // if ($pesanan->status_pesanan->value === 'menunggu_verifikasi') {
        //     $pesanan->update(['status_pesanan' => 'diproses']);
        // } elseif ($pesanan->status_pesanan->value === 'diproses') {
        //     $pesanan->update(['status_pesanan' => 'dikirim']);
        // }

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
        // TODO: Implementasi logic selesaikan pesanan
        // Contoh:
        // if ($pesanan->status_pesanan->value !== 'dikirim') {
        //     return back()->with('error', 'Pesanan harus dalam status dikirim.');
        // }
        //
        // $pesanan->update(['status_pesanan' => 'selesai']);

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
        // TODO: Implementasi logic batalkan pesanan
        // Contoh:
        // if (!in_array($pesanan->status_pesanan->value, ['menunggu_pembayaran', 'menunggu_verifikasi'])) {
        //     return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
        // }
        //
        // // Kembalikan stok produk
        // foreach ($pesanan->detail as $item) {
        //     $item->produkDetail->increment('stok', $item->kuantitas);
        // }
        //
        // $pesanan->update(['status_pesanan' => 'dibatalkan']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

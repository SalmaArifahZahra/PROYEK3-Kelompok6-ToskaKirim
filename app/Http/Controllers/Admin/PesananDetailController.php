<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Enums\StatusPesananEnum;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PesananDetailController extends Controller
{
    // Tampilkan detail pesanan
    public function index(Pesanan $pesanan): View
    {
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

    // Verifikasi pembayaran pesanan
    public function verify(Pesanan $pesanan): RedirectResponse
    {
        if ($pesanan->status_pesanan !== StatusPesananEnum::MENUNGGU_VERIFIKASI) {
            return back()->with('error', 'Pesanan hanya bisa diverifikasi jika status menunggu verifikasi.');
        }

        // Update status pesanan ke diproses
        $pesanan->update([
            'status_pesanan' => StatusPesananEnum::DIPROSES
        ]);

        // Update status pembayaran jika ada
        if ($pesanan->pembayaran) {
            $pesanan->pembayaran->update([
                'status_pembayaran' => 'diterima'
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi. Status pesanan diubah ke Diproses.');
    }

    // Proses pesanan (ubah status ke diproses atau dikirim)
    public function process(Pesanan $pesanan): RedirectResponse
    {
        if (!in_array($pesanan->status_pesanan->value, [
            StatusPesananEnum::MENUNGGU_VERIFIKASI->value,
            StatusPesananEnum::DIPROSES->value
        ])) {
            return back()->with('error', 'Pesanan hanya bisa diproses dari status menunggu verifikasi atau diproses.');
        }

        $newStatus = $pesanan->status_pesanan === StatusPesananEnum::DIPROSES 
            ? StatusPesananEnum::DIKIRIM 
            : StatusPesananEnum::DIPROSES;

        $pesanan->update([
            'status_pesanan' => $newStatus
        ]);

        $message = $newStatus === StatusPesananEnum::DIKIRIM 
            ? 'Status pesanan berhasil diubah ke Dikirim.' 
            : 'Status pesanan berhasil diubah ke Diproses.';

        return back()->with('success', $message);
    }

    // Selesaikan pesanan
    public function complete(Pesanan $pesanan): RedirectResponse
    {
        // Hanya bisa diselesaikan jika status dikirim
        if ($pesanan->status_pesanan !== StatusPesananEnum::DIKIRIM) {
            return back()->with('error', 'Pesanan hanya bisa diselesaikan jika status dikirim.');
        }

        $pesanan->update([
            'status_pesanan' => StatusPesananEnum::SELESAI
        ]);

        return back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    // Batalkan pesanan
    public function cancel(Pesanan $pesanan): RedirectResponse
    {
        // Hanya bisa dibatalkan dari status menunggu_pembayaran atau menunggu_verifikasi
        if (!in_array($pesanan->status_pesanan->value, [
            StatusPesananEnum::MENUNGGU_PEMBAYARAN->value,
            StatusPesananEnum::MENUNGGU_VERIFIKASI->value
        ])) {
            return back()->with('error', 'Pesanan hanya bisa dibatalkan jika status menunggu pembayaran atau menunggu verifikasi.');
        }

        $pesanan->update([
            'status_pesanan' => StatusPesananEnum::DIBATALKAN
        ]);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\ProdukDetail;
use App\Models\Ongkir;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Enums\StatusPesananEnum;
use App\Enums\StatusPembayaranEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PesananController extends Controller
{
    // Menampilkan Daftar Pesanan dengan Filter Status
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Pesanan::where('id_user', Auth::id())
            ->with(['detail.produkDetail', 'pembayaran'])
            ->latest('waktu_pesanan');

        if ($status) {
            $query->where('status_pesanan', $status);
        }

        $pesananList = $query->get();

        return view('customer.pesanan.index', compact('pesananList', 'status'));
    }

    // Membuat Pesanan dari Keranjang
    public function store(Request $request)
    {
        $request->validate([
            'id_produk_detail' => 'required|exists:produk_detail,id_produk_detail',
            'quantity' => 'required|integer|min:1'
        ]);

        $user = Auth::user();

        // Cek alamat utama
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();
        if (!$alamatUtama) {
            return back()->with('error', 'Silahkan atur alamat utama sebelum melakukan pemesanan.');
        }

        $produkVarian = ProdukDetail::findOrFail($request->id_produk_detail);

        // Cek Stok
        if ($produkVarian->stok < $request->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        DB::beginTransaction();
        try {
            // Dummy Ongkir, nanti diganti dengan perhitungan real
            $ongkir = Ongkir::create([
                'jarak' => 0,
                'tarif_per_km' => 0,
                'total_ongkir' => 15000 // Asumsi flat rate sementara
            ]);

            $subtotal = $produkVarian->harga_jual * $request->quantity;
            $grandTotal = $subtotal + $ongkir->total_ongkir;

            $pesanan = Pesanan::create([
                'id_user' => $user->id_user,
                'id_ongkir' => $ongkir->id_ongkir,
                'waktu_pesanan' => now(),
                'subtotal_produk' => $subtotal,
                'grand_total' => $grandTotal,
                'status_pesanan' => StatusPesananEnum::MENUNGGU_PEMBAYARAN,
                'penerima_nama' => $alamatUtama->nama_penerima,
                'penerima_telepon' => $alamatUtama->telepon_penerima,
                'alamat_lengkap' => "{$alamatUtama->jalan_patokan}, {$alamatUtama->kelurahan}, {$alamatUtama->kecamatan}, {$alamatUtama->kota_kabupaten}",
            ]);

            PesananDetail::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_produk_detail' => $produkVarian->id_produk_detail,
                'kuantitas' => $request->quantity,
                'harga_beli' => $produkVarian->harga_jual,
                'subtotal_item' => $subtotal
            ]);

            DB::commit();

            return redirect("customer/pesanan/" . $pesanan->id_pesanan)
                ->with('success', 'Pesanan berhasil dibuat. Silahkan lakukan pembayaran.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage());
        }
    }

    // Menampilkan Detail Pesanan
    public function show($id)
    {
        $pesanan = Pesanan::where('id_user', Auth::id())
            ->with(['detail.produkDetail.produk', 'ongkir', 'pembayaran'])
            ->findOrFail($id);

        $pengaturan = Pengaturan::first();

        // Hitung Deadline (Waktu Pesanan + 24 Jam)
        $deadline = Carbon::parse($pesanan->waktu_pesanan)->addHours(24);
        $deadlineTimestamp = $deadline->timestamp * 1000;

        return view('customer.pesanan.show', compact('pesanan', 'pengaturan', 'deadline', 'deadlineTimestamp'));
    }

    // Upload Bukti Pembayaran
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jumlah_bayar' => 'required|numeric'
        ]);

        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);

        if ($pesanan->status_pesanan !== StatusPesananEnum::MENUNGGU_PEMBAYARAN) {
            return back()->with('error', 'Status pesanan tidak valid untuk upload bukti.');
        }

        // Upload File
        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        DB::transaction(function () use ($pesanan, $request, $path) {
            Pembayaran::updateOrCreate(
                ['id_pesanan' => $pesanan->id_pesanan],
                [
                    'bukti_bayar' => $path,
                    'jumlah_bayar' => $request->jumlah_bayar,
                    'tanggal_bayar' => now(),
                    'status_pembayaran' => StatusPembayaranEnum::MENUNGGU_VERIFIKASI
                ]
            );

            $pesanan->update([
                'status_pesanan' => StatusPesananEnum::MENUNGGU_VERIFIKASI
            ]);
        });

        // return redirect()->('customer.pesanan.index')
        return redirect('customer/pesanan')
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi admin.');
    }

    // Batalkan Pesanan
    public function cancel($id)
    {
        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);

        if ($pesanan->status_pesanan === StatusPesananEnum::MENUNGGU_PEMBAYARAN && StatusPembayaranEnum::MENUNGGU_VERIFIKASI) {
            $pesanan->update(['status_pesanan' => StatusPesananEnum::DIBATALKAN]);
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan tidak dapat dibatalkan.');
    }
}

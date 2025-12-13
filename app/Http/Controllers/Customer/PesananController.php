<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\PesananDetail;
use App\Models\ProdukDetail;
use App\Models\Ongkir;
use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\MetodePembayaran;
use App\Models\LayananPengiriman;
use App\Models\Keranjang;
use App\Enums\StatusPesananEnum;
use App\Enums\StatusPembayaranEnum;
use App\Services\OngkirService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

    // Membuat Pesanan dari Keranjang (old single-item method - no longer used, removed)


    // Show confirmation page for checkout: display selected items before creating pesanan
    public function checkoutFromCart(Request $request)
    {
        // 1. Ambil data items
        $itemsInput = $request->input('items');

        // 2. Decode JSON String menjadi Array PHP
        $items = is_string($itemsInput) ? json_decode($itemsInput, true) : $itemsInput;

        // 3. Validasi: Pastikan hasil decode adalah array dan tidak kosong
        if (!is_array($items) || empty($items)) {
            return back()->with('error', 'Tidak ada produk yang dipilih atau data tidak valid.');
        }

        $selectedItems = [];
        $subtotal = 0;

        // 4. Looping data items
        foreach ($items as $item) {
            // Pastikan key yang diperlukan ada
            if (!isset($item['id_produk_detail'], $item['quantity'])) {
                continue; // Skip jika data rusak
            }

            // Ambil data produk dari DB
            $produkVarian = ProdukDetail::with('produk')->find($item['id_produk_detail']);

            // Cek apakah produk ditemukan
            if (!$produkVarian) {
                return back()->with('error', 'Salah satu produk tidak ditemukan.');
            }

            // Cek Stok
            if ($produkVarian->stok < $item['quantity']) {
                return back()->with('error', "Stok tidak mencukupi untuk: {$produkVarian->nama_varian} (Sisa: {$produkVarian->stok})");
            }

            $lineSubtotal = $produkVarian->harga_jual * $item['quantity'];
            $subtotal += $lineSubtotal;

            $selectedItems[] = [
                'produk_detail' => $produkVarian,
                'quantity' => $item['quantity'],
                'subtotal' => $lineSubtotal
            ];
        }

        $user = Auth::user();
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();

        // Cek Alamat
        if (!$alamatUtama) {
            return redirect()->route('customer.alamat.create')
                             ->with('error', 'Silahkan atur alamat utama sebelum melakukan pemesanan.');
        }

        // 5. Ambil Metode Pembayaran
        $paymentMethods = MetodePembayaran::where('is_active', 1)->get();

        // 6. Ambil Layanan Pengiriman yang Active
        $layananPengiriman = \App\Models\LayananPengiriman::where('is_active', 1)->get();
        $selectedLayanan = null;
        if ($layananPengiriman->count() == 1) {
            $selectedLayanan = $layananPengiriman->first()->id;
        }

        // 7. Hitung ongkir berdasarkan layanan default (jika hanya 1) atau default value
        $ongkir = 0;
        if ($selectedLayanan) {
            $ongkirService = new OngkirService();
            $ongkirData = $ongkirService->hitungOngkir($selectedLayanan, $alamatUtama->id_alamat);
            $ongkir = $ongkirData['total_ongkir'];
        }

        $grandTotal = $subtotal + $ongkir;

        // 8. Lempar ke View Konfirmasi
        return view('customer.pesanan.confirm', [
            'selectedItems' => $selectedItems,
            'subtotal' => $subtotal,
            'ongkir' => $ongkir,
            'grandTotal' => $grandTotal,
            'alamatUtama' => $alamatUtama,
            'paymentMethods' => $paymentMethods,
            'layananPengiriman' => $layananPengiriman,
            'selectedLayanan' => $selectedLayanan
        ]);
    }

    // Create pesanan from confirmed items
    public function storeFromConfirm(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'metode_pembayaran' => 'required',
            'id_layanan_pengiriman' => 'required|exists:layanan_pengiriman,id',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = Auth::user();
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();
        $items = json_decode($request->input('items'), true);

        DB::beginTransaction();
        try {
            // 1. Tentukan Status Awal
            $statusPesanan = StatusPesananEnum::MENUNGGU_PEMBAYARAN; // Default (Bayar Nanti)
            
            // Cek Metode Pembayaran
            if ($request->metode_pembayaran == 'COD') {
                $statusPesanan = StatusPesananEnum::DIPROSES; // Langsung diproses admin
            } elseif ($request->hasFile('bukti_bayar')) {
                $statusPesanan = StatusPesananEnum::MENUNGGU_VERIFIKASI; // Jika langsung upload
            }

            // 2. Hitung Ongkir Dinamis
            $ongkirService = new OngkirService();
            $ongkirData = $ongkirService->hitungOngkir(
                $request->id_layanan_pengiriman,
                $alamatUtama->id_alamat
            );

            // Cek jika ada error dalam kalkulasi ongkir
            if ($ongkirData['error']) {
                DB::rollBack();
                return back()->with('error', 'Kalkulasi ongkir gagal: ' . $ongkirData['error']);
            }

            // 3. Buat Data Ongkir & Pesanan Header
            $ongkir = Ongkir::create([
                'jarak' => $ongkirData['jarak'],
                'tarif_per_km' => $ongkirData['tarif_per_km'],
                'total_ongkir' => $ongkirData['total_ongkir']
            ]);
            
            $pesanan = Pesanan::create([
                'id_user' => $user->id_user,
                'id_ongkir' => $ongkir->id_ongkir,
                'id_layanan_pengiriman' => $request->id_layanan_pengiriman,
                'waktu_pesanan' => now(),
                'subtotal_produk' => 0,
                'grand_total' => 0,
                'status_pesanan' => $statusPesanan,
                'penerima_nama' => $alamatUtama->nama_penerima,
                'penerima_telepon' => $alamatUtama->telepon_penerima,
                'alamat_lengkap' => "{$alamatUtama->jalan_patokan}, {$alamatUtama->kelurahan}, {$alamatUtama->kecamatan}, {$alamatUtama->kota_kabupaten}",
            ]);

            // 4. Simpan Detail Produk
            $subtotal = 0;
            foreach ($items as $item) {
                $produkVarian = ProdukDetail::findOrFail($item['id_produk_detail']);
                
                // Kurangi Stok (Penting!)
                $produkVarian->decrement('stok', $item['quantity']);

                $lineSubtotal = $produkVarian->harga_jual * $item['quantity'];
                $subtotal += $lineSubtotal;

                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk_detail' => $produkVarian->id_produk_detail,
                    'kuantitas' => $item['quantity'],
                    'harga_beli' => $produkVarian->harga_jual,
                    'subtotal_item' => $lineSubtotal
                ]);
            }

            // Update Total
            $grandTotal = $subtotal + $ongkir->total_ongkir;
            $pesanan->update(['subtotal_produk' => $subtotal, 'grand_total' => $grandTotal]);

            // 5. Handle Bukti Pembayaran (Jika ada)
            if ($request->metode_pembayaran != 'COD' && $request->hasFile('bukti_bayar')) {
                $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
                
                Pembayaran::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'bukti_bayar' => $path,
                    'jumlah_bayar' => $grandTotal,
                    'tanggal_bayar' => now(),
                    'status_pembayaran' => StatusPembayaranEnum::MENUNGGU_VERIFIKASI
                ]);
            }

            // 6. Hapus Keranjang
            foreach ($items as $item) {
                Keranjang::where('id_user', $user->id_user)
                    ->where('id_produk_detail', $item['id_produk_detail'])
                    ->delete();
            }

            DB::commit();

            // 6. Redirect Sesuai Kondisi
            if ($request->metode_pembayaran == 'COD') {
                return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan COD berhasil dibuat!');
            }
            
            if ($request->hasFile('bukti_bayar')) {
                return redirect()->route('customer.pesanan.index')->with('success', 'Pesanan dibuat & bukti pembayaran terkirim!');
            }

            // Jika Bayar Nanti (Transfer tapi belum upload)
            return redirect()->route('customer.pesanan.show', $pesanan->id_pesanan)
                ->with('success', 'Pesanan dibuat. Silahkan lakukan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    // Menampilkan Detail Pesanan
    public function show($id)
    {
        $pesanan = \App\Models\Pesanan::where('id_pesanan', $id)
            ->with(['detail.produkDetail.produk', 'ongkir', 'pembayaran'])
            ->findOrFail($id);


        $pengaturan = Pengaturan::first();
        $alamatUtama = Auth::user()->alamatUser()->where('is_utama', true)->first();


        // Hitung Deadline (Waktu Pesanan + 24 Jam)
        $deadline = Carbon::parse($pesanan->waktu_pesanan)->addHours(24);
        $deadlineTimestamp = $deadline->timestamp * 1000;

        return view('customer.pesanan.show', compact('pesanan', 'pengaturan', 'deadline', 'deadlineTimestamp', 'alamatUtama'));
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

    // API: Hitung ongkir berdasarkan layanan pengiriman
    public function calculateOngkir(Request $request)
    {
        $request->validate([
            'id_layanan_pengiriman' => 'required|exists:layanan_pengiriman,id',
        ]);

        $user = Auth::user();
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();

        if (!$alamatUtama) {
            return response()->json([
                'success' => false,
                'error' => 'Alamat utama tidak ditemukan'
            ], 400);
        }

        $ongkirService = new OngkirService();
        $ongkirData = $ongkirService->hitungOngkir(
            $request->id_layanan_pengiriman,
            $alamatUtama->id_alamat
        );

        if ($ongkirData['error']) {
            return response()->json([
                'success' => false,
                'error' => $ongkirData['error']
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'jarak' => $ongkirData['jarak'],
                'tarif_per_km' => $ongkirData['tarif_per_km'],
                'total_ongkir' => $ongkirData['total_ongkir'],
                'total_ongkir_formatted' => 'Rp ' . number_format($ongkirData['total_ongkir'], 0, ',', '.')
            ]
        ]);
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

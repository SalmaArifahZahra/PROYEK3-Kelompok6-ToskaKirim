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
    // Menampilkan daftar pesanan customer
    public function index(Request $request)
    {
        $pesananList = Pesanan::where('id_user', Auth::id())
            ->with(['detail.produkDetail', 'pembayaran'])
            ->when($request->query('status'), function ($query, $status) {
                return $query->where('status_pesanan', $status);
            })
            ->latest('waktu_pesanan')
            ->get();

        return view('customer.pesanan.index', [
            'pesananList' => $pesananList,
            'status' => $request->query('status')
        ]);
    }

    // Menampilkan halaman konfirmasi pesanan dari keranjang
    public function checkoutFromCart(Request $request)
    {
        $itemsData = $this->parseItems($request->input('items'));
        if (!$itemsData) {
            return redirect()->route('customer.keranjang.index')
                ->with('error', 'Tidak ada produk yang dipilih.');
        }

        $user = Auth::user();
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();

        try {
            $summary = $this->calculateOrderSummary($itemsData, false);
        } catch (\Exception $e) {
            return redirect()->route('customer.keranjang.index')->with('error', $e->getMessage());
        }

        $paymentMethods = MetodePembayaran::where('is_active', 1)->get();
        $layananPengiriman = LayananPengiriman::where('is_active', 1)->get();
        $selectedLayanan = $layananPengiriman->first();

        // Jika belum ada alamat utama, jangan hitung ongkir — tampilkan kosong di view
        $ongkir = null;

        if ($selectedLayanan && $alamatUtama) {
            $ongkirService = new OngkirService();
            $ongkirResult = $ongkirService->hitungOngkir($selectedLayanan->id, $alamatUtama->id_alamat);
            $ongkir = $ongkirResult['total_ongkir'] ?? 0;
        }
        return view('customer.pesanan.confirm', [
            'selectedItems' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'ongkir' => $ongkir,
            'grandTotal' => $summary['subtotal'] + ($ongkir ?? 0),
            'alamatUtama' => $alamatUtama,
            'paymentMethods' => $paymentMethods,
            'layananPengiriman' => $layananPengiriman,
            'selectedLayananId' => $selectedLayanan->id ?? null
        ]);
    }

    // Proses penyimpanan pesanan dari konfirmasi
    public function storeFromConfirm(Request $request)
    {
        $request->validate([
            'items' => 'required|json',
            'metode_pembayaran' => 'required|exists:payment_methods,id',
            'id_layanan_pengiriman' => 'required|exists:layanan_pengiriman,id',
            'id_alamat' => 'nullable|exists:alamat_user,id_alamat',
            'bukti_bayar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'id_layanan_pengiriman.required' => 'Pilih layanan pengiriman terlebih dahulu.',
            'id_layanan_pengiriman.exists' => 'Layanan pengiriman yang dipilih tidak valid.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'metode_pembayaran.exists' => 'Metode pembayaran tidak valid atau tidak aktif.',
        ]);

        $user = Auth::user();

        // Jika ada id_alamat dari modal, gunakan yang ada. Jika tidak, gunakan alamat utama
        $alamat = null;
        if ($request->id_alamat) {
            $alamat = $user->alamatUser()->where('id_alamat', $request->id_alamat)->first();
        } else {
            $alamat = $user->alamatUser()->where('is_utama', true)->first();
        }

        if (!$alamat) {
            // Jika user belum punya alamat, kembalikan ke halaman konfirmasi
            // dan tampilkan notifikasi agar menambahkan alamat terlebih dahulu.
            return back()->with('error', 'Anda belum memiliki alamat pengiriman. Silakan tambahkan alamat terlebih dahulu.');
        }

        $itemsData = $this->parseItems($request->input('items'));
        if (!$itemsData) {
            return redirect()->route('customer.keranjang.index')->with('error', 'Data keranjang tidak valid.');
        }

        $idLayanan = $request->id_layanan_pengiriman ?? LayananPengiriman::where('is_active', 1)->value('id');
        if (!$idLayanan) return back()->with('error', 'Layanan pengiriman tidak tersedia.');

        $selectedPayment = MetodePembayaran::where('is_active', 1)->findOrFail($request->metode_pembayaran);
        $metodeJenis = strtoupper($selectedPayment->jenis);
        $isCOD = $metodeJenis === 'COD';

        DB::beginTransaction();
        try {
            $ongkirService = new OngkirService();
            $ongkirData = $ongkirService->hitungOngkir($idLayanan, $alamat->id_alamat);

            if (!empty($ongkirData['error'])) {
                throw new \Exception('Gagal menghitung ongkir: ' . $ongkirData['error']);
            }
            $summary = $this->calculateOrderSummary($itemsData, true);
            $ongkirRecord = Ongkir::create([
                'jarak' => $ongkirData['jarak'],
                'tarif_per_km' => $ongkirData['tarif_per_km'],
                'total_ongkir' => $ongkirData['total_ongkir']
            ]);

            $statusPesanan = $this->determineOrderStatus($isCOD, $request);

            $pesanan = Pesanan::create([
                'id_user' => $user->id_user,
                'id_ongkir' => $ongkirRecord->id_ongkir,
                'id_layanan_pengiriman' => $idLayanan,
                'waktu_pesanan' => now(),
                'status_pesanan' => $statusPesanan,
                'metode_pembayaran' => $selectedPayment->jenis,
                'penerima_nama' => $alamat->nama_penerima,
                'penerima_telepon' => $alamat->telepon_penerima,
                'alamat_lengkap' => "{$alamat->jalan_patokan}, {$alamat->kelurahan}, {$alamat->kecamatan}, {$alamat->kota_kabupaten}",
                'subtotal_produk' => $summary['subtotal'],
                'grand_total' => $summary['subtotal'] + $ongkirData['total_ongkir']
            ]);

            foreach ($summary['items'] as $item) {
                PesananDetail::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_produk_detail' => $item['produk_detail']->id_produk_detail,
                    'kuantitas' => $item['quantity'],
                    'harga_saat_beli' => $item['produk_detail']->harga_jual,
                    'subtotal_item' => $item['subtotal']
                ]);
            }
            if (
                !$isCOD &&
                $request->hasFile('bukti_bayar') &&
                $request->file('bukti_bayar')->isValid()
            ) {
                $this->savePaymentProof($request, $pesanan);
            }

            $productIds = array_column($itemsData, 'id_produk_detail');
            Keranjang::where('id_user', $user->id_user)
                ->whereIn('id_produk_detail', $productIds)
                ->delete();

            DB::commit();
            $hasValidProof = !$isCOD && $request->hasFile('bukti_bayar') && $request->file('bukti_bayar')->isValid();

            return $this->redirectAfterOrder($isCOD, $hasValidProof, $pesanan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('customer.keranjang.index')
                ->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pesanan = Pesanan::where('id_pesanan', $id)
            ->where('id_user', Auth::id())
            ->with(['detail.produkDetail.produk', 'ongkir', 'pembayaran', 'layananPengiriman'])
            ->findOrFail($id);


        $paymentMethods = MetodePembayaran::where('is_active', 1)->get();
        $deadline = Carbon::parse($pesanan->waktu_pesanan)->addHours(24);
        $deadlineTimestamp = $deadline->timestamp * 1000;
        $isExpired = now()->greaterThan($deadline);
        $isCOD = strtoupper($pesanan->metode_pembayaran ?? '') === 'COD';

        return view('customer.pesanan.show', compact(
            'pesanan',
            'deadline',
            'deadlineTimestamp',
            'paymentMethods',
            'isExpired',
            'isCOD'
        ));
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

        DB::transaction(function () use ($pesanan, $request) {
            $this->savePaymentProof($request, $pesanan);

            $pesanan->update([
                'status_pesanan' => StatusPesananEnum::MENUNGGU_VERIFIKASI
            ]);
        });

        return redirect()->route('customer.pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Bukti pembayaran berhasil diupload!');
    }

    // Batalkan Pesanan
    public function cancel($id)
    {
        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);

        // Hanya bisa batal jika status Menunggu Pembayaran
        if ($pesanan->status_pesanan === StatusPesananEnum::MENUNGGU_PEMBAYARAN ||
            $pesanan->status_pesanan === StatusPesananEnum::MENUNGGU_VERIFIKASI) {
            $pesanan->update(['status_pesanan' => StatusPesananEnum::DIBATALKAN]);
            return back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return back()->with('error', 'Pesanan tidak dapat dibatalkan saat ini.');
    }

    // Hitung Ongkir Dinamis dari Checkout
    public function calculateOngkir(Request $request)
    {
        $request->validate(['id_layanan_pengiriman' => 'required|exists:layanan_pengiriman,id']);

        $user = Auth::user();
        $alamatUtama = $user->alamatUser()->where('is_utama', true)->first();

        if (!$alamatUtama) {
            return response()->json(['success' => false, 'error' => 'Alamat utama tidak ditemukan'], 400);
        }

        $ongkirService = new OngkirService();
        $ongkirData = $ongkirService->hitungOngkir($request->id_layanan_pengiriman, $alamatUtama->id_alamat);

        if (!empty($ongkirData['error'])) {
            return response()->json(['success' => false, 'error' => $ongkirData['error']], 400);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'jarak' => $ongkirData['jarak'],
                'tarif_per_km' => $ongkirData['tarif_per_km'],
                'total_ongkir' => $ongkirData['total_ongkir'],
                'total_ongkir_formatted' => $ongkirData['total_ongkir_formatted']
            ]
        ]);
    }

    private function parseItems($itemsInput)
    {
        $items = is_string($itemsInput) ? json_decode($itemsInput, true) : $itemsInput;
        return (is_array($items) && !empty($items)) ? $items : null;
    }

    private function calculateOrderSummary(array $itemsData, bool $decreaseStock = false)
    {
        $selectedItems = [];
        $subtotal = 0;

        foreach ($itemsData as $item) {
            if (!isset($item['id_produk_detail'], $item['quantity'])) continue;

            $produkVarian = ProdukDetail::with('produk')->find($item['id_produk_detail']);

            if (!$produkVarian) {
                throw new \Exception("Produk ID {$item['id_produk_detail']} tidak ditemukan.");
            }

            if ($produkVarian->stok < $item['quantity']) {
                throw new \Exception("Stok tidak cukup untuk: {$produkVarian->nama_varian}.");
            }

            // Kurangi stok jika diminta (saat finalisasi pesanan)
            if ($decreaseStock) {
                $produkVarian->decrement('stok', $item['quantity']);
            }

            $lineSubtotal = $produkVarian->harga_jual * $item['quantity'];
            $subtotal += $lineSubtotal;

            $selectedItems[] = [
                'produk_detail' => $produkVarian,
                'quantity' => $item['quantity'],
                'subtotal' => $lineSubtotal
            ];
        }

        return ['items' => $selectedItems, 'subtotal' => $subtotal];
    }

    private function determineOrderStatus(bool $isCOD, Request $request)
    {
        if ($isCOD) {
            return StatusPesananEnum::MENUNGGU_VERIFIKASI;
        }

        if ($request->hasFile('bukti_bayar')) {
            return StatusPesananEnum::MENUNGGU_VERIFIKASI;
        }

        return StatusPesananEnum::MENUNGGU_PEMBAYARAN;
    }

    private function savePaymentProof(Request $request, Pesanan $pesanan)
    {
        $path = $request->file('bukti_bayar')->store('bukti_bayar', 'public');

        Pembayaran::updateOrCreate(
            ['id_pesanan' => $pesanan->id_pesanan],
            [
                'bukti_bayar' => $path,
                'jumlah_bayar' => $request->jumlah_bayar ?? $pesanan->grand_total,
                'tanggal_bayar' => now(),
                'status_pembayaran' => StatusPembayaranEnum::MENUNGGU_VERIFIKASI
            ]
        );
    }

    private function redirectAfterOrder(bool $isCOD, bool $hasValidProof, Pesanan $pesanan)
    {
        if ($isCOD) {
            return redirect()->route('customer.pesanan.index')
                ->with('success', 'Pesanan COD berhasil dibuat!');
        }

        if ($hasValidProof) {
            return redirect()->route('customer.pesanan.index')
                ->with('success', 'Pesanan dibuat & bukti pembayaran terkirim!');
        }

        return redirect()->route('customer.pesanan.show', $pesanan->id_pesanan)
            ->with('success', 'Pesanan berhasil. Silahkan lakukan pembayaran.');
    }

    public function selesai($id)
    {
        $pesanan = Pesanan::where('id_user', Auth::id())->findOrFail($id);
        if ($pesanan->status_pesanan == StatusPesananEnum::DIKIRIM) {
            
            $pesanan->update([
                'status_pesanan' => StatusPesananEnum::SELESAI,
            ]);
            return back()->with('success', 'Terima kasih! Pesanan telah diselesaikan.');
        }
        return back()->with('error', 'Pesanan tidak dapat diselesaikan saat ini.');
    }
}

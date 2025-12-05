<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapExport;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    private function getData($startDate, $endDate, $jenisLaporan)
    {
        $data = [];
        $totalOmset = 0;

        switch ($jenisLaporan) {
            case 'penjualan':
                $query = Pesanan::with('user', 'pembayaran')
                    ->whereBetween('waktu_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->where('status_pesanan', 'selesai')
                    ->latest('waktu_pesanan');
                
                $data = $query->get();
                $totalOmset = $query->sum('grand_total');
                break;

            case 'produk':
                $data = DB::table('pesanan_detail')
                    ->join('pesanan', 'pesanan.id_pesanan', '=', 'pesanan_detail.id_pesanan')
                    ->join('produk_detail', 'produk_detail.id_produk_detail', '=', 'pesanan_detail.id_produk_detail')
                    ->join('produk', 'produk.id_produk', '=', 'produk_detail.id_produk')
                    ->select(
                        'produk.nama as nama_produk',
                        'produk_detail.nama_varian',
                        'produk_detail.harga_modal',
                        'produk_detail.harga_jual',
                        DB::raw('SUM(pesanan_detail.kuantitas) as total_terjual'),
                        DB::raw('SUM(pesanan_detail.subtotal_item) as total_pendapatan')
                    )
                    ->whereBetween('pesanan.waktu_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->where('pesanan.status_pesanan', 'selesai')
                    ->groupBy('produk.id_produk', 'produk_detail.id_produk_detail', 'produk.nama', 'produk_detail.nama_varian', 'produk_detail.harga_modal', 'produk_detail.harga_jual')
                    ->orderByDesc('total_terjual')
                    ->get();
                break;

            case 'pengiriman':
                $data = Pesanan::with('ongkir')
                    ->whereBetween('waktu_pesanan', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->where('status_pesanan', 'selesai')
                    ->latest('waktu_pesanan')
                    ->get();
                break;
        }

        return ['data' => $data, 'totalOmset' => $totalOmset];
    }

    public function index(Request $request): View
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $jenisLaporan = $request->input('jenis', 'penjualan');

        $result = $this->getData($startDate, $endDate, $jenisLaporan);

        return view('admin.rekap.index', array_merge($result, compact('startDate', 'endDate', 'jenisLaporan')));
    }

    public function exportPDF(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenisLaporan = $request->input('jenis');

        $result = $this->getData($startDate, $endDate, $jenisLaporan);
        $pdf = Pdf::loadView('admin.rekap.pdf', array_merge($result, compact('startDate', 'endDate', 'jenisLaporan')));
        
        $pdf->setPaper('a4', 'landscape');

        return $pdf->download("Rekap-{$jenisLaporan}-{$startDate}-sd-{$endDate}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $jenisLaporan = $request->input('jenis');

        $result = $this->getData($startDate, $endDate, $jenisLaporan);

        return Excel::download(
            new RekapExport($result['data'], $result['totalOmset'], $jenisLaporan, $startDate, $endDate), 
            "Rekap-{$jenisLaporan}.xlsx"
        );
    }
}
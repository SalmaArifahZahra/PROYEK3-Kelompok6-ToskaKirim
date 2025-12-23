<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class RekapExport
{
    protected $data;
    protected $totalOmset;
    protected $jenisLaporan;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $totalOmset, $jenisLaporan, $startDate, $endDate)
    {
        $this->data = $data;
        $this->totalOmset = $totalOmset;
        $this->jenisLaporan = $jenisLaporan;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function export($filePath)
    {
        $rows = $this->prepareRows();
        return (new FastExcel($rows))->export($filePath);
    }

    protected function prepareRows(): Collection
    {
        $rows = collect();
        
        // Header
        $rows->push([
            'LAPORAN' => 'REKAP ' . strtoupper($this->jenisLaporan),
            'PERIODE' => $this->startDate . ' s/d ' . $this->endDate,
        ]);
        $rows->push([]); // Empty row

        // Table headers based on jenis laporan
        switch ($this->jenisLaporan) {
            case 'penjualan':
                $rows->push([
                    'No' => 'No',
                    'ID Pesanan' => 'ID Pesanan',
                    'Tanggal' => 'Tanggal',
                    'Customer' => 'Customer',
                    'Total' => 'Total',
                    'Status Bayar' => 'Status Bayar',
                ]);
                
                foreach ($this->data as $index => $item) {
                    $rows->push([
                        'No' => $index + 1,
                        'ID Pesanan' => $item->id_pesanan,
                        'Tanggal' => $item->waktu_pesanan->format('d/m/Y'),
                        'Customer' => $item->user->name ?? 'Guest',
                        'Total' => 'Rp ' . number_format($item->grand_total, 0, ',', '.'),
                        'Status Bayar' => $item->pembayaran->status_pembayaran->value ?? '-',
                    ]);
                }
                
                $rows->push([]); // Empty row
                $rows->push([
                    'No' => '',
                    'ID Pesanan' => '',
                    'Tanggal' => '',
                    'Customer' => 'TOTAL OMSET:',
                    'Total' => 'Rp ' . number_format($this->totalOmset, 0, ',', '.'),
                    'Status Bayar' => '',
                ]);
                break;

            case 'produk':
                $rows->push([
                    'No' => 'No',
                    'Produk' => 'Produk',
                    'Varian' => 'Varian',
                    'Harga Modal' => 'Harga Modal',
                    'Harga Jual' => 'Harga Jual',
                    'Terjual' => 'Terjual',
                    'Pendapatan' => 'Pendapatan',
                ]);
                
                foreach ($this->data as $index => $item) {
                    $rows->push([
                        'No' => $index + 1,
                        'Produk' => $item->nama_produk,
                        'Varian' => $item->nama_varian,
                        'Harga Modal' => 'Rp ' . number_format($item->harga_modal, 0, ',', '.'),
                        'Harga Jual' => 'Rp ' . number_format($item->harga_jual, 0, ',', '.'),
                        'Terjual' => $item->total_terjual,
                        'Pendapatan' => 'Rp ' . number_format($item->total_pendapatan, 0, ',', '.'),
                    ]);
                }
                break;

            case 'pengiriman':
                $rows->push([
                    'No' => 'No',
                    'ID Pesanan' => 'ID Pesanan',
                    'Tanggal' => 'Tanggal',
                    'Customer' => 'Customer',
                    'Alamat' => 'Alamat',
                    'Jarak' => 'Jarak (km)',
                    'Ongkir' => 'Ongkir',
                ]);
                
                foreach ($this->data as $index => $item) {
                    $rows->push([
                        'No' => $index + 1,
                        'ID Pesanan' => $item->id_pesanan,
                        'Tanggal' => $item->waktu_pesanan->format('d/m/Y'),
                        'Customer' => $item->user->name ?? 'Guest',
                        'Alamat' => $item->alamat_lengkap,
                        'Jarak' => number_format($item->ongkir->jarak ?? 0, 2),
                        'Ongkir' => 'Rp ' . number_format($item->ongkir->total_ongkir ?? 0, 0, ',', '.'),
                    ]);
                }
                break;
        }

        return $rows;
    }
}

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Rekapitulasi</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background-color: #f2f2f2; text-align: left; }
        .header { text-align: center; margin-bottom: 20px; }
        .meta { margin-bottom: 15px; font-size: 14px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .grand-total { font-weight: bold; background-color: #e6e6e6; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0;">TOSKA MART</h2>
        <p style="margin:5px 0;">Laporan Rekapitulasi {{ ucfirst($jenisLaporan) }}</p>
    </div>

    <div class="meta">
        <strong>Periode:</strong> {{ $startDate }} s/d {{ $endDate }}
    </div>

    {{-- LOGIKA TABEL SAMA PERSIS DENGAN INDEX, TAPI VERSI POLOS --}}
    
    @if($jenisLaporan == 'penjualan')
        <table>
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Grand Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>#{{ $row->id_pesanan }}</td>
                        <td>{{ $row->waktu_pesanan->format('d/m/Y H:i') }}</td>
                        <td>{{ $row->user->nama ?? '-' }}</td>
                        <td class="text-right">Rp {{ number_format($row->grand_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right grand-total">TOTAL OMSET</td>
                    <td class="text-right grand-total">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

    @elseif($jenisLaporan == 'produk')
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Varian</th>
                    <th>Harga Jual</th>
                    <th>Qty Terjual</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->nama_produk }}</td>
                        <td>{{ $row->nama_varian }}</td>
                        <td class="text-right">{{ number_format($row->harga_jual) }}</td>
                        <td class="text-center">{{ $row->total_terjual }}</td>
                        <td class="text-right">{{ number_format($row->total_pendapatan) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @elseif($jenisLaporan == 'pengiriman')
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Penerima</th>
                    <th>Alamat</th>
                    <th>Jarak</th>
                    <th>Ongkir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{ $row->waktu_pesanan->format('d/m/Y') }}</td>
                        <td>{{ $row->penerima_nama }}</td>
                        <td>{{ Str::limit($row->alamat_lengkap, 50) }}</td>
                        <td class="text-center">{{ $row->ongkir->jarak ?? 0 }} KM</td>
                        <td class="text-right">{{ number_format($row->ongkir->total_ongkir ?? 0) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
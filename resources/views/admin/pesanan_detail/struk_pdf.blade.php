<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Struk #{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        /* Thermal receipt size (80mm). DomPDF reads mm values. */
        @page { size: 80mm auto; margin: 4mm 3mm; }

        body {
            width: 80mm;
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.35;
            color: #000;
            word-wrap: break-word;
        }

        .center { text-align: center; }
        .right { text-align: right; }
        .mt-4 { margin-top: 4px; }
        .mt-6 { margin-top: 6px; }
        .mt-8 { margin-top: 8px; }
        .mb-6 { margin-bottom: 6px; }
        .bold { font-weight: bold; }
        .small { font-size: 11px; }
        .large { font-size: 14px; }

        .divider { border-top: 1px dashed #777; margin: 6px 0; }
        .double { border-top: 2px dashed #000; margin: 8px 0; }

        .header h1 { font-size: 16px; letter-spacing: 1px; }
        .header p { margin-top: 2px; }

        .row { display: flex; justify-content: space-between; gap: 6px; padding-left: 4px; padding-right: 4px; }
        .row .label { color: #333; min-width: 34mm; }
        .row .value { text-align: right; flex: 1; }

        table { width: 100%; border-collapse: collapse; padding-left: 4px; padding-right: 4px; }
        th, td { padding: 4px 0; font-size: 12px; }
        thead th { border-bottom: 1px dashed #777; text-align: left; }
        tbody td { border-bottom: 1px dashed #eee; vertical-align: top; }
        tbody tr:last-child td { border-bottom: none; }

        .prod-name { font-weight: bold; }
        .muted { color: #444; }

        .summary td { padding: 3px 0; }
        .summary .total { font-size: 13px; font-weight: 700; padding-left: 4px; padding-right: 4px; }

        .badge { display: inline-block; padding: 2px 6px; border: 1px dashed #333; border-radius: 2px; font-size: 10px; }

        .footer { text-align: center; margin-top: 10px; }
    </style>
    </head>
    <body>
        <div class="header center">
            <h1 class="bold">TOSKA KIRIM</h1>
            <p class="small">Struk Pembayaran & Pengiriman</p>
        </div>

        <div class="double"></div>

        <div class="row">
            <span class="label">No. Pesanan</span>
            <span class="value bold">TK-{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row">
            <span class="label">Tanggal</span>
            <span class="value">{{ $pesanan->waktu_pesanan->format('d M Y, H:i') }} WIB</span>
        </div>
        <div class="row">
            <span class="label">Status</span>
            <span class="value">
                <span class="badge">{{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan->value)) }}</span>
            </span>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span class="label">Penerima</span>
            <span class="value bold">{{ $pesanan->penerima_nama }}</span>
        </div>
        <div class="row">
            <span class="label">Telepon</span>
            <span class="value">{{ $pesanan->penerima_telepon }}</span>
        </div>
        <div class="row">
            <span class="label">Alamat</span>
            <span class="value">{{ $pesanan->alamat_lengkap }}</span>
        </div>

        @if($pesanan->layananPengiriman)
            <div class="row mt-4">
                <span class="label">Ekspedisi</span>
                <span class="value">{{ $pesanan->layananPengiriman->nama_layanan }}</span>
            </div>
        @endif

        <div class="double"></div>

        @php $totalItems = 0; @endphp
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->detail as $item)
                    @php $totalItems += $item->kuantitas; @endphp
                    <tr>
                        <td>
                            <div class="prod-name">{{ $item->produkDetail->produk->nama ?? 'Produk Tidak Tersedia' }}</div>
                            <div class="small muted">Varian: {{ $item->produkDetail->nama_varian ?? '-' }}</div>
                            <div class="small">{{ $item->kuantitas }} x Rp {{ number_format($item->harga_beli ?? ($item->subtotal_item / max($item->kuantitas,1)), 0, ',', '.') }}</div>
                        </td>
                        <td class="right">Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="double"></div>

        <table class="summary">
            <tr>
                <td>Subtotal ({{ $totalItems }} item)</td>
                <td class="right">Rp {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ongkir ({{ $pesanan->ongkir->nama_ongkir ?? 'Standar' }})</td>
                <td class="right">Rp {{ number_format($pesanan->ongkir->total_ongkir ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2"><div class="divider"></div></td>
            </tr>
            <tr>
                <td class="total">TOTAL</td>
                <td class="right total">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <div class="row">
            <span class="label">Metode Bayar</span>
            <span class="value">{{ $pesanan->pembayaran->metode_pembayaran ?? ($pesanan->metode_pembayaran ?? '-') }}</span>
        </div>
        @if($pesanan->pembayaran)
            <div class="row">
                <span class="label">Status Bayar</span>
                <span class="value">{{ strtoupper($pesanan->pembayaran->status_pembayaran->value) }}</span>
            </div>
            @if($pesanan->pembayaran->waktu_pembayaran)
            <div class="row">
                <span class="label">Waktu Bayar</span>
                <span class="value">{{ $pesanan->pembayaran->waktu_pembayaran->format('d M Y, H:i') }} WIB</span>
            </div>
            @endif
        @endif

        @if($pesanan->catatan_pesanan)
            <div class="divider"></div>
            <div class="small"><span class="bold">Catatan:</span> {{ $pesanan->catatan_pesanan }}</div>
        @endif

        <div class="double"></div>
        <div class="footer small">
            <div>Terima kasih telah berbelanja.</div>
            <div>Dokumen valid tanpa tanda tangan.</div>
        </div>
    </body>
    </html>

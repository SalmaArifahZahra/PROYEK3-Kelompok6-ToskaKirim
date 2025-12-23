<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice Pengiriman - {{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px double #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 28px;
            color: #5BC6BC;
            margin-bottom: 5px;
            letter-spacing: 2px;
        }
        
        .header h2 {
            font-size: 16px;
            color: #666;
            font-weight: normal;
            margin-bottom: 10px;
        }
        
        .divider {
            border-top: 3px double #333;
            margin: 15px 0;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        
        .info-label {
            display: table-cell;
            width: 35%;
            color: #666;
            font-size: 11px;
        }
        
        .info-value {
            display: table-cell;
            font-weight: bold;
            color: #333;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .status-menunggu_pembayaran { background-color: #FEF3C7; color: #92400E; }
        .status-menunggu_verifikasi { background-color: #FED7AA; color: #9A3412; }
        .status-diproses { background-color: #DBEAFE; color: #1E40AF; }
        .status-dikirim { background-color: #E9D5FF; color: #6B21A8; }
        .status-selesai { background-color: #D1FAE5; color: #065F46; }
        .status-dibatalkan { background-color: #FEE2E2; color: #991B1B; }
        
        .payment-status-pending { background-color: #FEF3C7; color: #92400E; }
        .payment-status-diterima { background-color: #D1FAE5; color: #065F46; }
        .payment-status-ditolak { background-color: #FEE2E2; color: #991B1B; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #f3f4f6;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #d1d5db;
        }
        
        table td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }
        
        table tbody tr:last-child td {
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-table {
            margin-top: 15px;
            width: 100%;
        }
        
        .summary-table tr td {
            padding: 6px 0;
            border: none;
        }
        
        .summary-table tr td:first-child {
            text-align: left;
            color: #666;
        }
        
        .summary-table tr td:last-child {
            text-align: right;
            font-weight: bold;
        }
        
        .summary-divider {
            border-top: 1px solid #ddd;
            margin: 8px 0;
        }
        
        .grand-total {
            font-size: 16px !important;
            color: #5BC6BC !important;
            padding-top: 10px !important;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            border-top: 3px double #333;
            padding-top: 15px;
            font-size: 11px;
            color: #666;
        }
        
        .notes {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 10px;
            margin-top: 15px;
            font-size: 11px;
        }
        
        .notes strong {
            color: #92400E;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>TOSKA KIRIM</h1>
        <h2>Invoice Pengiriman</h2>
    </div>

    {{-- Order Information --}}
    <div class="section">
        <div class="info-row">
            <div class="info-label">No. Pesanan</div>
            <div class="info-value">TK-{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">{{ $pesanan->waktu_pesanan->format('d F Y, H:i') }} WIB</div>
        </div>
        <div class="info-row">
            <div class="info-label">Status Pesanan</div>
            <div class="info-value">
                <span class="status-badge status-{{ $pesanan->status_pesanan->value }}">
                    {{ str_replace('_', ' ', strtoupper($pesanan->status_pesanan->value)) }}
                </span>
            </div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Customer Information --}}
    <div class="section">
        <div class="section-title">PELANGGAN</div>
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">{{ $pesanan->penerima_nama }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Telepon</div>
            <div class="info-value">{{ $pesanan->penerima_telepon }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Email</div>
            <div class="info-value">{{ $pesanan->user->email }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Alamat Pengiriman</div>
            <div class="info-value">{{ $pesanan->alamat_lengkap }}</div>
        </div>
    </div>

    <div class="divider"></div>

    {{-- Order Items --}}
    <div class="section">
        <div class="section-title">DETAIL PESANAN</div>
        
        <table>
            <thead>
                <tr>
                    <th style="width: 40%;">Produk</th>
                    <th style="width: 25%;">Varian</th>
                    <th style="width: 10%;" class="text-center">Qty</th>
                    <th style="width: 25%;" class="text-right">Harga</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalItems = 0;
                @endphp
                @foreach($pesanan->detail as $item)
                    @php
                        $totalItems += $item->kuantitas;
                    @endphp
                    <tr>
                        <td>{{ $item->produkDetail && $item->produkDetail->produk ? $item->produkDetail->produk->nama : 'Produk Tidak Tersedia' }}</td>
                        <td>{{ $item->produkDetail ? $item->produkDetail->nama_varian : 'Varian Tidak Tersedia' }}</td>
                        <td class="text-center">{{ $item->kuantitas }}x</td>
                        <td class="text-right">Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="divider"></div>

    {{-- Summary --}}
    <div class="section">
        <table class="summary-table">
            <tr>
                <td>Subtotal Produk ({{ $totalItems }} item)</td>
                <td>Rp {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Ongkos Kirim ({{ $pesanan->ongkir->nama_ongkir ?? 'Standar' }})</td>
                <td>Rp {{ number_format($pesanan->ongkir->total_ongkir ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2"><div class="summary-divider"></div></td>
            </tr>
            <tr class="grand-total">
                <td>TOTAL PEMBAYARAN</td>
                <td>Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    {{-- Payment Information --}}
    <div class="section">
        <div class="section-title">INFORMASI PEMBAYARAN</div>
        
        @if($pesanan->pembayaran)
            <div class="info-row">
                <div class="info-label">Metode Pembayaran</div>
                <div class="info-value">{{ $pesanan->pembayaran->metode_pembayaran }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Pembayaran</div>
                <div class="info-value">
                    <span class="status-badge payment-status-{{ $pesanan->pembayaran->status_pembayaran->value }}">
                        {{ strtoupper($pesanan->pembayaran->status_pembayaran->value) }} ✓
                    </span>
                </div>
            </div>
            @if($pesanan->pembayaran->waktu_pembayaran)
            <div class="info-row">
                <div class="info-label">Waktu Pembayaran</div>
                <div class="info-value">{{ $pesanan->pembayaran->waktu_pembayaran->format('d F Y, H:i') }} WIB</div>
            </div>
            @endif
        @else
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge payment-status-pending">MENUNGGU PEMBAYARAN</span>
                </div>
            </div>
        @endif
    </div>

    @if($pesanan->catatan_pesanan)
    <div class="notes">
        <strong>💬 Catatan:</strong><br>
        {{ $pesanan->catatan_pesanan }}
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <div class="divider"></div>
        <p style="margin-top: 10px;"><strong>Terima kasih atas kepercayaan Anda</strong></p>
        <p style="color: #999; margin-top: 5px;">Dokumen ini dibuat secara elektronik dan sah tanpa tanda tangan</p>
    </div>

</body>
</html>

@extends('layouts.layout_admin')

@section('title', 'Rekap Data')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Rekapitulasi Data</h1>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.rekap.export.pdf', ['jenis' => $jenisLaporan, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
            target="_blank"
            class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            
            <a href="{{ route('admin.rekap.export.excel', ['jenis' => $jenisLaporan, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
            target="_blank"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <form action="{{ route('admin.rekap.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <input type="hidden" name="jenis" value="{{ $jenisLaporan }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" 
                       class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}" 
                       class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none">
            </div>
            <button type="submit" class="bg-[#5BC6BC] text-white px-6 py-2 rounded-lg hover:bg-[#4aa89e] transition-colors">
                Filter Data
            </button>
        </form>
    </div>

    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.rekap.index', ['jenis' => 'penjualan', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="{{ $jenisLaporan == 'penjualan' ? 'border-[#5BC6BC] text-[#5BC6BC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Laporan Keuangan
            </a>

            <a href="{{ route('admin.rekap.index', ['jenis' => 'produk', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="{{ $jenisLaporan == 'produk' ? 'border-[#5BC6BC] text-[#5BC6BC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Performa Produk
            </a>

            <a href="{{ route('admin.rekap.index', ['jenis' => 'pengiriman', 'start_date' => $startDate, 'end_date' => $endDate]) }}"
               class="{{ $jenisLaporan == 'pengiriman' ? 'border-[#5BC6BC] text-[#5BC6BC]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Logistik & Pengiriman
            </a>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        
        {{-- TABEL 1: PENJUALAN --}}
        @if($jenisLaporan == 'penjualan')
            <div class="p-4 bg-blue-50 border-l-4 border-blue-500">
                <p class="text-sm text-blue-700">Total Omset Periode Ini: <span class="font-bold text-lg">Rp {{ number_format($totalOmset, 0, ',', '.') }}</span></p>
            </div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Pesanan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ongkir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grand Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $row)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $row->id_pesanan }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->waktu_pesanan->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->user->nama ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($row->subtotal_produk) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($row->ongkir->total_ongkir ?? 0) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($row->grand_total) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data penjualan pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>

        {{-- TABEL 2: PRODUK --}}
        @elseif($jenisLaporan == 'produk')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Varian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Modal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga Jual</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qty Terjual</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $row)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->nama_produk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->nama_varian }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($row->harga_modal) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($row->harga_jual) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-center">{{ $row->total_terjual }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($row->total_pendapatan) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data produk terjual.</td></tr>
                    @endforelse
                </tbody>
            </table>

        {{-- TABEL 3: PENGIRIMAN --}}
        @elseif($jenisLaporan == 'pengiriman')
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerima</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alamat (Kecamatan)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jarak (KM)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarif Ongkir</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($data as $row)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->waktu_pesanan->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $row->penerima_nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{-- Kita parse string alamat untuk ambil kecamatan (logika sederhana) --}}
                                {{ Str::limit($row->alamat_lengkap, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $row->ongkir->jarak ?? 0 }} KM</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">Rp {{ number_format($row->ongkir->total_ongkir ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengiriman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        @endif

    </div>
</div>

@endsection
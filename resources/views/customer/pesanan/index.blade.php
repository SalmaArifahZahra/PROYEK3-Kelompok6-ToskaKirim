@extends('layouts.layout_customer')

@section('title', 'Daftar Pesanan')

@section('content')

    <div class="max-w-5xl mx-auto my-8 px-4">

        @php
            $statusList = [
                '' => 'Semua',
                'menunggu_pembayaran' => 'Menunggu Pembayaran',
                'menunggu_verifikasi' => 'Menunggu Verifikasi',
                'diproses' => 'Sedang Diproses',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ];
        @endphp

        <div class="bg-white shadow-sm rounded-xl p-3 mb-6">
            <div class="flex gap-4 overflow-x-auto pb-1">

                @foreach ($statusList as $key => $label)
                    <a href="{{ route('customer.pesanan.index', ['status' => $key]) }}"
                        class="px-3 py-2 text-sm font-medium whitespace-nowrap rounded-lg transition
                {{ $status == $key ? 'text-white bg-teal-600 shadow-sm' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                        {{ $label }}
                    </a>
                @endforeach

            </div>
        </div>



        @forelse ($pesananList as $pesanan)

            @php
                $statusText = match ($pesanan->status_pesanan) {
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'diproses' => 'Sedang Diproses',
                    'dikirim' => 'Dikirim',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    default => 'Status Tidak Diketahui',
                };

                $statusColor = match ($pesanan->status_pesanan) {
                    'menunggu_pembayaran' => 'text-orange-500',
                    'menunggu_verifikasi' => 'text-yellow-500',
                    'diproses' => 'text-blue-600',
                    'dikirim' => 'text-red-500',
                    'selesai' => 'text-green-600',
                    'dibatalkan' => 'text-rose-600',
                    default => 'text-slate-600',
                };
            @endphp

            <div class="bg-white px-4 py-3 rounded-xl shadow-sm mb-3 flex justify-between items-center">

                <div class="text-slate-800 font-semibold">
                    {{ $pesanan->toko->nama_toko ?? 'Toska Kirim' }}
                </div>

                <div class="text-sm font-semibold {{ $statusColor }}">
                    {{ $statusText }}
                </div>
            </div>

            @foreach ($pesanan->detail as $detail)
                @php
                    $varian = $detail->produkDetail;
                    $produk = $varian?->produk;
                @endphp

                <div class="bg-white p-4 rounded-xl shadow-sm mb-3">

                    <div class="flex gap-4">

                        <img src="{{ $produk?->foto_url ?? asset('images/icon_toska.png') }}"
                            class="w-20 h-20 rounded-lg object-cover">

                        <div class="flex-1">
                            <div class="text-sm font-semibold text-slate-800">
                                {{ $produk->nama ?? 'Produk Tidak Tersedia' }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">
                                Variasi: {{ $varian?->nama_varian ?? '-' }}
                            </div>

                            <div class="text-xs text-slate-500 mt-1">
                                x{{ $detail->kuantitas }}
                            </div>
                        </div>

                        <div class="text-sm font-semibold text-slate-700">
                            Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}
                        </div>
                    </div>

                </div>
            @endforeach

            <div class="bg-white p-4 rounded-xl shadow-sm mb-5">

                <div class="flex justify-between items-center">
                    <div class="text-sm text-slate-600">
                        Total yang Perlu Dibayar:
                    </div>

                    <div class="text-lg font-extrabold text-teal-600">
                        Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}
                    </div>
                </div>

                {{-- @if ($pesanan->status_pesanan === 'dikirim')
                    <div class="flex justify-end mt-3">
                        <a href="{{ route('customer.pesanan.selesai', $pesanan->id_pesanan) }}"
                            class="px-4 py-2 border border-teal-600 text-teal-600 text-sm rounded-lg hover:bg-teal-50 transition">
                            Pesanan Selesai
                        </a>
                    </div>
                @endif --}}

            </div>

        @empty

            <div class="text-center py-10 text-slate-600">
                <img src="{{ asset('images/empty.png') }}" class="w-40 mx-auto mb-4 opacity-70">
                <p class="text-slate-500">Belum ada pesanan.</p>
            </div>
        @endforelse

    </div>

@endsection

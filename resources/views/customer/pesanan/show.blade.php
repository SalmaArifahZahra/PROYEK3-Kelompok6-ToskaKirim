@extends('layouts.layout_customer')

@section('title', 'Detail Pesanan')

@section('content')

    @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 text-center">
            <p class="text-orange-600 font-medium mb-1">Selesaikan Pembayaran Dalam:</p>

            <div id="countdown-timer" class="text-2xl font-bold text-orange-700 tracking-wider"
                data-deadline="{{ $deadlineTimestamp }}">
                Loading...
            </div>

            <p class="text-xs text-gray-500 mt-2">
                Batas Akhir: {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }} WIB
            </p>
        </div>
    @endif

    <div class="max-w-5xl mx-auto my-8 px-4">
        <nav class="text-sm text-slate-500 mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('customer.keranjang.index') }}" class="hover:underline">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('customer.keranjang.index') }}" class="hover:underline">Keranjang</a></li>
                <li>/</li>
                <li class="text-slate-700 font-medium">Pesanan</li>
            </ol>
        </nav>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-slate-700 font-medium mb-3">Produk Dipesan</h3>

                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                        <div
                            class="grid grid-cols-12 bg-slate-50 border-b border-slate-200 py-3 px-4 text-sm font-semibold text-slate-600">
                            <div class="col-span-6">Produk</div>
                            <div class="col-span-2 text-right">Harga Satuan</div>
                            <div class="col-span-2 text-center">Jumlah</div>
                            <div class="col-span-2 text-right">Subtotal Produk</div>
                        </div>

                        @foreach ($pesanan->detail as $detail)
                            @php
                                $prodDetail = $detail->produkDetail;
                                $produk = $prodDetail ? $prodDetail->produk : null;
                            @endphp

                            <div class="grid grid-cols-12 items-center bg-teal-50 border-b border-slate-200 py-4 px-4">
                                <div class="col-span-6 flex items-center gap-3">
                                    <img src="{{ $produk && $produk->foto_url ? $produk->foto_url : ($prodDetail && $prodDetail->foto ? asset('produk/' . $prodDetail->foto) : asset('images/icon_toska.png')) }}"
                                        class="w-16 h-16 rounded object-cover" alt="">

                                    <div>
                                        <div class="font-medium text-slate-800 text-sm">
                                            {{ $produk ? $produk->nama : 'Produk Tidak Tersedia' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            variasi: {{ $prodDetail ? ($prodDetail->nama_varian ?? '-') : 'Varian Tidak Tersedia' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-2 text-right text-sm font-medium text-slate-700">
                                    Rp {{ number_format($detail->harga_saat_beli ?? ($prodDetail ? $prodDetail->harga_jual : 0), 0, ',', '.') }}
                                </div>

                                <div class="col-span-2 text-center text-sm font-medium text-slate-700">
                                    {{ $detail->kuantitas }}
                                </div>

                                <div class="col-span-2 text-right text-sm font-semibold text-slate-800">
                                    Rp {{ number_format($detail->subtotal_item, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-12 gap-6">
                    <div class="col-span-8">
                        <div class="border border-slate-100 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-medium">Metode Pembayaran</div>
                                <div class="text-sm text-teal-600">COD</div>
                            </div>

                            <div class="mt-3">
                                <p class="text-sm text-slate-500">Pilih metode pembayaran untuk melanjutkan. (Contoh: COD /
                                    Transfer)</p>
                            </div>

                            <div class="mt-4 text-right">
                                <a href="#"
                                    class="text-sm border border-rose-400 text-rose-500 px-3 py-1 rounded">Ubah</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-4">
                        <div class="bg-white border border-slate-100 rounded-lg p-4">
                            <div class="text-sm flex justify-between"><span>Subtotal Pesanan</span><span>Rp.
                                    {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</span></div>
                            <div class="text-sm flex justify-between mt-2"><span>Subtotal Pengiriman</span><span>Rp.
                                    {{ number_format($pesanan->ongkir->total_ongkir ?? 0, 0, ',', '.') }}</span></div>
                            <hr class="my-3">
                            <div class="text-base font-semibold flex justify-between text-teal-500"> <span>Total
                                    Pembayaran</span><span>Rp.
                                    {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span></div>

                            <div class="mt-4">
                                @if (
                                    $pesanan->status_pesanan->value ??
                                        null == 'MENUNGGU_PEMBAYARAN' || $pesanan->status_pesanan == 'MENUNGGU_PEMBAYARAN')
                                    <form action="{{ route('customer.pesanan.index') }}" method="GET">
                                        <button type="button"
                                            onclick="location.href='{{ route('customer.pesanan.index') }}'"
                                            class="w-full bg-rose-500 hover:bg-rose-600 text-white py-3 rounded">Buat
                                            Pesanan</button>
                                    </form>
                                @else
                                    <button disabled class="w-full bg-slate-200 text-slate-500 py-3 rounded">Status:
                                        {{ $pesanan->status_pesanan }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const timerEl = document.getElementById('countdown-timer');
                if (timerEl) {
                    const deadlineTime = parseInt(timerEl.getAttribute('data-deadline'));
                    const updateTimer = setInterval(function() {
                        const now = new Date().getTime();
                        const distance = deadlineTime - now;
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                        const formattedTime =
                            (hours < 10 ? "0" + hours : hours) + " : " +
                            (minutes < 10 ? "0" + minutes : minutes) + " : " +
                            (seconds < 10 ? "0" + seconds : seconds);
                        timerEl.innerHTML = formattedTime;
                        if (distance < 0) {
                            clearInterval(updateTimer);
                            timerEl.innerHTML = "WAKTU HABIS";
                            timerEl.classList.add('text-red-600');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    }, 1000);
                }
            });
        </script>
    @endpush

@endsection

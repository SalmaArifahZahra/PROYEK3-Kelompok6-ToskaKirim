@extends('layouts.layout_customer')

@section('title', 'Detail Pesanan')

@section('content')

     @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
        <div
            class="fixed top-20 left-1/2 transform -translate-x-1/2 bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 text-center z-50 shadow-lg">
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

    <div class="max-w-5xl mx-auto my-8 px-4 relative z-10">

        <nav class="text-sm text-slate-500 mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('customer.keranjang.index') }}" class="hover:underline">Home</a></li>
                <li>/</li>
                <li><a href="{{ route('customer.keranjang.index') }}" class="hover:underline">Keranjang</a></li>
                <li>/</li>
                <li class="text-slate-700 font-medium">Pesanan</li>
            </ol>
        </nav>

        <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
            <h3 class="text-teal-600 font-semibold mb-3">Alamat Pengiriman</h3>

            @if ($alamatUtama)
                <div class="flex justify-between items-start">
                    <div class="text-sm">
                        <p class="font-medium text-slate-800">{{ $alamatUtama->nama_penerima }}</p>
                        <p class="text-slate-600">{{ $alamatUtama->telepon_penerima }}</p>

                        <p class="text-slate-700 mt-1">
                            {{ $alamatUtama->jalan_patokan }},
                            RT {{ $alamatUtama->rt }}/RW {{ $alamatUtama->rw }},
                            {{ $alamatUtama->kelurahan }},
                            {{ $alamatUtama->kecamatan }},
                            {{ $alamatUtama->kota_kabupaten }}
                        </p>

                        <p class="text-xs text-teal-500 font-medium mt-1">Utama</p>
                    </div>

                    <button onclick="openAlamatModal()"
                        class="px-3 py-1 border border-teal-500 text-teal-600 rounded hover:bg-teal-50 text-sm">
                        Ubah
                    </button>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-slate-600 mb-2">Anda belum memiliki alamat pengiriman</p>
                    <a href="{{ route('customer.alamat.create') }}"
                        class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">
                        Tambah Alamat
                    </a>
                </div>
            @endif
        </div>

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
                                            variasi:
                                            {{ $prodDetail ? $prodDetail->nama_varian ?? '-' : 'Varian Tidak Tersedia' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-2 text-right text-sm font-medium text-slate-700">
                                    Rp
                                    {{ number_format($detail->harga_beli ?? ($prodDetail ? $prodDetail->harga_jual : 0), 0, ',', '.') }}
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

                            <div class="flex items-center justify-between mb-3">
                                <div class="text-sm font-medium">Metode Pembayaran</div>
                                <div class="text-xs text-slate-500">Pilih metode pembayaran</div>
                            </div>

                            <div class="space-y-3">

                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="radio" name="metode_pembayaran" class="h-4 w-4" value="COD">
                                    <span class="text-sm font-medium text-slate-700">Cash on Delivery (COD)</span>
                                </label>

                                <div class="pt-2">
                                    <p class="text-xs text-slate-500 mb-2">Transfer Bank / E-Wallet</p>

                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" class="h-4 w-4" value="BCA">
                                        <span class="text-sm font-medium text-slate-700">Transfer Bank BCA</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer mt-2">
                                        <input type="radio" name="metode_pembayaran" class="h-4 w-4" value="BRI">
                                        <span class="text-sm font-medium text-slate-700">Transfer Bank BRI</span>
                                    </label>

                                    <label class="flex items-center gap-3 cursor-pointer mt-2">
                                        <input type="radio" name="metode_pembayaran" class="h-4 w-4" value="QRIS">
                                        <span class="text-sm font-medium text-slate-700">QRIS</span>
                                    </label>
                                </div>

                            </div>

                            <div class="mt-4 text-right">
                                <button class="text-sm bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
                                    Simpan Metode Pembayaran
                                </button>
                            </div>

                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="col-span-4">
                        <div class="bg-white border border-slate-100 rounded-lg p-4">
                            <div class="text-sm flex justify-between">
                                <span>Subtotal Pesanan</span>
                                <span>Rp. {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</span>
                            </div>

                            <div class="text-sm flex justify-between mt-2">
                                <span>Subtotal Pengiriman</span>
                                <span>Rp. 15.000</span>
                            </div>

                            <hr class="my-3">

                            <div class="text-base font-semibold flex justify-between text-teal-500">
                                <span>Total Pembayaran</span>
                                <span>Rp. {{ number_format($pesanan->subtotal_produk + 15000, 0, ',', '.') }}</span>
                            </div>

                            <div class="mt-4">
                                {{-- Button Buat Pesanan --}}
                                <button onclick="location.href='{{ route('customer.pesanan.index') }}'"
                                    class="w-full bg-rose-500 hover:bg-rose-600 text-white py-3 rounded">
                                    Buat Pesanan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="modalEditAlamat" class="fixed inset-0 bg-gray-200 bg-opacity-50 z-50 hidden flex items-center justify-center">

        <div class="bg-white w-full max-w-lg p-6 rounded-xl shadow-xl relative animate-fadeIn">
            <button onclick="closeAlamatModal()"
                class="absolute top-3 right-3 text-gray-600 hover:text-black text-lg font-bold">✕</button>

            <h2 class="text-xl font-semibold mb-4">Ubah Alamat Pengiriman</h2>

            <form action="{{ route('customer.alamat.update', $alamatUtama->id_alamat) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="block text-sm mb-1">Nama Penerima</label>
                    <input type="text" name="nama_penerima" class="w-full border px-3 py-2 rounded"
                        value="{{ $alamatUtama->nama_penerima }}">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Telepon</label>
                    <input type="text" name="telepon_penerima" class="w-full border px-3 py-2 rounded"
                        value="{{ $alamatUtama->telepon_penerima }}">
                </div>

                <div class="mb-3">
                    <label class="block text-sm mb-1">Jalan / Patokan</label>
                    <input type="text" name="jalan_patokan" class="w-full border px-3 py-2 rounded"
                        value="{{ $alamatUtama->jalan_patokan }}">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm mb-1">Kelurahan</label>
                        <input type="text" name="kelurahan" class="w-full border px-3 py-2 rounded"
                            value="{{ $alamatUtama->kelurahan }}">
                    </div>

                    <div>
                        <label class="block text-sm mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" class="w-full border px-3 py-2 rounded"
                            value="{{ $alamatUtama->kecamatan }}">
                    </div>
                </div>

                <div class="mt-3">
                    <label class="block text-sm mb-1">Kota / Kabupaten</label>
                    <input type="text" name="kota_kabupaten" class="w-full border px-3 py-2 rounded"
                        value="{{ $alamatUtama->kota_kabupaten }}">
                </div>

                <button class="mt-5 w-full bg-teal-600 text-white py-2 rounded-lg">
                    Simpan Perubahan
                </button>
            </form>

        </div>
    </div>

    <script>
        const modal = document.getElementById('modalEditAlamat');

        function openAlamatModal() {
            modal.classList.remove('hidden');
        }

        function closeAlamatModal() {
            modal.classList.add('hidden');
        }

        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeAlamatModal();
            }
        });
    </script>

    {{-- ===== Countdown Timer ===== --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const timerEl = document.getElementById('countdown-timer');

                if (!timerEl) return;

                const deadlineTime = parseInt(timerEl.getAttribute('data-deadline'));

                const updateTimer = setInterval(function() {
                    const now = new Date().getTime();
                    const distance = deadlineTime - now;

                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timerEl.innerHTML =
                        (hours < 10 ? "0" + hours : hours) + " : " +
                        (minutes < 10 ? "0" + minutes : minutes) + " : " +
                        (seconds < 10 ? "0" + seconds : seconds);

                    if (distance < 0) {
                        clearInterval(updateTimer);
                        timerEl.innerHTML = "WAKTU HABIS";
                        timerEl.classList.add('text-red-600');

                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }

                }, 1000);

            });
        </script>
    @endpush

@endsection

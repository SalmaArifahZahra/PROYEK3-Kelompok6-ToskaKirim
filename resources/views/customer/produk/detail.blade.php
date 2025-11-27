@extends('layouts.layout_customer')

@section('title', $produk->nama)

<style>
    .var-selected {
        border: 2px solid #3A767E;
        color: #174552;
    }
</style>

@section('content')

    <div class="w-full bg-white p-6 rounded-xl shadow-sm max-w-6xl mx-auto">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <img src="{{ asset($produk->foto_url) }}" class="w-full h-72 object-contain rounded-lg mb-4">

                <div class="flex gap-3">
                    @foreach ($produk->detail as $d)
                        <img src="{{ asset($d->foto) }}"
                            class="thumb-image w-16 h-16 rounded-lg border hover:border-[#3A767E] cursor-pointer object-cover"
                            data-id="{{ $d->id_produk_detail }}" data-src="{{ asset($d->foto) }}">
                    @endforeach
                </div>
            </div>

            <div>
                <h1 class="text-2xl font-semibold mb-2">{{ $produk->nama }}</h1>

                <p class="text-red-600 text-xl font-bold mb-4">
                    Rp {{ number_format($produk->harga, 0, ',', '.') }}
                </p>

                <div class="mb-4">
                    <p class="text-sm mb-1 font-medium">Variation</p>
                    <div class="flex gap-2 flex-wrap">
                        @foreach ($produk->detail as $d)
                            <button type="button"
                                class="btn-var px-3 py-1.5 text-sm bg-gray-100 rounded-md border border-gray-300"
                                data-id="{{ $d->id_produk_detail }}">
                                {{ $d->nama_varian }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-sm mb-1 font-medium">Kuantitas</p>
                    <div class="flex items-center gap-2">
                        <button id="minusBtn" class="w-8 h-8 border rounded flex items-center justify-center">-</button>
                        <input id="qtyInput" type="number" value="1" min="1"
                            class="w-14 border rounded text-center">
                        <button id="plusBtn" class="w-8 h-8 border rounded flex items-center justify-center">+</button>
                    </div>
                </div>

                <div class="flex gap-3 mt-5">
                    <!-- single correct button -->
                    <button id="btnAddCart" class="bg-green-500 text-white py-4 px-4 rounded-lg flex items-center gap-2"
                        type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
                        </svg>
                        Masukkan Keranjang
                    </button>

                    <a href="#" class="bg-orange-600 text-white py-2 px-4 rounded-lg flex items-center">Beli
                        Sekarang</a>
                </div>

            </div>

        </div>
    </div>

    <div class="max-w-6xl mx-auto mt-10">

        <h2 class="text-xl font-medium mb-4">Produk Lainnya</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6">
            @foreach ($produkLainnya as $item)
                @php
                    $foto = optional($item->detail->first())->foto ?? '/images/no-image.png';
                @endphp

                <a href="{{ route('customer.produk.detail', $item->id_produk) }}"
                    class="w-full bg-white p-4 rounded-xl border border-transparent transition-all duration-300 hover:border-[#3A767E] hover:shadow-sm active:border-[#3A767E] active:scale-[0.98]">

                    <img src="{{ asset($foto) }}" class="w-full h-32 object-contain mb-2">

                    <p class="text-sm h-10 overflow-hidden">{{ $item->nama }}</p>

                    <p class="text-[#3A767E] font-bold">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // highlight thumb click -> change main image (optional UX)
            document.querySelectorAll('.thumb-image').forEach(img => {
                img.addEventListener('click', () => {
                    const src = img.dataset.src;
                    const main = document.querySelector(
                        'img[src="{{ asset($produk->foto_url) }}"]');
                    if (main) main.src = src;
                });
            });

            // variant selection
            let selectedVarian = null;
            const buttons = document.querySelectorAll('.btn-var');

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('var-selected'));
                    btn.classList.add('var-selected');
                    selectedVarian = btn.dataset.id;
                    console.log('var selected:', selectedVarian);
                });
            });

            // qty control
            const qtyInput = document.getElementById('qtyInput');
            document.getElementById('minusBtn').addEventListener('click', () => {
                const val = parseInt(qtyInput.value) || 1;
                if (val > 1) qtyInput.value = val - 1;
            });
            document.getElementById('plusBtn').addEventListener('click', () => {
                const val = parseInt(qtyInput.value) || 1;
                qtyInput.value = val + 1;
            });

            // add to cart
            document.getElementById('btnAddCart').addEventListener('click', function() {

                if (!selectedVarian) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Varian Dahulu',
                        text: 'Silakan pilih variation sebelum menambahkan ke keranjang.',
                    });
                    return;
                }

                const qtyVal = parseInt(qtyInput.value) || 1;

                fetch("{{ route('customer.keranjang.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id_produk_detail: selectedVarian,
                            quantity: qtyVal
                        })


                    })
                    .then(async res => {
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw data;
                        return data;
                    })
                    .then(data => {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message ??
                                'Produk telah ditambahkan ke keranjang belanja',
                            timer: 1600,
                            showConfirmButton: false
                        });
                    })
                    .catch(err => {
                        console.error('Add to cart error:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err?.message ?? 'Terjadi kesalahan, coba lagi.'
                        });
                    });
            });

        });
    </script>

@endsection

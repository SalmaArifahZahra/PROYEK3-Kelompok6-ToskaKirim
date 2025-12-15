@extends('layouts.layout_customer')

@section('title', $produk->nama)

@push('styles')
    <style>
        .var-selected {
            border-color: #0d9488 !important;
            background-color: #f0fdfa !important;
            color: #0f766e !important;
            font-weight: 600;
        }

        .thumb-selected {
            border-color: #0d9488 !important;
            opacity: 1 !important;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex text-sm text-gray-500 mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('customer.dashboard') }}" class="hover:text-teal-600 transition-colors">Beranda</a>
                </li>
                <li>/</li>
                <li>
                    <a href="{{ route('customer.produk.search') }}" class="hover:text-teal-600 transition-colors">Produk</a>
                </li>
                <li>/</li>
                <li class="text-gray-800 font-medium truncate max-w-[200px]">{{ $produk->nama }}</li>
            </ol>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8">

                <div class="p-6 md:p-8 bg-white">
                    <div
                        class="relative w-full aspect-square md:aspect-[4/3] rounded-xl overflow-hidden bg-slate-50 border border-slate-100 mb-4 group">
                        <img id="mainImage" src="{{ asset($produk->foto_url) }}"
                            class="w-full h-full object-contain transition-transform duration-500 group-hover:scale-105"
                            alt="{{ $produk->nama }}">
                    </div>
                    @if ($produk->detail->count() > 0)
                        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                            <div class="thumb-image w-20 h-20 flex-shrink-0 rounded-lg border-2 border-transparent cursor-pointer overflow-hidden bg-slate-50 hover:border-slate-300 transition-all thumb-selected"
                                data-src="{{ asset($produk->foto_url) }}">
                                <img src="{{ asset($produk->foto_url) }}" class="w-full h-full object-contain">
                            </div>
                            @foreach ($produk->detail as $d)
                                @if ($d->foto)
                                    <div class="thumb-image w-20 h-20 flex-shrink-0 rounded-lg border-2 border-slate-200 cursor-pointer overflow-hidden bg-slate-50 hover:border-teal-400 transition-all"
                                        data-src="{{ asset($d->foto) }}">
                                        <img src="{{ asset($d->foto) }}" class="w-full h-full object-contain"
                                            alt="{{ $d->nama_varian }}">
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="p-6 md:p-8 md:pl-0 flex flex-col h-full">

                    <h1 class="text-2xl md:text-3xl font-bold text-slate-800 mb-2 leading-tight">{{ $produk->nama }}</h1>
                    @if (isset($produk->kategori))
                        <div class="mb-4">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                {{ $produk->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </div>
                    @endif

                    <div class="flex items-baseline gap-2 mb-6 border-b border-slate-100 pb-6">
                        <span class="text-3xl font-bold text-teal-600">Rp
                            {{ number_format($produk->harga, 0, ',', '.') }}</span>
                    </div>

                    <div class="mb-6 prose prose-sm text-slate-600">
                        <p>{{ $produk->deskripsi ?? 'Deskripsi produk belum tersedia.' }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Pilih Varian:</label>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($produk->detail as $d)
                                <button type="button"
                                    class="btn-var px-4 py-2 text-sm bg-white border border-slate-300 text-slate-600 rounded-lg
                       hover:border-teal-500 hover:text-teal-600 transition-all duration-200
                       focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                                    data-id="{{ $d->id_produk_detail }}" data-stok="{{ $d->stok }}"
                                    data-price="{{ $d->harga_jual ?? $produk->harga }}">
                                    {{ $d->nama_varian }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">Jumlah:</label>
                        <div class="flex items-center">
                            <div class="flex items-center border border-slate-300 rounded-lg bg-white w-fit">
                                <button id="minusBtn"
                                    class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-l-lg transition-colors disabled:opacity-50">
                                    <i class="fas fa-minus text-xs"></i>
                                </button>
                                <input id="qtyInput" type="number" value="1" min="1"
                                    class="w-14 h-10 text-center border-none focus:ring-0 text-slate-800 font-medium p-0"
                                    readonly>
                                <button id="plusBtn"
                                    class="w-10 h-10 flex items-center justify-center text-slate-600 hover:bg-slate-100 rounded-r-lg transition-colors">
                                    <i class="fas fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto flex flex-col sm:flex-row gap-3">
                        <button id="btnAddCart" type="button"
                            class="flex-1 bg-white border-2 border-teal-600 text-teal-700 py-3.5 px-6 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-teal-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-shopping-cart"></i>
                            <span>+ Keranjang</span>
                        </button>

                        <button id="btnBuyNow" type="button"
                            class="flex-1 bg-teal-600 text-white py-3.5 px-6 rounded-xl font-bold flex items-center justify-center gap-2 hover:bg-teal-700 shadow-lg shadow-teal-600/30 transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                            <span>Beli Sekarang</span>
                            <i class="fas fa-arrow-right text-sm"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-slate-800">Produk Lainnya</h2>
                <a href="{{ route('customer.produk.search') }}"
                    class="text-teal-600 text-sm font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach ($produkLainnya as $item)
                    @php
                        // Ambil foto varian pertama atau foto default produk
                        $foto = $item->foto_url;
                        if (!$foto && $item->detail->isNotEmpty()) {
                            $foto = $item->detail->first()->foto
                                ? asset($item->detail->first()->foto)
                                : asset('images/no-image.png');
                        }
                    @endphp

                    <a href="{{ route('customer.produk.detail', $item->id_produk) }}"
                        class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-md hover:border-teal-300 transition-all duration-300">

                        <div class="relative w-full aspect-square bg-slate-50 overflow-hidden">
                            <img src="{{ $foto }}"
                                class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500">
                        </div>

                        <div class="p-4">
                            <h3
                                class="text-slate-800 font-medium text-sm md:text-base line-clamp-2 min-h-[2.5rem] mb-2 group-hover:text-teal-600 transition-colors">
                                {{ $item->nama }}
                            </h3>
                            <div class="flex items-center justify-between">
                                <p class="text-orange-600 font-bold text-sm md:text-base">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </p>
                                <div
                                    class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-teal-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainImage');
            const thumbs = document.querySelectorAll('.thumb-image');

            thumbs.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const newSrc = this.dataset.src;
                    mainImage.style.opacity = '0.5';
                    setTimeout(() => {
                        mainImage.src = newSrc;
                        mainImage.style.opacity = '1';
                    }, 200);

                    thumbs.forEach(t => t.classList.remove('thumb-selected'));
                    this.classList.add('thumb-selected');
                });
            });


            let selectedVarianId = null;
            let maxStok = 0;

            const btnVarians = document.querySelectorAll('.btn-var');
            const btnAddCart = document.getElementById('btnAddCart');
            const btnBuyNow = document.getElementById('btnBuyNow');
            const qtyInput = document.getElementById('qtyInput');

            btnVarians.forEach(btn => {
                btn.addEventListener('click', function() {
                    btnVarians.forEach(b => b.classList.remove('var-selected'));
                    this.classList.add('var-selected');

                    selectedVarianId = this.dataset.id;
                    maxStok = parseInt(this.dataset.stok) || 0;

                    if (maxStok === 0) {
                        qtyInput.value = 0;
                    } else {
                        qtyInput.value = 1;
                    }

                    updateButtonState();
                });
            });
            const minusBtn = document.getElementById('minusBtn');
            const plusBtn = document.getElementById('plusBtn');

            minusBtn.addEventListener('click', () => {
                let val = parseInt(qtyInput.value) || 1;
                if (val > 1) qtyInput.value = val - 1;
            });

            plusBtn.addEventListener('click', () => {
                if (!selectedVarianId) {
                    Swal.fire({
                        icon: 'info',
                        text: 'Pilih varian terlebih dahulu'
                    });
                    return;
                }

                let val = parseInt(qtyInput.value) || 1;
                if (val < maxStok) {
                    qtyInput.value = val + 1;
                } else {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Mencapai batas stok'
                    });
                }
            });

            function updateButtonState() {
                const disabled = !selectedVarianId || maxStok === 0;
                btnAddCart.disabled = disabled;
                btnBuyNow.disabled = disabled;

                if (maxStok === 0 && selectedVarianId) {
                    btnAddCart.textContent = 'Stok Habis';
                    btnAddCart.classList.add('bg-slate-100', 'text-slate-400', 'border-slate-200');
                    btnAddCart.classList.remove('bg-white', 'text-teal-700', 'border-teal-600');
                } else {
                    btnAddCart.innerHTML = '<i class="fas fa-shopping-cart"></i><span>+ Keranjang</span>';
                    btnAddCart.classList.remove('bg-slate-100', 'text-slate-400', 'border-slate-200');
                    btnAddCart.classList.add('bg-white', 'text-teal-700', 'border-teal-600');
                }
            }

            btnAddCart.addEventListener('click', () => addToCart(false));
            btnBuyNow.addEventListener('click', () => addToCart(true));

            function addToCart(redirect) {
                if (!selectedVarianId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Varian',
                        text: 'Silakan pilih varian produk terlebih dahulu.',
                        confirmButtonColor: '#0d9488'
                    });
                    return;
                }

                const qty = parseInt(qtyInput.value);
                if (qty <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Jumlah minimal 1'
                    });
                    return;
                }

                fetch("{{ route('customer.keranjang.add') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            id_produk_detail: selectedVarianId,
                            quantity: qty
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            redirect
                                ?
                                window.location.href = "{{ route('customer.keranjang.index') }}" :
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: err.message
                        });
                    });
            }
        });
    </script>
    z
@endpush

@extends('layouts.layout_customer')

@section('title', 'Kategori - ' . $kategoriUtama->nama_kategori)

{{-- Keseluruhan Produk berdasarkan kategori yang dipilih --}}
@section('content')
    <div class="max-w-6xl mx-auto flex gap-6">
        <aside class="w-1/4 bg-white p-5 rounded-lg shadow">
            <h3 class="font-bold mb-3">{{ $kategoriUtama->nama_kategori }}</h3>

            <div id="kategori-accordion" data-accordion="collapse">

                <h2 id="accordion-heading-all">
                    <button type="button"
                        class="flex items-center justify-between w-full p-3 font-medium text-left text-gray-800 border border-gray-200 rounded-lg"
                        data-accordion-target="#accordion-body-all" aria-expanded="false" aria-controls="accordion-body-all">
                        <span>Semua Produk</span>
                        <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </h2>

                <div id="accordion-body-all" class="hidden" aria-labelledby="accordion-heading-all">
                    <div class="border border-t-0 border-gray-200 p-3 rounded-b-lg">

                        <a href="{{ route('customer.kategori.index', $kategoriUtama->id_kategori) }}"
                            class="block mb-3 text-blue-600">
                            Lihat Semua Produk
                        </a>

                        @foreach ($subKategoris as $sub)
                            <a href="{{ route('customer.kategori.show', $sub->id_kategori) }}"
                                class="block py-2 px-1 hover:text-blue-600">
                                {{ $sub->nama_kategori }}
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>

        </aside>

        <div class="flex-1">
            <h2 class="text-xl font-semibold mb-4">
                {{ $kategoriUtama->nama_kategori }}
            </h2>

            @include('component.customer.card_produk', ['produk' => $produkList])
        </div>

    </div>
@endsection

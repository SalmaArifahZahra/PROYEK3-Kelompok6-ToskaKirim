@extends('layouts.layout_customer')

@section('title', 'Kategori - ' . $activeSubKategori->nama_kategori)

@section('content')
    <div class="max-w-6xl mx-auto flex gap-6">

        <aside class="w-1/4 bg-white p-5 rounded-lg shadow">

            <h3 class="font-semibold text-lg text-gray-900 mb-4">
                {{ $kategoriUtama->nama_kategori }}
            </h3>

            <div x-data="{ open: true }" class="mt-1">

                <button @click="open = !open"
                    class="flex items-center justify-between w-full p-3 font-medium text-left
                   text-gray-800 border border-gray-200 rounded-lg bg-gray-50">
                    <span>Semua Produk</span>
                    <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transform transition" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" x-transition class="border border-t-0 border-gray-200 rounded-b-lg px-3 py-2">
                    @foreach ($subKategoris as $sub)
                        <a href="{{ route('customer.kategori.show', $sub->id_kategori) }}"
                            class="block py-2 text-sm
                    {{ $sub->id_kategori == $activeSubKategori->id_kategori
                        ? 'text-blue-600 font-semibold'
                        : 'text-gray-700 hover:text-blue-600' }}">
                            {{ $sub->nama_kategori }}
                        </a>
                    @endforeach

                </div>

            </div>
        </aside>



        <div class="flex-1">

            <div class="mb-4 text-sm">
                <a href="{{ route('customer.dashboard') }}" class="text-gray-600 hover:text-blue-600">
                    Home
                </a>
                <span class="mx-1 text-gray-400">></span>

                <a href="{{ route('customer.kategori.show', $kategoriUtama->id_kategori) }}"
                    class="text-gray-600 hover:text-blue-600">
                    {{ $kategoriUtama->nama_kategori }}
                </a>
                <span class="mx-1 text-gray-400">></span>

                <span class="text-slate-800 font-semibold"">
                    {{ $activeSubKategori->nama_kategori }}
                </span>
            </div>



            @include('component.customer.card_produk', [
                'produk' => $produkList,
                'hideTitle' => true,
            ])
        </div>

    </div>
@endsection

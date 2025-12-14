{{-- hasil pencarian ketika search produk --}}
@extends('layouts.layout_customer')

@section('content')
<div class="w-full max-w-6xl mx-auto py-8 px-4">

    <h2 class="text-xl font-medium mb-4">
        @if($keyword)
            Hasil Pencarian: <span class="text-[#5BC6BC]">"{{ $keyword }}"</span>
        @else
            Semua Produk
        @endif
    </h2>

    @if($produk->count() > 0)

        {{-- Panggil komponen card produk --}}
        @include('component.customer.card_produk', [
            'produk' => $produk,
            'hideTitle' => true   {{-- Supaya judul "Produk Pilihan" tidak tampil --}}
        ])

        <div class="flex justify-center mt-10">
            {{ $produk->links() }}
        </div>

    @else
        <div class="bg-gray-50 rounded-lg p-12 text-center">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-600 text-lg">
                @if($keyword)
                    Tidak ada produk yang cocok dengan "<strong>{{ $keyword }}</strong>"
                @else
                    Tidak ada produk ditemukan
                @endif
            </p>
            <a href="{{ route('customer.dashboard') }}"
                class="mt-4 inline-block px-6 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aab9e] transition">
                Kembali ke Beranda
            </a>
        </div>
    @endif

</div>
@endsection

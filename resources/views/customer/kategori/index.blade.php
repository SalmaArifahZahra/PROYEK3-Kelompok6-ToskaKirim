@extends('layouts.layout_customer')

@section('title', 'Kategori - ' . $kategoriUtama->nama_kategori)

// Keseluruhan Produk berdasarkan kategori yang dipilih
@section('content')
<div class="max-w-6xl mx-auto flex gap-6">

    <aside class="w-1/4 bg-white p-5 rounded-lg shadow">
        <h3 class="font-bold mb-3">Subkategori</h3>

        <ul>
            <li class="mb-2">
                <a href="{{ route('customer.kategori.index', $kategoriUtama->id_kategori) }}"
                   class="text-blue-600 font-medium">
                    Semua Produk
                </a>
            </li>

            @foreach ($subKategoris as $sub)
                <li class="mb-2">
                    <a href="{{ route('customer.kategori.show', $sub->id_kategori) }}"
                       class="hover:text-blue-600">
                        {{ $sub->nama_kategori }}
                    </a>
                </li>
            @endforeach
        </ul>
    </aside>

    <div class="flex-1">
        <h2 class="text-xl font-semibold mb-4">
            {{ $kategoriUtama->nama_kategori }}
        </h2>

        @include('component.customer.card_produk', ['produk' => $produkList])
    </div>

</div>
@endsection

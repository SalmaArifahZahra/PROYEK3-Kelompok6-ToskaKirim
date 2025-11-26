@extends('layouts.layout_customer')

@section('title', 'Kategori - ' . $activeSubKategori->nama_kategori)

@section('content')
    <div class="max-w-6xl mx-auto flex gap-6">

        <aside class="w-1/4 bg-white p-5 rounded-lg shadow">
            <h3 class="font-bold mb-3">{{ $kategoriUtama->nama_kategori }}</h3>

            <ul>
                @foreach ($subKategoris as $sub)
                    <li class="mb-2">
                        <a href="{{ route('customer.kategori.show', $sub->id_kategori) }}"
                            class="hover:text-blue-600 @if ($sub->id_kategori == $activeSubKategori->id_kategori) font-bold text-blue-600 @endif">
                            {{ $sub->nama_kategori }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        <div class="flex-1">

            <h2 class="text-xl font-semibold mb-4">
                Home > {{ $kategoriUtama->nama_kategori }} > {{ $activeSubKategori->nama_kategori }}
            </h2>

            @include('component.customer.card_produk', [
                'produk' => $produkList,
                'hideTitle' => true,
            ])
        </div>

    </div>
@endsection

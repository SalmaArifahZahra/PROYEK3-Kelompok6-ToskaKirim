@extends('layouts.layout_customer')

@section('title', 'Keranjang Saya')

@section('content')
    <div class="max-w-6xl mx-auto p-4">
        <h2 class="text-2xl font-bold mb-6">Keranjang Belanja</h2>

        @if ($keranjang->isEmpty())
            <p class="text-center text-gray-500">Keranjang Anda kosong.</p>
        @else
            @foreach ($keranjang as $item)
                <div class="flex items-start bg-white shadow-md rounded-lg p-4 mb-4 border">


                    <div class="w-28 h-28 mr-4">
                        <img src="{{ $item->produkDetail->produk->foto_url }}" alt="Product Image"
                            class="w-full h-full object-contain rounded-lg">
                    </div>

                    {{-- {{ dd($item->produkDetail->produk->foto_url) }} --}}
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold">{{ $item->produkDetail->produk->nama }}</h3>

                        <div class="mt-2">
                            <label class="text-sm text-gray-500">Variation:</label>
                            <select class="border rounded-md p-1 text-sm">
                                <option>{{ $item->produkDetail->nama_varian }}</option>
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="text-sm text-gray-500">Ukuran:</label>
                            <select class="border rounded-md p-1 text-sm">
                                <option>{{ $item->produkDetail->ukuran ?? '1000ml' }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-right w-48">
                        <p class="text-gray-600">Rp {{ number_format($item->produkDetail->harga_jual, 0, ',', '.') }}</p>

                        <div class="flex items-center justify-end mt-2">
                            <button class="px-2 border rounded">-</button>
                            <input type="text" value="{{ $item->quantity }}"
                                class="w-10 text-center border mx-1 rounded" />
                            <button class="px-2 border rounded">+</button>
                        </div>

                        <p class="mt-2 text-red-600 font-bold">
                            Rp {{ number_format($item->produkDetail->harga_jual * $item->quantity, 0, ',', '.') }}
                        </p>

                        <form action="{{ route('customer.keranjang.destroy', $item->id_produk_detail) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="mt-2 text-sm text-red-500 hover:underline">Hapus</button>
                        </form>

                    </div>
                </div>
            @endforeach

            <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow mt-6 border">

                <div>
                    <input type="checkbox" id="selectAll" class="mr-2">
                    <label for="selectAll">Pilih Semua ({{ count($keranjang) }})</label>
                </div>

                <div class="text-right">
                    <p class="font-semibold">Total ({{ count($keranjang) }} produk):
                        <span class="text-red-600">
                            Rp
                            {{ number_format($keranjang->sum(fn($i) => $i->produkDetail->harga_jual * $i->quantity), 0, ',', '.') }}
                        </span>
                    </p>
                    <button class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg">
                        Checkout
                    </button>
                </div>
            </div>

        @endif
    </div>
@endsection

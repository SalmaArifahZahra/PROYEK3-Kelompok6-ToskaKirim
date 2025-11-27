@extends('layouts.layout_customer')

@section('title', 'Keranjang Saya')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Keranjang Belanja</h2>

    @if($keranjang->isEmpty())
        <p class="text-center text-gray-500">Keranjang Anda kosong.</p>
    @else
        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Produk</th>
                    <th class="p-2">Varian</th>
                    <th class="p-2">Qty</th>
                    <th class="p-2">Harga</th>
                </tr>
            </thead>
            <tbody>
                @foreach($keranjang as $item)
                    <tr>
                        <td class="p-2">{{ $item->produkDetail->produk->nama }}</td>
                        <td class="p-2">{{ $item->produkDetail->nama_varian }}</td>
                        <td class="p-2">{{ $item->quantity }}</td>
                        <td class="p-2">
                            Rp {{ number_format($item->produkDetail->harga_jual * $item->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

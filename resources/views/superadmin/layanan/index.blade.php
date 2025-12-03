@extends('layouts.layout_superadmin')

@section('title', 'Kelola Layanan Pengiriman')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('superadmin.layanan.store') }}" method="POST" class="mb-8 p-4 bg-gray-50 rounded border">
        @csrf
        <h3 class="font-bold text-gray-700 mb-2">Tambah Layanan Baru</h3>
        <div class="flex gap-4">
            <input type="text" name="nama_layanan" placeholder="Nama (misal: Reguler)" class="border p-2 rounded w-1/3" required>
            <input type="number" name="tarif_per_km" placeholder="Tarif per KM (Rp)" class="border p-2 rounded w-1/3" required>
            <button type="submit" class="bg-[#2A9D8F] text-white px-4 py-2 rounded hover:bg-[#21867a]">+ Simpan</button>
        </div>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                <th class="py-3 px-6">Nama Layanan</th>
                <th class="py-3 px-6">Tarif / KM</th>
                <th class="py-3 px-6">Status</th>
                <th class="py-3 px-6 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light">
            @foreach($layanan as $item)
            <tr class="border-b border-gray-200 hover:bg-gray-100">
                <td class="py-3 px-6 font-bold">{{ $item->nama_layanan }}</td>
                <td class="py-3 px-6">Rp {{ number_format($item->tarif_per_km, 0, ',', '.') }}</td>
                <td class="py-3 px-6">
                    <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Aktif</span>
                </td>
                <td class="py-3 px-6 text-center">
                    <form action="{{ route('superadmin.layanan.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 font-bold">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
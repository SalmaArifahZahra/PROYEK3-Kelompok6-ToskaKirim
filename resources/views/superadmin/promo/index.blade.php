@extends('layouts.layout_superadmin')

@section('title', 'Kelola Promo Ongkir')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <form action="{{ route('superadmin.promo.store') }}" method="POST" class="mb-8 p-4 bg-gray-50 rounded border">
        @csrf
        <h3 class="font-bold text-gray-700 mb-2">Buat Promo Baru</h3>
        <div class="grid grid-cols-4 gap-4">
            <input type="text" name="nama_promo" placeholder="Nama Promo" class="border p-2 rounded" required>
            <input type="number" name="min_belanja" placeholder="Min. Belanja (Rp)" class="border p-2 rounded" required>
            <input type="number" name="potongan_jarak" placeholder="Potongan Jarak (KM)" class="border p-2 rounded" required>
            <button type="submit" class="bg-[#2A9D8F] text-white px-4 py-2 rounded hover:bg-[#21867a]">+ Simpan</button>
        </div>
    </form>

    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                <th class="py-3 px-6">Nama Promo</th>
                <th class="py-3 px-6">Min. Belanja</th>
                <th class="py-3 px-6">Benefit</th>
                <th class="py-3 px-6 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promos as $promo)
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-6 font-medium">{{ $promo->nama_promo }}</td>
                <td class="py-3 px-6">Rp {{ number_format($promo->min_belanja, 0, ',', '.') }}</td>
                <td class="py-3 px-6 text-blue-600 font-bold">Gratis {{ $promo->potongan_jarak }} KM</td>
                <td class="py-3 px-6 text-center">
                    <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Hapus promo?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 font-bold">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
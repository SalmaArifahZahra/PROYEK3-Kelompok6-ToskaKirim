@extends('layouts.layout_admin')

@section('title', 'Kelola Layanan Pengiriman')

@section('content')
<div class="space-y-6">

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li>
                <span class="text-gray-500">Logistik & Tarif</span>
            </li>
            <li>
                <span class="mx-2 text-gray-400">/</span>
            </li>
            <li class="font-bold text-gray-800">Layanan Pengiriman</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-[#5BC6BC] mb-4 border-b border-gray-100 pb-2">
            Tambah Layanan Baru
        </h2>
        
        <form action="{{ route('superadmin.layanan.store') }}" method="POST">
            @csrf
            <div class="flex flex-col md:flex-row gap-4 items-end">
                
                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Layanan</label>
                    <input type="text" name="nama_layanan" 
                           placeholder="Contoh: Reguler, Ekspres" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-all" 
                           required>
                </div>

                <div class="w-full md:w-1/3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tarif per KM (Rp)</label>
                    <input type="number" name="tarif_per_km" 
                           placeholder="Contoh: 2000" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-all" 
                           required>
                </div>

                <div class="w-full md:w-auto">
                    <button type="submit" 
                            class="w-full md:w-auto px-6 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">Daftar Layanan Tersedia</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                        <th class="py-4 px-6 font-medium">Nama Layanan</th>
                        <th class="py-4 px-6 font-medium">Tarif / KM</th>
                        <th class="py-4 px-6 font-medium text-center">Status</th>
                        <th class="py-4 px-6 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light divide-y divide-gray-100">
                    @forelse($layanan as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-gray-800">
                            {{ $item->nama_layanan }}
                        </td>
                        <td class="py-4 px-6">
                            Rp {{ number_format($item->tarif_per_km, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            {{-- Badge Status --}}
                            <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-semibold">
                                Aktif
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('superadmin.layanan.destroy', $item->id) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')"
                                  class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-700 transition flex items-center justify-center"
                                        title="Hapus Layanan">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                                <p>Belum ada layanan pengiriman.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
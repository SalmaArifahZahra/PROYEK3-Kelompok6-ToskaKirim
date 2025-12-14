@extends('layouts.layout_admin')

@section('title', 'Kelola Promo Ongkir')

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
            <li class="font-bold text-gray-800">Kelola Promo Ongkir</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="p-2 bg-[#5BC6BC]/10 rounded-lg">
                <i class="fas fa-tags text-[#5BC6BC] text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Buat Promo Baru</h2>
                <p class="text-sm text-gray-500">Tambahkan aturan potongan ongkir otomatis.</p>
            </div>
        </div>

        <form action="{{ route('superadmin.promo.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Nama Promo</label>
                    <input type="text" name="nama_promo" placeholder="Contoh: Diskon Awal Tahun" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Min. Belanja (Rp)</label>
                    <input type="number" name="min_belanja" placeholder="0" 
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors" required>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Potongan Jarak (KM)</label>
                    <div class="relative">
                        <input type="number" name="potongan_jarak" placeholder="0" 
                               class="w-full pl-4 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors" required>
                        <span class="absolute right-4 top-2.5 text-gray-400 text-sm font-medium">KM</span>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#5BC6BC] hover:bg-[#4aa89e] text-white font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-plus"></i> Simpan Promo
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-semibold text-gray-700">Daftar Promo Aktif</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider border-b border-gray-200">
                        <th class="py-4 px-6 font-semibold">Nama Promo</th>
                        <th class="py-4 px-6 font-semibold">Syarat Min. Belanja</th>
                        <th class="py-4 px-6 font-semibold">Benefit (Potongan)</th>
                        <th class="py-4 px-6 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($promos as $promo)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="py-4 px-6 font-medium text-gray-800">
                            {{ $promo->nama_promo }}
                        </td>
                        <td class="py-4 px-6 text-gray-600">
                            Rp {{ number_format($promo->min_belanja, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                <i class="fas fa-check-circle"></i> Gratis {{ $promo->potongan_jarak }} KM
                            </span>
                        </td>
                        <td class="py-4 px-6 text-center">
                            <form action="{{ route('superadmin.promo.destroy', $promo->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus promo ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-red-50">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 italic">
                            Belum ada promo yang dibuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
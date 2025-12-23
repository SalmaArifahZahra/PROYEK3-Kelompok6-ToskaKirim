@extends('layouts.layout_admin')
@section('title', 'Buat Promo Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('superadmin.promo.index') }}" class="hover:text-[#2A9D8F] transition">Promo</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Buat Baru</span>
    </div>

    <form action="{{ route('superadmin.promo.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        
        <div class="px-8 py-5 border-b border-gray-50 bg-gray-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-teal-100 text-[#2A9D8F] flex items-center justify-center text-lg">
                <i class="fas fa-percentage"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Konfigurasi Promo</h2>
                <p class="text-xs text-gray-500">Atur aturan main promo sesuai strategi toko.</p>
            </div>
        </div>

        <div class="p-8 space-y-6">

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Promo / Judul</label>
                    <input type="text" name="nama_promo" class="w-full border-gray-300 rounded-lg focus:ring-[#2A9D8F] focus:border-[#2A9D8F]" placeholder="Contoh: Promo Gajian (Kelipatan 125rb)" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-[#2A9D8F] focus:border-[#2A9D8F]" placeholder="Penjelasan singkat untuk admin..."></textarea>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Minimal Belanja (Trigger)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 font-bold text-xs">Rp</span>
                        <input type="number" name="min_belanja" class="w-full pl-8 border-gray-300 rounded-lg focus:ring-[#2A9D8F] focus:border-[#2A9D8F]" placeholder="125000" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Potongan Jarak (Reward)</label>
                    <div class="relative">
                        <input type="number" name="nilai_potongan" step="0.1" class="w-full pr-12 border-gray-300 rounded-lg focus:ring-[#2A9D8F] focus:border-[#2A9D8F]" placeholder="3" required>
                        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 font-bold text-xs">KM</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg p-5 border border-blue-100">
                <label class="block text-sm font-bold text-blue-800 mb-3">Pilih Mekanisme Perhitungan</label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <label class="cursor-pointer relative">
                        <input type="radio" name="mekanisme" value="flat" class="peer sr-only">
                        <div class="p-4 bg-white border border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition h-full">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-minus text-gray-400"></i>
                                <span class="font-bold text-gray-700">Flat (Tetap)</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Hanya berlaku sekali per transaksi.
                                <br>Belanja 125rb = Potong 3KM.
                                <br>Belanja 1 Juta = <b>Tetap 3KM</b>.
                            </p>
                        </div>
                    </label>

                    <label class="cursor-pointer relative">
                        <input type="radio" name="mekanisme" value="kelipatan" class="peer sr-only" checked>
                        <div class="p-4 bg-white border border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-200 transition h-full">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-layer-group text-purple-500"></i>
                                <span class="font-bold text-gray-700">Kelipatan (Multiples)</span>
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">
                                Berlaku akumulasi.
                                <br>Belanja 125rb = Potong 3KM.
                                <br>Belanja 250rb = <b>Potong 6KM</b>.
                            </p>
                        </div>
                    </label>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-bold text-gray-600 mb-1">Batas Maksimum Potongan (Safety Net)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="maksimum_potongan" class="w-full md:w-1/2 border-gray-300 rounded text-sm" placeholder="Contoh: 20">
                        <span class="text-xs text-gray-400">KM (Kosongkan jika unlimited)</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full border-gray-300 rounded-lg" value="{{ date('Y-m-d') }}" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full border-gray-300 rounded-lg" required>
                </div>
            </div>

        </div>

        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <a href="{{ route('superadmin.promo.index') }}" class="px-5 py-2.5 text-gray-600 bg-white border border-gray-300 font-medium rounded-lg hover:bg-gray-100 transition">Batal</a>
            <button type="submit" class="px-6 py-2.5 bg-[#2A9D8F] text-white font-bold rounded-lg hover:bg-teal-700 shadow-lg shadow-teal-500/20 transition transform active:scale-95">Simpan Promo</button>
        </div>
    </form>
</div>
@endsection
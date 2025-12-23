@extends('layouts.layout_admin')
@section('title', 'Edit Wilayah')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('superadmin.wilayah.index') }}" class="hover:text-[#2A9D8F] transition">Wilayah</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Edit Data</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="px-8 py-5 border-b border-gray-50 bg-gray-50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-100 text-orange-500 flex items-center justify-center text-lg">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Edit Data Wilayah</h2>
                <p class="text-xs text-gray-500">Perbarui informasi jarak atau nama daerah.</p>
            </div>
        </div>
        
        <form action="{{ route('superadmin.wilayah.update', $wilayah->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Kota / Kabupaten</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-city"></i></span>
                        <input type="text" name="kota_kabupaten" value="{{ $wilayah->kota_kabupaten }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition" required>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Kecamatan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-building"></i></span>
                        <input type="text" name="kecamatan" value="{{ $wilayah->kecamatan }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition" required>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Kelurahan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-home"></i></span>
                        <input type="text" name="kelurahan" value="{{ $wilayah->kelurahan }}" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition" required>
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-sm font-semibold text-gray-700">Jarak dari Toko</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-route"></i></span>
                        <input type="number" step="0.001" name="jarak_km" value="{{ $wilayah->jarak_km }}" class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-400 focus:border-orange-400 transition font-mono font-medium" required>
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 text-sm font-bold bg-gray-50 rounded-r-lg border-l px-3">KM</span>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('superadmin.wilayah.index') }}" class="px-5 py-2.5 text-gray-600 bg-white border border-gray-300 font-medium rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-orange-500 text-white font-medium rounded-lg hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition transform active:scale-95">
                    <i class="fas fa-check mr-2"></i> Update Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
@extends('layouts.layout_admin')

@section('title', 'Kontrol Toko')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li><span class="text-gray-500">Master Data & Kontrol</span></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="font-bold text-gray-800">Kontrol Toko</li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kontrol Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola identitas, kontak, dan alamat operasional toko.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-start gap-3">
            <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            <div>
                <p class="text-sm text-green-700 font-medium">Berhasil Disimpan!</p>
                <p class="text-xs text-green-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
            <p class="text-sm text-red-700 font-medium mb-1">Terjadi Kesalahan</p>
            <ul class="list-disc list-inside text-xs text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50 flex items-center gap-3">
            <div class="p-2 bg-[#5BC6BC]/10 rounded-lg">
                <i class="fas fa-store text-[#5BC6BC]"></i>
            </div>
            <h3 class="font-bold text-gray-700">Informasi & Kontak</h3>
        </div>
        
        <div class="p-8">
            <form action="{{ route('superadmin.kontrol_toko.update') }}" method="POST">
                @csrf
                
                <div class="mb-8">
                    <label class="block text-gray-800 text-sm font-semibold mb-2">
                        Nomor WhatsApp Admin
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Nomor ini digunakan untuk fitur "Hubungi Kami". Format: 0812xxx (tanpa spasi/strip).
                    </p>

                    <div class="relative rounded-md shadow-sm max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fab fa-whatsapp text-green-500 text-lg"></i>
                        </div>
                        <input type="number" 
                               name="nomor_wa" 
                               value="{{ $wa }}" 
                               class="block w-full rounded-lg border-gray-300 pl-10 py-3 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors text-gray-800" 
                               placeholder="08xxxxxxxxxx"
                               required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-800 text-sm font-semibold mb-2">
                        Alamat Lengkap Toko
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Alamat ini akan ditampilkan di footer website atau invoice.
                    </p>
                    
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-red-500 text-lg"></i>
                        </div>
                        <textarea name="alamat_toko" 
                                  rows="4" 
                                  class="block w-full rounded-lg border-gray-300 pl-10 py-3 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors text-gray-800 resize-none" 
                                  placeholder="Jl. Nama Jalan No. XX, Kecamatan, Kota..." 
                                  required>{{ $alamat }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-6 border-t border-gray-100">
                    <button type="submit" class="bg-[#5BC6BC] hover:bg-[#4aa89e] text-white font-medium py-2.5 px-6 rounded-lg transition-colors flex items-center shadow-lg shadow-teal-500/10">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
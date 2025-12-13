@extends('layouts.layout_admin')

@section('title', 'Kontrol Toko')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kontrol Toko</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola identitas, kontak, dan alamat operasional toko.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start animate-fade-in-down">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700 font-medium">Berhasil!</p>
                <p class="text-sm text-green-600">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
            <div class="ml-3">
                <p class="text-sm text-red-700 font-medium">Terjadi Kesalahan</p>
                <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50 flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-700 flex items-center">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-[#2A9D8F] flex items-center justify-center mr-3 text-sm">
                    <i class="fas fa-store"></i>
                </div>
                Informasi & Kontak
            </h3>
        </div>
        
        <div class="p-6">
            <form action="{{ route('superadmin.kontrol_toko.update') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nomor WhatsApp Admin
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Nomor ini akan digunakan saat pelanggan menekan tombol <strong>"Hubungi Kami"</strong> di aplikasi.
                        <br>Gunakan format angka biasa (contoh: 081234567890).
                    </p>

                    <div class="relative rounded-md shadow-sm max-w-md">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fab fa-whatsapp text-green-500 text-lg"></i>
                        </div>
                        <input type="number" 
                               name="nomor_wa" 
                               value="{{ $wa }}" 
                               class="block w-full rounded-lg border-gray-300 pl-10 py-3 focus:border-[#2A9D8F] focus:ring-[#2A9D8F] text-gray-800 transition duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="08xxxxxxxxxx"
                               required>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Alamat Lengkap Toko
                    </label>
                    <p class="text-xs text-gray-500 mb-3">
                        Alamat ini akan ditampilkan di footer website atau sebagai titik jemput (jika ada).
                    </p>
                    
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <i class="fas fa-map-marker-alt text-red-500 text-lg"></i>
                        </div>
                        <textarea name="alamat_toko" 
                                  rows="4" 
                                  class="block w-full rounded-lg border-gray-300 pl-10 py-3 focus:border-[#2A9D8F] focus:ring-[#2A9D8F] text-gray-800 transition duration-200 bg-gray-50 focus:bg-white" 
                                  placeholder="Jl. Nama Jalan No. XX, Kecamatan, Kota..." 
                                  required>{{ $alamat }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-gray-100">
                    <button type="submit" class="px-6 py-2.5 bg-[#2A9D8F] text-white font-medium rounded-lg hover:bg-[#21867a] transition shadow-lg shadow-teal-500/20 flex items-center">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
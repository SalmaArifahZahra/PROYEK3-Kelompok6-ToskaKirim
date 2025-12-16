@extends('layouts.layout_admin')

@section('title', 'Database Wilayah')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Jarak Wilayah</h2>
            <p class="text-sm text-gray-500">Kelola jarak pengiriman untuk kalkulasi tarif otomatis.</p>
        </div>
        
        <div class="flex gap-3">
            <form action="{{ route('superadmin.wilayah.auto') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Hitung Jarak Otomatis
                </button>
            </form>
        </div>
    </div>

    <div class="mb-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Berhasil!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Gagal!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100">
            <input type="text" id="searchBox" placeholder="Cari Kelurahan atau Kecamatan..." 
                   class="w-full md:w-1/3 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F] text-sm">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-6 py-4">Wilayah Administratif</th>
                        <th class="px-6 py-4">Kelurahan</th>
                        <th class="px-6 py-4 text-center">Jarak (KM)</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm" id="tableContent">
                    @foreach($wilayah as $w)
                    <tr class="hover:bg-gray-50 transition">
                        
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $w->kecamatan }}</div>
                            <div class="text-xs text-gray-500">{{ $w->kota_kabupaten }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">
                            {{ $w->kelurahan }}
                        </td>
                        
                        <td class="px-6 py-4 text-center" colspan="2">
                            <form action="{{ route('superadmin.wilayah.update', $w->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                @csrf 
                                @method('PUT')
                                
                                <div class="relative w-24">
                                    <input type="number" 
                                        step="0.001" 
                                        name="jarak_km" 
                                        value="{{ $w->jarak_km }}" 
                                        class="w-full px-2 py-1 text-center border rounded border-gray-300 focus:border-[#2A9D8F] focus:ring-1 focus:ring-[#2A9D8F] font-mono text-gray-700 font-bold">
                                </div>
                                <span class="text-xs text-gray-400">km</span>

                                <button type="submit" class="ml-2 text-white bg-[#2A9D8F] hover:bg-[#21867a] focus:ring-2 focus:ring-teal-300 font-medium rounded text-xs px-3 py-1.5 transition">
                                    <i class="fas fa-save"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-gray-100">
            {{ $wilayah->links() }}
        </div>
    </div>
</div>

<script>
    document.getElementById('searchBox').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableContent tr");
        
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.indexOf(value) > -1 ? "" : "none";
        });
    });
</script>
@endsection
@extends('layouts.layout_superadmin')

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
                    Hitung Jarak (API Google)
                </button>
            </form>
        </div>
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
                        
                        <form action="{{ route('superadmin.wilayah.update', $w->id) }}" method="POST">
                            @csrf @method('PUT')
                            <td class="px-6 py-4 text-center">
                                <div class="relative inline-block w-24">
                                    <input type="number" step="0.001" name="jarak_km" value="{{ $w->jarak_km }}" 
                                           class="w-full px-2 py-1 text-center border rounded border-gray-300 focus:border-[#2A9D8F] focus:ring-1 focus:ring-[#2A9D8F] font-mono text-gray-700 font-bold"
                                           {{ $w->jarak_km > 0 ? 'readonly' : '' }}> <span class="absolute right-[-25px] top-1 text-gray-400 text-xs">km</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($w->jarak_km == 0)
                                    <button type="submit" class="text-[#2A9D8F] hover:text-[#21867a] font-medium text-xs border border-[#2A9D8F] px-3 py-1 rounded">
                                        Simpan
                                    </button>
                                @else
                                    <span class="text-green-600 text-xs font-bold bg-green-100 px-2 py-1 rounded-full">✓ Terisi</span>
                                @endif
                            </td>
                        </form>
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
    // Script Search Sederhana
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
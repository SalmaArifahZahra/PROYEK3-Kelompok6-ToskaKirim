@extends('layouts.layout_admin')

@section('title', 'Database Wilayah')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Wilayah</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data jarak dan area pengiriman.</p>
        </div>
        
        <a href="{{ route('superadmin.wilayah.create') }}" class="px-5 py-2.5 bg-[#2A9D8F] text-white font-medium rounded-lg hover:bg-teal-700 shadow-lg shadow-teal-500/20 flex items-center transition transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i> Tambah Wilayah
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start">
            <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-500 mt-0.5"></i></div>
            <div class="ml-3"><p class="text-sm text-green-700 font-medium">{{ session('success') }}</p></div>
        </div>
    @endif

    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col lg:flex-row gap-4 justify-between items-center">
        
        <form action="{{ route('superadmin.wilayah.index') }}" method="GET" class="w-full lg:w-96 relative" id="searchForm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            
            <input type="text" 
                   id="searchInput"
                   name="search" 
                   value="{{ $search ?? '' }}" 
                   class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-[#2A9D8F] focus:border-[#2A9D8F] sm:text-sm transition duration-150 ease-in-out" 
                   placeholder="Ketik untuk mencari..."
                   autocomplete="off">

            @if(request('search'))
                <a href="{{ route('superadmin.wilayah.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 cursor-pointer transition">
                    <i class="fas fa-times-circle"></i>
                </a>
            @endif
        </form>

        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
            <form action="{{ route('superadmin.wilayah.auto') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 font-medium text-sm flex items-center transition border border-indigo-200" title="Hitung Jarak Otomatis">
                    <i class="fas fa-calculator lg:mr-2"></i> <span class="hidden lg:inline">Hitung</span>
                </button>
            </form>

            <div class="h-8 w-px bg-gray-300 mx-1 hidden lg:block"></div>

            <form action="{{ route('superadmin.wilayah.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="cursor-pointer px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium text-sm flex items-center transition shadow-sm" title="Import CSV">
                    <i class="fas fa-file-upload lg:mr-2 text-orange-500"></i> <span class="hidden lg:inline">Import</span>
                    <input type="file" name="file_csv" class="hidden" onchange="this.form.submit()">
                </label>
            </form>

            <a href="{{ route('superadmin.wilayah.export') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium text-sm flex items-center transition shadow-sm" title="Export CSV">
                <i class="fas fa-file-download lg:mr-2 text-green-600"></i> <span class="hidden lg:inline">Export</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 font-semibold border-b border-gray-200 uppercase tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4">Kota / Kabupaten</th>
                        <th class="px-6 py-4">Kecamatan</th>
                        <th class="px-6 py-4">Kelurahan</th>
                        <th class="px-6 py-4 text-center">Jarak (KM)</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($wilayah as $w)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 text-gray-800">{{ $w->kota_kabupaten }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $w->kecamatan }}</td>
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $w->kelurahan }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($w->jarak_km > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $w->jarak_km }} km
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    0 km
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('superadmin.wilayah.edit', $w->id) }}" class="text-blue-500 hover:text-blue-700 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <button type="button" onclick="konfirmasiHapus('{{ $w->id }}', '{{ $w->kelurahan }}')" class="text-red-400 hover:text-red-600 transition" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                <form id="delete-form-{{ $w->id }}" action="{{ route('superadmin.wilayah.destroy', $w->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $wilayah->links() }}
        </div>
    </div>
</div>

<script>
    // Fungsi Konfirmasi Hapus Cantik
    function konfirmasiHapus(id, nama) {
        Swal.fire({
            title: 'Yakin hapus ' + nama + '?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik Ya, submit form yang tersembunyi tadi
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>

<script>
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let timeout = null;

    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);

        timeout = setTimeout(function() {
            searchForm.submit();
        }, 800); 
    });
    
    const val = searchInput.value;
    searchInput.focus();
    searchInput.value = '';
    searchInput.value = val;
</script>
@endsection
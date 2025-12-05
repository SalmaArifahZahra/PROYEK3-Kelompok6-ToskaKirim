@extends('layouts.layout_admin')

@section('title', 'Pilih Kategori Produk')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Produk']
        ]
    ])

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pilih Kategori Produk</h1>
            <p class="text-gray-600 mt-1">Pilih kategori untuk melihat dan mengelola produk</p>
        </div>
    </div>

    <!-- Grid Kategori -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($kategoriList as $kategori)
        <a href="{{ route('admin.produk.index', ['kategori' => $kategori->id_kategori]) }}" 
           class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
            
            <!-- Foto Kategori -->
            <div class="h-48 bg-gray-100 overflow-hidden">
                @if($kategori->foto)
                <img src="{{ asset($kategori->foto) }}" 
                     alt="{{ $kategori->nama_kategori }}" 
                     class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-box-open text-gray-300 text-6xl"></i>
                </div>
                @endif
            </div>

            <!-- Info Kategori -->
            <div class="p-5">
                <h3 class="text-lg font-semibold text-gray-800 group-hover:text-[#5BC6BC] transition-colors">
                    {{ $kategori->nama_kategori }}
                </h3>
                <div class="mt-3 flex items-center justify-between text-sm">
                    <span class="text-gray-600">
                        <i class="fas fa-box mr-2"></i>
                        {{ $kategori->produk_count ?? 0 }} Produk
                    </span>
                    <span class="text-[#5BC6BC] font-medium group-hover:translate-x-1 transition-transform">
                        Lihat <i class="fas fa-arrow-right ml-1"></i>
                    </span>
                </div>
            </div>
        </a>
        @empty
        <div class="col-span-full py-16 text-center text-gray-500">
            <i class="fas fa-folder-open text-6xl mb-4 text-gray-300"></i>
            <p class="text-lg">Belum ada kategori</p>
            <p class="text-sm mt-2">Silakan tambahkan kategori terlebih dahulu</p>
            <a href="{{ route('admin.kategori.create') }}" 
               class="inline-flex items-center gap-2 mt-4 px-6 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                Tambah Kategori
            </a>
        </div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#5BC6BC',
            timer: 3000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#5BC6BC'
        });
    @endif
</script>
@endpush

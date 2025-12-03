@extends('layouts.layout_admin')

@section('title', 'Produk')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Produk', 'url' => route('admin.produk.selectKategori')],
            ['label' => $kategori->nama_kategori ?? 'Semua Produk']
        ]
    ])

    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">
                <a href="{{ route('admin.produk.selectKategori') }}" class="text-[#5BC6BC] hover:underline">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Pilih Kategori
                </a>
            </p>
        </div>
    </div>

    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Produk'])

        <div class="flex items-center gap-3">
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <a href="{{ route('admin.produk.create', ['kategori' => $kategori->id_kategori]) }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Produk</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sub-Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produkList as $produk)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $produk->nama }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($produk->kategori)
                                @if($produk->kategori->parent_id)
                                    {{ $produk->kategori->nama_kategori }}
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($produk->deskripsi, 50) }}
                        </td>
                        <td class="px-6 py-4">
                            @include('component.admin.table_actions', [
                                'detailUrl' => route('admin.produk_detail.index', ['produk' => $produk->id_produk, 'kategori' => $kategori->id_kategori]),
                                'editUrl' => route('admin.produk.edit', ['produk' => $produk->id_produk, 'kategori' => $kategori->id_kategori]),
                                'deleteUrl' => route('admin.produk.destroy', $produk->id_produk),
                                'dataNama' => $produk->nama
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data produk</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($produkList, 'hasPages') && $produkList->hasPages())
        <div class="mt-6">
            {{ $produkList->appends(request()->except('page'))->links() }}
        </div>
    @endif

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
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

        const deleteForms = document.querySelectorAll('.swal-delete');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); // Stop submit form langsung
                
                const namaItem = this.getAttribute('data-nama') || 'item ini';
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Menghapus " + namaItem + " akan menghapus semua varian stoknya juga!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Merah untuk bahaya
                    cancelButtonColor: '#3085d6', // Biru untuk batal
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit(); // Lanjutkan submit form manual
                    }
                });
            });
        });
    });
</script>

@endsection
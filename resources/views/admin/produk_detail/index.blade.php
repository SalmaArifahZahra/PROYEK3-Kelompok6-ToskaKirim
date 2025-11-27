@extends('layouts.layout_admin')

@section('title', 'Produk Detail')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Produk', 'url' => route('admin.produk.index')],
            ['label' => $produk->nama]
        ]
    ])

    <!-- Search Bar and Actions -->
    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Varian Produk'])

        <div class="flex items-center gap-3">
            <!-- Trash Icon -->
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Varian Produk Button -->
            <a href="{{ route('admin.produk.detail.create', $produk->id_produk) }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Varian Produk</span>
            </a>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Foto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Varian</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga Modal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Harga Jual</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Stok</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($produk->detail as $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
                        </td>
                        <td class="px-6 py-4">
                            @if($detail->foto)
                            <img
                                src="{{ asset($detail->foto) }}"
                                alt="{{ $detail->nama_varian }}"
                                class="w-16 h-16 object-contain rounded">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $detail->nama_varian }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            Rp. {{ number_format($detail->harga_modal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            Rp. {{ number_format($detail->harga_jual, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $detail->stok }}
                        </td>
                        <td class="px-6 py-4">
                            @include('component.admin.table_actions', [
                                'editUrl' => route('admin.produk.detail.edit', [$produk->id_produk, $detail->id_produk_detail]),
                                'deleteUrl' => route('admin.produk.detail.destroy', [$produk->id_produk, $detail->id_produk_detail]),
                                'confirmMessage' => 'Yakin ingin menghapus varian ini?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data varian produk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle delete forms with SweetAlert confirmation
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.swal-delete').forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var nama = form.getAttribute('data-nama') || 'produk ini';

                Swal.fire({
                    title: 'Yakin ingin menghapus? ',
                    text: "Data ini akan terhapus permanen",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show session success message via SweetAlert
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ addslashes(session('success')) }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        // Show first validation error (if any)
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi kesalahan',
                html: `@foreach ($errors->all() as $err) <div>- {{ addslashes($err) }}</div> @endforeach`,
            });
        @endif
    });
</script>
@endpush
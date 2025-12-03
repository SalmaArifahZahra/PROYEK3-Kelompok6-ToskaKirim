@extends('layouts.layout_admin')

@section('title', 'Produk Detail')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @php
        $breadcrumbItems = [
            ['label' => 'Produk', 'url' => route('admin.produk.selectKategori')]
        ];
        if($kategori) {
            $breadcrumbItems[] = ['label' => $kategori->nama_kategori, 'url' => route('admin.produk.index', ['kategori' => $kategori->id_kategori])];
        }
        $breadcrumbItems[] = ['label' => $produk->nama];
    @endphp
    
    @include('component.admin.breadcrumb', ['items' => $breadcrumbItems])

    <!-- Search Bar and Actions -->
    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Varian Produk'])

        <div class="flex items-center gap-3">
            <button id="batch-delete-btn" disabled class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Varian Produk Button -->
            <a href="{{ route('admin.produk.detail.create', ['produk' => $produk->id_produk, 'kategori' => $kategori ? $kategori->id_kategori : null]) }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Varian Produk</span>
            </a>
        </div>
    </div>

    <!-- Hidden form for batch delete -->
    <form id="batch-delete-form" action="{{ route('admin.produk.detail.batchDelete', $produk->id_produk) }}" method="POST" style="display: none;">
        @csrf
        @if($kategori)
        <input type="hidden" name="kategori" value="{{ $kategori->id_kategori }}">
        @endif
    </form>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
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
                    @forelse($detailList as $detail)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="item-checkbox rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]" value="{{ $detail->id_produk_detail }}">
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
                                'editUrl' => route('admin.produk.detail.edit', ['produk' => $produk->id_produk, 'detail' => $detail->id_produk_detail, 'kategori' => $kategori ? $kategori->id_kategori : null]),
                                'deleteUrl' => route('admin.produk.detail.destroy', ['produk' => $produk->id_produk, 'detail' => $detail->id_produk_detail, 'kategori' => $kategori ? $kategori->id_kategori : null]),
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

    @if(method_exists($detailList, 'hasPages') && $detailList->hasPages())
        <div class="mt-6">
            {{ $detailList->appends(request()->except('page'))->links() }}
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAll = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const batchDeleteBtn = document.getElementById('batch-delete-btn');
        const batchDeleteForm = document.getElementById('batch-delete-form');

        // Select all functionality
        selectAll.addEventListener('change', function() {
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBatchDeleteButton();
        });

        // Individual checkbox change
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllState();
                updateBatchDeleteButton();
            });
        });

        // Update select-all checkbox state
        function updateSelectAllState() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            selectAll.checked = checkedCount === itemCheckboxes.length && checkedCount > 0;
            selectAll.indeterminate = checkedCount > 0 && checkedCount < itemCheckboxes.length;
        }

        // Enable/disable batch delete button
        function updateBatchDeleteButton() {
            const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;
            batchDeleteBtn.disabled = checkedCount === 0;
        }

        // Batch delete confirmation
        batchDeleteBtn.addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.item-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);

            if (ids.length === 0) return;

            Swal.fire({
                title: 'Hapus Varian Terpilih?',
                text: `${ids.length} varian produk akan dihapus. Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Clear existing inputs
                    const existingInputs = batchDeleteForm.querySelectorAll('input[name="ids[]"]');
                    existingInputs.forEach(input => input.remove());

                    // Add selected IDs to form
                    ids.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        batchDeleteForm.appendChild(input);
                    });

                    // Submit form
                    batchDeleteForm.submit();
                }
            });
        });

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

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ addslashes(session('success')) }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ addslashes(session('error')) }}",
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: "{{ addslashes(session('warning')) }}",
            });
        @endif

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
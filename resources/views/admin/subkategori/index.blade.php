@extends('layouts.layout_admin')

@section('title', 'Sub-Kategori')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Kategori', 'url' => route('admin.kategori.index')],
            ['label' => $kategori->nama_kategori]
        ]
    ])

    <!-- Search Bar and Actions -->
    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Sub-Kategori'])

        <div class="flex items-center gap-3">
            <!-- Trash Icon -->
            <button id="batch-delete-btn" disabled class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Sub-Kategori Button -->
            <a href="{{ route('admin.kategori.subkategori.create', $kategori->id_kategori) }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Sub-Kategori</span>
            </a>
        </div>
    </div>

    <!-- Hidden form for batch delete -->
    <form id="batch-delete-form" action="{{ route('admin.kategori.subkategori.batchDelete', $kategori->id_kategori) }}" method="POST" style="display: none;">
        @csrf
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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Sub-Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($subKategoriList as $subkategori)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="item-checkbox rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]" value="{{ $subkategori->id_kategori }}">
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $subkategori->nama_kategori }}
                        </td>
                        <td class="px-6 py-4">
                            @include('component.admin.table_actions', [
                                'editUrl' => route('admin.kategori.subkategori.edit', [$kategori->id_kategori, $subkategori->id_kategori]),
                                'deleteUrl' => route('admin.kategori.subkategori.destroy', [$kategori->id_kategori, $subkategori->id_kategori]),
                                'dataNama' => $subkategori->nama_kategori
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data sub-kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($subKategoriList, 'hasPages') && $subKategoriList->hasPages())
        <div class="mt-6">
            {{ $subKategoriList->appends(request()->except('page'))->links() }}
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                title: 'Hapus Sub-Kategori Terpilih?',
                text: `${ids.length} sub-kategori akan dihapus. Tindakan ini tidak dapat dibatalkan!`,
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
        
        const deleteForms = document.querySelectorAll('.swal-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const itemName = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: `Sub-kategori "${itemName}" akan dihapus permanen`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#5BC6BC',
                    cancelButtonColor: '#d33'
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
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#5BC6BC'
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

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#5BC6BC'
            });
        @endif
    });
</script>
@endpush

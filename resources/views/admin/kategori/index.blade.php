@extends('layouts.layout_admin')

@section('title', 'Kategori')

@section('content')

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Kategori</h1>
    </div>

    <!-- Search Bar and Actions -->
    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Kategori'])

        <div class="flex items-center gap-3">
            <!-- Trash Icon for Batch Delete -->
            <button id="batch-delete-btn" disabled class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Kategori Button -->
            <a href="{{ route('admin.kategori.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Kategori</span>
            </a>
        </div>
    </div>

    <!-- Batch Delete Form (Hidden) -->
    <form id="batch-delete-form" action="{{ route('admin.kategori.batchDelete') }}" method="POST" style="display: none;">
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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Foto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoriList as $kategori)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="item-checkbox rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]" value="{{ $kategori->id_kategori }}">
                        </td>
                        <td class="px-6 py-4">
                            @if($kategori->foto)
                            <img
                                src="{{ asset($kategori->foto) }}"
                                alt="{{ $kategori->nama_kategori }}"
                                class="w-16 h-16 object-contain rounded">
                            @else
                            <div class="w-16 h-16 bg-gray-200 rounded flex items-center justify-center">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $kategori->nama_kategori }}
                        </td>
                        <td class="px-6 py-4">
                            @include('component.admin.table_actions', [
                                'showEllipsis' => route('admin.kategori.subkategori.index', $kategori->id_kategori),
                                'ellipsisTitle' => 'Sub-Kategori',
                                'editUrl' => route('admin.kategori.edit', $kategori->id_kategori),
                                'deleteUrl' => route('admin.kategori.destroy', $kategori->id_kategori),
                                'confirmMessage' => 'Yakin ingin menghapus kategori ini?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($kategoriList, 'hasPages') && $kategoriList->hasPages())
        <div class="mt-6">
            {{ $kategoriList->appends(request()->except('page'))->links() }}
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
            title: 'Hapus Kategori Terpilih?',
            text: `${ids.length} kategori akan dihapus. Tindakan ini tidak dapat dibatalkan!`,
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
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. HANDLE TOMBOL HAPUS (KONFIRMASI) ---
        // Cari semua form yang punya class 'swal-delete'
        const deleteForms = document.querySelectorAll('.swal-delete');
        
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Stop submit normal
                
                // Ambil nama dari atribut data, atau default
                const nama = this.getAttribute('data-nama') || 'data ini';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Menghapus " + nama + " tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user klik Ya, baru submit form secara manual
                        this.submit();
                    }
                });
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // --- 3. ALERT ERROR (GAGAL HAPUS / DATABASE ERROR) ---
        // Bagian yang menangkap return back()->with('error', ...) dari controller
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#d33',
                confirmButtonText: 'Tutup'
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                text: "{{ session('warning') }}",
                confirmButtonColor: '#f59e0b'
            });
        @endif

        // --- 4. ALERT ERROR VALIDASI (INPUT TIDAK VALID) ---
        @if($errors->any())
            let errorHtml = '<ul class="text-left pl-5">';
            @foreach ($errors->all() as $error)
                errorHtml += '<li>- {{ $error }}</li>';
            @endforeach
            errorHtml += '</ul>';

            Swal.fire({
                icon: 'error',
                title: 'Input Tidak Valid',
                html: errorHtml,
            });
        @endif

    });
</script>
@endpush

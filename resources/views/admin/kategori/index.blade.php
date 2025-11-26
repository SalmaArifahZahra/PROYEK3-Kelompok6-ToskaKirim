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
            <!-- Trash Icon -->
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Kategori Button -->
            <a href="{{ route('admin.kategori.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Kategori</span>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($kategoriList as $kategori)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-[#5BC6BC] focus:ring-[#5BC6BC]">
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
                                'showEllipsis' => true,
                                'ellipsisUrl' => route('admin.kategori.subkategori.index', $kategori->id_kategori),
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

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- 1. HANDLE DELETE CONFIRMATION ---
        // Mencari semua form dengan class 'swal-delete'
        const deleteForms = document.querySelectorAll('form.swal-delete');
        
        deleteForms.forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah submit langsung

                // Ambil nama item dari atribut data (jika ada), atau default
                const nama = form.getAttribute('data-nama') || 'data ini';

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Menghapus " + nama + " tidak dapat dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33', // Warna merah untuk hapus
                    cancelButtonColor: '#3085d6', // Warna biru untuk batal
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user klik Ya, submit form secara manual
                        form.submit();
                    }
                });
            });
        });

        // --- 2. SUCCESS ALERT (SESSION) ---
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // --- 3. ERROR ALERT (VALIDATION/SESSION) ---
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: '<ul class="text-left">@foreach ($errors->all() as $error)<li>- {{ $error }}</li>@endforeach</ul>',
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
            });
        @endif
    });
</script>
@endpush

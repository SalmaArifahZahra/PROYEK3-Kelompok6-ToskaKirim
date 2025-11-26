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
        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow-md p-4 w-1/2">
            <div class="relative">
                <input type="text"
                       placeholder="Search for Kategori"
                       class="w-full pl-4 pr-12 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-transparent">
                <button class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Trash Icon -->
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>

            <!-- Tambah Kategori Button -->
            <a href="{{ route('admin.kategori.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
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
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            {{ $kategori->nama_kategori }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.kategori.edit', $kategori->id_kategori) }}" class="text-gray-600 hover:text-[#5BC6BC] transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                <!-- Delete Button (handled by SweetAlert) -->
                                <form action="{{ route('admin.kategori.destroy', $kategori->id_kategori) }}" method="POST" class="inline swal-delete" data-nama="{{ $kategori->nama_kategori }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-600 hover:text-red-600 transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>

                                <!-- Sub-Kategori Button (3 dots) -->
                                <a href="{{ route('admin.kategori.subkategori.index', $kategori->id_kategori) }}" class="text-gray-600 hover:text-[#5BC6BC] transition-colors" title="Sub-Kategori">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                    </svg>
                                </a>
                            </div>
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
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Handle delete forms with SweetAlert confirmation
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form.swal-delete').forEach(function(form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                var nama = form.getAttribute('data-nama') || 'kategori ini';

                Swal.fire({
                    title: 'Yakin ingin menghapus? ',
                    text: "Menghapus '" + nama + "' akan menghapus data ini. Tindakan tidak dapat dibatalkan.",
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

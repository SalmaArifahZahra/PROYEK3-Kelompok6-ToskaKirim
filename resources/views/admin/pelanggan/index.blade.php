@extends('layouts.layout_admin')

@section('title', 'Pelanggan')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Pelanggan']
        ]
    ])

    @include('component.admin.search_bar', ['placeholder' => 'Cari nama atau email pelanggan'])

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            <a href="{{ route('admin.pelanggan.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'nama', 'sort_order' => $sortBy === 'nama' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-2 hover:text-[#5BC6BC] transition-colors">
                                Nama
                                <i class="fas fa-{{ $sortBy === 'nama' ? ($sortOrder === 'asc' ? 'sort-up' : 'sort-down') : 'sort' }} text-xs"></i>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            <a href="{{ route('admin.pelanggan.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'email', 'sort_order' => $sortBy === 'email' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-2 hover:text-[#5BC6BC] transition-colors">
                                Email
                                <i class="fas fa-{{ $sortBy === 'email' ? ($sortOrder === 'asc' ? 'sort-up' : 'sort-down') : 'sort' }} text-xs"></i>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            <a href="{{ route('admin.pelanggan.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'pesanan_count', 'sort_order' => $sortBy === 'pesanan_count' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-2 hover:text-[#5BC6BC] transition-colors">
                                Total Pesanan
                                <i class="fas fa-{{ $sortBy === 'pesanan_count' ? ($sortOrder === 'asc' ? 'sort-up' : 'sort-down') : 'sort' }} text-xs"></i>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                            <a href="{{ route('admin.pelanggan.index', array_merge(request()->except(['sort_by', 'sort_order']), ['sort_by' => 'created_at', 'sort_order' => $sortBy === 'created_at' && $sortOrder === 'asc' ? 'desc' : 'asc'])) }}" 
                               class="flex items-center gap-2 hover:text-[#5BC6BC] transition-colors">
                                Terdaftar Sejak
                                <i class="fas fa-{{ $sortBy === 'created_at' ? ($sortOrder === 'asc' ? 'sort-up' : 'sort-down') : 'sort' }} text-xs"></i>
                            </a>
                        </th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Pesanan Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pelangganList as $pelanggan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-gray-800">{{ $pelanggan->nama }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $pelanggan->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $pelanggan->pesanan_count }} pesanan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $pelanggan->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($pelanggan->pesanan->isNotEmpty())
                                {{ $pelanggan->pesanan->first()->waktu_pesanan->format('d M Y H:i') }}
                            @else
                                <span class="text-gray-400 italic">Belum ada pesanan</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada data pelanggan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($pelangganList, 'hasPages') && $pelangganList->hasPages())
        <div class="mt-6">
            {{ $pelangganList->links() }}
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
    });
</script>

@endsection

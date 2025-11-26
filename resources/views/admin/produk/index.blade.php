@extends('layouts.layout_admin')

@section('title', 'Produk')

@section('content')

<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Produk</h1>
    </div>

    <!-- Search Bar and Actions -->
    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Search for Produk'])

        <div class="flex items-center gap-3">
            <!-- Trash Icon -->
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                <i class="fas fa-trash text-xl"></i>
            </button>

            <!-- Tambah Produk Button -->
            <a href="{{ route('admin.produk.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Tambah Produk</span>
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
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori</th>
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
                            {{ $produk->kategori->nama_kategori ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ Str::limit($produk->deskripsi, 100) }}
                        </td>
                        <td class="px-6 py-4">
                            @include('component.admin.table_actions', [
                                'showDetail' => true,
                                'detailUrl' => route('admin.produk_detail.index', $produk->id_produk),
                                'editUrl' => route('admin.produk.edit', $produk->id_produk),
                                'deleteUrl' => route('admin.produk.destroy', $produk->id_produk),
                                'confirmMessage' => 'Yakin ingin menghapus produk ini beserta semua variannya?'
                            ])
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            Belum ada data produk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($produkList, 'hasPages') && $produkList->hasPages())
    <div class="flex items-center justify-between mt-6">
        <!-- Showing info -->
        <div class="text-sm text-gray-700">
            Showing <span class="font-semibold text-gray-900">{{ $produkList->firstItem() }}</span>
            to <span class="font-semibold text-gray-900">{{ $produkList->lastItem() }}</span>
            of <span class="font-semibold text-gray-900">{{ $produkList->total() }}</span> results
        </div>

        <!-- Pagination buttons -->
        <nav aria-label="Page navigation">
            <ul class="inline-flex -space-x-px text-sm">
                {{-- Previous Button --}}
                @if ($produkList->onFirstPage())
                    <li>
                        <span class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-400 bg-white border border-e-0 border-gray-300 rounded-s-lg cursor-not-allowed">
                            Previous
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $produkList->previousPageUrl() }}" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-700 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-[#5BC6BC] hover:text-white transition-colors">
                            Previous
                        </a>
                    </li>
                @endif

                {{-- Page Numbers --}}
                @foreach ($produkList->getUrlRange(1, $produkList->lastPage()) as $page => $url)
                    @if ($page == $produkList->currentPage())
                        <li>
                            <span aria-current="page" class="flex items-center justify-center px-3 h-8 text-white border border-[#5BC6BC] bg-[#5BC6BC]">
                                {{ $page }}
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $url }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-700 bg-white border border-gray-300 hover:bg-[#5BC6BC] hover:text-white transition-colors">
                                {{ $page }}
                            </a>
                        </li>
                    @endif
                @endforeach

                {{-- Next Button --}}
                @if ($produkList->hasMorePages())
                    <li>
                        <a href="{{ $produkList->nextPageUrl() }}" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-700 bg-white border border-gray-300 rounded-e-lg hover:bg-[#5BC6BC] hover:text-white transition-colors">
                            Next
                        </a>
                    </li>
                @else
                    <li>
                        <span class="flex items-center justify-center px-3 h-8 leading-tight text-gray-400 bg-white border border-gray-300 rounded-e-lg cursor-not-allowed">
                            Next
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
    @endif

</div>

@endsection

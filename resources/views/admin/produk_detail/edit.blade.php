@extends('layouts.layout_admin')

@section('title', 'Edit Varian Produk')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Produk', 'url' => route('admin.produk.index')],
            ['label' => $produk->nama, 'url' => route('admin.produk_detail.index', $produk->id_produk)],
            ['label' => 'Edit Varian Produk']
        ]
    ])

    <!-- Form Card -->
    <div class="flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-8 inline-block">
            <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Edit Varian Produk</h2>

        <form action="{{ route('admin.produk.detail.update', [$produk->id_produk, $detail->id_produk_detail]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex gap-x-8 gap-y-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Nama Varian -->
                    <div>
                        <label for="nama_varian" class="block text-sm font-medium text-gray-900 mb-2">
                            Nama Varian
                        </label>
                        <input type="text"
                               id="nama_varian"
                               name="nama_varian"
                               value="{{ old('nama_varian', $detail->nama_varian) }}"
                               placeholder="Masukkan nama produk"
                               class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('nama_varian') border-red-500 @enderror"
                               required>
                        @error('nama_varian')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga Modal & Stok Awal -->
                    <div class="flex gap-4">
                        <!-- Harga Modal -->
                        <div>
                            <label for="harga_modal" class="block text-sm font-medium text-gray-900 mb-2">
                                Harga Modal
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp.</span>
                                <input type="number"
                                       id="harga_modal"
                                       name="harga_modal"
                                       value="{{ old('harga_modal', $detail->harga_modal) }}"
                                       placeholder="xxx.xxx"
                                       class="w-full pl-12 pr-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('harga_modal') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('harga_modal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok Awal -->
                        <div class="w-32">
                            <label for="stok" class="block text-sm font-medium text-gray-900 mb-2">
                                Stok Awal
                            </label>
                            <input type="number"
                                   id="stok"
                                   name="stok"
                                   value="{{ old('stok', $detail->stok) }}"
                                   placeholder="0"
                                   class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('stok') border-red-500 @enderror"
                                   required>
                            @error('stok')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Harga Jual -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="harga_jual" class="block text-sm font-medium text-gray-900 mb-2">
                                Harga Jual
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp.</span>
                                <input type="number"
                                       id="harga_jual"
                                       name="harga_jual"
                                       value="{{ old('harga_jual', $detail->harga_jual) }}"
                                       placeholder="xxx.xxx"
                                       class="w-full pl-12 pr-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('harga_jual') border-red-500 @enderror"
                                       required>
                            </div>
                            @error('harga_jual')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Right Column - Foto Produk -->
                <div class="w-auto">
                    @include('component.admin.image_upload', [
                        'inputId' => 'foto',
                        'name' => 'foto',
                        'label' => 'Foto Produk',
                        'existingImage' => $detail->foto ? asset($detail->foto) : null
                    ])
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 mt-10">
                <a href="{{ route('admin.produk_detail.index', $produk->id_produk) }}"
                   class="px-8 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors font-medium">
                    Simpan Perubahan
                </button>
            </div>
        </form>
        </div>
    </div>

@endsection
@extends('layouts.layout_admin')

@section('title', 'Edit Varian Produk')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li>
                <a href="{{ route('admin.produk.index') }}" class="text-2xl font text-gray-800 hover:text-[#5BC6BC]">Produk</a>
            </li>
            <li>
                <span class="mx-2">&gt;</span>
            </li>
            <li>
                <a href="{{ route('admin.produk_detail.index', $produk->id_produk) }}" class=" text-2xl font text-gray-800 hover:text-[#5BC6BC]">{{ $produk->nama }}</a>
            </li>
            <li>
                <span class="mx-2">&gt;</span>
            </li>
            <li class="text-2xl font-bold text-gray-800">Edit Varian Produk</li>
        </ol>
    </nav>

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
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Foto Produk
                    </label>
                    <div class="flex flex-col">
                        <!-- Preview Image -->
                        <div class="w-64 h-64 bg-gray-100 rounded-lg flex items-center justify-center mb-3 overflow-hidden">
                            <img id="preview-image"
                                 src="{{ $detail->foto ? asset($detail->foto) : '' }}"
                                 alt="Preview"
                                 class="{{ $detail->foto ? '' : 'hidden' }} w-full h-full object-contain">
                            <div id="preview-placeholder" class="text-gray-400 {{ $detail->foto ? 'hidden' : '' }}">
                                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Upload Button -->
                        <label for="foto" class="cursor-pointer text-[#5BC6BC] hover:text-[#4aa89e] font-medium text-center">
                            Upload Foto
                            <input type="file"
                                   id="foto"
                                   name="foto"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(event)">
                        </label>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
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

</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview-image');
    const placeholder = document.getElementById('preview-placeholder');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>

@endsection

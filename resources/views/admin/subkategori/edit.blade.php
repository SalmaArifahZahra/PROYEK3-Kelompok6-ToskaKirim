@extends('layouts.layout_admin')

@section('title', 'Edit Sub-Kategori')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li>
                <a href="{{ route('admin.kategori.index') }}" class="text-2xl font text-gray-800 hover:text-[#5BC6BC]">Kategori</a>
            </li>
            <li>
                <span class="mx-2">&gt;</span>
            </li>
            <li>
                <a href="{{ route('admin.kategori.subkategori.index', $kategori->id_kategori) }}" class="text-2xl font text-gray-800 hover:text-[#5BC6BC]">{{ $kategori->nama_kategori }}</a>
            </li>
            <li>
                <span class="mx-2">&gt;</span>
            </li>
            <li class="text-2xl font-bold text-gray-800">Edit Sub-Kategori</li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-8 inline-block">
            <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Edit Sub-Kategori</h2>

            <form action="{{ route('admin.kategori.subkategori.update', [$kategori->id_kategori, $subkategori->id_kategori]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Nama Sub-Kategori -->
                        <div class="w-96">
                            <label for="nama_kategori" class="block text-sm font-medium text-gray-900 mb-2">
                                Nama Sub-Kategori
                            </label>
                            <input type="text"
                                   id="nama_kategori"
                                   name="nama_kategori"
                                   value="{{ old('nama_kategori', $subkategori->nama_kategori) }}"
                                   placeholder="Masukkan nama sub-kategori"
                                   class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('nama_kategori') border-red-500 @enderror"
                                   required>
                            @error('nama_kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Foto -->
                    <div class="flex flex-col items-center">
                        <label for="foto" class="block text-sm font-medium text-gray-900 mb-2">
                            Foto
                        </label>
                        <div class="relative">
                            <div id="image-preview" class="w-64 h-64 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-gray-50">
                                @if($subkategori->foto)
                                <img id="preview-image" src="{{ asset($subkategori->foto) }}" class="w-full h-full object-contain" alt="Preview">
                                <div id="placeholder" class="hidden text-center">
                                @else
                                <div id="placeholder" class="text-center">
                                @endif
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">Upload foto</p>
                                </div>
                                @if(!$subkategori->foto)
                                <img id="preview-image" class="hidden w-full h-full object-contain" alt="Preview">
                                @endif
                            </div>
                            <input type="file"
                                   id="foto"
                                   name="foto"
                                   accept="image/*"
                                   class="hidden"
                                   onchange="previewImage(event)">
                            <label for="foto" class="absolute bottom-2 right-2 bg-[#5BC6BC] text-white p-2 rounded-lg cursor-pointer hover:bg-[#4aa89e] transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </label>
                        </div>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-8">
                    <a href="{{ route('admin.kategori.subkategori.index', $kategori->id_kategori) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-image');
        const placeholder = document.getElementById('placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

@endsection

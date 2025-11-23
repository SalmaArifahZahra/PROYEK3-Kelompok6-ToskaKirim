@extends('layouts.layout_admin')

@section('title', 'Tambah Produk')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li>
                <a href="{{ route('admin.produk.index') }}" <h1 class="text-2xl font text-gray-800">Produk</h1></a>
            </li>
            <li>
                <span class="mx-2">&gt;</span>
            </li>
            <li class="text-2xl font-bold text-gray-800">Tambah Produk</li>
        </ol>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Produk Baru</h2>

        <form action="{{ route('admin.produk.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                <!-- Nama Produk -->
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-900 mb-2">
                        Nama Produk
                    </label>
                    <input type="text"
                           id="nama"
                           name="nama"
                           value="{{ old('nama') }}"
                           placeholder="Masukkan nama produk"
                           class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('nama') border-red-500 @enderror"
                           required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi Produk -->
                <div class="row-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-900 mb-2">
                        Deskripsi Produk
                    </label>
                    <textarea id="deskripsi"
                              name="deskripsi"
                              rows="9"
                              placeholder="Berikan deskripsi produk untuk memberikan informasi tambahan terkait produk"
                              class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none resize-none text-gray-500 @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="id_kategori" class="block text-sm font-medium text-gray-900 mb-2">
                        Kategori
                    </label>
                    <div class="flex gap-3">
                        <select id="id_kategori"
                                name="id_kategori"
                                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none appearance-none @error('id_kategori') border-red-500 @enderror"
                                style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;"
                                required>
                            <option value="">Tak berkategori</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori->id_kategori }}" {{ old('id_kategori') == $kategori->id_kategori ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>

                        <button type="button"
                                class="px-4 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors whitespace-nowrap font-medium">
                            + Kategori Baru
                        </button>
                    </div>
                    @error('id_kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4 mt-10">
                <a href="{{ route('admin.produk.index') }}"
                   class="px-8 py-3 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                    Batal
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors font-medium">
                    Buat Produk
                </button>
            </div>
        </form>
    </div>

</div>

@endsection

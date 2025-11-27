@extends('layouts.layout_admin')

@section('title', 'Edit Sub-Kategori')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Kategori', 'url' => route('admin.kategori.index')],
            ['label' => $kategori->nama_kategori, 'url' => route('admin.kategori.subkategori.index', $kategori->id_kategori)],
            ['label' => 'Edit Sub-Kategori']
        ]
    ])

    <!-- Form Card -->
    <div class="flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-8 inline-block">
            <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Edit Sub-Kategori</h2>

            <form action="{{ route('admin.kategori.subkategori.update', [$kategori->id_kategori, $subkategori->id_kategori]) }}" method="POST">
                @csrf
                @method('PUT')

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

@endsection

@extends('layouts.layout_admin')

@section('title', 'Edit Kategori')

@section('content')

<div class="space-y-6">

    <!-- Breadcrumb -->
    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Kategori', 'url' => route('admin.kategori.index')],
            ['label' => 'Edit Kategori']
        ]
    ])

    <!-- Form Card -->
    <div class="flex justify-center">
        <div class="bg-white rounded-lg shadow-md p-8 inline-block">
            <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Edit Kategori</h2>

            <form action="{{ route('admin.kategori.update', $kategori->id_kategori) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex gap-x-8 gap-y-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Nama Kategori -->
                        <div>
                            <label for="nama_kategori" class="block text-sm font-medium text-gray-900 mb-2">
                                Nama Kategori
                            </label>
                            <input type="text"
                                   id="nama_kategori"
                                   name="nama_kategori"
                                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                   placeholder="Masukkan nama kategori"
                                   class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('nama_kategori') border-red-500 @enderror"
                                   required>
                            @error('nama_kategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Parent Kategori -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-900 mb-2">
                                Parent Kategori
                            </label>
                            <select id="parent_id"
                                    name="parent_id"
                                    class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none @error('parent_id') border-red-500 @enderror">
                                <option value="">Pilih Parent (Opsional)</option>
                                @foreach($parentKategori as $parent)
                                    <option value="{{ $parent->id_kategori }}" {{ old('parent_id', $kategori->parent_id) == $parent->id_kategori ? 'selected' : '' }}>
                                        {{ $parent->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Foto Kategori -->
                    <div class="w-auto">
                        @include('component.admin.image_upload', [
                            'inputId' => 'foto',
                            'name' => 'foto',
                            'label' => 'Foto Kategori',
                            'existingImage' => $kategori->foto ? asset($kategori->foto) : null
                        ])
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-10">
                    <a href="{{ route('admin.kategori.index') }}"
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

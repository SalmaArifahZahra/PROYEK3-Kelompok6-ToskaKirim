@extends('layouts.layout_admin')

@section('title', 'Tambah Produk')

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Produk', 'url' => route('admin.produk.index')],
            ['label' => 'Tambah Produk']
        ]
    ])

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Produk Baru</h2>

        <form action="{{ route('admin.produk.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
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

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Kategori (Pilih Kategori → Sub-Kategori)
                    </label>
                    
                    <div class="mb-3">
                        <label for="parent_id" class="block text-xs font-medium text-gray-700 mb-1">
                            Kategori Utama
                        </label>
                        <div class="flex gap-2">
                            <select id="parent_id"
                                    name="parent_id"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none">
                                <option value="">Pilih Kategori Utama</option>
                                @foreach($parentCategories as $category)
                                    <option value="{{ $category->id_kategori }}" 
                                            data-children="{{ json_encode($category->children->map(fn($c) => ['id' => $c->id_kategori, 'nama' => $c->nama_kategori])) }}"
                                            {{ old('parent_id') == $category->id_kategori ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <a href="{{ route('admin.kategori.create') }}" 
                                    class="px-4 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors whitespace-nowrap font-medium text-sm
                               title="Buat Kategori Baru">
                                + 
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_kategori" class="block text-xs font-medium text-gray-700 mb-1">
                            Sub-Kategori
                        </label>
                        <div class="flex gap-2">
                            <select id="id_kategori" 
                                    name="id_kategori"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none @error('id_kategori') border-red-500 @enderror"
                                    required>
                                <option value="">Pilih Kategori Utama Terlebih Dahulu</option>
                            </select>
                            
                            <button type="button" id="btn-add-subkategori" disabled class="px-4 py-3 bg-gray-300 text-gray-600 rounded-lg whitespace-nowrap font-medium text-sm cursor-not-allowed">
                                +
                            </button>
                        </div>
                    </div>

                    @error('id_kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelect = document.getElementById('parent_id');
        const subCategorySelect = document.getElementById('id_kategori');
        const btnAddSubkategori = document.getElementById('btn-add-subkategori');

        // Fungsi untuk mengisi dropdown sub kategori
        function populateSubCategories(selectedOption) {
            subCategorySelect.innerHTML = '<option value="">Pilih Sub-Kategori</option>';
            
            if (!selectedOption || !selectedOption.value) {
                disableAddButton();
                return;
            }

            enableAddButton();
            
            // Ambil data anak dari atribut data-children
            const childrenData = selectedOption.getAttribute('data-children');
            
            if (childrenData) {
                try {
                    const children = JSON.parse(childrenData);
                    children.forEach(child => {
                        const option = document.createElement('option');
                        option.value = child.id;
                        option.textContent = child.nama;
                        subCategorySelect.appendChild(option);
                    });
                } catch (e) {
                    console.error('Error parsing children data:', e);
                }
            }
        }

        // Helper untuk styling tombol tambah sub
        function enableAddButton() {
            btnAddSubkategori.disabled = false;
            btnAddSubkategori.classList.remove('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
            btnAddSubkategori.classList.add('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]', 'transition-colors');
        }

        function disableAddButton() {
            btnAddSubkategori.disabled = true;
            btnAddSubkategori.classList.remove('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]');
            btnAddSubkategori.classList.add('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
        }

        // Event Listener: Saat Parent Berubah
        parentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            populateSubCategories(selectedOption);
        });

        if (parentSelect.value) {
            const selectedOption = parentSelect.options[parentSelect.selectedIndex];
            populateSubCategories(selectedOption);

            // Restore old value untuk sub kategori
            const oldSubCategoryId = '{{ old("id_kategori") }}';
            if (oldSubCategoryId) {
                subCategorySelect.value = oldSubCategoryId;
            }
        }
    });
</script>
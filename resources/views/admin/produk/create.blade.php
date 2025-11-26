@extends('layouts.layout_admin')

@section('title', 'Tambah Produk')

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

                <!-- Kategori (Two-Step Selection) -->
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
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none appearance-none"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;">
                                <option value="">Pilih Kategori Utama</option>
                                @foreach($parentCategories as $category)
                                    <option value="{{ $category->id_kategori }}" data-children="{{ json_encode($category->children->map(fn($c) => ['id' => $c->id_kategori, 'nama' => $c->nama_kategori])) }}" 
                                        {{ old('parent_id') == $category->id_kategori ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <a href="{{ route('admin.kategori.create') }}"
                                    class="px-4 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors whitespace-nowrap font-medium text-sm">
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
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white text-gray-700 focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] focus:outline-none appearance-none @error('id_kategori') border-red-500 @enderror"
                                    style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%236b7280%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.75rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;"
                                    required>
                                <option value="">Pilih Sub-Kategori</option>
                            </select>
                            
                            <button type="button" id="btn-add-subkategori"
                                    class="px-4 py-3 bg-gray-300 text-gray-600 rounded-lg whitespace-nowrap font-medium text-sm cursor-not-allowed"
                                    disabled
                                    title="Pilih kategori utama terlebih dahulu">
                                    +
                            </button>
                        </div>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelect = document.getElementById('parent_id');
        const subCategorySelect = document.getElementById('id_kategori');
        const btnAddSubkategori = document.getElementById('btn-add-subkategori');

        parentSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const childrenData = selectedOption.getAttribute('data-children');
            
            // Clear sub-category select
            subCategorySelect.innerHTML = '<option value="">Pilih Sub-Kategori</option>';

            // Enable/disable add subkategori button based on parent selection
            if (this.value) {
                btnAddSubkategori.disabled = false;
                btnAddSubkategori.classList.remove('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
                btnAddSubkategori.classList.add('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]', 'transition-colors');
                btnAddSubkategori.title = 'Buat sub-kategori baru';
            } else {
                btnAddSubkategori.disabled = true;
                btnAddSubkategori.classList.remove('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]', 'transition-colors');
                btnAddSubkategori.classList.add('bg-gray-300', 'text-gray-600', 'cursor-not-allowed');
                btnAddSubkategori.title = 'Pilih kategori utama terlebih dahulu';
            }

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
        });

        // Handle add subkategori button click
        btnAddSubkategori.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectedParentId = parentSelect.value;
            
            if (!selectedParentId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pilih Kategori Terlebih Dahulu',
                    text: 'Anda harus memilih kategori utama sebelum membuat sub-kategori.',
                    confirmButtonColor: '#5BC6BC',
                });
                return;
            }
            
            // Redirect to create subkategori with parent id
            window.location.href = `{{ route('admin.kategori.subkategori.create', ':id') }}`.replace(':id', selectedParentId);
        });

        // Trigger change event on page load if parent_id is pre-selected (for old values)
        if (parentSelect.value) {
            parentSelect.dispatchEvent(new Event('change'));
            
            // If id_kategori is also pre-selected, restore it
            const oldSubCategoryId = '{{ old("id_kategori") }}';
            if (oldSubCategoryId) {
                subCategorySelect.value = oldSubCategoryId;
            }
        }
    });
</script>
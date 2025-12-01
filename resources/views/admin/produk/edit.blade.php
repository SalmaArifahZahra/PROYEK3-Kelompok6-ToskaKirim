@extends('layouts.layout_admin')

@section('title', 'Edit Produk')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @php
        $breadcrumbItems = [
            ['label' => 'Produk', 'url' => route('admin.produk.selectKategori')]
        ];
        if($kategori) {
            $breadcrumbItems[] = ['label' => $kategori->nama_kategori, 'url' => route('admin.produk.index', ['kategori' => $kategori->id_kategori])];
        }
        $breadcrumbItems[] = ['label' => 'Edit Produk'];
    @endphp
    
    @include('component.admin.breadcrumb', ['items' => $breadcrumbItems])

    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-semibold text-[#5BC6BC] mb-8">Edit Produk</h2>

        <form action="{{ route('admin.produk.update', $produk->id_produk) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-900 mb-2">Nama Produk</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $produk->nama) }}"
                           placeholder="Masukkan nama produk"
                           class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:outline-none" required>
                </div>

                <div class="row-span-2">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-900 mb-2">Deskripsi Produk</label>
                    <textarea id="deskripsi" name="deskripsi" rows="9"
                              class="w-full px-4 py-3 border border-[#5BC6BC] rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:outline-none resize-none">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-900 mb-2">
                        Kategori & Sub-Kategori
                    </label>

                    <div class="mb-4">
                        <label for="parent_id" class="block text-xs font-medium text-gray-500 mb-1">
                            Kategori Utama
                        </label>
                        <div class="flex gap-2">
                            <select id="parent_id" name="parent_id"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#5BC6BC] outline-none">
                                <option value="">Pilih Kategori Utama</option>
                                @foreach($parentCategories as $category)
                                <option value="{{ $category->id_kategori }}"
                                        data-children="{{ json_encode($category->children) }}"
                                        {{ ($currentParentId == $category->id_kategori) ? 'selected' : '' }}>
                                        {{ $category->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <a href="{{ route('admin.kategori.create') }}"
                               class="px-4 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] flex items-center justify-center font-bold"
                               title="Buat Kategori Utama Baru">
                                +
                            </a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="id_kategori" class="block text-xs font-medium text-gray-500 mb-1">
                            Sub-Kategori
                        </label>
                        <div class="flex gap-2">
                            <select id="id_kategori" name="id_kategori"
                                    data-selected="{{ $currentSubId ?? '' }}"
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-[#5BC6BC] outline-none disabled:bg-gray-100 disabled:cursor-not-allowed"
                                    disabled required>
                                <option value="">Pilih Kategori Utama Dulu</option>
                            </select>

                            <a id="btn-add-subkategori" href="#"
                               class="px-4 py-3 bg-gray-300 text-gray-500 rounded-lg flex items-center justify-center font-bold pointer-events-none cursor-not-allowed transition-colors"
                               title="Tambah Sub Kategori di Kategori Ini">
                                +
                            </a>
                        </div>
                        @error('id_kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 mt-10">
                <a href="{{ $kategori ? route('admin.produk.index', ['kategori' => $kategori->id_kategori]) : route('admin.produk.selectKategori') }}"
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const parentSelect = document.getElementById('parent_id');
        const subCategorySelect = document.getElementById('id_kategori');
        const btnAddSubkategori = document.getElementById('btn-add-subkategori');
        const routePattern = "{{ route('admin.kategori.subkategori.create', 'PLACEHOLDER_ID') }}";
        const preSelectedSubId = subCategorySelect.getAttribute('data-selected');

        function updateUIState(selectedOption) {
            if (!selectedOption || !selectedOption.value) {
                subCategorySelect.innerHTML = '<option value="">Pilih Kategori Utama Dulu</option>';
                subCategorySelect.disabled = true;
                
                btnAddSubkategori.href = "#";
                btnAddSubkategori.classList.add('bg-gray-300', 'text-gray-500', 'pointer-events-none', 'cursor-not-allowed');
                btnAddSubkategori.classList.remove('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]');
                return;
            }

            const parentId = selectedOption.value;

            subCategorySelect.disabled = false;
            subCategorySelect.innerHTML = '<option value="">Pilih Sub-Kategori</option>';
            const newUrl = routePattern.replace('PLACEHOLDER_ID', parentId);
            btnAddSubkategori.href = newUrl;
            
            btnAddSubkategori.classList.remove('bg-gray-300', 'text-gray-500', 'pointer-events-none', 'cursor-not-allowed');
            btnAddSubkategori.classList.add('bg-[#5BC6BC]', 'text-white', 'hover:bg-[#4aa89e]');

            const childrenData = selectedOption.getAttribute('data-children');
            if (childrenData) {
                try {
                    const children = JSON.parse(childrenData);
                    children.forEach(child => {
                        const option = document.createElement('option');
                        option.value = child.id_kategori; 
                        option.textContent = child.nama_kategori;
                        
                        if (String(child.id_kategori) === String(preSelectedSubId)) {
                            option.selected = true;
                        }
                        subCategorySelect.appendChild(option);
                    });
                } catch (e) {
                    console.error('JSON Error:', e);
                }
            }
        }

        parentSelect.addEventListener('change', function() {
            subCategorySelect.setAttribute('data-selected', ''); 
            
            const selectedOption = this.options[this.selectedIndex];
            updateUIState(selectedOption);
        });

        if (parentSelect.value) {
            const selectedOption = parentSelect.options[parentSelect.selectedIndex];
            updateUIState(selectedOption);
        }
    });
</script>
@endsection
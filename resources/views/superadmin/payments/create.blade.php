@extends('layouts.layout_admin')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="space-y-6">

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li><span class="text-gray-500">Dashboard</span></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li><a href="{{ route('superadmin.payments.index') }}" class="text-gray-500 hover:text-[#5BC6BC]">Metode Pembayaran</a></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="font-bold text-gray-800">Tambah Baru</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-50 bg-gray-50 flex items-center gap-3">
            <div class="p-2 bg-[#5BC6BC]/10 rounded-lg">
                <i class="fas fa-wallet text-[#5BC6BC]"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-700">Detail Pembayaran</h3>
                <p class="text-xs text-gray-500">Tambahkan opsi pembayaran baru untuk pelanggan.</p>
            </div>
        </div>

        <div class="p-8">
            {{-- Tampilkan Error Validasi Global --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <p class="font-bold text-red-700 text-sm">Gagal Menyimpan</p>
                    <ul class="list-disc list-inside text-xs text-red-600 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('superadmin.payments.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="col-span-2">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                            Nama Bank / E-Wallet <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_bank" value="{{ old('nama_bank') }}" 
                               placeholder="Contoh: BCA, Mandiri, atau GoPay" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                            Jenis Metode <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="jenis" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none appearance-none bg-white transition-colors">
                                <option value="COD" {{ old('jenis') == 'COD' ? 'selected' : '' }}>COD</option>
                                <option value="Bank Transfer" {{ old('jenis') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="E-Wallet" {{ old('jenis') == 'E-Wallet' ? 'selected' : '' }}>E-Wallet</option>
                                <option value="QRIS" {{ old('jenis') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div id="rekening-field">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                            Nomor Rekening <span class="text-red-500 required-mark">*</span>
                        </label>
                        <input type="number" name="nomor_rekening" value="{{ old('nomor_rekening') }}" 
                               placeholder="Contoh: 1234567890" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors">
                        <p class="text-[10px] text-gray-400 mt-1">* Kosongkan jika QRIS</p>
                    </div>

                    <div class="col-span-2" id="atas-nama-field">
                        <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                            Atas Nama (Pemilik Rekening) <span class="text-red-500 required-mark">*</span>
                        </label>
                        <input type="text" name="atas_nama" value="{{ old('atas_nama') }}" 
                               placeholder="Nama pemilik rekening sesuai buku tabungan" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none transition-colors">
                    </div>
                </div>

                <div class="mb-8" id="gambar-field">
                    <label class="block text-gray-700 text-xs font-bold mb-2 uppercase tracking-wide">
                        Logo Bank / Gambar QRIS <span class="text-red-500 required-mark">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 hover:border-[#5BC6BC] transition-colors relative group">
                        <div class="space-y-1 text-center">
                            <div class="mx-auto h-12 w-12 text-gray-400 group-hover:text-[#5BC6BC] transition-colors">
                                <i class="fas fa-cloud-upload-alt text-4xl"></i>
                            </div>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-[#5BC6BC] hover:text-[#4aa89e] focus-within:outline-none">
                                    <span>Upload file gambar</span>
                                    <input id="file-upload" name="gambar" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 hidden text-center">
                        <p class="text-xs text-gray-500 mb-2">Preview:</p>
                        <img id="preview-img" src="#" alt="Preview" class="h-24 mx-auto rounded shadow-sm object-contain border p-2">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('superadmin.payments.index') }}" 
                       class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2.5 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors font-medium shadow-lg shadow-teal-500/20 flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Metode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const imageField = document.getElementById("preview-img");
        const previewContainer = document.getElementById("image-preview");

        reader.onload = function(){
            if(reader.readyState == 2){
                imageField.src = reader.result;
                previewContainer.classList.remove("hidden");
            }
        }

        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }

    // Toggle fields based on payment method
    document.addEventListener('DOMContentLoaded', function() {
        const jenisSelect = document.querySelector('select[name="jenis"]');
        const rekeningField = document.getElementById('rekening-field');
        const atasNamaField = document.getElementById('atas-nama-field');
        const gambarField = document.getElementById('gambar-field');
        const requiredMarks = document.querySelectorAll('.required-mark');

        function toggleFields() {
            const isCOD = jenisSelect.value.toUpperCase() === 'COD';
            
            if (isCOD) {
                rekeningField.style.display = 'none';
                atasNamaField.style.display = 'none';
                gambarField.style.display = 'none';
                document.querySelector('input[name="nomor_rekening"]').removeAttribute('required');
                document.querySelector('input[name="atas_nama"]').removeAttribute('required');
                document.querySelector('input[name="gambar"]').removeAttribute('required');
            } else {
                rekeningField.style.display = 'block';
                atasNamaField.style.display = 'block';
                gambarField.style.display = 'block';
            }
        }

        jenisSelect.addEventListener('change', toggleFields);
        toggleFields(); // Run on page load
    });
</script>
@endsection
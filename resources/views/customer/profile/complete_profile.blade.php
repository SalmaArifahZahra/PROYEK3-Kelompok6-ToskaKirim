@extends('layouts.layout_customer')

@section('content')
<div class="max-w-lg mx-auto bg-white shadow-md rounded-xl p-8 my-10">

    <h2 class="text-center font-bold text-[#5BC6BC] text-2xl mb-2">
        Lengkapi Data Diri
    </h2>
    <p class="text-center text-gray-600 mb-8">
        Hanya satu langkah lagi! Masukkan alamat pengiriman utama Anda.
    </p>

    <form action="{{ route('customer.alamat.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="label_alamat" class="block text-gray-700 font-medium mb-1">Label Alamat</label>
            <input type="text" id="label_alamat" name="label_alamat" value="{{ old('label_alamat', 'Rumah') }}" required
                   placeholder="Contoh: Rumah, Kantor"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none @error('label_alamat') border-red-500 @enderror">
            @error('label_alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="nama_penerima" class="block text-gray-700 font-medium mb-1">Nama Penerima</label>
                 <input type="text" id="nama_penerima" name="nama_penerima" value="{{ old('nama_penerima', auth()->check() ? auth()->user()->nama : session('temp_user_name')) }}" required
                   placeholder="Masukkan nama penerima"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none @error('nama_penerima') border-red-500 @enderror">
            @error('nama_penerima')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="telepon_penerima" class="block text-gray-700 font-medium mb-1">Nomor Telepon Penerima</label>
            <input type="tel" id="telepon_penerima" name="telepon_penerima" value="{{ old('telepon_penerima') }}" required
                   placeholder="Contoh: 08123456789"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none @error('telepon_penerima') border-red-500 @enderror">
            @error('telepon_penerima')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="my-6">

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Provinsi</label>
            <select id="provinsi" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                <option value="">Pilih Provinsi</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Kota/Kabupaten</label>
            <select id="kota_kabupaten_select" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                <option value="">Pilih Kota/Kabupaten</option>
            </select>
        </div>
        <input type="hidden" name="kota_kabupaten" id="kota_kabupaten_hidden">
        @error('kota_kabupaten')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror


        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Kecamatan</label>
            <select id="kecamatan_select" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                <option value="">Pilih Kecamatan</option>
            </select>
        </div>
        <input type="hidden" name="kecamatan" id="kecamatan_hidden">
        @error('kecamatan')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror


        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Kelurahan</label>
            <select id="kelurahan_select" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                <option value="">Pilih Kelurahan</option>
            </select>
        </div>
        <input type="hidden" name="kelurahan" id="kelurahan_hidden">
        @error('kelurahan')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <hr class="my-6">

        <div class="mb-4">
            <label for="jalan_patokan" class="block text-gray-700 font-medium mb-1">Nama Jalan & Patokan</label>
            <textarea id="jalan_patokan" name="jalan_patokan" rows="3" required
                      placeholder="Contoh: Jl. Merdeka No. 5, depan gapura warna hijau"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none @error('jalan_patokan') border-red-500 @enderror">{{ old('jalan_patokan') }}</textarea>
            @error('jalan_patokan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label for="no_rumah" class="block text-gray-700 font-medium mb-1">No. Rumah</label>
                <input type="text" id="no_rumah" name="no_rumah" value="{{ old('no_rumah') }}"
                       placeholder="No. 12 B"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none @error('no_rumah') border-red-500 @enderror">
            </div>

            <div>
                <label for="rt" class="block text-gray-700 font-medium mb-1">RT</label>
                <input type="text" id="rt" name="rt" value="{{ old('rt') }}"
                       placeholder="001"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none @error('rt') border-red-500 @enderror">
            </div>
            <div>
                <label for="rw" class="block text-gray-700 font-medium mb-1">RW</label>
                <input type="text" id="rw" name="rw" value="{{ old('rw') }}"
                       placeholder="002"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none @error('rw') border-red-500 @enderror">
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-8">
            <button type="submit" class="w-full px-5 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#3A767E] transition font-medium">
                Simpan dan Lanjutkan
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Script dimulai...');
        
        const provinsiSelect = document.getElementById('provinsi');
        const kotaSelect = document.getElementById('kota_kabupaten_select');
        const kecamatanSelect = document.getElementById('kecamatan_select');
        const kelurahanSelect = document.getElementById('kelurahan_select');

        console.log('Select elements:', {
            provinsi: provinsiSelect,
            kota: kotaSelect,
            kecamatan: kecamatanSelect,
            kelurahan: kelurahanSelect
        });

        const kotaHidden = document.getElementById('kota_kabupaten_hidden');
        const kecamatanHidden = document.getElementById('kecamatan_hidden');
        const kelurahanHidden = document.getElementById('kelurahan_hidden');

        const API_URL = '/api/wilayah';

        async function fetchData(endpoint, selectElement, placeholder) {
            try {
                console.log(`Fetching: ${API_URL}/${endpoint}`);
                
                const response = await fetch(`${API_URL}/${endpoint}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                console.log(`Response from ${endpoint}:`, result);
                
                const data = result.data || result || [];
                console.log(`Data array:`, data);
                
                selectElement.innerHTML = `<option value="">Pilih ${placeholder}</option>`;
                
                if (Array.isArray(data)) {
                    console.log(`Adding ${data.length} items to select`);
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        selectElement.appendChild(option);
                    });
                    console.log(`Select updated with ${data.length} options`);
                } else {
                    console.error('Data is not an array:', data);
                    selectElement.innerHTML = `<option value="">Data format error</option>`;
                }
            } catch (error) {
                console.error(`Error fetching ${endpoint}:`, error);
                selectElement.innerHTML = `<option value="">Error: ${error.message}</option>`;
            }
        }

        console.log('Loading provinces...');
        fetchData('provinces', provinsiSelect, 'Provinsi');

        provinsiSelect.addEventListener('change', function () {
            console.log('Province changed:', this.value);
            if (this.value) {
                fetchData(`regencies/${this.value}`, kotaSelect, 'Kota/Kabupaten');
                kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                kotaHidden.value = '';
                kecamatanHidden.value = '';
                kelurahanHidden.value = '';
            }
        });

        kotaSelect.addEventListener('change', function () {
            console.log('City changed:', this.value);
            if (this.value) {
                fetchData(`districts/${this.value}`, kecamatanSelect, 'Kecamatan');
                kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                
                const selectedText = this.options[this.selectedIndex].text;
                kotaHidden.value = selectedText;
                
                kecamatanHidden.value = '';
                kelurahanHidden.value = '';
            }
        });

        kecamatanSelect.addEventListener('change', function () {
            console.log('District changed:', this.value);
            if (this.value) {
                fetchData(`villages/${this.value}`, kelurahanSelect, 'Kelurahan');
                
                const selectedText = this.options[this.selectedIndex].text;
                kecamatanHidden.value = selectedText;
                
                kelurahanHidden.value = '';
            }
        });
        
        kelurahanSelect.addEventListener('change', function () {
            console.log('Village changed:', this.value);
            if (this.value) {
                const selectedText = this.options[this.selectedIndex].text;
                kelurahanHidden.value = selectedText;
            }
        });
    });
</script>
@endpush
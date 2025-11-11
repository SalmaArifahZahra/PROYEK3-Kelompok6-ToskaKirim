@extends('layouts.layout_customer')

@section('content')
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-xl p-8">

        <h2 class="text-center font-bold text-[#33A1E0] text-2xl mb-2">
            Complete Your Profile
        </h2>
        <p class="text-center text-gray-600 mb-6">
            Add your personal details to enhance your shopping 
        </p>


        <form action="/customer/profile" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Name</label>
                <input type="text" name="nama" required placeholder="Enter your full name"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Alamat</label>
                <input type="text" name="alamat" required placeholder="Enter your address"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Provinsi</label>
                <select name="provinsi" id="provinsi" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Provinsi</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Kota/Kabupaten</label>
                <select name="kota" id="kota" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Kota/Kabupaten</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Kecamatan</label>
                <select name="kecamatan" id="kecamatan" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Kecamatan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">Kelurahan</label>
                <select name="kelurahan" id="kelurahan" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    <option value="">Pilih Kelurahan</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">RT / RW</label>
                <input type="text" name="rtrw" required placeholder="Contoh: 05 / 02"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 focus:border-blue-500 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="/customer/home"
                    class="px-5 py-2.5 border border-[#0b5fa4] text-[#0b5fa4] rounded-lg hover:bg-blue-50 transition">
                    Later
                </a>

                <button type="submit" class="px-5 py-2.5 bg-[#0b5fa4] text-white rounded-lg hover:bg-[#094c82] transition">
                    Simpan
                </button>
            </div>
        </form>

    </div>
@endsection

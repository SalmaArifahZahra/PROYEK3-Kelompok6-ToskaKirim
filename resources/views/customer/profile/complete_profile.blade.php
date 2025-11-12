@extends('layouts.layout_customer')

@section('content')
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-xl p-8">

        <h2 class="text-center font-bold text-[#5BC6BC] text-2xl mb-2">
            Lengkapi Data Diri
        </h2>
        <p class="text-center text-gray-600 mb-6">
            Masukkan Data diri anda untuk Berbelanja
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
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-700 font-medium mb-1">RT</label>
                        <input type="text" name="rt" placeholder="RT" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-1">RW</label>
                        <input type="text" name="rw" placeholder="RW" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:border-blue-500 focus:ring-blue-500 outline-none">
                    </div>
                </div>
            </div>


            <div class="flex justify-end gap-3 mt-6">
                <a href="/customer/home"
                    class="px-5 py-2.5 border border-[#5BC6BC] text-[#5BC6BC] rounded-lg hover:bg-blue-50 transition">
                    Later
                </a>

                <button type="submit" class="px-5 py-2.5 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#3A767E] transition">
                    Simpan
                </button>
            </div>
        </form>

    </div>
@endsection

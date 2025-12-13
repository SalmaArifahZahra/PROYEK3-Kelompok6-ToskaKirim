@extends('layouts.layout_admin')

@section('title', 'Tambah Metode Pembayaran')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6 text-gray-700">Detail Pembayaran</h2>
    
    <form action="{{ route('superadmin.payments.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Bank / E-Wallet</label>
            <input type="text" name="nama_bank" placeholder="Contoh: BCA / GoPay" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis</label>
            <select name="jenis" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]">
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="QRIS">QRIS</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Rekening (Opsional untuk QRIS)</label>
            <input type="text" name="nomor_rekening" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Logo Bank / Gambar QRIS</label>
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:bg-gray-50 cursor-pointer relative">
                <input type="file" name="gambar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                <div class="text-gray-500">
                    <i class="fas fa-cloud-upload-alt text-2xl mb-2"></i>
                    <p>Klik untuk upload gambar</p>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('superadmin.payments.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#2A9D8F] text-white rounded-lg hover:bg-[#21867a]">Simpan</button>
        </div>
    </form action="{{ route('superadmin.payments.store') }}" method="POST">
</div>
@endsection
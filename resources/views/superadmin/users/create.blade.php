@extends('layouts.layout_superadmin')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-6 text-gray-700">Form Admin</h2>
    
    <form action="{{ route('superadmin.users.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#2A9D8F]" required>
        </div>

        <input type="hidden" name="peran" value="admin">

        <div class="flex justify-end gap-4 mt-6">
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700">Batal</a>
            <button type="submit" class="px-6 py-2 bg-[#2A9D8F] text-white rounded-lg hover:bg-[#21867a]">Simpan</button>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
                <strong class="font-bold">Ada kesalahan input!</strong>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
    <form action="{{ route('superadmin.users.store') }}" method="POST">
</div>
@endsection

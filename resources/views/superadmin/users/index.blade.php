@extends('layouts.layout_admin')

@section('title', 'Daftar Admin')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-700">Kelola Admin</h2>
        <a href="{{ route('superadmin.users.create') }}" class="px-4 py-2 bg-[#2A9D8F] text-white rounded hover:bg-[#21867a]">
            + Tambah Admin
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($admins as $admin)
            <div class="bg-white p-6 rounded-lg shadow-md text-center">
                <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center mb-4 text-2xl text-gray-500">
                    {{ strtoupper(substr($admin->nama, 0, 1)) }}
                </div>
                
                <h3 class="text-lg font-bold text-gray-800">{{ $admin->nama }}</h3>
                <p class="text-gray-500 text-sm mb-4">{{ $admin->email }}</p>
                
                <div class="flex justify-center space-x-2">
                    <a href="#" class="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600">Edit</a>
                    <form action="#" method="POST" onsubmit="return confirm('Hapus admin ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-3 text-center">Belum ada data admin.</p>
        @endforelse
    </div>
@endsection
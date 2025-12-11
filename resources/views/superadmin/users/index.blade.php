@extends('layouts.layout_admin')

@section('title', 'Daftar Admin')

@section('content')
<div class="space-y-6">

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li><span class="text-gray-500">Master Data & Kontrol</span></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="font-bold text-gray-800">Kelola Admin</li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row justify-between items-center bg-white p-6 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center gap-3 mb-4 md:mb-0">
            <div class="p-2 bg-[#5BC6BC]/10 rounded-lg">
                <i class="fas fa-users-cog text-[#5BC6BC] text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">Daftar Administrator</h2>
                <p class="text-sm text-gray-500">Kelola akun yang memiliki akses ke dashboard.</p>
            </div>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="bg-[#5BC6BC] hover:bg-[#4aa89e] text-white px-4 py-2.5 rounded-lg transition-colors font-medium flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Admin
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded shadow-sm flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500"></i>
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($admins as $admin)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow overflow-hidden group">
                <div class="p-6 text-center">
                    <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4 text-3xl font-bold text-[#5BC6BC] group-hover:bg-[#5BC6BC] group-hover:text-white transition-colors">
                        {{ strtoupper(substr($admin->nama, 0, 1)) }}
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $admin->nama }}</h3>
                    <p class="text-gray-500 text-sm mb-6 bg-gray-50 inline-block px-3 py-1 rounded-full border border-gray-100">{{ $admin->email }}</p>
                    
                    <div class="flex justify-center items-center gap-3 border-t border-gray-100 pt-4">
                        <a href="#" class="text-yellow-500 hover:text-yellow-600 px-3 py-1 rounded hover:bg-yellow-50 transition-colors" title="Edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="#" method="POST" onsubmit="return confirm('Hapus admin ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-600 px-3 py-1 rounded hover:bg-red-50 transition-colors" title="Hapus">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 bg-white rounded-lg border border-gray-200 border-dashed">
                <i class="fas fa-user-slash text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Belum ada data admin.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
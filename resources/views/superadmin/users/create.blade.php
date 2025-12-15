@extends('layouts.layout_admin')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class=" mx-auto">

    {{-- Header & Tombol Kembali --}}
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Admin</h1>
        <a href="{{ route('superadmin.users.index') }}" 
           class="text-slate-500 hover:text-slate-700 flex items-center gap-2 text-sm font-medium transition-colors">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Card Form --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        
        <div class="p-8">
            {{-- Alert Error Global --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-r">
                    <p class="font-bold text-sm">Terdapat kesalahan:</p>
                    <ul class="list-disc list-inside text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('superadmin.users.store') }}" method="POST">
                @csrf
                
                {{-- Input Nama --}}
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-slate-400"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-[#5BC6BC] focus:ring-1 focus:ring-[#5BC6BC] transition-colors @error('name') border-red-500 @enderror" 
                               placeholder="Masukkan nama lengkap" required>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Email --}}
                <div class="mb-5">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-[#5BC6BC] focus:ring-1 focus:ring-[#5BC6BC] transition-colors @error('email') border-red-500 @enderror" 
                               placeholder="contoh@email.com" required>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Password --}}
                <div class="mb-6">
                    <label class="block text-slate-700 text-sm font-bold mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password"
                               class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:outline-none focus:border-[#5BC6BC] focus:ring-1 focus:ring-[#5BC6BC] transition-colors @error('password') border-red-500 @enderror" 
                               placeholder="Masukkan password kuat" required>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">* Minimal 8 karakter</p>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <input type="hidden" name="peran" value="admin">

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <a href="{{ route('superadmin.users.index') }}" 
                       class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 text-sm font-bold text-white bg-[#5BC6BC] rounded-lg hover:bg-[#4aa89f] shadow-md shadow-teal-500/20 transition-all transform hover:scale-[1.02]">
                        <i class="fas fa-save mr-2"></i> Simpan Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
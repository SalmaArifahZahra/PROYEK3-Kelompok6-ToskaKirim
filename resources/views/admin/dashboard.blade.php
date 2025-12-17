@extends('layouts.layout_admin')

@section('title', 'Dashboard Admin')

@section('page-title', 'Dashboard')

@section('content')

<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-[#5BC6BC] mb-2">Selamat Datang di Dashboard Admin</h2>
        <p class="text-gray-600">Kelola aplikasi ToskaKirim dengan mudah dari sini.</p>
    </div>

    {{-- Statistik Ringkas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-blue-500">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Kategori Produk</h3>
                <p class="text-3xl font-bold text-blue-600">{{ $totalKategori }}</p>
            </div>
            <div class="text-4xl text-blue-200">
                <i class="fas fa-tags"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-purple-500">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Produk</h3>
                <p class="text-3xl font-bold text-purple-600">{{ $totalProduk }}</p>
            </div>
            <div class="text-4xl text-purple-200">
                <i class="fas fa-box"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-orange-500">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h3>
                <p class="text-3xl font-bold text-orange-600">{{ $totalMetodePembayaran }}</p>
            </div>
            <div class="text-4xl text-orange-200">
                <i class="fas fa-credit-card"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-green-500">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Kesiapan Operasional</h3>
                <p class="text-3xl font-bold text-green-600">{{ $completedCount }}/{{ $totalRequired }}</p>
            </div>
            <div class="text-4xl text-green-200">
                <i class="fas fa-tasks"></i>
            </div>
        </div>
    </div>

    {{-- Checklist Data Penting --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-[#5BC6BC] to-[#4aa89e] px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <i class="fas fa-list-check"></i>
                Checklist Kesiapan Operasional Toko
            </h2>
        </div>

        <!-- Warning Banner -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-400 px-6 py-4 border-l-4 border-orange-600">
            <div class="flex items-center gap-3">
                <div class="text-3xl">
                    <i class="fas fa-exclamation-triangle text-orange-900"></i>
                </div>
                <p class="text-orange-900 font-bold text-lg">
                    ⚠️ PERHATIAN: Pastikan semua data penting sudah terisi agar customer dapat berbelanja dengan lancar!
                </p>
            </div>
        </div>

        <div class="divide-y">
            @foreach ($checklist as $item)
                @php
                    $isComplete = $item['count'] > 0;
                    $canManage = $item['managed_by'] === 'admin';
                    $statusColor = $isComplete ? 'bg-green-50 border-l-4 border-green-500' : ($item['required'] ? 'bg-red-50 border-l-4 border-red-500' : 'bg-gray-50 border-l-4 border-gray-300');
                    $iconColor = $isComplete ? 'text-green-500' : ($item['required'] ? 'text-red-500' : 'text-gray-400');
                    $checkIcon = $isComplete ? 'fa-check-circle' : 'fa-exclamation-circle';
                @endphp
                <div class="p-6 {{ $statusColor }} transition-all hover:shadow-md">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="text-3xl {{ $iconColor }}">
                                <i class="fas {{ $item['icon'] }}"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-lg font-semibold text-gray-800">{{ $item['title'] }}</h3>
                                    @if ($item['required'])
                                        <span class="px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded-full">
                                            WAJIB
                                        </span>
                                    @endif
                                    @if (!$canManage)
                                        <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full">
                                            <i class="fas fa-shield-alt"></i> Dikelola Superadmin
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $item['description'] }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="text-center">
                                <div class="text-3xl font-bold {{ $isComplete ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $item['count'] }}
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    @if ($isComplete)
                                        <i class="fas {{ $checkIcon }} text-green-500"></i> Lengkap
                                    @else
                                        <i class="fas {{ $checkIcon }} text-red-500"></i> 
                                        {{ $item['required'] ? 'Belum Diisi' : 'Kosong' }}
                                    @endif
                                </p>
                            </div>
                            @if ($canManage)
                                <a href="{{ $item['url'] }}" 
                                   class="px-4 py-2 rounded font-medium transition {{ $isComplete ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-[#5BC6BC] hover:bg-[#4aa89e] text-white' }}">
                                    {{ $isComplete ? 'Lihat' : 'Atur' }}
                                </a>
                            @else
                                <div class="px-4 py-2 rounded font-medium bg-gray-200 text-gray-500 cursor-not-allowed" title="Hanya Superadmin yang dapat mengelola">
                                    <i class="fas fa-lock"></i> Terkunci
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Progress Bar --}}
        <div class="px-6 py-4 bg-gray-50 border-t">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-gray-700">Progress Kesiapan Operasional</span>
                <span class="text-sm font-bold text-gray-800">{{ $totalRequired > 0 ? round(($completedCount / $totalRequired) * 100) : 0 }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-green-400 to-[#5BC6BC] h-3 rounded-full transition-all duration-300"
                     style="width: {{ $totalRequired > 0 ? ($completedCount / $totalRequired) * 100 : 0 }}%">
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">
                @if ($completedCount === $totalRequired)
                    <i class="fas fa-check-circle text-green-500"></i> Semua data penting sudah lengkap! Toko siap untuk operasional.
                @else
                    <i class="fas fa-info-circle text-orange-500"></i> Masih ada {{ $totalRequired - $completedCount }} data penting yang perlu dilengkapi.
                @endif
            </p>
        </div>
    </div>
</div>

@endsection

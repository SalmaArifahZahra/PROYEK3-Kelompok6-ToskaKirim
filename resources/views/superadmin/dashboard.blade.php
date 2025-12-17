@extends('layouts.layout_admin')

@section('title', 'Dashboard Superadmin')

@section('content')
    <div class="space-y-6">
        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-teal-500">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Admin</h3>
                    <p class="text-3xl font-bold text-teal-600">{{ $totalAdmin }}</p>
                </div>
                <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 transition">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-orange-500">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h3>
                    <p class="text-3xl font-bold text-orange-600">{{ $totalPayment }}</p>
                </div>
                <a href="{{ route('superadmin.payments.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600 transition">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between border-l-4 border-blue-500">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Kesiapan Operasional</h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $completedCount }}/{{ $totalRequired }}</p>
                </div>
                <div class="text-4xl text-blue-200">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
        </div>

        {{-- Checklist Data Penting --}}
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-teal-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-list-check"></i>
                    Checklist Kesiapan Operasional
                </h2>
                <p class="text-teal-100 text-sm mt-1">Pastikan semua data penting sudah terisi agar customer dapat berbelanja dengan lancar</p>
            </div>

            <div class="divide-y">
                @foreach ($checklist as $item)
                    @php
                        $isComplete = $item['count'] > 0;
                        $statusColor = $isComplete ? 'bg-green-50 border-l-4 border-green-500' : ($item['required'] ? 'bg-red-50 border-l-4 border-red-500' : 'bg-gray-50 border-l-4 border-gray-300');
                        $badgeColor = $isComplete ? 'bg-green-100 text-green-700' : ($item['required'] ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700');
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
                                <a href="{{ $item['url'] }}" 
                                   class="px-4 py-2 rounded font-medium transition {{ $isComplete ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-blue-500 hover:bg-blue-600 text-white' }}">
                                    {{ $isComplete ? 'Lihat' : 'Atur' }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Progress Bar --}}
            <div class="px-6 py-4 bg-gray-50 border-t">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Progress Kesiapan</span>
                    <span class="text-sm font-bold text-gray-800">{{ round(($completedCount / $totalRequired) * 100) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-green-400 to-teal-500 h-3 rounded-full transition-all duration-300"
                         style="width: {{ ($completedCount / $totalRequired) * 100 }}%">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
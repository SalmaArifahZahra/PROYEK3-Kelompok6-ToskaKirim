@extends('layouts.layout_admin')

@section('title', 'Control Tower')

@section('content')
<div class="space-y-8 pb-10">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Omzet</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-coins"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Pelanggan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomer }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Tim Admin</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAdmin }}</h3>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-user-shield"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Coverage Area</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $persenLogistik }}%</h3>
                </div>
            </div>
            <div class="w-12 h-12 {{ $persenLogistik < 100 ? 'bg-orange-50 text-orange-500' : 'bg-teal-50 text-[#2A9D8F]' }} rounded-xl flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="px-8 py-6 border-b border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-server text-[#2A9D8F]"></i> Status Operasional Sistem
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Pastikan indikator di bawah berwarna hijau agar toko berjalan optimal.
                </p>
            </div>
            
            <div class="w-full md:w-1/3">
                <div class="flex justify-between text-xs font-bold text-gray-500 mb-2">
                    <span>Kesiapan Toko</span>
                    <span>{{ $progressPersen }}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5">
                    <div class="bg-gradient-to-r from-[#2A9D8F] to-teal-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $progressPersen }}%"></div>
                </div>
            </div>
        </div>

        <div class="p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
            @foreach($checklist as $item)
                @php
                    $isComplete = $item['count'] > 0;
                    // Mapping warna class Tailwind
                    $colors = [
                        'teal' => ['bg' => 'bg-teal-50', 'text' => 'text-teal-600', 'border' => 'border-teal-200'],
                        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-200'],
                        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'border' => 'border-indigo-200'],
                        'orange' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-200'],
                        'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-200'],
                    ];
                    $theme = $colors[$item['color']] ?? $colors['teal'];
                    
                    // Jika belum lengkap, ubah jadi abu-abu/merah
                    if(!$isComplete) {
                         $theme = ['bg' => 'bg-red-50', 'text' => 'text-red-500', 'border' => 'border-red-200'];
                    }
                @endphp

                <a href="{{ $item['url'] }}" class="group block relative p-5 rounded-xl border {{ $isComplete ? 'border-gray-100 hover:border-[#2A9D8F]' : 'border-red-200 hover:border-red-400' }} transition-all hover:shadow-md bg-white">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg {{ $theme['bg'] }} {{ $theme['text'] }} flex items-center justify-center text-lg">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                        @if($isComplete)
                            <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        @else
                            <i class="fas fa-exclamation-circle text-red-500 text-lg animate-pulse"></i>
                        @endif
                    </div>
                    
                    <h3 class="font-bold text-gray-700 text-sm group-hover:text-[#2A9D8F] transition-colors">
                        {{ $item['title'] }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-1 mb-3">
                        {{ $item['desc'] }}
                    </p>

                    <div class="text-xs font-bold {{ $isComplete ? 'text-gray-800' : 'text-red-500' }}">
                        Data: {{ $item['count'] }}
                        @if(isset($item['target']))
                            <span class="text-gray-400 font-normal">/ {{ $item['target'] }}</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>


    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Tim Admin Terbaru</h3>
            <a href="{{ route('superadmin.users.index') }}" class="text-sm font-bold text-[#2A9D8F] hover:underline">Kelola Semua &rarr;</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-8 py-4">Nama</th>
                        <th class="px-8 py-4">Email</th>
                        <th class="px-8 py-4 text-right">Bergabung</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($latestAdmins as $admin)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-8 py-4 font-medium text-gray-800 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                {{ substr($admin->nama, 0, 1) }}
                            </div>
                            {{ $admin->nama }}
                        </td>
                        <td class="px-8 py-4 text-gray-500">{{ $admin->email }}</td>
                        <td class="px-8 py-4 text-right text-gray-400 text-xs">
                            {{ $admin->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-8 py-8 text-center text-gray-400 italic">
                            Belum ada admin lain selain Anda.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
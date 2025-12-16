@extends('layouts.layout_admin')

@section('title', 'Control Tower')

@section('content')
<div class="space-y-8">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Total Omzet</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-coins"></i>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Pelanggan</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomer }}</h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-users"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Tim Admin</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAdmin }} <span class="text-sm font-normal text-gray-400">Orang</span></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide">Data Jarak</p>
                <div class="flex items-end gap-2">
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $persenLogistik }}%</h3>
                    <span class="text-xs text-gray-400 mb-1">Tercover</span>
                </div>
            </div>
            <div class="w-12 h-12 {{ $persenLogistik < 100 ? 'bg-orange-100 text-orange-600' : 'bg-teal-100 text-teal-600' }} rounded-full flex items-center justify-center text-xl shadow-sm">
                <i class="fas fa-map-marked-alt"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-6 flex items-center">
                <i class="fas fa-rocket text-[#2A9D8F] mr-2"></i> Status Sistem
            </h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $tokoSiap ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <span class="text-sm text-gray-600 font-medium">Identitas Toko</span>
                    </div>
                    @if($tokoSiap)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">Siap</span>
                    @else
                        <a href="{{ route('superadmin.kontrol_toko.index') }}" class="text-xs text-red-500 hover:underline">Lengkapi</a>
                    @endif
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full {{ $persenLogistik >= 100 ? 'bg-green-500' : 'bg-orange-500' }}"></div>
                        <span class="text-sm text-gray-600 font-medium">Database Wilayah</span>
                    </div>
                    @if($persenLogistik >= 100)
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-bold">Lengkap</span>
                    @else
                        <a href="{{ route('superadmin.wilayah.index') }}" class="text-xs text-orange-500 hover:underline">Update Jarak</a>
                    @endif
                </div>

                <div class="pt-4 grid grid-cols-2 gap-3">
                    <a href="{{ route('superadmin.users.create') }}" class="flex flex-col items-center justify-center p-3 border border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-[#2A9D8F] hover:text-[#2A9D8F] transition">
                        <i class="fas fa-plus mb-1"></i>
                        <span class="text-xs">Admin Baru</span>
                    </a>
                    <a href="{{ route('superadmin.payments.create') }}" class="flex flex-col items-center justify-center p-3 border border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-[#2A9D8F] hover:text-[#2A9D8F] transition">
                        <i class="fas fa-wallet mb-1"></i>
                        <span class="text-xs">Payment Baru</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Tim Admin Terbaru</h3>
                <a href="{{ route('superadmin.users.index') }}" class="text-xs font-bold text-[#2A9D8F] hover:text-teal-700">Kelola Semua &rarr;</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-[10px] uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-3">Nama</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3 text-right">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($latestAdmins as $admin)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-3 font-medium text-gray-800 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 border border-gray-300 flex items-center justify-center text-xs font-bold text-gray-600">
                                    {{ substr($admin->nama, 0, 1) }}
                                </div>
                                {{ $admin->nama }}
                            </td>
                            <td class="px-6 py-3 text-gray-500">{{ $admin->email }}</td>
                            <td class="px-6 py-3 text-right text-gray-400 text-xs">
                                {{ $admin->created_at->format('d M Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">
                                Belum ada admin lain selain Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
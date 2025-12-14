@extends('layouts.layout_admin')

@section('title', 'Metode Pembayaran')

@section('content')
<div class="space-y-6">

    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 text-sm text-gray-700">
            <li><span class="text-gray-500">Master Data & Kontrol</span></li>
            <li><span class="mx-2 text-gray-400">/</span></li>
            <li class="font-bold text-gray-800">Metode Pembayaran</li>
        </ol>
    </nav>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-[#5BC6BC]/10 rounded-lg">
                    <i class="fas fa-wallet text-[#5BC6BC] text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-700">Daftar Rekening & E-Wallet</h3>
                    <p class="text-xs text-gray-500">Kelola tujuan transfer pembayaran manual.</p>
                </div>
            </div>
            <a href="{{ route('superadmin.payments.create') }}" class="bg-[#5BC6BC] hover:bg-[#4aa89e] text-white text-sm px-4 py-2 rounded-lg transition-colors font-medium flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Metode
            </a>
        </div>

        @if(session('success'))
            <div class="m-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider border-b border-gray-200">
                        <th class="py-4 px-6 font-semibold">Bank / E-Wallet</th>
                        <th class="py-4 px-6 font-semibold">Jenis</th>
                        <th class="py-4 px-6 font-semibold">Nomor Rekening</th>
                        <th class="py-4 px-6 font-semibold">Logo</th>
                        <th class="py-4 px-6 text-center font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6 font-medium text-gray-800">{{ $payment->nama_bank }}</td>
                            <td class="py-4 px-6">
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-600">
                                    {{ strtoupper($payment->jenis) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-mono text-gray-600">{{ $payment->nomor_rekening ?? '-' }}</td>
                            <td class="py-4 px-6">
                                @if($payment->gambar)
                                    <div class="bg-white p-1 border rounded w-fit">
                                        <img src="{{ asset('storage/' . $payment->gambar) }}" class="h-8 w-auto object-contain" alt="Logo">
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs italic">No Image</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form action="{{ route('superadmin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Hapus metode ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-red-50" title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-wallet text-4xl text-gray-200 mb-3"></i>
                                    <p>Belum ada metode pembayaran.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
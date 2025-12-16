@extends('layouts.layout_customer')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="max-w-5xl mx-auto my-8 px-4">

        <h1 class="text-2xl font-bold text-slate-800 mb-6">Pesanan Saya</h1>
        {{-- Navigasi Pesanan --}}
        <div class="flex overflow-x-auto gap-2 mb-6 pb-2 border-b border-slate-200 no-scrollbar">
            <a href="{{ route('customer.pesanan.index') }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ !$status ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Semua
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'menunggu_pembayaran']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'menunggu_pembayaran' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu Pembayaran
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'menunggu_verifikasi']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'menunggu_verifikasi' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Menunggu Verifikasi
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'diproses']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'diproses' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Diproses
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'dikirim']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'dikirim' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Dikirim
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'dibatalkan']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'dibatalkan' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Dibatalkan
            </a>

            <a href="{{ route('customer.pesanan.index', ['status' => 'selesai']) }}"
                class="whitespace-nowrap px-4 py-2 rounded-full text-sm font-medium transition-colors
           {{ $status == 'selesai' ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                Selesai
            </a>
        </div>

        <div class="space-y-4">
            @forelse ($pesananList as $pesanan)
                <div class="bg-white border border-slate-200 rounded-lg p-4 hover:shadow-md transition-shadow">


                    <div
                        class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-slate-100 pb-3 mb-3 gap-2">
                        <div class="flex items-center gap-2 text-sm">
                            <span class="font-bold text-slate-700">ID Pesanan-{{ $pesanan->id_pesanan }}</span>
                            <span class="text-slate-400">•</span>
                            <span class="text-slate-500">{{ $pesanan->waktu_pesanan->format('d M Y, H:i') }}</span>
                        </div>


                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold
                        @if ($pesanan->status_pesanan == 'menunggu_pembayaran') bg-orange-100 text-orange-600
                        @elseif($pesanan->status_pesanan == 'menunggu_verifikasi') bg-blue-100 text-blue-600
                        @elseif($pesanan->status_pesanan == 'diproses') bg-indigo-100 text-indigo-600
                        @elseif($pesanan->status_pesanan == 'dikirim') bg-teal-100 text-teal-600
                        @elseif($pesanan->status_pesanan == 'selesai') bg-green-100 text-green-600
                        @elseif($pesanan->status_pesanan == 'dibatalkan') bg-red-100 text-red-600 
                        @endif">
                            {{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan->value)) }}
                        </span>
                    </div>


                    <div class="flex gap-4 items-center">
                        @php
                            $firstDetail = $pesanan->detail->first();
                            $firstProduct = $firstDetail ? $firstDetail->produkDetail->produk : null;
                        @endphp

                        <img src="{{ $firstProduct->foto_url ?? asset('images/icon_toska.png') }}"
                            class="w-16 h-16 rounded object-cover border border-slate-100">

                        <div class="flex-1">
                            <h4 class="font-bold text-slate-700 text-sm md:text-base">
                                {{ $firstProduct->nama ?? 'Produk' }}
                            </h4>
                            <p class="text-xs text-slate-500">
                                {{ $firstDetail->kuantitas }} barang
                                @if ($pesanan->detail->count() > 1)
                                    <span class="text-slate-400">(+{{ $pesanan->detail->count() - 1 }} produk
                                        lainnya)</span>
                                @endif
                            </p>
                        </div>

                        <div class="text-right hidden md:block">
                            <p class="text-xs text-slate-500">Total Belanja</p>
                            <p class="font-bold text-teal-600">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between items-center">
                        <div class="block md:hidden">
                            <p class="text-xs text-slate-500">Total Belanja</p>
                            <p class="font-bold text-teal-600">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="ml-auto flex gap-2">
                            @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
                                <a href="{{ route('customer.pesanan.show', $pesanan->id_pesanan) }}"
                                    class="px-4 py-2 bg-orange-500 text-white text-sm font-bold rounded hover:bg-orange-600 transition">
                                    Bayar Sekarang
                                </a>
                            @else
                                <a href="{{ route('customer.pesanan.show', $pesanan->id_pesanan) }}"
                                    class="px-4 py-2 border border-teal-600 text-teal-600 text-sm font-bold rounded hover:bg-teal-50 transition">
                                    Lihat Detail
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

            @empty
                <div class="text-center py-12 bg-slate-50 rounded-lg border border-dashed border-slate-300">
                    <i class="fas fa-shopping-bag text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500 font-medium">Belum ada pesanan</p>
                    <a href="{{ route('customer.dashboard') }}" class="text-teal-600 hover:underline text-sm mt-2 block">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>

    </div>
@endsection

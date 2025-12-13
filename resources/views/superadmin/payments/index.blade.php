@extends('layouts.layout_admin')

@section('title', 'Metode Pembayaran')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-700">Control Payment</h2>
        <a href="{{ route('superadmin.payments.create') }}" class="px-4 py-2 bg-[#2A9D8F] text-white rounded hover:bg-[#21867a]">
            + Tambah Metode
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Bank/E-Wallet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor Rekening</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gambar</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->nama_bank }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $payment->jenis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $payment->nomor_rekening ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->gambar)
                                <img src="{{ asset('storage/' . $payment->gambar) }}" class="h-10 w-auto rounded shadow-sm" alt="Logo">
                            @else
                                <span class="text-gray-400 text-xs italic">No Image</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('superadmin.payments.destroy', $payment->id) }}" method="POST" onsubmit="return confirm('Hapus metode ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2 text-gray-300 block"></i>
                            Belum ada metode pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
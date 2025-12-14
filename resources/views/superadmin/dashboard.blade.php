@extends('layouts.layout_admin')

@section('title', 'Dashboard Superadmin')

@section('content')
    <div class="grid grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Total Admin</h3>
                <p class="text-3xl font-bold text-[#2A9D8F]">{{ $totalAdmin }}</p>
            </div>
            <a href="{{ route('superadmin.users.index') }}" class="px-4 py-2 bg-[#2A9D8F] text-white rounded hover:bg-[#21867a]">Lihat</a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-700">Metode Pembayaran</h3>
                <p class="text-3xl font-bold text-[#2A9D8F]">{{ $totalPayment }}</p>
            </div>
            <a href="{{ route('superadmin.payments.index') }}" class="px-4 py-2 bg-[#2A9D8F] text-white rounded hover:bg-[#21867a]">Atur</a>
        </div>
    </div>
@endsection
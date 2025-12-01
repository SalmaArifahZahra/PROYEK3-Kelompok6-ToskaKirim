{{-- @if ($pesanan->status_pesanan == 'menunggu_pembayaran')
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 text-center">
        <p class="text-orange-600 font-medium mb-1">Selesaikan Pembayaran Dalam:</p>

        <div id="countdown-timer"
             class="text-2xl font-bold text-orange-700 tracking-wider"
             data-deadline="{{ $deadlineTimestamp }}">
             Loading...
        </div>

        <p class="text-xs text-gray-500 mt-2">
            Batas Akhir: {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }} WIB
        </p>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerEl = document.getElementById('countdown-timer');

        // Cek jika elemen ada (hanya muncul saat status menunggu_pembayaran)
        if (timerEl) {
            const deadlineTime = parseInt(timerEl.getAttribute('data-deadline'));
            const updateTimer = setInterval(function() {
                const now = new Date().getTime();

                const distance = deadlineTime - now;

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const formattedTime =
                    (hours < 10 ? "0" + hours : hours) + " : " +
                    (minutes < 10 ? "0" + minutes : minutes) + " : " +
                    (seconds < 10 ? "0" + seconds : seconds);

                timerEl.innerHTML = formattedTime;

                if (distance < 0) {
                    clearInterval(updateTimer);
                    timerEl.innerHTML = "WAKTU HABIS";
                    timerEl.classList.add('text-red-600');

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }, 1000); // Update setiap 1000ms (1 detik)
        }
    });
</script>
@endpush --}}

@extends('layouts.layout_customer')

@section('content')
<div class="max-w-4xl mx-auto py-8">


        <h1 class="text-2xl font-bold mb-6">Detail Pesanan</h1>

        @if ($pesanan->status_pesanan->value == 'menunggu_pembayaran')
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6 text-center">
                <p class="text-orange-600 font-medium mb-1">Selesaikan Pembayaran Dalam:</p>

                <div id="countdown-timer" class="text-2xl font-bold text-orange-700 tracking-wider"
                    data-deadline="{{ $deadlineTimestamp }}">
                    Loading...
                </div>

                <p class="text-xs text-gray-500 mt-2">
                    Batas Akhir: {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }} WIB
                </p>
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Pesanan</h2>

            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <p class="text-gray-500">No. Pesanan</p>
                    <p class="font-medium">{{ $pesanan->kode_pesanan }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-medium">
                        {{ $pesanan->created_at->format('d M Y H:i') }} WIB
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <span @class([
                        'px-3 py-1 rounded-full text-xs font-semibold',
                        'bg-yellow-100 text-yellow-700' =>
                            $pesanan->status_pesanan->value == 'menunggu_pembayaran',
                        'bg-blue-100 text-blue-700' => $pesanan->status_pesanan->value == 'diproses',
                        'bg-green-100 text-green-700' => $pesanan->status_pesanan->value == 'selesai',
                        'bg-gray-100 text-gray-600' => !in_array($pesanan->status_pesanan->value, [
                            'menunggu_pembayaran',
                            'diproses',
                            'selesai',
                        ]),
                    ])>
                        {{ ucfirst(str_replace('_', ' ', $pesanan->status_pesanan->value)) }}
                    </span>
                </div>

                <div>
                    <p class="text-gray-500">Total Pembayaran</p>
                    <p class="font-medium">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold mb-4">Item Pesanan</h2>

            <div class="space-y-4">
                @foreach ($pesanan->detail as $item)
                    <div class="flex items-center justify-between border-b pb-3">
                        <div>
                            <p class="font-medium">{{ $item->produk->nama ?? '' }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->jumlah }}</p>
                        </div>
                        <p class="font-semibold">
                            Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($pesanan->bukti_pembayaran)
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold mb-4">Bukti Pembayaran</h2>

                <img src="{{ asset('storage/' . $pesanan->bukti_pembayaran) }}" alt="Bukti Pembayaran"
                    class="w-64 rounded shadow">
            </div>
        @endif

        <a href="{{ route('customer.pesanan.index') }}" class="inline-block px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
            Kembali
        </a>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerEl = document.getElementById('countdown-timer');

        if (timerEl) {
            const deadlineTime = parseInt(timerEl.getAttribute('data-deadline'));

            const updateTimer = setInterval(function() {
                const now = new Date().getTime();
                const distance = deadlineTime - now;

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const formattedTime =
                    (hours < 10 ? "0" + hours : hours) + " : " +
                    (minutes < 10 ? "0" + minutes : minutes) + " : " +
                    (seconds < 10 ? "0" + seconds : seconds);

                timerEl.innerHTML = formattedTime;

                if (distance < 0) {
                    clearInterval(updateTimer);
                    timerEl.innerHTML = "WAKTU HABIS";
                    timerEl.classList.add('text-red-600');

                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                }
            }, 1000);
        }
    });
</script>
@endpush

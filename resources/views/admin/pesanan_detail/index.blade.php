@extends('layouts.layout_admin')

@section('title', 'Detail Pesanan')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Pesanan', 'url' => route('admin.pesanan.index')],
            ['label' => 'Detail Pesanan #' . str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT)]
        ]
    ])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Order Info & Items -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Order Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Informasi Pesanan</h2>
                    @php
                        $statusColors = [
                            'menunggu_pembayaran' => 'bg-yellow-100 text-yellow-800',
                            'menunggu_verifikasi' => 'bg-orange-100 text-orange-800',
                            'diproses' => 'bg-blue-100 text-blue-800',
                            'dikirim' => 'bg-purple-100 text-purple-800',
                            'selesai' => 'bg-green-100 text-green-800',
                            'dibatalkan' => 'bg-red-100 text-red-800',
                        ];
                        $statusLabels = [
                            'menunggu_pembayaran' => 'Menunggu Pembayaran',
                            'menunggu_verifikasi' => 'Menunggu Verifikasi',
                            'diproses' => 'Diproses',
                            'dikirim' => 'Dikirim',
                            'selesai' => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="px-4 py-2 text-sm font-medium rounded-full {{ $statusColors[$pesanan->status_pesanan->value] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$pesanan->status_pesanan->value] ?? $pesanan->status_pesanan->value }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">ID Pesanan</p>
                        <p class="font-semibold text-gray-900">#{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Waktu Pesanan</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->waktu_pesanan->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->user->name ?? 'Guest' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Informasi Pengiriman</h2>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama Penerima</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->penerima_nama }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Nomor Telepon</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->penerima_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Alamat Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->alamat_lengkap }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Ongkos Kirim</p>
                        <p class="font-semibold text-gray-900">
                            {{ $pesanan->ongkir->nama_ongkir ?? '-' }} - 
                            Rp {{ number_format($pesanan->ongkir->harga ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Item Pesanan</h2>
                
                <div class="space-y-4">
                    @foreach($pesanan->detail as $item)
                    <div class="flex items-center gap-4 p-4 border border-gray-200 rounded-lg">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->produkDetail->foto)
                                <img src="{{ asset($item->produkDetail->foto) }}" 
                                     alt="{{ $item->produkDetail->produk->nama }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->produkDetail->produk->nama }}</h3>
                            <p class="text-sm text-gray-600">Varian: {{ $item->produkDetail->nama_varian }}</p>
                            <p class="text-sm text-gray-600">
                                Rp {{ number_format($item->harga_beli, 0, ',', '.') }} × {{ $item->kuantitas }}
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">
                                Rp {{ number_format($item->subtotal_item, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Right Column: Summary & Actions -->
        <div class="space-y-6">
            
            <!-- Payment Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Pembayaran</h2>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal Produk</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Ongkos Kirim</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($pesanan->ongkir->harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-gray-200 pt-3 flex justify-between">
                        <span class="font-semibold text-gray-900">Grand Total</span>
                        <span class="font-bold text-[#5BC6BC] text-lg">Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($pesanan->pembayaran)
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Informasi Pembayaran</h2>
                
                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Metode Pembayaran</p>
                        <p class="font-semibold text-gray-900">{{ $pesanan->pembayaran->metode_pembayaran }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Status Pembayaran</p>
                        @php
                            $paymentStatusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'verified' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                        @endphp
                        <span class="inline-block mt-1 px-3 py-1 text-xs font-medium rounded-full {{ $paymentStatusColors[$pesanan->pembayaran->status_pembayaran->value] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($pesanan->pembayaran->status_pembayaran->value) }}
                        </span>
                    </div>
                    @if($pesanan->pembayaran->bukti_transfer)
                    <div>
                        <p class="text-gray-500 mb-2">Bukti Transfer</p>
                        <img src="{{ asset($pesanan->pembayaran->bukti_transfer) }}" 
                             alt="Bukti Transfer" 
                             class="w-full rounded-lg border border-gray-200 cursor-pointer hover:opacity-90"
                             onclick="window.open(this.src, '_blank')">
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-6">Aksi</h2>
                
                <div class="space-y-3">
                    @if($pesanan->status_pesanan->value === 'menunggu_verifikasi')
                    <form action="{{ route('admin.pesanan_detail.verify', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-check-circle mr-2"></i>
                            Verifikasi Pembayaran
                        </button>
                    </form>
                    @endif

                    @if(in_array($pesanan->status_pesanan->value, ['menunggu_verifikasi', 'diproses']))
                    <form action="{{ route('admin.pesanan_detail.process', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-box mr-2"></i>
                            {{ $pesanan->status_pesanan->value === 'menunggu_verifikasi' ? 'Proses Pesanan' : 'Update ke Dikirim' }}
                        </button>
                    </form>
                    @endif

                    @if($pesanan->status_pesanan->value === 'dikirim')
                    <form action="{{ route('admin.pesanan_detail.complete', $pesanan->id_pesanan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-[#5BC6BC] text-white rounded-lg hover:bg-[#4aa89e] transition-colors font-medium">
                            <i class="fas fa-check-double mr-2"></i>
                            Selesaikan Pesanan
                        </button>
                    </form>
                    @endif

                    @if(in_array($pesanan->status_pesanan->value, ['menunggu_pembayaran', 'menunggu_verifikasi']))
                    <button type="button" 
                            onclick="confirmCancel({{ $pesanan->id_pesanan }})"
                            class="w-full px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-times-circle mr-2"></i>
                        Batalkan Pesanan
                    </button>

                    <form id="cancel-form-{{ $pesanan->id_pesanan }}" 
                          action="{{ route('admin.pesanan_detail.cancel', $pesanan->id_pesanan) }}" 
                          method="POST" 
                          class="hidden">
                        @csrf
                    </form>
                    @endif

                    <a href="{{ route('admin.pesanan.index') }}" 
                       class="block w-full px-4 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#5BC6BC'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#5BC6BC'
        });
    @endif

    function confirmCancel(pesananId) {
        Swal.fire({
            title: 'Yakin ingin membatalkan?',
            text: "Pesanan ini akan dibatalkan permanen",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan',
            cancelButtonText: 'Tidak',
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('cancel-form-' + pesananId).submit();
            }
        });
    }
</script>
@endpush

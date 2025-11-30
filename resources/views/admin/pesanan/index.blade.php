@extends('layouts.layout_admin')

@section('title', 'Pesanan')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="space-y-6">

    @include('component.admin.breadcrumb', [
        'items' => [
            ['label' => 'Pesanan']
        ]
    ])

    <div class="flex items-center justify-between">
        @include('component.admin.search_bar', ['placeholder' => 'Cari pesanan berdasarkan ID atau nama penerima'])

        <div class="flex items-center gap-3">
            <!-- Filter Status -->
            <select id="filter-status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5BC6BC] focus:border-[#5BC6BC] outline-none">
                <option value="">Semua Status</option>
                <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                <option value="menunggu_verifikasi">Menunggu Verifikasi</option>
                <option value="diproses">Diproses</option>
                <option value="dikirim">Dikirim</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
            </select>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">ID Pesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Penerima</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Waktu Pesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Grand Total</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($pesananList ?? [] as $pesanan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800">
                            #{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <div class="font-medium text-gray-900">{{ $pesanan->penerima_nama }}</div>
                                <div class="text-gray-500">{{ $pesanan->penerima_telepon }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $pesanan->waktu_pesanan->format('d M Y, H:i') }}
                        </td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                            Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
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
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $statusColors[$pesanan->status_pesanan->value] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $statusLabels[$pesanan->status_pesanan->value] ?? $pesanan->status_pesanan->value }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.pesanan_detail.index', $pesanan->id_pesanan) }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 text-sm text-white bg-[#5BC6BC] rounded-lg hover:bg-[#4aa89e] transition-colors">
                                <i class="fas fa-eye"></i>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                            <p>Belum ada data pesanan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(isset($pesananList) && method_exists($pesananList, 'hasPages') && $pesananList->hasPages())
        <div class="mt-6">
            {{ $pesananList->links() }}
        </div>
    @endif

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
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

        const filterStatus = document.getElementById('filter-status');
        if (filterStatus) {
            filterStatus.addEventListener('change', function() {
                const url = new URL(window.location.href);
                if (this.value) {
                    url.searchParams.set('status', this.value);
                } else {
                    url.searchParams.delete('status');
                }
                window.location.href = url.toString();
            });

            const urlParams = new URLSearchParams(window.location.search);
            const currentStatus = urlParams.get('status');
            if (currentStatus) {
                filterStatus.value = currentStatus;
            }
        }
    });
</script>
@endpush
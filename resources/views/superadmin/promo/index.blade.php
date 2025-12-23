@extends('layouts.layout_admin')
@section('title', 'Kelola Promo Ongkir')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Promo & Voucher Ongkir</h1>
            <p class="text-sm text-gray-500 mt-1">Atur strategi diskon pengiriman untuk menarik pelanggan.</p>
        </div>
        <a href="{{ route('superadmin.promo.create') }}" class="px-5 py-2.5 bg-[#2A9D8F] text-white font-medium rounded-lg hover:bg-teal-700 shadow-lg shadow-teal-500/20 flex items-center transition">
            <i class="fas fa-ticket-alt mr-2"></i> Buat Promo Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start">
            <div class="flex-shrink-0"><i class="fas fa-check-circle text-green-500 mt-0.5"></i></div>
            <div class="ml-3"><p class="text-sm text-green-700 font-medium">{{ session('success') }}</p></div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($promos as $promo)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group relative flex flex-col justify-between">
            
            <div class="absolute top-4 right-4">
                @if($promo->is_active && ($promo->tanggal_selesai >= now() || $promo->tanggal_selesai == null))
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">AKTIF</span>
                @else
                    <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs font-bold rounded-full">BERAKHIR</span>
                @endif
            </div>

            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center text-xl shadow-sm border border-orange-100">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 group-hover:text-[#2A9D8F] transition line-clamp-1">{{ $promo->nama_promo }}</h3>
                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $promo->deskripsi ?? 'Potongan ongkir spesial untuk pelanggan.' }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Min. Belanja</span>
                        <span class="font-bold text-gray-800">Rp {{ number_format($promo->min_belanja, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Benefit</span>
                        <span class="font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded">
                            Potong {{ $promo->nilai_potongan + 0 }} KM
                        </span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Sistem</span>
                        @if($promo->mekanisme == 'kelipatan')
                            <span class="text-purple-600 font-bold text-xs flex items-center">
                                <i class="fas fa-layer-group mr-1"></i> BERLAKU KELIPATAN
                            </span>
                        @else
                            <span class="text-gray-600 font-bold text-xs">FLAT (SEKALI)</span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-50 flex items-center justify-between text-xs text-gray-400">
                    <div class="flex items-center gap-1">
                        <i class="far fa-calendar-alt"></i>
                        {{ $promo->tanggal_mulai->format('d M') }} - {{ $promo->tanggal_selesai->format('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center mt-auto">
                <a href="{{ route('superadmin.promo.edit', $promo->id) }}" class="text-blue-600 text-sm font-medium hover:underline">Edit Detail</a>
                
                <button type="button" onclick="konfirmasiHapusPromo('{{ $promo->id }}', '{{ $promo->nama_promo }}')" class="text-red-500 text-sm hover:text-red-700 font-medium">
                    <i class="fas fa-trash-alt mr-1"></i> Hapus
                </button>

                <form id="delete-promo-{{ $promo->id }}" action="{{ route('superadmin.promo.destroy', $promo->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full py-12 text-center bg-white rounded-xl border border-dashed border-gray-300">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto text-gray-400 mb-4">
                <i class="fas fa-ticket-alt text-2xl"></i>
            </div>
            <h3 class="text-gray-900 font-medium">Belum ada promo aktif</h3>
            <p class="text-gray-500 text-sm mt-1">Mulai buat promo pertama Anda sekarang.</p>
        </div>
        @endforelse
    </div>
</div>

<script>
    function konfirmasiHapusPromo(id, nama) {
        Swal.fire({
            title: 'Hapus Promo?',
            text: "Anda akan menghapus: " + nama,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-promo-' + id).submit();
            }
        })
    }
</script>
@endsection
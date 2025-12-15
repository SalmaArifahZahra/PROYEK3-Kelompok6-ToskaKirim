@extends('layouts.layout_customer')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')


    @if ($pesanan->status_pesanan->value == 'menunggu_pembayaran' && !$isExpired)
        <div class="sticky top-20 z-40 bg-orange-50 border-b border-orange-200 p-4 mb-6 shadow-sm">
            <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3 text-orange-700">
                    <i class="fas fa-clock text-2xl"></i>
                    <div>
                        <p class="font-bold text-sm">Selesaikan Pembayaran Dalam:</p>
                        <p class="text-xs">Pesanan akan otomatis dibatalkan jika melewati batas waktu.</p>
                    </div>
                </div>
                
                <div class="text-center">
                    <div id="countdown-timer" class="text-2xl font-bold text-orange-600 tracking-wider font-mono"
                        data-deadline="{{ $deadlineTimestamp }}">
                        -- : -- : --
                    </div>
                    <p class="text-xs text-orange-500 font-medium">
                        Batas: {{ \Carbon\Carbon::parse($deadline)->format('d M Y, H:i') }} WIB
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="max-w-5xl mx-auto my-8 px-4">
        <nav class="text-sm text-slate-500 mb-6 flex justify-between items-center">
            <ol class="flex items-center gap-2">
                <li><a href="{{ route('customer.pesanan.index') }}" class="hover:underline">Pesanan Saya</a></li>
                <li>/</li>
                <li class="text-slate-700 font-medium">INV-{{ $pesanan->id_pesanan }}</li>
            </ol>
            
            <span class="text-xs text-slate-400">
                Dipesan pada: {{ $pesanan->waktu_pesanan->format('d M Y, H:i') }}
            </span>
        </nav>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
            <div class="md:col-span-8 space-y-6">
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-700">Detail Produk</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($pesanan->status_pesanan->value == 'menunggu_pembayaran') bg-orange-100 text-orange-600
                            @elseif($pesanan->status_pesanan->value == 'menunggu_verifikasi') bg-blue-100 text-blue-600
                            @elseif($pesanan->status_pesanan->value == 'diproses') bg-indigo-100 text-indigo-600
                            @elseif($pesanan->status_pesanan->value == 'dikirim') bg-teal-100 text-teal-600
                            @elseif($pesanan->status_pesanan->value == 'selesai') bg-green-100 text-green-600
                            @else bg-red-100 text-red-600 @endif">
                            {{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan->value)) }}
                        </span>
                    </div>

   
                    <div class="divide-y divide-slate-100">
                        @foreach ($pesanan->detail as $detail)
                            <div class="p-6 flex gap-4">
                                <img src="{{ $detail->produkDetail->produk->foto_url ?? asset('images/no-image.png') }}"
                                     class="w-20 h-20 rounded-md object-cover border border-slate-100 shrink-0">
                                
                                <div class="flex-1">
                                    <h4 class="font-medium text-slate-800">{{ $detail->produkDetail->produk->nama }}</h4>
                                    <p class="text-xs text-slate-500 mb-1">Varian: {{ $detail->produkDetail->nama_varian }}</p>
                                    <div class="flex justify-between items-end mt-2">
                                        <p class="text-sm text-slate-600">{{ $detail->kuantitas }} x Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</p>
                                        <p class="font-semibold text-slate-800">Rp {{ number_format($detail->subtotal_item, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700">Informasi Pengiriman</h3>
                    </div>
                    <div class="p-6 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-slate-500 text-xs mb-1 uppercase tracking-wide">Penerima</p>
                                <p class="font-semibold text-slate-800 text-base">{{ $pesanan->penerima_nama }}</p>
                                <p class="text-slate-600 mt-1">{{ $pesanan->penerima_telepon }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs mb-1 uppercase tracking-wide">Alamat Tujuan</p>
                                <p class="text-slate-700 leading-relaxed">{{ $pesanan->alamat_lengkap }}</p>
                            </div>
                            @if($pesanan->layananPengiriman)
                            <div class="md:col-span-2 pt-4 border-t border-slate-100">
                                <p class="text-slate-500 text-xs mb-1 uppercase tracking-wide">Layanan Ekspedisi</p>
                                <p class="font-semibold text-teal-600 text-base">
                                    {{ $pesanan->layananPengiriman->nama_layanan }} 
                                    <span class="text-slate-500 font-normal text-sm ml-2">
                                        (Estimasi {{ $pesanan->layananPengiriman->estimasi_waktu }})
                                    </span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>


            <div class="md:col-span-8 space-y-6 sticky top-24">

                <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700">Rincian Pembayaran</h3>
                    </div>

                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Harga Produk</span>
                            <span>Rp {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($pesanan->ongkir->total_ongkir, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-slate-100 my-4">
                        <div class="flex justify-between font-bold text-lg text-teal-600">
                            <span>Total Tagihan</span>
                            <span>Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>


                @if($pesanan->status_pesanan->value == 'menunggu_pembayaran')
                    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                            <h3 class="font-bold text-slate-700">Pembayaran</h3>
                            <p class="text-xs text-slate-500 mt-1">Silakan transfer ke rekening di bawah:</p>
                        </div>

                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                @foreach($paymentMethods as $bank)
                                    <div class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg bg-white hover:border-slate-300 transition-colors">
                                        @if($bank->gambar)
                                            <img src="{{ asset('storage/' . $bank->gambar) }}" class="h-8 w-auto object-contain">
                                        @else
                                            <div class="h-8 w-12 bg-slate-100 rounded flex items-center justify-center text-xs font-bold text-slate-400">BANK</div>
                                        @endif
                                        <div class="overflow-hidden flex-1">
                                            <p class="font-bold text-slate-700 text-sm truncate">{{ $bank->nama_bank }}</p>
                                            <p class="font-mono text-slate-600 text-sm truncate">{{ $bank->nomor_rekening }}</p>
                                        </div>
                                        <button onclick="navigator.clipboard.writeText('{{ $bank->nomor_rekening }}'); alert('No Rekening Disalin!')" 
                                                class="text-teal-600 hover:text-teal-800 text-xs font-bold shrink-0">
                                            SALIN
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <form action="{{ route('customer.pesanan.upload', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Transfer</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-6 text-center hover:bg-slate-50 transition cursor-pointer group" id="drop-area">
                                    <input type="file" name="bukti_bayar" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                    <div id="preview-container" class="group-hover:scale-105 transition-transform">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                                        <p class="text-xs text-slate-500">Klik atau tarik foto ke sini</p>
                                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    </div>
                                    <img id="img-preview" class="hidden max-h-40 mx-auto rounded shadow-sm mt-2">
                                </div>
                                
                                <input type="hidden" name="jumlah_bayar" value="{{ $pesanan->grand_total }}">

                                <button type="submit" class="w-full mt-4 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-teal-500/30 transition-transform active:scale-95">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                            
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <form action="{{ route('customer.pesanan.cancel', $pesanan->id_pesanan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 border border-red-200 text-red-500 rounded-lg text-sm font-semibold hover:bg-red-50 hover:border-red-300 transition-all">
                                        Batalkan Pesanan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @elseif($pesanan->status_pesanan->value == 'menunggu_verifikasi')
                    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                            <h3 class="font-bold text-slate-700">Status Pembayaran</h3>
                        </div>
                        <div class="p-6 text-center">
                            <div class="bg-blue-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-hourglass-half text-2xl text-blue-500"></i>
                            </div>
                            <h4 class="font-bold text-slate-800">Bukti Sedang Diverifikasi</h4>
                            <p class="text-sm text-slate-500 mt-2 mb-4">Mohon tunggu 1x24 jam, admin sedang mengecek pembayaran Anda.</p>
                            
                            @if($pesanan->pembayaran)
                                <div class="border rounded p-2 inline-block">
                                    <p class="text-[10px] text-slate-400 mb-1 text-left">Bukti Anda:</p>
                                    <a href="{{ asset('storage/'.$pesanan->pembayaran->bukti_bayar) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$pesanan->pembayaran->bukti_bayar) }}" class="h-16 rounded object-cover">
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif($pesanan->status_pesanan->value == 'diproses' || $pesanan->status_pesanan->value == 'dikirim')
                     <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
                        <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                             <h3 class="font-bold text-slate-700">Lacak Pengiriman</h3>
                        </div>
                        <div class="p-6 text-center">
                            <div class="bg-teal-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shipping-fast text-2xl text-teal-500"></i>
                            </div>
                            @if($pesanan->status_pesanan->value == 'dikirim')
                                <h4 class="font-bold text-slate-800">Paket Sedang Dikirim</h4>
                                <p class="text-sm text-slate-500 mt-2">Kurir sedang mengantar paket ke alamat tujuan.</p>
                            @else
                                <h4 class="font-bold text-slate-800">Sedang Dikemas</h4>
                                <p class="text-sm text-slate-500 mt-2">Penjual sedang menyiapkan barang pesanan Anda.</p>
                            @endif

                            <button class="w-full mt-6 bg-slate-100 text-slate-600 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors">
                                Lihat Detail Pelacakan
                            </button>
                        </div>
                     </div>
                @endif

            </div>
        </div>
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

                if (distance < 0) {
                    clearInterval(updateTimer);
                    timerEl.innerHTML = "WAKTU HABIS";
                    timerEl.classList.add('text-red-600');
                    setTimeout(() => location.reload(), 2000); 
                    return;
                }

                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerEl.innerHTML =
                    (hours < 10 ? "0" + hours : hours) + " : " +
                    (minutes < 10 ? "0" + minutes : minutes) + " : " +
                    (seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        }

        // 2. LOGIC PREVIEW GAMBAR UPLOAD
        const fileInput = document.getElementById('file-input');
        const imgPreview = document.getElementById('img-preview');
        const previewContainer = document.getElementById('preview-container');

        if(fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if(file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imgPreview.src = e.target.result;
                        imgPreview.classList.remove('hidden');
                        previewContainer.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
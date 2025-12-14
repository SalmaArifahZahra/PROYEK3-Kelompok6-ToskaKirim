@extends('layouts.layout_customer')

@section('title', 'Detail Pesanan #' . $pesanan->id_pesanan)

@section('content')

    {{-- A. LOGIKA HITUNG MUNDUR (Hanya jika Menunggu Pembayaran & Bukan COD) --}}
    @if ($pesanan->status_pesanan == 'menunggu_pembayaran' && !$isExpired)
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

        {{-- B. BREADCRUMB --}}
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

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">

            {{-- C. KOLOM KIRI: Detail Produk & Pengiriman --}}
            <div class="md:col-span-8 space-y-6">

                {{-- Card Produk --}}
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-6 py-3 border-b border-slate-200 flex justify-between items-center">
                        <h3 class="font-bold text-slate-700">Detail Produk</h3>
                        <span class="px-3 py-1 rounded-full text-xs font-bold
                            @if($pesanan->status_pesanan == 'menunggu_pembayaran') bg-orange-100 text-orange-600
                            @elseif($pesanan->status_pesanan == 'menunggu_verifikasi') bg-blue-100 text-blue-600
                            @elseif($pesanan->status_pesanan == 'diproses') bg-indigo-100 text-indigo-600
                            @elseif($pesanan->status_pesanan == 'dikirim') bg-teal-100 text-teal-600
                            @elseif($pesanan->status_pesanan == 'selesai') bg-green-100 text-green-600
                            @else bg-red-100 text-red-600 @endif">
                            {{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan->value)) }}
                        </span>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($pesanan->detail as $detail)
                            <div class="p-4 flex gap-4">
                                <img src="{{ $detail->produkDetail->produk->foto_url ?? asset('images/no-image.png') }}"
                                     class="w-20 h-20 rounded-md object-cover border border-slate-100">
                                
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

                {{-- Card Info Pengiriman (SNAPSHOT) --}}
                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden shadow-sm">
                    <div class="bg-slate-50 px-6 py-3 border-b border-slate-200">
                        <h3 class="font-bold text-slate-700">Informasi Pengiriman</h3>
                    </div>
                    <div class="p-6 text-sm">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-slate-500 text-xs mb-1">Penerima</p>
                                <p class="font-semibold text-slate-800">{{ $pesanan->penerima_nama }}</p>
                                <p class="text-slate-600">{{ $pesanan->penerima_telepon }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs mb-1">Alamat Tujuan</p>
                                <p class="text-slate-700 leading-relaxed">{{ $pesanan->alamat_lengkap }}</p>
                            </div>
                            @if($pesanan->layananPengiriman)
                            <div class="md:col-span-2 pt-4 border-t border-slate-100 mt-2">
                                <p class="text-slate-500 text-xs mb-1">Layanan Ekspedisi</p>
                                <p class="font-semibold text-teal-600">
                                    {{ $pesanan->layananPengiriman->nama_layanan }} - 
                                    <span class="text-slate-600 font-normal">Estimasi {{ $pesanan->layananPengiriman->estimasi_waktu }}</span>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            {{-- D. KOLOM KANAN: Pembayaran & Aksi --}}
            <div class="md:col-span-4 space-y-6">

                {{-- Card Ringkasan Biaya --}}
                <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6">
                    <h3 class="font-bold text-slate-700 mb-4">Rincian Pembayaran</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Harga Produk</span>
                            <span>Rp {{ number_format($pesanan->subtotal_produk, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($pesanan->ongkir->total_ongkir, 0, ',', '.') }}</span>
                        </div>
                        <hr class="border-slate-100 my-2">
                        <div class="flex justify-between font-bold text-lg text-teal-600">
                            <span>Total Tagihan</span>
                            <span>Rp {{ number_format($pesanan->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card Instruksi Pembayaran / Upload Bukti --}}
                {{-- Hanya Tampil Jika Status "Menunggu Pembayaran" --}}
                @if($pesanan->status_pesanan == 'menunggu_pembayaran')
                    <div class="bg-white border border-teal-200 rounded-lg shadow-sm overflow-hidden">
                        
                        {{-- Header Card --}}
                        <div class="bg-teal-50 px-6 py-4 border-b border-teal-100">
                            <h3 class="font-bold text-teal-800">Pembayaran</h3>
                            <p class="text-xs text-teal-600 mt-1">Silakan transfer ke salah satu rekening berikut:</p>
                        </div>

                        <div class="p-6">
                            {{-- List Bank --}}
                            <div class="space-y-4 mb-6">
                                @foreach($paymentMethods as $bank)
                                    <div class="flex items-center gap-3 p-3 border border-slate-100 rounded-lg bg-slate-50">
                                        @if($bank->gambar)
                                            <img src="{{ asset('storage/' . $bank->gambar) }}" class="h-8 w-auto object-contain">
                                        @else
                                            <div class="h-8 w-12 bg-slate-200 rounded flex items-center justify-center text-xs font-bold text-slate-500">BANK</div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-700 text-sm">{{ $bank->nama_bank }}</p>
                                            <p class="font-mono text-slate-600 text-sm">{{ $bank->nomor_rekening }}</p>
                                            <p class="text-xs text-slate-500">a.n {{ $bank->atas_nama }}</p>
                                        </div>
                                        <button onclick="navigator.clipboard.writeText('{{ $bank->nomor_rekening }}'); alert('No Rekening Disalin!')" 
                                                class="ml-auto text-teal-600 hover:text-teal-800 text-xs font-bold">
                                            SALIN
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Form Upload --}}
                            <form action="{{ route('customer.pesanan.upload', $pesanan->id_pesanan) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label class="block text-sm font-bold text-slate-700 mb-2">Upload Bukti Transfer</label>
                                
                                <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:bg-slate-50 transition cursor-pointer" id="drop-area">
                                    <input type="file" name="bukti_bayar" id="file-input" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                                    <div id="preview-container">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                                        <p class="text-xs text-slate-500">Klik atau tarik foto ke sini</p>
                                        <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                    </div>
                                    <img id="img-preview" class="hidden max-h-40 mx-auto rounded shadow-sm mt-2">
                                </div>
                                
                                {{-- Input Jumlah Bayar (Otomatis terisi Total, tapi bisa diedit jika perlu) --}}
                                <input type="hidden" name="jumlah_bayar" value="{{ $pesanan->grand_total }}">

                                <button type="submit" class="w-full mt-4 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg shadow-lg shadow-teal-500/30 transition-transform active:scale-95">
                                    Kirim Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tombol Batalkan --}}
                    <form action="{{ route('customer.pesanan.cancel', $pesanan->id_pesanan) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan?');">
                        @csrf
                        <button type="submit" class="w-full text-slate-400 hover:text-red-500 text-sm font-medium py-3 transition-colors">
                            Batalkan Pesanan
                        </button>
                    </form>

                @elseif($pesanan->status_pesanan == 'menunggu_verifikasi')
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg text-center">
                        <i class="fas fa-hourglass-half text-4xl text-blue-300 mb-3"></i>
                        <h3 class="font-bold text-blue-800 mb-1">Bukti Terkirim</h3>
                        <p class="text-sm text-blue-600">Admin sedang memverifikasi pembayaran Anda. Mohon tunggu 1x24 jam.</p>
                        
                        @if($pesanan->pembayaran)
                            <div class="mt-4">
                                <p class="text-xs text-slate-500 mb-1">Bukti yang diupload:</p>
                                <a href="{{ asset('storage/'.$pesanan->pembayaran->bukti_bayar) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$pesanan->pembayaran->bukti_bayar) }}" class="h-20 mx-auto rounded border border-slate-200 hover:opacity-75 transition">
                                </a>
                            </div>
                        @endif
                    </div>

                @elseif($pesanan->status_pesanan == 'diproses' || $pesanan->status_pesanan == 'dikirim')
                     <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-6 text-center">
                        <h3 class="font-bold text-slate-700 mb-2">Status Pengiriman</h3>
                        @if($pesanan->status_pesanan == 'dikirim')
                            <p class="text-sm text-slate-600 mb-4">Paket sedang dalam perjalanan.</p>
                            {{-- Jika ada resi, tampilkan disini --}}
                            {{-- <div class="bg-slate-100 p-3 rounded font-mono font-bold text-slate-700">JP123456789</div> --}}
                        @else
                            <p class="text-sm text-slate-600">Pesanan sedang dikemas oleh penjual.</p>
                        @endif

                        <button class="w-full mt-4 bg-slate-100 text-slate-600 py-2 rounded-lg text-sm font-medium hover:bg-slate-200">
                            Lacak Pesanan
                        </button>
                     </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. LOGIC TIMER HITUNG MUNDUR
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
                    setTimeout(() => location.reload(), 2000); // Reload otomatis jika habis
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
                        previewContainer.classList.add('hidden'); // Sembunyikan ikon upload
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
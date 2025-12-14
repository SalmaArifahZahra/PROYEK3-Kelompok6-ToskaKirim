@extends('layouts.layout_customer')

@section('title', 'Konfirmasi & Pembayaran')

@section('content')

<div class="max-w-5xl mx-auto my-8 px-4 relative z-10">

    <nav class="text-sm text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2">
            <li><a href="{{ route('customer.keranjang.index') }}" class="hover:underline">Keranjang</a></li>
            <li>/</li>
            <li class="text-slate-700 font-medium">Konfirmasi & Pembayaran</li>
        </ol>
    </nav>

    <form action="{{ route('customer.pesanan.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
        @csrf
        {{-- Data Item Hidden (Dari Javascript Keranjang) --}}
        <input type="hidden" name="items" id="itemsInput">
        <input type="hidden" name="id_layanan_pengiriman" value="{{ $selectedLayananId ?? 1 }}">
        
        <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-teal-600 font-semibold">Alamat Pengiriman</h3>
                <a href="{{ route('customer.alamat.index') }}" target="_blank" class="text-xs bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200 transition-colors">
                    Edit Alamat
                </a>
            </div>
            <div class="text-sm">
                <p class="font-medium text-slate-800">{{ $alamatUtama->nama_penerima }}</p>
                <p class="text-slate-600">{{ $alamatUtama->telepon_penerima }}</p>
                <p class="text-slate-700 mt-1">
                    {{ $alamatUtama->jalan_patokan }},
                    {{ $alamatUtama->kelurahan }}, {{ $alamatUtama->kecamatan }},
                    {{ $alamatUtama->kota_kabupaten }}
                </p>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
            <div class="p-6">
                <div class="mb-6">
                    <h3 class="text-slate-700 font-medium mb-3">Produk Dipesan</h3>
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                        <div class="grid grid-cols-12 bg-slate-50 border-b border-slate-200 py-3 px-4 text-sm font-semibold text-slate-600">
                            <div class="col-span-6">Produk</div>
                            <div class="col-span-2 text-right">Harga</div>
                            <div class="col-span-2 text-center">Qty</div>
                            <div class="col-span-2 text-right">Subtotal</div>
                        </div>

                        @foreach ($selectedItems as $item)
                            @php
                                $produkDetail = $item['produk_detail'];
                                $produk = $produkDetail->produk;
                            @endphp
                            <div class="grid grid-cols-12 items-center bg-white border-b border-slate-200 py-4 px-4 hover:bg-slate-50">
                                <div class="col-span-6 flex items-center gap-3">
                                    <img src="{{ $produk->foto_url ?? asset('images/icon_toska.png') }}" class="w-12 h-12 rounded object-cover">
                                    <div>
                                        <p class="font-medium text-slate-800 text-sm">{{ $produk->nama }}</p>
                                        <p class="text-xs text-slate-500">Varian: {{ $produkDetail->nama_varian }}</p>
                                    </div>
                                </div>
                                <div class="col-span-2 text-right text-sm text-slate-700">
                                    Rp {{ number_format($produkDetail->harga_jual, 0, ',', '.') }}
                                </div>
                                <div class="col-span-2 text-center text-sm text-slate-700">
                                    {{ $item['quantity'] }}
                                </div>
                                <div class="col-span-2 text-right text-sm font-semibold text-slate-800">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    {{-- Bagian Kiri: Metode Pembayaran --}}
                    <div class="md:col-span-8">
                        {{-- Layanan Pengiriman --}}
                        <div class="border border-slate-200 rounded-lg p-5 mb-6">
                            <h3 class="font-semibold text-slate-700 mb-4">Pilih Layanan Pengiriman</h3>

                            @if($layananPengiriman->isEmpty())
                                <div class="text-center py-6">
                                    <p class="text-slate-500 text-sm">Tidak ada layanan pengiriman yang tersedia saat ini.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach($layananPengiriman as $layanan)
                                        <label class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-slate-50 transition border-slate-200 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                            <input type="radio" name="id_layanan_pengiriman" value="{{ $layanan->id }}" 
                                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500"
                                                   @if($selectedLayanan == $layanan->id) checked @endif
                                                   required>
                                            <div class="flex-1">
                                                <p class="font-medium text-slate-700">{{ $layanan->nama_layanan }}</p>
                                                <p class="text-xs text-slate-500">Tarif: Rp {{ number_format($layanan->tarif_per_km, 0, ',', '.') }} per km</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="border border-slate-200 rounded-lg p-5 mb-6">
                            <h3 class="font-semibold text-slate-700 mb-4">Pilih Metode Pembayaran</h3>

                            <div class="space-y-4">
                                {{-- Opsi COD --}}
                                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition border-slate-200 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="metode_pembayaran" value="COD" class="h-4 w-4 text-teal-600 focus:ring-teal-500" required>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-money-bill-wave text-teal-600 text-xl"></i>
                                        <span class="font-medium text-slate-700">Cash on Delivery (COD)</span>
                                    </div>
                                </label>

                                <p class="text-xs text-slate-500 uppercase font-bold tracking-wider mt-4">Transfer Bank / E-Wallet</p>

                                {{-- Loop Metode Pembayaran dari Database --}}
                                @foreach($paymentMethods as $method)
                                <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition border-slate-200 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                    <input type="radio" name="metode_pembayaran" value="{{ $method->id }}" 
                                           class="h-4 w-4 text-teal-600 focus:ring-teal-500 payment-radio"
                                           data-bank="{{ $method->nama_bank }}"
                                           data-rek="{{ $method->nomor_rekening }}"
                                           data-name="{{ $method->atas_nama }}"
                                           data-img="{{ $method->gambar ? asset('storage/' . $method->gambar) : '' }}">
                                    
                                    <div class="flex items-center gap-3 w-full">
                                        @if($method->gambar)
                                            <img src="{{ asset('storage/' . $method->gambar) }}" class="h-8 w-auto object-contain">
                                        @else
                                            <i class="fas fa-university text-slate-400 text-xl"></i>
                                        @endif
                                        <span class="font-medium text-slate-700">{{ $method->nama_bank }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            {{-- Info Transfer (Hidden by default) --}}
                            <div id="transfer-info" class="hidden mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 animate-fade-in-down">
                                <h4 class="text-blue-800 font-semibold mb-2 text-sm">Informasi Transfer</h4>
                                <div class="flex items-center gap-4">
                                    <img id="bank-img" src="" class="h-12 w-auto object-contain hidden">
                                    <div>
                                        <p class="text-sm text-slate-600">Bank: <span id="bank-name" class="font-bold"></span></p>
                                        <p class="text-sm text-slate-600">No. Rekening: <span id="bank-rek" class="font-mono font-bold text-lg text-slate-800"></span></p>
                                        <p class="text-sm text-slate-600">A.N: <span id="bank-owner"></span></p>
                                    </div>
                                </div>

                                {{-- Form Upload Bukti --}}
                                <div class="mt-4 pt-4 border-t border-blue-200">
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Upload Bukti Transfer (Sekarang)</label>
                                    <input type="file" name="bukti_bayar" class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-100 file:text-blue-700
                                        hover:file:bg-blue-200
                                    ">
                                    <p class="text-xs text-slate-500 mt-1">* Kosongkan jika ingin membayar nanti.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Bagian Kanan: Ringkasan --}}
                    <div class="md:col-span-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 sticky top-24">
                            <h3 class="font-semibold text-slate-700 mb-4">Ringkasan Pembayaran</h3>
                            
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-600">Total Harga ({{ count($selectedItems) }} barang)</span>
                                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>

                                {{-- Detail Ongkir --}}
                                <div class="bg-white rounded p-3 border border-slate-200">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 text-xs">Jarak Pengiriman:</span>
                                        <span class="font-medium text-xs" id="distanceDisplay">-</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 text-xs">Tarif per km:</span>
                                        <span class="font-medium text-xs" id="tarifDisplay">-</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-slate-600">Ongkos Kirim</span>
                                        <span class="font-medium" id="ongkirDisplay">Rp {{ number_format($ongkir, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <hr class="border-slate-200">
                                <div class="flex justify-between text-lg font-bold text-teal-600">
                                    <span>Total Tagihan</span>
                                    <span id="totalDisplay">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                {{-- Tombol ini berubah teks tergantung JS --}}
                                <button type="submit" id="btn-submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg shadow transition-colors">
                                    Buat Pesanan
                                </button>
                                
                                <a href="{{ route('customer.keranjang.index') }}" class="block w-full text-center text-slate-500 hover:text-slate-700 text-sm font-medium">
                                    Batalkan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Constant: subtotal dari server
        const subtotal = {{ $subtotal }};

        // 1. Masukkan data items dari Session/Controller ke Input Hidden
        const items = [
            @foreach ($selectedItems as $item)
                { id_produk_detail: "{{ $item['produk_detail']->id_produk_detail }}", quantity: {{ $item['quantity'] }} },
            @endforeach
        ];
        document.getElementById('itemsInput').value = JSON.stringify(items);

        // 2. Set selected layanan pengiriman dan hitung ongkir awal
        const selectedLayananRadio = document.querySelector('input[name="id_layanan_pengiriman"]:checked');
        if (selectedLayananRadio) {
            document.getElementById('selectedLayananInput').value = selectedLayananRadio.value;
            // Hitung ongkir untuk layanan yang sudah dipilih
            calculateAndUpdateOngkir(selectedLayananRadio.value);
        }

        // 3. Update ongkir dan hidden input ketika layanan pengiriman berubah
        document.querySelectorAll('input[name="id_layanan_pengiriman"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('selectedLayananInput').value = this.value;
                // Hitung ongkir untuk layanan yang baru dipilih
                calculateAndUpdateOngkir(this.value);
            });
        });

        // Function untuk menghitung dan update ongkir
        function calculateAndUpdateOngkir(layananId) {
            fetch('{{ route("customer.pesanan.calculateOngkir") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_layanan_pengiriman: layananId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ongkir = data.data.total_ongkir;
                    const jarak = data.data.jarak;
                    const tarif = data.data.tarif_per_km;
                    const grandTotal = subtotal + ongkir;

                    // Update display
                    document.getElementById('distanceDisplay').textContent = jarak.toFixed(3) + ' km';
                    document.getElementById('tarifDisplay').textContent = 'Rp ' + tarif.toLocaleString('id-ID') + '/km';
                    document.getElementById('ongkirDisplay').textContent = data.data.total_ongkir_formatted;
                    document.getElementById('totalDisplay').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                } else {
                    console.error('Error menghitung ongkir:', data.error);
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // 4. Logic Ganti Metode Pembayaran
        const radioButtons = document.querySelectorAll('input[name="metode_pembayaran"]');
        const transferInfo = document.getElementById('transfer-info');
        const bankName = document.getElementById('bank-name');
        const bankRek = document.getElementById('bank-rek');
        const bankOwner = document.getElementById('bank-owner');
        const bankImg = document.getElementById('bank-img');
        const btnSubmit = document.getElementById('btn-submit');
        const fileInput = document.querySelector('input[name="bukti_bayar"]');

        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'COD') {
                    // Jika COD
                    transferInfo.classList.add('hidden');
                    btnSubmit.textContent = 'Buat Pesanan (COD)';
                    btnSubmit.classList.remove('bg-teal-600');
                    btnSubmit.classList.add('bg-orange-500');
                    // Reset file input agar tidak terkirim
                    fileInput.value = '';
                } else {
                    // Jika Transfer
                    transferInfo.classList.remove('hidden');
                    
                    // Isi data bank
                    bankName.textContent = this.dataset.bank;
                    bankRek.textContent = this.dataset.rek;
                    bankOwner.textContent = this.dataset.name;
                    
                    if(this.dataset.img) {
                        bankImg.src = this.dataset.img;
                        bankImg.classList.remove('hidden');
                    } else {
                        bankImg.classList.add('hidden');
                    }

                    // Reset tombol text (akan berubah lagi jika file dipilih)
                    updateTransferButtonText();
                }
            });
        });

        // 5. Logic Ganti Teks Tombol saat upload file
        fileInput.addEventListener('change', updateTransferButtonText);

        function updateTransferButtonText() {
            const selectedRadio = document.querySelector('input[name="metode_pembayaran"]:checked');
            if (selectedRadio && selectedRadio.value !== 'COD') {
                if (fileInput.files.length > 0) {
                    btnSubmit.textContent = 'Bayar & Buat Pesanan';
                    btnSubmit.classList.remove('bg-orange-500');
                    btnSubmit.classList.add('bg-teal-600', 'hover:bg-teal-700');
                } else {
                    btnSubmit.textContent = 'Bayar Nanti (Buat Pesanan)';
                    btnSubmit.classList.remove('bg-teal-600');
                    btnSubmit.classList.add('bg-orange-500');
                }
            }
        }
    });
</script>
@endpush
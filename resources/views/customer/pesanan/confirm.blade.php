@extends('layouts.layout_customer')

@section('title', 'Konfirmasi & Pembayaran')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

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
            <input type="hidden" name="items" id="itemsInput">
            <input type="hidden" name="id_layanan_pengiriman" id="selectedLayananInput"
                value="{{ $selectedLayananId ?? 1 }}">

            <div class="bg-white border border-slate-200 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-teal-600 font-semibold">Alamat Pengiriman</h3>
                    <button type="button" id="btnEditAlamat"
                        class="text-xs bg-teal-100 text-teal-700 px-3 py-1 rounded hover:bg-teal-200 transition-colors">
                        Ubah Alamat
                    </button>
                </div>
                <div class="text-sm">
                    @if ($alamatUtama)
                        <p class="font-medium text-slate-800">{{ $alamatUtama->nama_penerima }}</p>
                        <p class="text-slate-600">{{ $alamatUtama->telepon_penerima }}</p>
                        <p class="text-slate-700 mt-1">
                            {{ $alamatUtama->jalan_patokan }},
                            {{ $alamatUtama->kelurahan }}, {{ $alamatUtama->kecamatan }},
                            {{ $alamatUtama->kota_kabupaten }}
                        </p>
                    @else
                        <p class="text-red-500">Belum ada alamat utama. Silakan atur alamat.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-slate-700 font-medium mb-3">Produk Dipesan</h3>
                        <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                            <div
                                class="grid grid-cols-12 bg-slate-50 border-b border-slate-200 py-3 px-4 text-sm font-semibold text-slate-600">
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
                                <div
                                    class="grid grid-cols-12 items-center bg-white border-b border-slate-200 py-4 px-4 hover:bg-slate-50">
                                    <div class="col-span-6 flex items-center gap-3">
                                        <img src="{{ $produk->foto_url ?? asset('images/icon_toska.png') }}"
                                            class="w-12 h-12 rounded object-cover">
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
                        <div class="md:col-span-8">
                            {{-- Layanan Pengiriman --}}
                            <div class="border border-slate-200 rounded-lg p-5 mb-6">
                                <h3 class="font-semibold text-slate-700 mb-4">Pilih Layanan Pengiriman</h3>
                                @if ($layananPengiriman->isEmpty())
                                    <div class="text-center py-6 text-slate-500 text-sm">Tidak ada layanan tersedia.</div>
                                @else
                                    <div class="space-y-3">
                                        @foreach ($layananPengiriman as $layanan)
                                            <label
                                                class="flex items-center gap-3 p-4 border rounded-lg cursor-pointer hover:bg-slate-50 transition border-slate-200 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                                <input type="radio" name="id_layanan_pengiriman_radio"
                                                    value="{{ $layanan->id }}"
                                                    class="h-4 w-4 text-teal-600 focus:ring-teal-500"
                                                    @if (isset($selectedLayananId) && $selectedLayananId == $layanan->id) checked @endif required>
                                                <div class="flex-1">
                                                    <p class="font-medium text-slate-700">{{ $layanan->nama_layanan }}</p>
                                                    <p class="text-xs text-slate-500">Tarif: Rp
                                                        {{ number_format($layanan->tarif_per_km, 0, ',', '.') }} per km</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Metode Pembayaran --}}
                            <div class="border border-slate-200 rounded-lg p-5 mb-6">
                                <h3 class="font-semibold text-slate-700 mb-4">Pilih Metode Pembayaran</h3>
                                @if ($paymentMethods->isEmpty())
                                    <div class="text-center py-6 text-slate-500 text-sm">Tidak ada metode pembayaran tersedia.</div>
                                @else
                                <div class="space-y-4">
                                    @foreach ($paymentMethods as $method)
                                        <label
                                            class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition border-slate-200 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                                            <input type="radio" name="metode_pembayaran" value="{{ $method->id }}"
                                                class="h-4 w-4 text-teal-600 focus:ring-teal-500 payment-radio"
                                                data-bank="{{ $method->nama_bank }}"
                                                data-rek="{{ $method->nomor_rekening }}"
                                                data-name="{{ $method->atas_nama }}"
                                                data-img="{{ $method->gambar ? asset('storage/' . $method->gambar) : '' }}"
                                                required
                                                @if ($loop->first) checked @endif>
                                            <div class="flex items-center gap-3 w-full">
                                                @if ($method->gambar)
                                                    <img src="{{ asset('storage/' . $method->gambar) }}"
                                                        class="h-8 w-auto object-contain">
                                                @else
                                                    @if (strtoupper($method->nama_bank) === 'COD')
                                                        <i class="fas fa-money-bill-wave text-teal-600 text-xl"></i>
                                                    @else
                                                        <i class="fas fa-university text-slate-400 text-xl"></i>
                                                    @endif
                                                @endif
                                                <span class="font-medium text-slate-700">{{ $method->nama_bank }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <div id="transfer-info"
                                    class="hidden mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4 animate-fade-in-down">
                                    <h4 class="text-blue-800 font-semibold mb-2 text-sm">Informasi Transfer</h4>
                                    <div class="flex items-center gap-4">
                                        <img id="bank-img" src="" class="h-12 w-auto object-contain hidden">
                                        <div>
                                            <p class="text-sm text-slate-600">Bank: <span id="bank-name"
                                                    class="font-bold"></span></p>
                                            <p class="text-sm text-slate-600">No. Rekening: <span id="bank-rek"
                                                    class="font-mono font-bold text-lg text-slate-800"></span></p>
                                            <p class="text-sm text-slate-600">A.N: <span id="bank-owner"></span></p>
                                        </div>
                                    </div>
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <label class="block text-sm font-medium text-slate-700 mb-2">Upload Bukti Transfer
                                            (Sekarang)</label>

                                        <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 text-center hover:bg-slate-50 transition cursor-pointer"
                                            id="drop-area">
                                            <input type="file" name="bukti_bayar" id="bukti_bayar_input"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" disabled>
                                            <div id="preview-container">
                                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2"></i>
                                                <p class="text-xs text-slate-500">Klik atau tarik foto ke sini</p>
                                                <p class="text-[10px] text-slate-400 mt-1">Format: JPG, PNG (Max 2MB)</p>
                                            </div>
                                        </div>

                                        <p class="text-xs text-slate-500 mt-1">* Kosongkan jika ingin membayar nanti.</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-4">
                            <div class="bg-slate-50 border border-slate-200 rounded-lg p-5 sticky top-24">
                                <h3 class="font-semibold text-slate-700 mb-4">Ringkasan Pembayaran</h3>
                                <div class="space-y-3 text-sm">
                                    <div class="bg-white rounded p-3 border border-slate-200">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-slate-600 text-xs">Jarak Pengiriman:</span>
                                            <span class="font-medium text-xs" id="distanceDisplay">@if(is_null($ongkir)) - @else 0 km @endif</span>
                                        </div>
                                        <div class="flex justify-between mb-2">
                                            <span class="text-slate-600 text-xs">Tarif per km:</span>
                                            <span class="font-medium text-xs" id="tarifDisplay">@if(is_null($ongkir)) - @else Rp 0 @endif</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-600">Ongkos Kirim</span>
                                            <span class="font-medium" id="ongkirDisplay">@if(is_null($ongkir)) - @else Rp {{ number_format($ongkir ?? 0, 0, ',', '.') }} @endif</span>
                                        </div>
                                    </div>

                                    <hr class="border-slate-200">
                                    <div class="flex justify-between text-lg font-bold text-teal-600">
                                        <span>Total Tagihan</span>
                                        <span id="totalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="mt-6 space-y-3">
                                    @if(!$alamatUtama)
                                    <button type="submit" id="btn-submit" disabled
                                        class="w-full bg-orange-500 text-white font-bold py-3 rounded-lg shadow opacity-50 cursor-not-allowed">
                                        Buat Pesanan
                                    </button>
                                    <p class="text-sm text-red-500 text-center">Anda belum memiliki alamat pengiriman. Klik "Ubah Alamat" untuk menambah alamat terlebih dahulu.</p>
                                    @else
                                    <button type="submit" id="btn-submit"
                                        class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg shadow transition-colors">
                                        Buat Pesanan
                                    </button>
                                    @endif

                                    <a href="{{ route('customer.keranjang.index') }}"
                                        class="block w-full text-center text-slate-500 hover:text-slate-700 text-sm font-medium">
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

    @include('customer.components.address_modal')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log("Script Loaded - All Systems Go");

            document.getElementById('checkoutForm').addEventListener('submit', function(e) {
                const metodeSelected = document.querySelector('input[name="metode_pembayaran"]:checked');
                const layananSelected = document.querySelector('input[name="id_layanan_pengiriman_radio"]:checked');
                
                if (!metodeSelected) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Metode Pembayaran',
                        text: 'Silakan pilih metode pembayaran terlebih dahulu.',
                        confirmButtonText: 'Baik'
                    });
                    return false;
                }
                
                if (!layananSelected) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Layanan Pengiriman',
                        text: 'Hubungi admin untuk menambahkan layanan pengiriman.',
                        confirmButtonText: 'Baik'
                    });
                    return false;
                }
                
                console.log('Form submitted');
                console.log('Metode Pembayaran:', metodeSelected?.value);
                console.log('Layanan Pengiriman:', layananSelected?.value);
                console.log('Items:', document.getElementById('itemsInput').value);
            });

            const subtotal = {{ $subtotal ?? 0 }};
            const items = [
                @if (isset($selectedItems))
                    @foreach ($selectedItems as $item)
                        {
                            id_produk_detail: "{{ $item['produk_detail']->id_produk_detail }}",
                            quantity: {{ $item['quantity'] }}
                        },
                    @endforeach
                @endif
            ];

            const itemsInput = document.getElementById('itemsInput');
            if (itemsInput) itemsInput.value = JSON.stringify(items);

            const selectedLayananRadio = document.querySelector(
                'input[name="id_layanan_pengiriman_radio"]:checked');
            if (selectedLayananRadio) {
                calculateAndUpdateOngkir(selectedLayananRadio.value);
            } else {
                // Jika tidak ada layanan yang dipilih, reset ongkir display ke '-'
                if (document.getElementById('distanceDisplay')) document.getElementById('distanceDisplay').textContent = '-';
                if (document.getElementById('tarifDisplay')) document.getElementById('tarifDisplay').textContent = '-';
                if (document.getElementById('ongkirDisplay')) document.getElementById('ongkirDisplay').textContent = '-';
            }

            document.querySelectorAll('input[name="id_layanan_pengiriman_radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const hiddenInput = document.getElementById('selectedLayananInput');
                    if (hiddenInput) hiddenInput.value = this.value;
                    calculateAndUpdateOngkir(this.value);
                });
            });

            function calculateAndUpdateOngkir(layananId) {
                if (document.getElementById('distanceDisplay')) document.getElementById('distanceDisplay')
                    .textContent = '...';
                if (document.getElementById('tarifDisplay')) document.getElementById('tarifDisplay').textContent =
                    '...';
                if (document.getElementById('ongkirDisplay')) document.getElementById('ongkirDisplay').textContent =
                    '...';

                fetch('{{ route('customer.pesanan.calculateOngkir') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            id_layanan_pengiriman: layananId
                        })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            const ongkir = data.data.total_ongkir || 0;
                            const jarak = data.data.jarak || 0;
                            const tarif = data.data.tarif_per_km || 0;
                            const grandTotal = subtotal + ongkir;

                            const elDistance = document.getElementById('distanceDisplay');
                            const elTarif = document.getElementById('tarifDisplay');
                            const elOngkir = document.getElementById('ongkirDisplay');
                            const elTotal = document.getElementById('totalDisplay');

                            if (elDistance) elDistance.textContent = jarak.toFixed(2) + ' km';
                            if (elTarif) elTarif.textContent = 'Rp ' + tarif.toLocaleString('id-ID') + '/km';
                            if (elOngkir) elOngkir.textContent = 'Rp ' + ongkir.toLocaleString('id-ID');
                            if (elTotal) elTotal.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: data.error
                            });
                            // Reset ke 0 jika gagal
                            if (document.getElementById('ongkirDisplay')) document.getElementById(
                                'ongkirDisplay').textContent = 'Rp 0';
                        }
                    })
                    .catch(error => {
                        console.error('Ongkir Error:', error);
                    });
            }

            const fileInput = document.querySelector('input[name="bukti_bayar"]');
            const btnSubmit = document.getElementById('btn-submit');
            const transferInfo = document.getElementById('transfer-info');

            document.querySelectorAll('input[name="metode_pembayaran"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    if (strtoupper(this.dataset.bank) === 'COD' || this.dataset.bank === 'COD') {
                        if (transferInfo) transferInfo.classList.add('hidden');
                        if (btnSubmit) {
                            btnSubmit.textContent = 'Buat Pesanan (COD)';
                            btnSubmit.className =
                                'w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg shadow transition-colors';
                        }
                        if (fileInput) fileInput.value = '';
                    } else {
                        if (transferInfo) transferInfo.classList.remove('hidden');

                        const bankName = document.getElementById('bank-name');
                        const bankRek = document.getElementById('bank-rek');
                        const bankOwner = document.getElementById('bank-owner');
                        const imgEl = document.getElementById('bank-img');

                        if (bankName) bankName.textContent = this.dataset.bank;
                        if (bankRek) bankRek.textContent = this.dataset.rek;
                        if (bankOwner) bankOwner.textContent = this.dataset.name;

                        if (imgEl) {
                            if (this.dataset.img) {
                                imgEl.src = this.dataset.img;
                                imgEl.classList.remove('hidden');
                            } else {
                                imgEl.classList.add('hidden');
                            }
                        }
                        updateTransferButtonText();
                    }
                });
            });

            if (fileInput) fileInput.addEventListener('change', updateTransferButtonText);

            function updateTransferButtonText() {
                const selected = document.querySelector('input[name="metode_pembayaran"]:checked');
                if (selected && selected.value !== 'COD' && btnSubmit) {
                    if (fileInput && fileInput.files.length > 0) {
                        btnSubmit.textContent = 'Bayar & Buat Pesanan';
                        btnSubmit.className =
                            'w-full bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-lg shadow transition-colors';
                    } else {
                        btnSubmit.textContent = 'Bayar Nanti (Buat Pesanan)';
                        btnSubmit.className =
                            'w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-lg shadow transition-colors';
                    }
                }
            }

            // modal alamat
            const modal = document.getElementById('addressModal');
            const btnOpen = document.getElementById('btnEditAlamat');
            const btnClose = document.getElementById('closeAddressModal');
            const btnCancel = document.getElementById('cancelModal');
            const btnAdd = document.getElementById('btnAddAddress');
            const btnCancelForm = document.getElementById('cancelAddressForm');
            const btnSubmitModal = document.getElementById('submitModal');

            const listContainer = document.getElementById('addressList');
            const formContainer = document.getElementById('addressFormContainer');
            const addBtnContainer = document.getElementById('addAddressButtonContainer');
            const form = document.getElementById('addressForm');
     
            const addressIdInput = document.getElementById('addressId');
            const provinsiSelect = document.getElementById('provinsi');
            const kotaSelect = document.getElementById('kota_kabupaten_select');
            const kecamatanSelect = document.getElementById('kecamatan_select');
            const kelurahanSelect = document.getElementById('kelurahan_select');
            const kotaHidden = document.getElementById('kota_kabupaten_hidden');
            const kecamatanHidden = document.getElementById('kecamatan_hidden');
            const kelurahanHidden = document.getElementById('kelurahan_hidden');

            const API_WILAYAH = '/api/wilayah';

            if (modal) {
                document.body.appendChild(modal);
            }

            if (btnOpen) {
                btnOpen.addEventListener('click', (e) => {
                    e.preventDefault();
                    loadAddresses();
                    if (provinsiSelect && provinsiSelect.options.length <= 1) {
                        fetchData('provinces', provinsiSelect, 'Provinsi');
                    }
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                });
            }

            function hideModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                resetFormView();
            }
            if (btnClose) btnClose.addEventListener('click', hideModal);
            if (btnCancel) btnCancel.addEventListener('click', hideModal);

            function resetFormView() {
                formContainer.classList.add('hidden');
                addBtnContainer.classList.remove('hidden');
                form.reset();
                addressIdInput.value = '';

                if (kotaSelect) kotaSelect.innerHTML = '<option value="">Pilih Kota</option>';
                if (kecamatanSelect) kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                if (kelurahanSelect) kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            }

            function loadAddresses() {
                listContainer.innerHTML =
                    '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-teal-600 mb-2"></i><br>Memuat data...</div>';

                fetch('{{ route('customer.alamat.api.all') }}')
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) renderList(res.data);
                    })
                    .catch(err => {
                        console.error(err);
                        listContainer.innerHTML =
                            '<div class="text-center text-red-500 py-4">Gagal memuat alamat.</div>';
                    });
            }

            function renderList(data) {
                listContainer.innerHTML = '';
                const mainSubmit = document.getElementById('btn-submit');

                if (!data || data.length === 0) {
                    listContainer.innerHTML =
                        '<div class="text-center py-8 text-gray-500 bg-slate-50 rounded border border-dashed">Belum ada alamat.</div>';
                    if (mainSubmit) {
                        mainSubmit.disabled = true;
                        mainSubmit.classList.add('opacity-50');
                        mainSubmit.classList.add('cursor-not-allowed');
                    }
                    return;
                }

                data.forEach(addr => {
                    const isUtama = addr.is_utama == 1;
                    const el = document.createElement('div');
                    el.className =
                        `border rounded-lg p-4 cursor-pointer mb-3 transition-all ${isUtama ? 'border-teal-500 bg-teal-50 ring-1 ring-teal-500' : 'border-gray-300 hover:border-teal-300'}`;

                    el.innerHTML = `
                        <div class="flex items-start gap-3 pointer-events-none">
                            <input type="radio" name="selectedAddressModal" value="${addr.id_alamat}" class="mt-1 w-4 h-4 text-teal-600 pointer-events-auto" ${isUtama ? 'checked' : ''}>
                            <div class="flex-1">
                                <div class="font-bold text-gray-800 text-sm">
                                    ${addr.label_alamat} 
                                    ${isUtama ? '<span class="ml-2 text-xs bg-teal-600 text-white px-2 py-0.5 rounded">Utama</span>' : ''}
                                </div>
                                <div class="text-sm text-gray-600 mt-1">${addr.nama_penerima} (${addr.telepon_penerima})</div>
                                <div class="text-xs text-gray-500 mt-1">${addr.jalan_patokan}, ${addr.kecamatan}, ${addr.kota_kabupaten}</div>
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end gap-3 text-xs font-medium border-t pt-2">
                            <button type="button" onclick="window.editAddress(${addr.id_alamat})" class="text-blue-600 hover:underline px-2 pointer-events-auto">Edit</button>
                            <button type="button" onclick="window.deleteAddress(${addr.id_alamat})" class="text-red-600 hover:underline px-2 pointer-events-auto">Hapus</button>
                        </div>
                    `;

                    el.addEventListener('click', (e) => {
                        if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'INPUT') {
                            const radio = el.querySelector('input[type="radio"]');
                            if (radio) {
                                radio.checked = true;
                                Array.from(listContainer.children).forEach(c => {
                                    c.classList.remove('border-teal-500', 'bg-teal-50',
                                        'ring-1', 'ring-teal-500');
                                    c.classList.add('border-gray-300');
                                });
                                el.classList.remove('border-gray-300');
                                el.classList.add('border-teal-500', 'bg-teal-50', 'ring-1',
                                    'ring-teal-500');
                            }
                        }
                    });
                    listContainer.appendChild(el);
                });

                // If we have at least one address, enable main submit
                if (mainSubmit) {
                    mainSubmit.disabled = false;
                    mainSubmit.classList.remove('opacity-50');
                    mainSubmit.classList.remove('cursor-not-allowed');
                }
            }

            window.editAddress = function(id) {
                Swal.fire({
                    title: 'Memuat...',
                    didOpen: () => Swal.showLoading()
                });

                fetch(`{{ url('customer/alamat/api') }}/${id}`)
                    .then(r => r.json())
                    .then(res => {
                        Swal.close();
                        if (res.success) {
                            const d = res.data;

                            document.getElementById('addressId').value = d.id_alamat;
                            document.getElementById('label_alamat').value = d.label_alamat;
                            document.getElementById('nama_penerima').value = d.nama_penerima;
                            document.getElementById('telepon_penerima').value = d.telepon_penerima;
                            document.getElementById('jalan_patokan').value = d.jalan_patokan;
                            document.getElementById('no_rumah').value = d.no_rumah || '';
                            document.getElementById('rt').value = d.rt || '';
                            document.getElementById('rw').value = d.rw || '';


                            if (kotaHidden) kotaHidden.value = d.kota_kabupaten;
                            if (kecamatanHidden) kecamatanHidden.value = d.kecamatan;
                            if (kelurahanHidden) kelurahanHidden.value = d.kelurahan;


                            kotaSelect.innerHTML =
                                `<option value="${d.kota_kabupaten}" selected>${d.kota_kabupaten}</option>`;
                            kecamatanSelect.innerHTML =
                                `<option value="${d.kecamatan}" selected>${d.kecamatan}</option>`;
                            kelurahanSelect.innerHTML =
                                `<option value="${d.kelurahan}" selected>${d.kelurahan}</option>`;

                            document.getElementById('formTitle').textContent = 'Edit Alamat';
                            formContainer.classList.remove('hidden');
                            addBtnContainer.classList.add('hidden');
                            formContainer.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        } else {
                            Swal.fire('Error', 'Gagal mengambil data alamat', 'error');
                        }
                    })
                    .catch(e => Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error'));
            };

            window.deleteAddress = function(id) {
                Swal.fire({
                    title: 'Hapus Alamat?',
                    text: "Data tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('customer/alamat/api') }}/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                }
                            })
                            .then(r => r.json())
                            .then(res => {
                                if (res.success) {
                                    Swal.fire('Terhapus!', 'Alamat berhasil dihapus.', 'success');
                                    loadAddresses();
                                } else {
                                    Swal.fire('Gagal', res.message, 'error');
                                }
                            });
                    }
                });
            };

            if (form) {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const id = addressIdInput.value;
                    const url = id ? `{{ url('customer/alamat/api') }}/${id}` :
                        '{{ route('customer.alamat.store') }}';

                    const formData = new FormData(form);
                    if (id) formData.append('_method', 'PUT');

                    Swal.fire({
                        title: 'Menyimpan...',
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        })
                        .then(r => r.json())
                        .then(res => {
                            if (res.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: res.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                resetFormView();
                                loadAddresses();
                            } else {
                                let msg = res.message || 'Gagal menyimpan data.';
                                if (res.errors) {
                                    msg = Object.values(res.errors).flat().join('\n');
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: msg
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan sistem.'
                            });
                        });
                });
            }

            if (btnSubmitModal) {
                btnSubmitModal.addEventListener('click', () => {
                    const selected = document.querySelector('input[name="selectedAddressModal"]:checked');
                    if (!selected) return Swal.fire('Info', 'Pilih salah satu alamat dulu.', 'info');

                    btnSubmitModal.textContent = 'Menyimpan...';

                    fetch(`{{ url('customer/alamat/api') }}/${selected.value}/utama`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Content-Type': 'application/json'
                        }
                    }).then(r => r.json()).then(data => {
                        if (data.success) {
                            Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: 'Alamat pengiriman diubah.',
                                    timer: 1000,
                                    showConfirmButton: false
                                })
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Gagal', data.message, 'error');
                            btnSubmitModal.textContent = 'Pilih Alamat Ini';
                        }
                    }).catch(() => {
                        Swal.fire('Error', 'Gagal koneksi server', 'error');
                        btnSubmitModal.textContent = 'Pilih Alamat Ini';
                    });
                });
            }

            if (btnAdd) {
                btnAdd.addEventListener('click', () => {
                    resetFormView();
                    document.getElementById('formTitle').textContent = 'Tambah Alamat Baru';
                    formContainer.classList.remove('hidden');
                    addBtnContainer.classList.add('hidden');


                    if (provinsiSelect) fetchData('provinces', provinsiSelect, 'Provinsi');
                    formContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                });
            }

            if (btnCancelForm) {
                btnCancelForm.addEventListener('click', resetFormView);
            }

            async function fetchData(endpoint, selectElement, placeholder) {
                try {
                    const response = await fetch(`${API_WILAYAH}/${endpoint}`);
                    const result = await response.json();
                    const data = result.data || result || [];

                    selectElement.innerHTML = `<option value="">Pilih ${placeholder}</option>`;

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.name;
                        selectElement.appendChild(option);
                    });
                } catch (error) {
                    console.error(`Error fetching ${endpoint}:`, error);
                }
            }


            if (provinsiSelect) {
                provinsiSelect.addEventListener('change', function() {
                    if (this.value) {
                        fetchData(`regencies/${this.value}`, kotaSelect, 'Kota/Kabupaten');
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                    }
                });
            }

            if (kotaSelect) {
                kotaSelect.addEventListener('change', function() {
                    if (this.value) {
                        fetchData(`districts/${this.value}`, kecamatanSelect, 'Kecamatan');
                        kelurahanSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
                        if (kotaHidden) kotaHidden.value = this.options[this.selectedIndex].text;
                    }
                });
            }

            if (kecamatanSelect) {
                kecamatanSelect.addEventListener('change', function() {
                    if (this.value) {
                        fetchData(`villages/${this.value}`, kelurahanSelect, 'Kelurahan');
                        if (kecamatanHidden) kecamatanHidden.value = this.options[this.selectedIndex].text;
                    }
                });
            }

            if (kelurahanSelect) {
                kelurahanSelect.addEventListener('change', function() {
                    if (this.value && kelurahanHidden) {
                        kelurahanHidden.value = this.options[this.selectedIndex].text;
                    }
                });
            }

        });
    </script>
@endpush

@extends('layouts.layout_customer')

@section('title', 'Keranjang Belanja')

@section('content')

    <div class="max-w-6xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">

        <h1 class="text-2xl font-bold text-slate-800 mb-6">Keranjang Belanja</h1>

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        @if ($keranjang->isEmpty())
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-12 text-center">
                <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shopping-cart text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-2">Keranjang Anda Kosong</h3>
                <p class="text-slate-500 mb-6">Sepertinya Anda belum menambahkan produk apapun.</p>
                <a href="{{ route('customer.dashboard') }}"
                    class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-medium">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden mb-6">
                <div
                    class="bg-slate-50 px-4 sm:px-6 py-4 border-b border-slate-200 flex items-center font-semibold text-slate-700 text-xs sm:text-sm">
                    <div class="w-1/12 flex justify-center">
                        <input type="checkbox" id="selectAllTop"
                            class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 cursor-pointer">
                    </div>
                    <div class="w-5/12 md:w-4/12">Produk</div>
                    <div class="hidden md:block md:w-2/12 text-right pr-8">Harga Satuan</div>
                    <div class="w-3/12 md:w-2/12 text-center">Qty</div>
                    <div class="w-3/12 md:w-2/12 text-right pr-1 sm:pr-4">Total</div>
                    <div class="hidden md:block md:w-1/12 text-center">Aksi</div>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach ($keranjang as $item)
                        @php
                            $produkDetail = $item->produkDetail;
                            $produk = $produkDetail->produk;
                            $totalHarga = $item->quantity * $produkDetail->harga_jual;
                        @endphp

                        <div class="p-4 sm:p-6 flex items-center hover:bg-slate-50 transition-colors">
                            <div class="w-1/12 flex justify-center">
                                <input type="checkbox"
                                    class="itemCheckbox w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 cursor-pointer"
                                    data-id="{{ $item->id_produk_detail }}">
                            </div>

                            <div class="w-5/12 md:w-4/12">
                                <a href="{{ route('customer.produk.detail', $produk->id_produk) }}"
                                    class="flex gap-2 sm:gap-4 group items-center">
                                    <div
                                        class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 border border-slate-200 rounded-md overflow-hidden bg-white">
                                        <img src="{{ $produk->foto_url }}" loading="lazy" decoding="async"
                                            class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"
                                            onerror="this.src='{{ asset('images/icon_toska.png') }}'">
                                    </div>
                                    <div class="flex flex-col justify-center flex-1">
                                        <p
                                            class="font-medium text-slate-800 group-hover:text-teal-600 transition-colors line-clamp-2 text-xs sm:text-sm">
                                            {{ $produk->nama }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1 bg-slate-100 px-2 py-0.5 rounded w-fit">
                                            {{ $produkDetail->nama_varian }}
                                        </p>
                                        <div class="md:hidden mt-2">
                                            <form
                                                action="{{ route('customer.keranjang.destroy', $item->id_produk_detail) }}"
                                                method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="hidden md:block md:w-2/12 text-right pr-8 text-slate-600 text-sm">
                                Rp {{ number_format($produkDetail->harga_jual, 0, ',', '.') }}
                            </div>

                            <div class="w-3/12 md:w-2/12 flex justify-center">
                                <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden">
                                    <button
                                        class="minusBtn px-2 sm:px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 transition text-sm"
                                        data-id="{{ $item->id_produk_detail }}">−</button>

                                    <input type="number" value="{{ $item->quantity }}" min="1"
                                        class="qtyInput w-10 sm:w-12 text-center border-x border-slate-300 text-xs sm:text-sm focus:outline-none py-1"
                                        data-id="{{ $item->id_produk_detail }}">

                                    <button
                                        class="plusBtn px-2 sm:px-3 py-1 bg-slate-50 hover:bg-slate-100 text-slate-600 transition text-sm"
                                        data-id="{{ $item->id_produk_detail }}">+</button>
                                </div>
                            </div>

                            <div class="w-3/12 md:w-2/12 text-right pr-1 sm:pr-4 font-bold text-orange-600 text-xs sm:text-base"
                                id="total-{{ $item->id_produk_detail }}" data-price="{{ $produkDetail->harga_jual }}">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </div>

                            <div class="hidden md:block md:w-1/12 text-center">
                                <form action="{{ route('customer.keranjang.destroy', $item->id_produk_detail) }}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                        class="btn-delete text-slate-400 hover:text-red-500 transition-colors tooltip"
                                        title="Hapus Item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 sm:p-6 sticky bottom-0 z-10">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="selectAll"
                                class="w-4 h-4 text-teal-600 rounded border-slate-300 focus:ring-teal-500 cursor-pointer">
                            <label for="selectAll" class="text-slate-700 font-medium cursor-pointer">Pilih Semua ({{ $keranjang->count() }})</label>
                        </div>
                        <span class="text-slate-300">|</span>
                        <button id="deleteSelected"
                            class="text-red-500 hover:text-red-700 font-medium hover:underline disabled:opacity-50">
                            Hapus
                        </button>

                        <form id="formBulkDelete" action="{{ route('customer.keranjang.destroyBulk') }}" method="POST"
                            style="display: none;">
                            @csrf @method('DELETE')
                            <input type="hidden" name="selected_ids" id="bulkDeleteInput">
                        </form>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-right w-full sm:w-auto">
                            <p class="text-slate-500 text-xs">Total (<span id="selectedCount">0</span> produk):</p>
                            <p class="text-orange-600 font-bold text-lg sm:text-xl">Rp <span id="grandTotal">0</span></p>
                        </div>

                        <form id="formCheckoutReal" action="{{ route('customer.keranjang.checkout') }}" method="POST" class="w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="items" id="itemsInputHidden">

                            <button type="submit"
                                class="w-full sm:w-auto bg-orange-500 hover:bg-orange-600 text-white px-6 sm:px-8 py-3 rounded-lg font-bold shadow-lg shadow-orange-500/30 transition-all hover:scale-105 active:scale-95">
                                Checkout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateTotal(id, qty) {
                const totalEl = document.getElementById('total-' + id);
                const price = parseFloat(totalEl.dataset.price);
                totalEl.textContent = 'Rp ' + (price * qty).toLocaleString('id-ID').replace(/,/g, '.');
                updateSelectedInfo();
            }

            document.querySelectorAll('.plusBtn, .minusBtn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const input = document.querySelector('.qtyInput[data-id="' + id + '"]');
                    let val = parseInt(input.value);

                    if (this.classList.contains('plusBtn')) val++;
                    if (this.classList.contains('minusBtn') && val > 1) val--;

                    input.value = val;
                    updateTotal(id, val);

                });
            });

            document.querySelectorAll('.qtyInput').forEach(input => {
                input.addEventListener('input', function() {
                    if (this.value < 1) this.value = 1;
                    updateTotal(this.dataset.id, parseInt(this.value));
                });
            });

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    let form = this.closest("form");
                    Swal.fire({
                        title: 'Hapus produk?',
                        text: "Produk akan dihapus dari keranjang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, hapus',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            function updateSelectedInfo() {
                let selectedCount = 0;
                let grandTotal = 0;

                document.querySelectorAll('.itemCheckbox').forEach(chk => {
                    if (chk.checked) {
                        selectedCount++;
                        const id = chk.dataset.id;
                        const qty = parseInt(document.querySelector('.qtyInput[data-id="' + id + '"]')
                            .value);
                        const price = parseFloat(document.getElementById('total-' + id).dataset.price);
                        grandTotal += qty * price;
                    }
                });

                document.getElementById('selectedCount').textContent = selectedCount;
                document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('id-ID');
            }


            document.getElementById('selectAllTop')?.addEventListener('change', function() {
                const checked = this.checked;
                document.getElementById('selectAll').checked = checked;
                document.querySelectorAll('.itemCheckbox').forEach(chk => chk.checked = checked);
                updateSelectedInfo();
            });

            document.getElementById('selectAll')?.addEventListener('change', function() {
                const checked = this.checked;
                document.getElementById('selectAllTop').checked = checked;
                document.querySelectorAll('.itemCheckbox').forEach(chk => chk.checked = checked);
                updateSelectedInfo();
            });

            document.querySelectorAll('.itemCheckbox').forEach(chk => {
                chk.addEventListener('change', function() {
                    const all = document.querySelectorAll('.itemCheckbox').length;
                    const checked = document.querySelectorAll('.itemCheckbox:checked').length;


                    const allChecked = (all > 0 && all === checked);
                    if (document.getElementById('selectAll')) document.getElementById('selectAll')
                        .checked = allChecked;
                    if (document.getElementById('selectAllTop')) document.getElementById(
                        'selectAllTop').checked = allChecked;

                    updateSelectedInfo();
                });
            });


            document.getElementById('formCheckoutReal')?.addEventListener('submit', function(e) {
                const selected = [];
                document.querySelectorAll('.itemCheckbox:checked').forEach(chk => {
                    const id = chk.dataset.id;
                    const qtyInput = document.querySelector('.qtyInput[data-id="' + id + '"]');
                    if (qtyInput) {
                        selected.push({
                            id_produk_detail: id,
                            quantity: parseInt(qtyInput.value)
                        });
                    }
                });

                if (selected.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum ada produk',
                        text: 'Silakan centang minimal 1 produk untuk checkout.',
                        confirmButtonColor: '#f97316'
                    });
                    return false;
                }

                document.getElementById('itemsInputHidden').value = JSON.stringify(selected);
                return true;
            });

            document.getElementById('deleteSelected')?.addEventListener('click', function() {
                const selectedIds = [];
                document.querySelectorAll('.itemCheckbox:checked').forEach(chk => {
                    selectedIds.push(chk.dataset.id);
                });

                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Produk',
                        text: 'Silakan centang produk yang ingin dihapus.',
                        confirmButtonColor: '#f97316'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Hapus ' + selectedIds.length + ' produk?',
                    text: "Produk yang dipilih akan dihapus permanen dari keranjang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Ya, hapus semua',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('bulkDeleteInput').value = selectedIds.join(',');
                        document.getElementById('formBulkDelete').submit();
                    }
                });
            });
        });
    </script>

@endsection

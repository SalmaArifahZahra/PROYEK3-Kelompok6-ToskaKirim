@extends('layouts.layout_customer')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="max-w-6xl mx-auto mt-6">

        <h1 class="text-2xl font-semibold mb-6">Keranjang Belanja</h1>

        @if ($keranjang->isEmpty())

            <p class="text-center text-gray-500 py-10">Keranjang kosong.</p>
        @else
            <div class="bg-white p-4 rounded-lg shadow-[0_1px_4px_rgba(0,0,0,0.05)] flex font-semibold text-gray-700">

                <div class="w-1/12 flex justify-center">
                    <input type="checkbox" id="selectAllTop" class="w-4 h-4">
                </div>

                <div class="w-2/6">Produk</div>
                <div class="w-1/6">Harga Satuan</div>
                <div class="w-1/6">Kuantitas</div>
                <div class="w-1/6">Total Harga</div>
                <div class="w-1/6 text-center">Aksi</div>
            </div>

            <div class="mt-2 space-y-4">

                @foreach ($keranjang as $item)
                    @php
                        $produkDetail = $item->produkDetail;
                        $produk = $produkDetail->produk;
                        $totalHarga = $item->quantity * $produkDetail->harga_jual;
                    @endphp

                    <div class="bg-white p-4 rounded-lg shadow-[0_1px_4px_rgba(0,0,0,0.05)] flex items-center">

                        <div class="w-1/12 flex justify-center">
                            <input type="checkbox" class="itemCheckbox w-4 h-4" data-id="{{ $item->id_produk_detail }}">
                        </div>

                        <div class="w-2/6">
                            <a href="{{ route('customer.produk.detail', $produk->id_produk) }}" class="flex gap-4 group">

                                <img src="{{ $produk->foto_url }}"
                                    class="w-20 h-20 rounded object-contain group-hover:opacity-80 transition-opacity duration-300">

                                <div class="flex flex-col justify-center">
                                    <p class="font-medium group-hover:text-orange-500 transition-colors duration-300">
                                        {{ $produk->nama }}
                                    </p>
                                    <p class="text-sm text-gray-500">Varian: {{ $produkDetail->nama_varian }}</p>
                                </div>
                            </a>
                        </div>

                        <div class="w-1/6 font-semibold text-gray-700">
                            Rp {{ number_format($produkDetail->harga_jual) }}
                        </div>

                        <div class="w-1/6 flex items-center">
                            <div class="flex items-center gap-2">
                                <button class="minusBtn px-3 py-1 border rounded  hover:bg-gray-100"
                                    data-id="{{ $item->id_produk_detail }}">-</button>

                                <input type="number" value="{{ $item->quantity }}" min="1"
                                    class="qtyInput w-14 text-center border rounded "
                                    data-id="{{ $item->id_produk_detail }}">

                                <button class="plusBtn px-3 py-1 border rounded  hover:bg-gray-100"
                                    data-id="{{ $item->id_produk_detail }}">+</button>
                            </div>
                        </div>

                        <div class="w-1/6 font-bold text-red-600" id="total-{{ $item->id_produk_detail }}"
                            data-price="{{ $produkDetail->harga_jual }}">
                            Rp {{ number_format($totalHarga) }}
                        </div>

                        <div class="w-1/6 flex justify-center items-center">
                            <form action="{{ route('customer.keranjang.destroy', $item->id_produk_detail) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                    class="btn-delete px-3 py-1 border border-red-500 text-red-500 rounded hover:bg-red-50 transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>
                @endforeach

            </div>

            <div class="mt-6 bg-white p-4 rounded-lg shadow-[0_1px_4px_rgba(0,0,0,0.05)] flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="selectAll" class="w-4 h-4">
                    <label for="selectAll" class="text-gray-700">Pilih Semua ({{ $keranjang->count() }})</label>

                    <button id="deleteSelected" class="ml-4 text-red-500 hover:underline">
                        Hapus
                    </button>

                    <form id="formBulkDelete" action="{{ route('customer.keranjang.destroyBulk') }}" method="POST"
                        style="display: none;">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="selected_ids" id="bulkDeleteInput">
                    </form>
                </div>

                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-gray-600 text-sm">Total (<span id="selectedCount">0</span> produk):</p>
                        <p class="text-red-600 font-bold text-xl">Rp <span id="grandTotal">0</span></p>
                    </div>

                    <form id="formCheckoutReal" action="{{ route('customer.keranjang.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="items" id="itemsInputHidden">

                        <button type="submit"
                            class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 font-bold shadow-md transition-transform transform hover:scale-105">
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function updateTotal(id, qty) {
                const totalEl = document.getElementById('total-' + id);
                const price = parseFloat(totalEl.dataset.price);
                totalEl.textContent = 'Rp ' + (price * qty).toLocaleString('id-ID');
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
                        title: 'Yakin ingin menghapus?',
                        text: "Produk ini akan dihapus dari keranjang.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
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



            document.getElementById('selectAllTop').addEventListener('change', function() {
                const checked = this.checked;

                document.getElementById('selectAll').checked = checked;
                document.querySelectorAll('.itemCheckbox').forEach(chk => chk.checked = checked);

                updateSelectedInfo();
            });



            document.getElementById('selectAll').addEventListener('change', function() {
                const checked = this.checked;

                document.getElementById('selectAllTop').checked = checked;
                document.querySelectorAll('.itemCheckbox').forEach(chk => chk.checked = checked);

                updateSelectedInfo();
            });



            document.querySelectorAll('.itemCheckbox').forEach(chk => {
                chk.addEventListener('change', function() {

                    const all = document.querySelectorAll('.itemCheckbox').length;
                    const checked = document.querySelectorAll('.itemCheckbox:checked').length;

                    document.getElementById('selectAll').checked = (all === checked);
                    document.getElementById('selectAllTop').checked = (all === checked);

                    updateSelectedInfo();
                });
            });

            document.getElementById('formCheckoutReal').addEventListener('submit', function(e) {
                const selected = [];
                document.querySelectorAll('.itemCheckbox:checked').forEach(chk => {
                    const id = chk.dataset.id;
                    const qtyInput = document.querySelector('.qtyInput[data-id="' + id + '"]');

                    if (qtyInput) {
                        const qty = parseInt(qtyInput.value);
                        selected.push({
                            id_produk_detail: id,
                            quantity: qty
                        });
                    }
                });

                if (selected.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum ada produk',
                        text: 'Silakan centang minimal 1 produk untuk checkout.'
                    });
                    return false;
                }

                const jsonString = JSON.stringify(selected);
                document.getElementById('itemsInputHidden').value = jsonString;

                console.log("Mengirim data:", jsonString);
                return true;
            });

            document.getElementById('deleteSelected').addEventListener('click', function() {


                const selectedIds = [];
                document.querySelectorAll('.itemCheckbox:checked').forEach(chk => {
                    selectedIds.push(chk.dataset.id);
                });


                if (selectedIds.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Produk',
                        text: 'Silakan centang minimal 1 produk yang ingin dihapus.'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Yakin ingin menghapus ' + selectedIds.length + ' produk?',
                    text: "Produk yang dipilih akan dihapus dari keranjang.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus semua',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {

                        document.getElementById('bulkDeleteInput').value = selectedIds.join(',');

                        // 5. Submit form
                        document.getElementById('formBulkDelete').submit();
                    }
                });
            });
        });
    </script>

@endsection

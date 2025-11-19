{{-- card produk item --}}
<div class="w-full max-w-6xl mx-auto mt-10 px-4">

    <h2 class="text-xl font-medium mb-4">Produk Pilihan</h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">

        @foreach ($produk as $item)
            <div class="w-full bg-white p-6 border border-gray-200 rounded-xl shadow-sm flex flex-col">

                <div class="w-full h-40 mb-4">
                    <a href="#">
                        <img src="{{ asset($item->foto) }}" alt="{{ $item->nama }}"
                            class="rounded-lg w-full h-full object-contain" />
                    </a>
                </div>

                <div class="h-14 overflow-hidden">
                    <h5 class="text-lg font-normal text-gray-900 tracking-tight">
                        {{ $item->nama }}
                    </h5>
                </div>

                <div class="flex items-center justify-between mt-auto pt-4">
                    <span class="text-base font-extrabold text-gray-900">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </span>

                    <button type="button"
                        class="inline-flex items-center text-white bg-[#5BC6BC] border border-transparent focus:ring-4 focus:ring-teal-200 shadow-sm font-medium rounded-lg text-sm px-3 py-2 focus:outline-none">
                        Lihat Detail
                    </button>
                </div>

            </div>
        @endforeach

    </div>

</div>

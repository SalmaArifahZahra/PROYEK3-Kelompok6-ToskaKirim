{{-- card produk item --}}
<div class="w-full max-w-6xl mx-auto mt-10 px-4">

    @if (!isset($hideTitle) || $hideTitle === false)
        <h2 class="text-xl font-medium mb-4">Produk Pilihan</h2>
    @endif

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
        @foreach ($produk as $item)
            <a href="{{ route('customer.produk.detail', $item->id_produk) }}"
                class="w-full bg-white p-6 rounded-xl border border-transparent transition-all duration-300 hover:border-[#3A767E] hover:shadow-sm active:border-[#3A767E] active:scale-[0.98]">

                <div class="w-full h-40 mb-4">
                    <img src="{{ $item->foto_url }}" alt="{{ $item->nama }}"
                        class="rounded-lg w-full h-full object-contain" />
                </div>

                <div class="h-14 overflow-hidden">
                    <h5 class="text-lg font-normal text-black tracking-tight">
                        {{ $item->nama }}
                    </h5>
                </div>

                <div class="flex items-center justify-between mt-auto pt-4">
                    <span class="text-lg font-bold text-[#3A767E]">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </span>
                </div>

            </a>
        @endforeach
    </div>
</div>

{{-- card produk item --}}
<div class="w-full max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8">

    @if (!isset($hideTitle) || $hideTitle === false)
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class=" text-teal-600 text-lg"></i> Produk Pilihan
            </h2>
        </div>
    @endif


    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
        @foreach ($produk as $item)
            <a href="{{ route('customer.produk.detail', $item->id_produk) }}"
               class="group bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:border-teal-300 transition-all duration-300 hover:-translate-y-1 flex flex-col h-full relative">


                <div class="relative w-full aspect-square bg-slate-50 overflow-hidden">
                    @if(isset($item->stok) && $item->stok <= 0)
                        <div class="absolute top-2 right-2 bg-slate-800/80 text-white text-[10px] px-2 py-1 rounded font-bold z-10">
                            HABIS
                        </div>
                    @endif

                    <img src="{{ $item->foto_url }}"
                         alt="{{ $item->nama }}"
                         class="w-full h-full object-contain p-4 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply" 
                    />
                </div>


                <div class="p-4 flex flex-col flex-grow">
                    @if(isset($item->kategori))
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-1">
                            {{ $item->kategori->nama_kategori }}
                        </p>
                    @endif

                    <h3 class="text-slate-800 font-medium text-sm md:text-base line-clamp-2 mb-3 group-hover:text-teal-600 transition-colors flex-grow leading-snug">
                        {{ $item->nama }}
                    </h3>

                    <div class="flex items-end justify-between mt-auto pt-2 border-t border-slate-50">
                        <div class="flex flex-col">
                            <span class="text-teal-600 font-bold text-base md:text-lg">
                                <span class="text-xs font-normal text-slate-500 mr-0.5">Rp</span>{{ number_format($item->harga, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-teal-600 group-hover:text-white transition-colors shadow-sm">
                            <i class="fas fa-arrow-right text-xs transform group-hover:translate-x-0.5 transition-transform"></i>
                        </div>
                    </div>
                </div>

            </a>
        @endforeach
    </div>
</div>
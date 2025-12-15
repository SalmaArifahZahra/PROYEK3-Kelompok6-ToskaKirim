{{-- Kategori Belanja Section --}}
<div class="w-full max-w-7xl mx-auto mt-12 px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl md:text-2xl font-bold text-slate-800">
            Kategori Belanja
        </h2>
    </div>

    <div
        id="scrollContainer"
        class="flex gap-4 overflow-x-auto pt-4 pb-8 -mx-4 px-4
               scrollbar-hide snap-x snap-mandatory scroll-smooth">

        @foreach ($kategori ?? [] as $item)
            <a href="{{ route('customer.kategori.index', $item->id_kategori) }}"
               class="group min-w-[150px] md:min-w-[180px] block snap-start">

                <div
                    class="relative bg-white border border-slate-200 rounded-2xl p-5 h-40
                           flex flex-col justify-between transition-all duration-300
                           hover:shadow-[0_10px_25px_-5px_rgba(0,0,0,0.1)]
                           hover:border-teal-400 hover:-translate-y-1 overflow-hidden">

                    <div
                        class="absolute -bottom-6 -right-6 w-24 h-24
                               bg-gradient-to-br from-teal-50 to-teal-100
                               rounded-full transition-transform duration-500
                               group-hover:scale-150 z-0">
                    </div>

                    <div class="z-10">
                        <h3
                            class="text-sm md:text-base font-bold text-slate-700
                                   leading-tight line-clamp-2
                                   group-hover:text-teal-700 transition-colors">
                            {{ $item->nama_kategori }}
                        </h3>
                    </div>

                    <div
                        class="self-end relative z-10 w-16 h-16
                               transform transition-transform duration-500
                               group-hover:scale-110 group-hover:-rotate-6">

                        <img
                            src="{{ asset($item->foto) }}"
                            alt="{{ $item->nama_kategori }}"
                            class="w-full h-full object-contain drop-shadow-sm"
                            onerror="this.src='{{ asset('images/no-image.png') }}'">
                    </div>

                </div>
            </a>
        @endforeach

    </div>
</div>

<div class="w-full max-w-6xl mx-auto mt-10 px-4">

    <h2 class="text-xl font-medium mb-4">Kategori Belanja</h2>

    <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">

        @foreach ($kategori ?? [] as $item)
            <a href="#" class="min-w-[150px] block">
                <div
                    class="relative border border-[#5BC6BC] rounded-xl p-4  flex flex-col justify-between h-32 hover:shadow-md transition">

                    <h3 class="text-sm font-semibold text-left leading-tight max-w-20 wrap-break-word">
                        {{ $item->nama_kategori }}
                    </h3>



                    <div class="absolute bottom-2 right-2 w-14 h-14 opacity-100">
                        <img src="{{ asset($item->foto) }}" alt="{{ $item->nama_kategori }}"
                            class="object-contain w-full h-full">
                    </div>

                </div>
            </a>
        @endforeach

    </div>
</div>

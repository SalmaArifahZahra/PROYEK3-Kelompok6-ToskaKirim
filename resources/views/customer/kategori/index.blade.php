@extends('layouts.layout_customer')

@section('title', 'Kategori - ' . $kategoriUtama->nama_kategori)

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Mobile filter toggle -->
        <div class="md:hidden mb-4">
            <button id="toggleKategori" class="w-full px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-slate-700 font-medium">
                Filter Kategori
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-6">

        <aside class="md:w-1/4 w-full bg-white p-5 rounded-lg shadow md:block hidden" id="kategoriPanel">

            <h3 class="font-semibold text-lg text-gray-900 mb-4">
                {{ $kategoriUtama->nama_kategori }}
            </h3>

            <div id="kategori-accordion" data-accordion="collapse" class="mt-1">

                <h2 id="accordion-heading-all">
                    <button type="button"
                        class="flex items-center justify-between w-full p-3 font-medium text-left text-gray-800 border border-gray-200 rounded-lg"
                        data-accordion-target="#accordion-body-all" aria-expanded="false" aria-controls="accordion-body-all">

                        <span class="text-gray-800 font-medium">Semua Produk</span>

                        <svg data-accordion-icon class="w-5 h-5 rotate-180 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                </h2>

                <div id="accordion-body-all" class="hidden p-3 border border-t-0 border-gray-200 rounded-b-lg">

                    @foreach ($subKategoris as $sub)
                        <a href="{{ route('customer.kategori.show', $sub->id_kategori) }}"
                            class="block py-2 px-1 hover:text-blue-600">
                            {{ $sub->nama_kategori }}
                        </a>
                    @endforeach

                </div>
            </div>
        </aside>

        <div class="flex-1">


            <nav class="mb-7 text-sm text-slate-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-1">
                    <li>
                        <a href="{{ route('customer.dashboard') }}" class="hover:text-teal-600 transition-colors">
                            Home
                        </a>
                    </li>

                    <li class="text-slate-400">/</li>

                    <li class="text-slate-800 font-semibold">
                        {{ $kategoriUtama->nama_kategori }}
                    </li>
                </ol>
            </nav>

            @include('component.customer.card_produk', [
                'produk' => $produkList,
                'hideTitle' => true,
            ])
        </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const toggleBtn = document.getElementById('toggleKategori');
    const panel = document.getElementById('kategoriPanel');
    if (toggleBtn && panel) {
        toggleBtn.addEventListener('click', () => {
            panel.classList.toggle('hidden');
        });
    }
</script>
@endpush

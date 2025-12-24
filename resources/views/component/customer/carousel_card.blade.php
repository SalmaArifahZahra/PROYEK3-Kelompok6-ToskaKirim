<div class="w-full max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
    
    <div class="relative group">
        <div class="swiper main-swiper overflow-hidden rounded-2xl">
            <div class="swiper-wrapper">
                {{-- Slide 1 --}}
                <div class="swiper-slide">
                    <div class="relative w-full h-40 sm:h-48 md:h-64 overflow-hidden rounded-xl">
                        <img src="{{ asset('images/slider1.png') }}" alt="Promo 1" loading="lazy"
                             class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>
                {{-- Slide 2 --}}
                <div class="swiper-slide">
                    <div class="relative w-full h-40 sm:h-48 md:h-64 overflow-hidden rounded-xl">
                        <img src="{{ asset('images/slider2.png') }}" alt="Promo 2" loading="lazy"
                             class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>
                {{-- Slide 3 --}}
                <div class="swiper-slide">
                    <div class="relative w-full h-40 sm:h-48 md:h-64 overflow-hidden rounded-xl">
                        <img src="{{ asset('images/slider1.png') }}" alt="Promo 3" loading="lazy"
                             class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>

                {{-- Slide 4 --}}
                <div class="swiper-slide">
                    <div class="relative w-full h-40 sm:h-48 md:h-64 overflow-hidden rounded-xl">
                        <img src="{{ asset('images/slider2.png') }}" alt="Promo 4" loading="lazy"
                             class="w-full h-full object-cover transform transition-transform duration-700 hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="swiper-button-prev-custom absolute top-1/2 -left-4 z-10 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-teal-600 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-teal-50">
            <i class="fas fa-chevron-left"></i>
        </div>
        <div class="swiper-button-next-custom absolute top-1/2 -right-4 z-10 -translate-y-1/2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-teal-600 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-teal-50">
            <i class="fas fa-chevron-right"></i>
        </div>

    </div>
</div>

@push('styles')
<style>
    .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #cbd5e1; 
        opacity: 1;
        transition: all 0.3s;
    }
    .swiper-pagination-bullet-active {
        background: #0d9488 !important; 
        width: 24px; 
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.main-swiper', {
                loop: true,
                spaceBetween: 20,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1, 
                        spaceBetween: 10
                    },
                    640: {
                        slidesPerView: 2, 
                        spaceBetween: 15
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    }
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next-custom',
                    prevEl: '.swiper-button-prev-custom',
                },
                grabCursor: true,
            });
        });
    </script>
@endpush
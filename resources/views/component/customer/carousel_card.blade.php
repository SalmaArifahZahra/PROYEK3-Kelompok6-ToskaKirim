<div class="w-full max-w-6xl mx-auto mt-6 px-4">

    <div class="swiper overflow-hidden">
        <div class="swiper-wrapper">
            <div class="swiper-slide rounded-lg overflow-hidden shadow">
                <img src="{{ asset('images/slider1.png') }}" alt="slide1" class="w-full h-40 object-cover">
            </div>

            <div class="swiper-slide rounded-lg overflow-hidden shadow">
                <img src="{{ asset('images/slider2.png') }}" alt="slide2" class="w-full h-40 object-cover">
            </div>

            <div class="swiper-slide rounded-lg overflow-hidden shadow">
                <img src="{{ asset('images/slider1.png') }}" alt="slide3" class="w-full h-40 object-cover">
            </div>

            <div class="swiper-slide rounded-lg overflow-hidden shadow">
                <img src="{{ asset('images/slider2.png') }}" alt="slide4" class="w-full h-40 object-cover">
            </div>
        </div>

        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>

        <div class="swiper-pagination mt-3"></div>
    </div>
</div>

<style>
    .swiper-button-prev,
    .swiper-button-next {
        color: #fff;
        width: 25px;
        height: 25px;
    }

    .swiper-pagination-bullet-active {
        background-color: #5BC6BC !important;
        opacity: 1 !important;
    }
</style>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.swiper', {
                loop: true,
                spaceBetween: 17,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                breakpoints: {
                    320: {
                        slidesPerView: 1
                    },
                    640: {
                        slidesPerView: 2
                    },
                    1024: {
                        slidesPerView: 3
                    }
                },
                grabCursor: true,
                speed: 720
            });
        });
    </script>
@endpush

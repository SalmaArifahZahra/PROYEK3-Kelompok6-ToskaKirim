<nav class="bg-[#5BC6BC] shadow-md sticky top-0 z-50">
    <div class="max-w-9xl mx-auto px-4 sm:px-6 md:px-7 py-2 md:py-3 flex items-center justify-between">

        <div class="flex items-center">
            <img src="{{ asset('images/icon_toska.png') }}" alt="ToskaKirim Logo" class="h-12 md:h-16">
        </div>

        <div class="flex items-center space-x-3 md:space-x-6">
            <!-- Desktop search -->
            <div class="hidden md:flex justify-center">
                <form id="searchForm" class="w-[280px] lg:w-[350px]" method="GET" action="{{ route('customer.produk.search') }}">
                    <div class="relative">
                        <input id="searchInput" type="text" name="q" placeholder="Search"
                            class="w-full bg-white text-gray-700 rounded-full px-5 py-2 border border-gray-300 focus:outline-none"
                            value="{{ request('q') }}">
                        <button type="submit" class="absolute top-1/2 -translate-y-1/2 right-4">
                            <i class="fas fa-search text-[#5BC6BC] text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Cart -->
            <a href="{{ route('customer.keranjang.index') }}"
                class="relative text-white text-xl hover:text-gray-100 transition">
                <i class="fas fa-shopping-cart"></i>

                @php
                    $cartCount = \App\Models\Keranjang::totalCartCount();
                @endphp

                @if ($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-full">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>

            <!-- Desktop links -->
            <a href="/" class="hidden md:inline text-white font-medium hover:text-[#174552] transition">Home</a>
            <div class="hidden md:block relative group">
                <button class="flex items-center space-x-2 text-white font-medium focus:outline-none">
                    <span>Halo, {{ Auth::user()->nama }}</span>
                    <i class="fas fa-chevron-down text-sm"></i>
                </button>

                <div
                    class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg py-3
               opacity-0 invisible group-hover:opacity-100 group-hover:visible
               transition-all duration-200 z-50">

                    <a href="{{ route('customer.pesanan.index') }}"
                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition">

                        <i class="fas fa-box text-gray-500 text-lg w-6"></i>
                        <span class="ml-2">Pesanan Saya</span>
                    </a>
                    <button id="logoutDropdownBtn"
                        class="w-full flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                        <i class="fas fa-sign-out-alt text-gray-500 text-lg w-6"></i>
                        <span class="ml-2">Logout</span>
                    </button>

                    <form id="logoutForm" method="POST" action="{{ route('logout') }}" class="hidden">
                        @csrf
                    </form>

                </div>
            </div>

            <!-- Mobile menu button -->
            <button id="mobileMenuBtn" class="md:hidden text-white text-2xl" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>

    <!-- Mobile panel -->
    <div id="mobileMenu" class="md:hidden bg-white shadow-md px-4 py-3 hidden">
        <form method="GET" action="{{ route('customer.produk.search') }}" class="mb-3">
            <div class="relative">
                <input type="text" name="q" placeholder="Search"
                       class="w-full bg-white text-gray-700 rounded-full px-4 py-2 border border-gray-300 focus:outline-none"
                       value="{{ request('q') }}">
                <button type="submit" class="absolute top-1/2 -translate-y-1/2 right-4">
                    <i class="fas fa-search text-[#5BC6BC]"></i>
                </button>
            </div>
        </form>

        <div class="flex items-center justify-between">
            <a href="/" class="text-gray-700 font-medium hover:text-[#174552] transition">Home</a>
            <a href="{{ route('customer.pesanan.index') }}" class="text-gray-700 font-medium hover:text-[#174552] transition">Pesanan Saya</a>
            <button id="logoutMobileBtn" class="text-red-600 font-medium">Logout</button>
        </div>
    </div>
</nav>

<script>
    // Toggle mobile menu
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // Bind logout to both desktop dropdown and mobile button
    document.querySelectorAll('#logoutDropdownBtn, #logoutMobileBtn').forEach(function(btn){
        if (!btn) return;
        btn.addEventListener('click', function() {
            Swal.fire({
                title: 'Apakah kamu yakin keluar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, logout!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        });
    });
</script>

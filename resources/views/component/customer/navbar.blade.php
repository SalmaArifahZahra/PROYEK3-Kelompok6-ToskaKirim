<nav class="bg-[#5BC6BC] shadow-md sticky top-0 z-50">
    <div class="max-w-9xl mx-auto px-7 py-3 flex items-center justify-between">

        <div class="flex items-center">
            <img src="{{ asset('images/icon_toska.png') }}" alt="ToskaKirim Logo" class="h-16">
        </div>

        <div class="flex items-center space-x-6">

            <div class="flex justify-center mx-10">
                <div class="relative w-[350px]">
                    <input type="text" placeholder="Search"
                        class="w-full bg-white text-gray-700 rounded-full px-5 py-2 border border-gray-300 focus:outline-none">
                    <button class="absolute top-1/2 -translate-y-1/2 right-4">
                        <i class="fas fa-search text-[#5BC6BC] text-lg"></i>
                    </button>
                </div>
            </div>
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


            <a href="/" class="text-white font-medium hover:text-[#174552] transition">Home</a>

            <span class="text-white font-medium">
                Halo, {{ Auth::user()->nama }}
            </span>
            <form id="logoutForm" action="/logout" method="POST">
                @csrf
                <button type="button" id="logoutBtn"
                    class=" hover:bg-[#dc2626] text-white font-medium px-6 py-3 rounded-lg transition-colors">
                    Logout
                </button>
            </form>

            <script>
                document.getElementById('logoutBtn').addEventListener('click', function() {
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
            </script>




        </div>

    </div>
</nav>

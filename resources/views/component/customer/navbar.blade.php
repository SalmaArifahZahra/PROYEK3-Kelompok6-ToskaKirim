<nav class="bg-[#5BC6BC] shadow-md">
    <div class="max-w-9xl mx-auto px-7 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <img src="{{ asset('images/icon_toska.png') }}" alt="ToskaKirim Logo" class="h-16">
        </div>

        <div class="flex items-center space-x-4">
            <a href="#" class="text-white text-xl hover:text-gray-100 transition">
                <i class="fas fa-shopping-cart"></i>
            </a>

            <a href="{{ route('login') }}"
                class="text-white border border-white px-4 py-1 rounded-md text-sm hover:bg-white hover:text-[#5BC6BC] transition">
                Login
            </a>
        </div>
    </div>
</nav>

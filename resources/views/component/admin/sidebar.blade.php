<aside class="fixed left-0 z-40 w-64 bg-white shadow-lg"
       style="top: 4rem; bottom: 0; height: calc(100vh - 4rem);"
       id="sidebar">

    <!-- Navigation Menu -->
    <nav class="flex flex-col h-full justify-between py-6">
        
        <!-- Main Menu -->
        <div class="px-4 space-y-2">
            
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/dashboard') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home text-lg"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Produk -->
            <a href="{{ route('admin.produk.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/produk*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-box text-lg"></i>
                <span class="font-medium">Produk</span>
            </a>

            <!-- Kategori -->
            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/kategori*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tags text-lg"></i>
                <span class="font-medium">Kategori</span>
            </a>

            <!-- Pelanggan -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/pelanggan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-users text-lg"></i>
                <span class="font-medium">Pelanggan</span>
            </a>

            <!-- Pesanan -->
            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/pesanan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-shopping-bag text-lg"></i>
                <span class="font-medium">Pesanan</span>
            </a>

            <!-- Rekap -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/rekap*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-bar text-lg"></i>
                <span class="font-medium">Rekap</span>
            </a>

        </div>

        <!-- Bottom Menu -->
        <div class="px-4 space-y-2 border-t border-gray-200 pt-4">
            
            <!-- Pengaturan -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->is('admin/pengaturan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-cog text-lg"></i>
                <span class="font-medium">Pengaturan</span>
            </a>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>

        </div>

    </nav>

</aside>

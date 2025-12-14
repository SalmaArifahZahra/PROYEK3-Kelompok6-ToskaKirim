<aside class="fixed left-0 z-40 w-64 bg-white shadow-lg"
       style="top: 4rem; bottom: 0; height: calc(100vh - 4rem);"
       id="sidebar">

    @php
        $user = Auth::user();
        $isSuperadmin = $user->peran === \App\Enums\RoleEnum::SUPERADMIN;
        
        // Helper kecil untuk class active agar kodingan tidak terlalu panjang di bawah
        $activeClass = 'bg-[#5BC6BC] text-white';
        $inactiveClass = 'text-gray-700 hover:bg-gray-100';
    @endphp

    <nav class="flex flex-col h-full justify-between py-6 overflow-y-auto">
        
        <div class="px-4 space-y-2">
            
            {{-- 1. DASHBOARD --}}
            <a href="{{ $isSuperadmin ? route('superadmin.dashboard') : route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->routeIs('*.dashboard') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-home text-lg w-6 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- 2. MENU KHUSUS SUPERADMIN --}}
            @if($isSuperadmin)
                
                {{-- GROUP: MASTER DATA --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Master Data & Kontrol
                    </p>
                </div>

                <a href="{{ route('superadmin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.users.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-users-cog text-lg w-6 text-center"></i>
                    <span class="font-medium">Kelola Admin</span>
                </a>

                <a href="{{ route('superadmin.payments.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.payments.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-wallet text-lg w-6 text-center"></i>
                    <span class="font-medium">Pembayaran</span>
                </a>

                <a href="{{ route('superadmin.kontrol_toko.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.kontrol_toko.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-store text-lg w-6 text-center"></i>
                    <span class="font-medium">Kontrol Toko</span>
                </a>

                {{-- GROUP: LOGISTIK --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Logistik & Tarif
                    </p>
                </div>

                <a href="{{ route('superadmin.layanan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.layanan.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-truck text-lg w-6 text-center"></i>
                    <span class="font-medium">Layanan Kirim</span>
                </a>

                <a href="{{ route('superadmin.promo.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.promo.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-tags text-lg w-6 text-center"></i>
                    <span class="font-medium">Promo Ongkir</span>
                </a>

                <a href="{{ route('superadmin.wilayah.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.wilayah.*') ? $activeClass : $inactiveClass }}">
                    <i class="fas fa-map-marked-alt text-lg w-6 text-center"></i>
                    <span class="font-medium">Database Wilayah</span>
                </a>

                {{-- Header Pemisah untuk Operasional (Khusus tampilan Superadmin) --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Operasional Toko
                    </p>
                </div>

            @endif

            {{-- 3. MENU OPERASIONAL (SHARED ADMIN & SUPERADMIN) --}}
            
            <a href="{{ route('admin.produk.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->routeIs('admin.produk.*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-box text-lg w-6 text-center"></i>
                <span class="font-medium">Produk</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->routeIs('admin.kategori.*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-tags text-lg w-6 text-center"></i>
                <span class="font-medium">Kategori</span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/pesanan*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-shopping-bag text-lg w-6 text-center"></i>
                <span class="font-medium">Pesanan</span>
            </a>

            <a href="{{ route('admin.pelanggan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/pelanggan*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-users text-lg w-6 text-center"></i>
                <span class="font-medium">Pelanggan</span>
            </a>

            <a href="{{ route('admin.rekap.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/rekap*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-chart-bar text-lg w-6 text-center"></i>
                <span class="font-medium">Rekap</span>
            </a>

        </div>

        {{-- 4. FOOTER MENU --}}
        <div class="px-4 space-y-2 border-t border-gray-200 pt-4">
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                  {{ request()->is('admin/pengaturan*') ? $activeClass : $inactiveClass }}">
                <i class="fas fa-cog text-lg w-6 text-center"></i>
                <span class="font-medium">Pengaturan</span>
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors">
                    <i class="fas fa-sign-out-alt text-lg w-6 text-center"></i>
                    <span class="font-medium">Logout</span>
                </button>
            </form>

        </div>
        
    </nav>

</aside>
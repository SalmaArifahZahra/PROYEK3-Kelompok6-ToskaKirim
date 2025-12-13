<aside class="fixed left-0 z-40 w-64 bg-white shadow-lg"
       style="top: 4rem; bottom: 0; height: calc(100vh - 4rem);"
       id="sidebar">

    @php
        $user = Auth::user();
        $isSuperadmin = $user->peran === \App\Enums\RoleEnum::SUPERADMIN;
    @endphp

    <nav class="flex flex-col h-full justify-between py-6 overflow-y-auto">
        
        <div class="px-4 space-y-2">
            
            <a href="{{ $isSuperadmin ? route('superadmin.dashboard') : route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                      {{ request()->routeIs('*.dashboard') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-home text-lg w-6 text-center"></i>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- MENU KHUSUS SUPERADMIN --}}
            @if($isSuperadmin)
                
                {{-- GROUP: MASTER DATA --}}
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Master Data & Kontrol
                    </p>
                </div>

                <a href="{{ route('superadmin.users.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.users.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-users-cog text-lg w-6 text-center"></i>
                    <span class="font-medium">Kelola Admin</span>
                </a>

                <a href="{{ route('superadmin.payments.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.payments.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-wallet text-lg w-6 text-center"></i>
                    <span class="font-medium">Pembayaran</span>
                </a>

                <a href="{{ route('superadmin.kontrol_toko.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.kontrol_toko.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-store text-lg w-6 text-center"></i>
                    <span class="font-medium">Kontrol Toko</span>
                </a>

                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Logistik & Tarif
                    </p>
                </div>

                <a href="{{ route('superadmin.layanan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.layanan.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-truck text-lg w-6 text-center"></i>
                    <span class="font-medium">Layanan Kirim</span>
                </a>

                <a href="{{ route('superadmin.promo.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.promo.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tags text-lg w-6 text-center"></i>
                    <span class="font-medium">Promo Ongkir</span>
                </a>

                <a href="{{ route('superadmin.wilayah.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                         {{ request()->routeIs('superadmin.wilayah.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-map-marked-alt text-lg w-6 text-center"></i>
                    <span class="font-medium">Database Wilayah</span>
                </a>

                <a href="{{ route('superadmin.kontrol_toko.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                          {{ request()->routeIs('superadmin.kontrol_toko.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
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
                          {{ request()->routeIs('superadmin.layanan.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-truck text-lg w-6 text-center"></i>
                    <span class="font-medium">Layanan Kirim</span>
                </a>

                <a href="{{ route('superadmin.promo.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                          {{ request()->routeIs('superadmin.promo.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tags text-lg w-6 text-center"></i>
                    <span class="font-medium">Promo Ongkir</span>
                </a>

                <a href="{{ route('superadmin.wilayah.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                          {{ request()->routeIs('superadmin.wilayah.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-map-marked-alt text-lg w-6 text-center"></i>
                    <span class="font-medium">Database Wilayah</span>
                </a>
                
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Operasional Toko
                    </p>
                </div>

            @endif

            {{-- MENU UMUM (ADMIN & SUPERADMIN) --}}
            
            <a href="{{ route('admin.produk.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->routeIs('admin.produk.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-box text-lg w-6 text-center"></i>
                <span class="font-medium">Produk</span>
            </a>

            <a href="{{ route('admin.kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->routeIs('admin.kategori.*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-tags text-lg w-6 text-center"></i>
                <span class="font-medium">Kategori</span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/pesanan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-shopping-bag text-lg w-6 text-center"></i>
                <span class="font-medium">Pesanan</span>
            </a>

            <a href="{{ route('admin.pelanggan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/pelanggan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-users text-lg w-6 text-center"></i>
                <span class="font-medium">Pelanggan</span>
            </a>

            <a href="{{ route('admin.rekap.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                     {{ request()->is('admin/rekap*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                <i class="fas fa-chart-bar text-lg w-6 text-center"></i>
                <span class="font-medium">Rekap</span>
            </a>

        </div>

        <div class="px-4 space-y-2 border-t border-gray-200 pt-4">
            
            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors
                  {{ request()->is('admin/pengaturan*') ? 'bg-[#5BC6BC] text-white' : 'text-gray-700 hover:bg-gray-100' }}">
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
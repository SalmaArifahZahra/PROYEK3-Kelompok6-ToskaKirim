<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superadmin Dashboard - ToskaKirim</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <aside class="w-64 bg-[#2A9D8F] text-white flex flex-col">
            <div class="p-6 text-2xl font-bold border-b border-[#264653]">ToskaKirim</div>
            
            <nav class="flex-1 mt-6 overflow-y-auto">
                <div class="px-4 mb-2 text-xs font-semibold text-gray-200 uppercase tracking-wider">
                    Menu Utama
                </div>
                
                <a href="{{ route('superadmin.dashboard') }}" 
                   class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.dashboard') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-home w-6 text-center mr-2"></i> Dashboard
                </a>
                
                <a href="{{ route('superadmin.users.index') }}" 
                   class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.users.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-users-cog w-6 text-center mr-2"></i> Kelola Admin
                </a>
                
                <a href="{{ route('superadmin.payments.index') }}" 
                   class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.payments.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-wallet w-6 text-center mr-2"></i> Metode Pembayaran
                </a>

                <a href="{{ route('superadmin.kontrol_toko.index') }}" 
                class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.kontrol_toko.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-store w-6 text-center mr-2"></i> Kontrol Toko
                </a>

                <div class="px-4 mt-6 mb-2 text-xs font-semibold text-gray-200 uppercase tracking-wider">Logistik & Tarif</div>

                <a href="{{ route('superadmin.layanan.index') }}" 
                class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.layanan.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-truck w-6 text-center mr-2"></i> Layanan Pengiriman
                </a>

                <a href="{{ route('superadmin.promo.index') }}" 
                class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.promo.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-tags w-6 text-center mr-2"></i> Promo Ongkir
                </a>

                <a href="{{ route('superadmin.wilayah.index') }}" 
                class="block py-2.5 px-4 hover:bg-[#264653] transition flex items-center {{ request()->routeIs('superadmin.wilayah.*') ? 'bg-[#21867a]' : '' }}">
                    <i class="fas fa-map-marked-alt w-6 text-center mr-2"></i> Database Wilayah
                </a>
            </nav>

             <div class="p-4 border-t border-[#264653]">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full py-2.5 px-4 text-red-100 hover:bg-red-700 hover:text-white rounded transition">
                        <i class="fas fa-sign-out-alt w-6 text-center mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow p-4 flex justify-between items-center z-10">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                <div class="text-sm font-bold text-gray-700">Halo, Superadmin</div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto bg-[#F4F7F6]">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen">
        <aside class="w-64 bg-[#2A9D8F] text-white">
            <div class="p-6 text-2xl font-bold">ToskaKirim</div>
            <nav class="mt-6">
                <a href="{{ route('superadmin.dashboard') }}" class="block py-2.5 px-4 hover:bg-[#264653]">Dashboard</a>
                <a href="{{ route('superadmin.users.index') }}" class="block py-2.5 px-4 hover:bg-[#264653]">Kelola Admin</a>
                <a href="{{ route('superadmin.payments.index') }}" class="block py-2.5 px-4 hover:bg-[#264653]">Metode Pembayaran</a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow p-4 flex justify-between">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title')</h2>
                <div>Halo, Superadmin</div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
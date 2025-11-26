<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-[#f3f4f6]">

    @include('component.admin.navbar')

    @include('component.admin.sidebar')

    <!-- Main Content -->
    <div class="ml-64 min-h-screen flex flex-col pt-16">

        <main class="flex-1 p-6">
            @yield('content')
        </main>

        @include('component.admin.footer')

    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('scripts')

</body>

</html>


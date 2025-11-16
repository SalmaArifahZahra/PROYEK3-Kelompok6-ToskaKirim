<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

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

    @stack('scripts')

</body>

</html>


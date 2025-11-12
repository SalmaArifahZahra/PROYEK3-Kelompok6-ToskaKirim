<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard Admin - ToskaKirim</title>

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

<body class="bg-[#DEE1EA] min-h-screen flex items-center justify-center">

    <div class="text-center">
        <h1 class="text-[2.5rem] font-bold text-[#14b8a6] mb-4">Dashboard Admin</h1>
        <p class="text-gray-600 mb-8">Selamat datang di dashboard ToskaKirim</p>

        <form action="/logout" method="POST">
            @csrf
            <button type="submit"
                    class="bg-[#ef4444] hover:bg-[#dc2626] text-white font-medium px-6 py-3 rounded-lg transition-colors">
                Logout
            </button>
        </form>
    </div>

</body>

</html>

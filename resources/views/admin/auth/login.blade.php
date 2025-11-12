<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Login - ToskaKirim</title>

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Hilangkan icon show/hide password bawaan browser */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            pointer-events: none;
            position: absolute;
            right: 0;
        }
    </style>

</head>

<body class="bg-[#E9F4FF] min-h-screen flex flex-col items-center justify-between p-4 py-8">
    
    <!-- Spacer atas -->
    <div></div>
    
    <!-- Main Content - di tengah -->
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-4">
                <img src="{{ asset('images/icon_toska.png') }}" class="h-16 w-auto" alt="ToskaKirim Logo">
                <div class="text-center">
                    <h1 class="text-[3rem] font-bold text-[#5BC6BC] leading-none">ADMIN</h1>
                    <p class="text-gray-600 text-sm">Sign in to your account</p>
                </div>
            </div>
        </div>

        <!-- Login Form Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if (session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    <span class="font-medium">Error!</span> {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            <form action="/login" method="POST" class="space-y-6">
                @csrf

                <!-- Username Input -->
                <div>
                    <label class="block text-gray-700 mb-2 text-sm font-medium">Username</label>
                    <div class="flex items-center border border-[#d1d5db] rounded-lg bg-white">
                        <span class="px-3 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </span>
                        <input type="text" name="username"
                               class="w-full py-2.5 bg-transparent outline-none text-gray-900"
                               placeholder="Enter your Username"
                               value="{{ old('username') }}"
                               required>
                    </div>
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-gray-700 mb-2 text-sm font-medium">Password</label>
                    <div class="flex items-center border border-[#d1d5db] rounded-lg bg-white">
                        <span class="px-3 text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>

                        <input type="password" id="password" name="password"
                               class="w-full py-2.5 bg-transparent outline-none text-gray-900"
                               placeholder="••••••••"
                               required>

                        <span id="togglePassword" class="px-3 text-gray-500 cursor-pointer">
                            <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </span>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-[#5BC6BC] text-white py-2.5 rounded-lg hover:bg-[#3A767E] font-medium transition-colors">
                    Sign In
                </button>

            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="w-full text-center pt-8">
        <p class="text-sm text-gray-600">
            © 2025 <a href="#" class="text-[#14b8a6] hover:text-[#0f9488] font-medium">ToskaKirim</a>. All rights reserved.
        </p>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eye-open');
            const eyeClosed = document.getElementById('eye-closed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
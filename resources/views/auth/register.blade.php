<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    @vite(['resources/css/app.css'])
</head>

<body class="bg-[#e8ebf1] min-h-screen flex items-center justify-center px-4 py-6">

    <div class="flex flex-col-reverse lg:flex-row w-full lg:w-[900px] bg-white rounded-xl overflow-hidden shadow-lg">

        <div class="flex-1 flex items-center justify-center px-4 sm:px-8 lg:px-10 py-8 sm:py-12 lg:py-14">
            <div class="w-full max-w-xs">

                <div class="flex justify-center mb-6 lg:mb-6">
                    <img src="{{ asset('images/icon_toska.png') }}" class="w-16 sm:w-20 h-16 sm:h-20 object-contain" alt="Logo">
                </div>

                <h2 class="text-[#5BC6BC] text-center text-xl sm:text-2xl font-bold">SIGN UP</h2>
                <p class="text-center text-gray-500 mb-6 text-xs sm:text-sm">Create a new account</p>

                @if ($errors->any())
                    <div class="bg-red-100 text-red-600 p-3 rounded-md mb-4 text-xs sm:text-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4 text-xs sm:text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('register.action') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-gray-700 mb-1 text-xs sm:text-sm">Nama Lengkap</label>
                        <div class="flex items-center border border-[#bcd3e9] rounded-lg bg-white">
                            <span class="px-3 text-gray-500 text-sm sm:text-base"><i class="fa-solid fa-user"></i></span>
                            <input type="text" name="nama" required
                                class="w-full py-2 sm:py-2.5 px-2 bg-transparent outline-none text-sm"
                                placeholder="Masukkan Nama Lengkap Anda"
                                value="{{ old('nama') }}"> 
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-xs sm:text-sm">Email</label>
                        <div class="flex items-center border border-[#bcd3e9] rounded-lg bg-white">
                            <span class="px-3 text-gray-500 text-sm sm:text-base"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" required
                                class="w-full py-2 sm:py-2.5 px-2 bg-transparent outline-none text-sm"
                                placeholder="Masukkan Email Anda"
                                value="{{ old('email') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-xs sm:text-sm">Password</label>
                        <div class="flex items-center border border-[#bcd3e9] rounded-lg bg-white">
                            <span class="px-3 text-gray-500 text-sm sm:text-base"><i class="fa-solid fa-lock"></i></span>

                            <input type="password" id="password" name="password" required
                                class="w-full py-2 sm:py-2.5 px-2 bg-transparent outline-none text-sm"
                                placeholder="Masukkan Password Anda">

                            <span id="togglePassword" class="px-3 text-gray-500 cursor-pointer text-sm sm:text-base">
                                <i class="fa-solid fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1 text-xs sm:text-sm">Konfirmasi Password</label>
                        <div class="flex items-center border border-[#bcd3e9] rounded-lg bg-white">
                            <span class="px-3 text-gray-500 text-sm sm:text-base"><i class="fa-solid fa-lock"></i></span>
                            
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full py-2 sm:py-2.5 px-2 bg-transparent outline-none text-sm"
                                placeholder="Ketik Ulang Password Anda">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-[#5BC6BC] text-white py-2.5 rounded-lg hover:bg-[#3A767E] font-medium text-sm sm:text-base">
                        Sign Up
                    </button>

                    <div class="text-center text-xs sm:text-sm">
                        <span class="text-gray-600">Sudah punya akun?</span>
                        <a href="/" class="text-[#2d9cdb] hover:underline font-medium">Sign In</a>
                    </div>

                </form>

            </div>
        </div>

        <div class="hidden lg:flex flex-1 bg-[#5BC6BC] text-white flex-col justify-center px-8 sm:px-12 lg:px-16 py-8 sm:py-10">

            <h1 class="font-extrabold text-2xl sm:text-3xl lg:text-4xl leading-tight mb-4">
                JOIN US,<br>WELCOME!
            </h1>

            <p class="opacity-90 text-sm sm:text-base mb-6">
                Create your account and start your journey with us today.
            </p>

            <p class="text-white text-xs sm:text-sm opacity-90 mb-3">
                Already have an account?
            </p>

            <a href="/login"
                class="inline-block w-fit border border-white text-white px-6 py-2 rounded-full text-sm font-medium
                       hover:bg-white hover:text-[#2d9cdb]">
                Sign In
            </a>

        </div>

    </div>


    <script>
        document.getElementById('togglePassword').addEventListener('click', function () {
            const input = document.getElementById('password');
            const icon = this.querySelector('i');

            input.type = input.type === "password" ? "text" : "password";
            icon.classList.toggle("fa-eye");
            icon.classList.toggle("fa-eye-slash");
        });
    </script>

</body>
</html>

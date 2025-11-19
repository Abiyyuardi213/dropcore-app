<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Customer - Garuda Fiber</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="h-screen w-full">

    <div class="flex h-full">

        <!-- LEFT SIDE (Background Image) -->
        <div class="hidden md:block w-1/2 bg-cover bg-center"
             style="background-image: url('{{ asset('image/warehouse.jpg') }}');">
        </div>

        <!-- RIGHT SIDE (Login Area) -->
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-100 px-6">

            <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

                <h1 class="text-3xl font-bold text-blue-700 text-center mb-1">Login Customer</h1>
                <p class="text-gray-500 text-center mb-6">Silahkan masuk untuk melanjutkan</p>

                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.customer') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block mb-2 text-sm font-medium">Email atau Username</label>
                        <input type="text" name="login"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            placeholder="Email / Username" required value="{{ old('login') }}">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium">Password</label>
                        <input type="password" name="password"
                            class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                            placeholder="Masukkan password" required>
                    </div>

                    <button type="submit"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 font-semibold rounded-lg text-sm px-5 py-2.5">
                        Masuk
                    </button>
                </form>

                <!-- LOGIN GOOGLE -->
                <div class="my-5 flex items-center">
                    <div class="flex-grow h-px bg-gray-300"></div>
                    <span class="px-3 text-gray-500 text-sm">atau</span>
                    <div class="flex-grow h-px bg-gray-300"></div>
                </div>

                <a href="#"
                class="w-full flex items-center justify-center gap-3 border border-gray-300 rounded-lg py-2.5 text-sm font-semibold hover:bg-gray-100 transition">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="">
                    Login dengan Google
                </a>

                <!-- LINK DAFTAR -->
                <p class="text-center mt-4 text-sm">
                    Belum punya akun?
                    <a href="#" class="text-blue-700 font-semibold hover:underline">
                        Daftar Akun
                    </a>
                </p>

            </div>

        </div>
    </div>

    <footer class="absolute bottom-3 right-3 text-gray-600 text-xs">
        © 2025 Garuda Fiber • Customer Portal
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>

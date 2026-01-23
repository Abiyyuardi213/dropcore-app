<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Garuda Fiber</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background font-sans text-foreground antialiased min-h-screen">

    <div class="flex min-h-screen">

        <!-- LEFT SIDE (Login Area) -->
        <div class="flex flex-col w-full lg:w-[480px] p-8 md:p-12 relative z-10 bg-background">
            <div class="mb-auto">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-bold text-xl tracking-tighter">
                    <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo" class="h-8 w-auto">
                </a>
            </div>

            <div class="w-full max-w-sm mx-auto">
                <div class="space-y-2 mb-8">
                    <h1 class="text-2xl font-bold tracking-tight">Selamat Datang Kembali</h1>
                    <p class="text-sm text-muted-foreground">
                        Masukkan akun Anda untuk mengakses portal distributor.
                    </p>
                </div>

                @if ($errors->any())
                    <div
                        class="mb-6 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                        <i class="bi bi-exclamation-circle text-base"></i>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ route('login.distributor') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="login">
                            Email atau Username
                        </label>
                        <input type="text" name="login" id="login"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="nama@email.com" required value="{{ old('login') }}">
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                for="password">
                                Kata Sandi
                            </label>
                            <a href="#"
                                class="text-xs text-muted-foreground hover:text-primary underline-offset-4 hover:underline">
                                Lupa sandi?
                            </a>
                        </div>
                        <input type="password" name="password" id="password"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full">
                        Masuk ke Akun
                    </button>
                </form>

                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t"></span>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-background px-2 text-muted-foreground">atau lanjut dengan</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <button
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2 w-full">
                        <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="mr-2 h-4 w-4"
                            alt="Google">
                        Google
                    </button>
                </div>

                <p class="mt-8 text-center text-sm text-muted-foreground">
                    Belum memiliki akun?
                    <a href="{{ route('register.distributor.form') }}"
                        class="font-medium text-primary underline-offset-4 hover:underline">
                        Daftar sekarang
                    </a>
                </p>
            </div>

            <div class="mt-auto pt-8">
                <footer class="text-center text-[10px] text-muted-foreground">
                    &copy; {{ date('Y') }} Garuda Fiber. Seluruh hak cipta dilindungi.
                </footer>
            </div>
        </div>

        <!-- RIGHT SIDE (Background Image) -->
        <div class="hidden lg:block relative flex-1 bg-zinc-900 border-l">
            <div class="absolute inset-0 z-0 overflow-hidden">
                <img src="{{ asset('image/warehouse.jpg') }}" alt="Marketing Image"
                    class="h-full w-full object-cover opacity-50 grayscale hover:grayscale-0 transition-all duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent"></div>
            </div>

            <div class="absolute bottom-12 left-12 right-12 z-10">
                <blockquote class="space-y-2">
                    <p class="text-lg text-zinc-100 italic leading-relaxed">
                        "Efisien, transparan, dan terpercaya. Garuda Fiber memberikan standar baru dalam pengelolaan
                        infrastruktur jaringan kami."
                    </p>
                    <footer class="text-sm">
                        <div class="font-semibold text-zinc-100">Bapak Direktur</div>
                        <div class="text-zinc-500 text-xs">CEO PT Maju Mundur</div>
                    </footer>
                </blockquote>
            </div>
        </div>

    </div>

</body>

</html>

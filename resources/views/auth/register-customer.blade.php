<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Garuda Fiber</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background font-sans text-foreground antialiased min-h-screen">

    <div class="flex min-h-screen">

        <!-- LEFT SIDE (Registration Area) -->
        <div class="flex flex-col w-full lg:w-[480px] p-8 md:p-12 relative z-10 bg-background">
            <div class="mb-auto">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 font-bold text-xl tracking-tighter">
                    <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo" class="h-8 w-auto">
                </a>
            </div>

            <div class="w-full max-w-sm mx-auto">
                <div class="space-y-2 mb-8">
                    <h1 class="text-2xl font-bold tracking-tight">Buat Akun Baru</h1>
                    <p class="text-sm text-muted-foreground">
                        Daftar sekarang untuk mulai berbelanja di portal pelanggan kami.
                    </p>
                </div>

                @if ($errors->any())
                    <div
                        class="mb-6 rounded-lg border border-destructive/50 bg-destructive/10 p-3 text-sm text-destructive flex items-center gap-3 animate-in fade-in slide-in-from-top-2">
                        <i class="bi bi-exclamation-circle text-base"></i>
                        <p>{{ $errors->first() }}</p>
                    </div>
                @endif

                <form action="{{ route('register.customer') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="name">
                            Nama Lengkap
                        </label>
                        <input type="text" name="name" id="name"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="Nama Lengkap Anda" required value="{{ old('name') }}">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="email">
                            Email
                        </label>
                        <input type="email" name="email" id="email"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="nama@email.com" required value="{{ old('email') }}">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="no_telepon">
                            No. Telepon (Opsional)
                        </label>
                        <input type="text" name="no_telepon" id="no_telepon"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="08123456789" value="{{ old('no_telepon') }}">
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="password">
                            Kata Sandi
                        </label>
                        <input type="password" name="password" id="password"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="••••••••" required>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            for="password_confirmation">
                            Konfirmasi Kata Sandi
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full">
                        Daftar Akun
                    </button>
                </form>

                <p class="mt-8 text-center text-sm text-muted-foreground">
                    Sudah memiliki akun?
                    <a href="{{ route('login.customer.form') }}"
                        class="font-medium text-primary underline-offset-4 hover:underline">
                        Masuk sekarang
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
                        "Bergabunglah dengan ribuan pelanggan lainnya dan nikmati kemudahan akses serta layanan terbaik
                        kami."
                    </p>
                    <footer class="text-sm">
                        <div class="font-semibold text-zinc-100">Tim Dukungan</div>
                        <div class="text-zinc-500 text-xs">Garuda Fiber</div>
                    </footer>
                </blockquote>
            </div>
        </div>

    </div>

</body>

</html>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Garuda Fiber - Premium Networking Solutions</title>

    <!-- Fonts: Inter is standard for shadcn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background font-sans text-foreground antialiased min-h-screen flex flex-col">
    @include('include.navbar-client')

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative border-b bg-muted/30 overflow-hidden">
            <div
                class="absolute inset-0 z-0 opacity-10 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]">
            </div>
            <div class="container mx-auto px-4 py-24 md:py-32 relative z-10">
                <div class="max-w-3xl">
                    <div
                        class="inline-flex items-center rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary mb-6 animate-in fade-in slide-in-from-bottom-3 duration-500">
                        <span class="mr-2 h-1.5 w-1.5 rounded-full bg-primary animate-pulse"></span>
                        Solusi Jaringan Terpercaya
                    </div>
                    <h1
                        class="text-4xl md:text-6xl font-bold tracking-tight mb-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
                        Bangun Konektivitas Digital <br class="hidden md:block"> Tanpa Batas
                    </h1>
                    <p
                        class="text-lg md:text-xl text-muted-foreground mb-10 max-w-2xl animate-in fade-in slide-in-from-bottom-5 duration-1000">
                        Platform terintegrasi untuk kebutuhan perangkat jaringan, instalasi, dan pengelolaan
                        infrastruktur IT dengan kualitas premium dan layanan efisien.
                    </p>
                    <div class="flex flex-wrap gap-4 animate-in fade-in slide-in-from-bottom-6 duration-1000">
                        <a href="{{ route('customer.products') }}"
                            class="inline-flex items-center justify-center rounded-md bg-primary px-8 py-3 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                            Mulai Belanja
                        </a>
                        <a href="{{ url('/tentang') }}"
                            class="inline-flex items-center justify-center rounded-md border border-input bg-background px-8 py-3 text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground">
                            Pelajari Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats/Features -->
        <section class="border-b bg-background py-12">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="flex flex-col items-center text-center space-y-2">
                        <span class="text-3xl font-bold">100+</span>
                        <p class="text-sm text-muted-foreground">Produk Ready</p>
                    </div>
                    <div class="flex flex-col items-center text-center space-y-2">
                        <span class="text-3xl font-bold">24/7</span>
                        <p class="text-sm text-muted-foreground">Support Teknis</p>
                    </div>
                    <div class="flex flex-col items-center text-center space-y-2">
                        <span class="text-3xl font-bold">1.2k+</span>
                        <p class="text-sm text-muted-foreground">Klien Puas</p>
                    </div>
                    <div class="flex flex-col items-center text-center space-y-2">
                        <span class="text-3xl font-bold">Original</span>
                        <p class="text-sm text-muted-foreground">Garansi Resmi</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="container mx-auto px-4 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">

                <!-- Sidebar (Desktop) -->
                <aside class="hidden lg:block lg:col-span-1 space-y-8">
                    <!-- Categories -->
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground px-2">
                            Kategori Produk
                        </h3>
                        <nav class="flex flex-col space-y-1">
                            <a href="{{ route('customer.products') }}"
                                class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium bg-accent text-accent-foreground transition-colors">
                                <i class="bi bi-grid text-xs"></i> Semua Produk
                            </a>
                            @forelse($categories as $category)
                                <a href="{{ route('customer.products', ['category' => $category->id]) }}"
                                    class="flex items-center gap-3 rounded-md px-3 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-muted hover:text-foreground">
                                    <i class="bi bi-chevron-right text-[10px]"></i> {{ $category->category_name }}
                                </a>
                            @empty
                                <p class="px-3 py-2 text-sm text-muted-foreground italic">Tidak ada kategori</p>
                            @endforelse
                        </nav>
                    </div>

                    <!-- Promo Card -->
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden p-6 relative">
                        <div class="relative z-10 space-y-4">
                            <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                                <i class="bi bi-lightning-charge-fill text-primary"></i>
                            </div>
                            <h4 class="font-bold">Butuh Bantuan?</h4>
                            <p class="text-xs text-muted-foreground leading-relaxed">
                                Konsultasikan kebutuhan infrastruktur jaringan Anda dengan tim ahli kami secara gratis.
                            </p>
                            <button
                                class="w-full inline-flex items-center justify-center rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow-sm hover:bg-secondary/80 transition-colors">
                                Hubungi Kami
                            </button>
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-12">
                    <!-- Product Section Header -->
                    <section id="produk" class="space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-6">
                            <div>
                                <h2 class="text-3xl font-bold tracking-tight">Produk Pilihan</h2>
                                <p class="text-sm text-muted-foreground mt-1">Jelajahi beberapa perangkat jaringan
                                    terbaik kami.</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('customer.products') }}"
                                    class="text-sm font-semibold text-primary hover:underline">Lihat Semua Katalog <i
                                        class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>

                        <!-- Product Grid -->
                        @if ($products->isEmpty())
                            <div
                                class="flex flex-col items-center justify-center py-24 text-center border border-dashed rounded-xl">
                                <div class="h-12 w-12 rounded-full bg-muted flex items-center justify-center mb-4">
                                    <i class="bi bi-box-seam text-xl text-muted-foreground"></i>
                                </div>
                                <h3 class="font-medium">Belum ada produk</h3>
                                <p class="text-sm text-muted-foreground">Silakan periksa kembali nanti untuk melihat
                                    pembaruan katalog kami.</p>
                            </div>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach ($products as $product)
                                    <div
                                        class="group relative flex flex-col rounded-xl border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/20">
                                        <!-- Image Container -->
                                        <div
                                            class="aspect-square relative overflow-hidden rounded-t-xl bg-muted/50 flex items-center justify-center p-8">
                                            @if ($product->image)
                                                <img src="{{ asset('uploads/product/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    class="object-contain w-full h-full transition-transform duration-500 group-hover:scale-110">
                                            @else
                                                <i class="bi bi-image text-3xl text-muted-foreground/40"></i>
                                            @endif

                                            @if ($product->merk)
                                                <div class="absolute top-3 left-3">
                                                    <span
                                                        class="inline-flex items-center rounded-full border bg-background/80 backdrop-blur px-2.5 py-0.5 text-[10px] font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2">
                                                        {{ $product->merk }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="flex flex-col flex-1 p-5">
                                            <div class="mb-4">
                                                <p
                                                    class="text-[10px] font-semibold uppercase tracking-wider text-primary mb-1">
                                                    {{ optional($product->category)->category_name ?? 'Hardware' }}
                                                </p>
                                                <h3
                                                    class="font-bold text-base leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                                    {{ $product->name }}
                                                </h3>
                                            </div>

                                            <div class="mt-auto space-y-4">
                                                <div class="flex items-baseline gap-1">
                                                    <span class="text-lg font-bold">Rp
                                                        {{ number_format($product->price, 0, ',', '.') }}</span>
                                                    <span class="text-[10px] text-muted-foreground">/
                                                        {{ $product->uom->name ?? 'Unit' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 relative z-10">
                                                    <button onclick="addToCart('{{ $product->id }}', event)"
                                                        class="flex-1 inline-flex items-center justify-center rounded-md bg-primary h-9 px-4 text-xs font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                                                        <i class="bi bi-cart-plus me-2"></i> Tambah
                                                    </button>
                                                    <button
                                                        class="h-9 w-9 inline-flex items-center justify-center rounded-md border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground transition-colors">
                                                        <i class="bi bi-heart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Hidden overlay link -->
                                        <a href="#" class="absolute inset-0 z-0">
                                            <span class="sr-only">Detail: {{ $product->name }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <!-- Featured Section (Horizontal Card) -->
                    <section
                        class="rounded-2xl border bg-zinc-950 text-zinc-50 p-8 md:p-12 relative overflow-hidden shadow-2xl">
                        <div
                            class="absolute top-0 right-0 -mt-10 -mr-10 h-64 w-64 rounded-full bg-primary/20 blur-3xl">
                        </div>
                        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <div class="space-y-6">
                                <h2 class="text-3xl font-bold tracking-tight">Layanan Instalasi Profesional</h2>
                                <p class="text-zinc-400 text-lg">
                                    Bukan sekedar alat, kami juga menyediakan layanan setup dan instalasi infrastruktur
                                    jaringan dari nol hingga siap pakai.
                                </p>
                                <ul class="space-y-3 text-sm text-zinc-300">
                                    <li class="flex items-center gap-2"><i
                                            class="bi bi-check-circle-fill text-primary"></i> Survey Lokasi Gratis</li>
                                    <li class="flex items-center gap-2"><i
                                            class="bi bi-check-circle-fill text-primary"></i> Dukungan Maintenance</li>
                                    <li class="flex items-center gap-2"><i
                                            class="bi bi-check-circle-fill text-primary"></i> Sertifikat Instalasi
                                        Resmi</li>
                                </ul>
                                <button
                                    class="inline-flex items-center justify-center rounded-md bg-white text-zinc-950 px-8 py-3 text-sm font-bold hover:bg-zinc-200 transition-colors">
                                    Mulai Konsultasi
                                </button>
                            </div>
                            <div class="hidden md:block">
                                <div
                                    class="aspect-video rounded-xl bg-zinc-900 border border-zinc-800 flex items-center justify-center text-zinc-700">
                                    <i class="bi bi-cpu text-8xl opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')
</body>

</html>

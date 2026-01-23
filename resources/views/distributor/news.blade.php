<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Artikel - Garuda Fiber</title>

    <!-- Fonts -->
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
        <!-- Header -->
        <section class="py-16 md:py-24 bg-muted/30 border-b">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-4">Berita & Artikel</h1>
                <p class="text-lg text-muted-foreground max-w-2xl mx-auto">
                    Tetap terinformasi dengan perkembangan terbaru dari industri fiber optic, rilis produk, dan tips
                    teknologi jaringan.
                </p>
            </div>
        </section>

        <!-- News Grid -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach ($newsItems as $news)
                        <article
                            class="group flex flex-col bg-card rounded-2xl border shadow-sm overflow-hidden transition-all hover:shadow-xl hover:border-primary/20">
                            <!-- Image -->
                            <div class="aspect-[16/9] overflow-hidden relative">
                                <img src="{{ $news['image'] }}" alt="{{ $news['title'] }}"
                                    class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider text-primary shadow-sm border border-primary/10">
                                        {{ $news['category'] }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center gap-2 text-xs text-muted-foreground mb-3">
                                    <i class="bi bi-calendar3"></i>
                                    <span>{{ $news['date'] }}</span>
                                </div>
                                <h2
                                    class="text-xl font-bold mb-3 group-hover:text-primary transition-colors leading-tight">
                                    <a href="#">{{ $news['title'] }}</a>
                                </h2>
                                <p class="text-sm text-muted-foreground leading-relaxed mb-6 line-clamp-3">
                                    {{ $news['excerpt'] }}
                                </p>
                                <div class="mt-auto">
                                    <a href="#"
                                        class="inline-flex items-center text-sm font-bold text-primary group/link">
                                        Baca Selengkapnya
                                        <i
                                            class="bi bi-arrow-right ml-2 transition-transform group-hover/link:translate-x-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination Placeholder -->
                <div class="mt-16 flex justify-center">
                    <nav class="flex items-center gap-2">
                        <button
                            class="h-10 w-10 flex items-center justify-center rounded-md border bg-card text-muted-foreground hover:bg-muted transition-colors">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button
                            class="h-10 w-10 flex items-center justify-center rounded-md bg-primary text-primary-foreground font-bold shadow-sm">
                            1
                        </button>
                        <button
                            class="h-10 w-10 flex items-center justify-center rounded-md border bg-card hover:bg-muted transition-colors font-medium">
                            2
                        </button>
                        <button
                            class="h-10 w-10 flex items-center justify-center rounded-md border bg-card hover:bg-muted transition-colors font-medium">
                            3
                        </button>
                        <button
                            class="h-10 w-10 flex items-center justify-center rounded-md border bg-card text-muted-foreground hover:bg-muted transition-colors">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="py-20 bg-muted/20 border-t">
            <div class="container mx-auto px-4 max-w-4xl">
                <div class="bg-card border rounded-3xl p-8 md:p-12 shadow-sm text-center">
                    <h2 class="text-2xl font-bold mb-4">Jangan Lewatkan Pembaruan Kami</h2>
                    <p class="text-muted-foreground mb-8">Dapatkan berita terbaru dan penawaran eksklusif langsung di
                        inbox Anda.</p>
                    <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                        <input type="email" placeholder="Alamat email Anda"
                            class="flex-1 h-12 rounded-full px-6 bg-muted/50 border-transparent focus:bg-background focus:ring-2 focus:ring-primary focus:border-transparent transition-all outline-none text-sm">
                        <button type="submit"
                            class="h-12 px-8 rounded-full bg-primary text-primary-foreground font-bold shadow-md hover:bg-primary/90 transition-all">
                            Berlangganan
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')
</body>

</html>

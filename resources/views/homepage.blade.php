<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Garuda Fiber - Belanja Perangkat Jaringan</title>

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
        <div class="container mx-auto px-6 md:px-12 lg:px-16 py-6 md:py-8">
            <form action="{{ route('distributor.products') }}" method="GET" role="search" class="mb-5">
                <label for="homepageSearch" class="sr-only">Cari produk</label>
                <div class="flex items-center gap-2 rounded-lg border border-input bg-background p-1.5 shadow-sm focus-within:ring-2 focus-within:ring-ring">
                    <i class="bi bi-search ml-3 text-muted-foreground" aria-hidden="true"></i>
                    <input id="homepageSearch" name="search" type="search" placeholder="Cari produk, kabel fiber, atau perangkat jaringan..." class="min-w-0 flex-1 bg-transparent px-2 py-2 text-sm outline-none">
                    <button type="submit" class="rounded-md bg-primary px-5 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90">Cari</button>
                </div>
            </form>
            <nav aria-label="Kategori produk" class="mb-8 flex gap-2 overflow-x-auto pb-2">
                <a href="{{ route('distributor.products') }}" class="inline-flex shrink-0 items-center gap-2 rounded-full bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"><i class="bi bi-grid" aria-hidden="true"></i>Semua Produk</a>
                @foreach($categories as $category)
                    <a href="{{ route('distributor.products', ['category' => $category->id]) }}" class="inline-flex shrink-0 items-center rounded-full border border-border bg-background px-4 py-2 text-sm font-medium text-muted-foreground transition-colors hover:bg-accent hover:text-foreground">{{ $category->category_name }}</a>
                @endforeach
            </nav>

            <section aria-labelledby="productRecommendations">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div><h1 id="productRecommendations" class="text-xl md:text-2xl font-bold tracking-tight">Rekomendasi Produk</h1><p class="mt-1 text-sm text-muted-foreground">Pilihan terbaru dari katalog kami.</p></div>
                    <a href="{{ route('distributor.products') }}" class="inline-flex items-center gap-2 text-sm font-medium hover:underline">Lihat Semua <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                </div>
                @if($products->isEmpty())
                    <div class="rounded-xl border border-dashed bg-muted/20 py-16 text-center">
                        <i class="bi bi-box-seam text-3xl text-muted-foreground" aria-hidden="true"></i>
                        <h2 class="mt-3 font-semibold">Belum ada produk</h2><p class="mt-1 text-sm text-muted-foreground">Produk akan tampil setelah ditambahkan ke katalog.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 md:gap-5 xl:grid-cols-4">
                        @foreach($products as $product)
                            <article class="group flex flex-col overflow-hidden rounded-xl border border-border bg-card text-card-foreground transition-shadow hover:shadow-md">
                                <a href="{{ route('distributor.products.detail', $product->id) }}" class="relative flex aspect-square items-center justify-center overflow-hidden bg-muted/30 p-4 md:p-6" aria-label="Detail {{ $product->name }}">
                                    @if($product->image)
                                        <img src="{{ asset('uploads/product/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy" class="h-full w-full object-contain transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <i class="bi bi-image text-4xl text-muted-foreground/40" aria-hidden="true"></i>
                                    @endif
                                    @if($product->merk)<span class="absolute left-2 top-2 max-w-full truncate rounded-md border bg-background/90 px-2 py-1 text-xs font-medium">{{ $product->merk }}</span>@endif
                                </a>
                                <div class="flex flex-1 flex-col p-3 md:p-4">
                                    <p class="mb-1 truncate text-xs text-muted-foreground">{{ $product->category->category_name ?? 'Produk' }}</p>
                                    <h2 class="mb-3 min-h-10 text-sm font-semibold leading-5"><a href="{{ route('distributor.products.detail', $product->id) }}" class="line-clamp-2 hover:underline" title="{{ $product->name }}">{{ $product->name }}</a></h2>
                                    <div class="mt-auto">
                                        <p class="text-base md:text-lg font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                        <p class="mb-3 text-xs text-muted-foreground">per {{ $product->uom->name ?? 'unit' }} &middot; {{ ($product->stok_sum_quantity ?? 0) > 0 ? 'Stok tersedia' : 'Stok habis' }}</p>
                                        @if(($product->stok_sum_quantity ?? 0) > 0)
                                            <button type="button" onclick="addToCart('{{ $product->id }}', event)" class="inline-flex w-full items-center justify-center gap-2 rounded-md bg-primary px-3 py-2 text-xs md:text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90"><i class="bi bi-cart-plus" aria-hidden="true"></i>Tambah</button>
                                        @else
                                            <button type="button" disabled class="inline-flex w-full items-center justify-center rounded-md bg-muted px-3 py-2 text-xs md:text-sm font-medium text-muted-foreground cursor-not-allowed">Stok Habis</button>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
            <div class="py-8 text-center"><a href="{{ route('distributor.products') }}" class="inline-flex items-center gap-2 rounded-md border border-input px-6 py-2.5 text-sm font-medium hover:bg-accent">Jelajahi Semua Produk<i class="bi bi-arrow-right" aria-hidden="true"></i></a></div>
        </div>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')
</body>

</html>

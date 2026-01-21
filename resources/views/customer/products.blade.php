<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Katalog Produk - Garuda Fiber</title>

    <!-- Fonts: Inter -->
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
        <!-- Header Section -->
        <div class="border-b bg-muted/30">
            <div class="container mx-auto px-4 py-12 md:py-16">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">Katalog Produk</h1>
                        <p class="text-muted-foreground mt-2">Temukan perangkat jaringan terbaik untuk infrastruktur
                            Anda.</p>
                    </div>

                    <form action="{{ route('customer.products') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative group">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="h-10 w-full md:w-64 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            <i class="bi bi-search absolute right-3 top-3 text-muted-foreground text-xs"></i>
                        </div>
                        <button type="submit"
                            class="h-10 px-4 rounded-md bg-primary text-primary-foreground text-sm font-medium hover:bg-primary/90 transition-colors">Cari</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">

                <!-- Sidebar Filters -->
                <aside class="lg:col-span-3 space-y-8">
                    <div class="space-y-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground px-2">
                            Filter Kategori
                        </h3>
                        <nav class="flex flex-col space-y-1">
                            <a href="{{ route('customer.products') }}"
                                class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-medium {{ !request('category') ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }} transition-colors">
                                <span>Semua Produk</span>
                                <i class="bi bi-chevron-right text-[10px]"></i>
                            </a>
                            @foreach ($categories as $category)
                                <a href="{{ route('customer.products', ['category' => $category->id, 'search' => request('search')]) }}"
                                    class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-medium {{ request('category') == $category->id ? 'bg-accent text-accent-foreground' : 'text-muted-foreground hover:bg-muted hover:text-foreground' }} transition-colors">
                                    <span>{{ $category->category_name }}</span>
                                    <i class="bi bi-chevron-right text-[10px]"></i>
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <div class="rounded-xl border bg-card p-6 space-y-4">
                        <h4 class="font-bold">Info Pengiriman</h4>
                        <p class="text-xs text-muted-foreground leading-relaxed">
                            Gratis ongkir untuk wilayah Jabodetabek dengan minimal pembelian Rp 2.000.000.
                        </p>
                        <div class="flex items-center gap-2 text-primary">
                            <i class="bi bi-truck text-xl"></i>
                            <span class="text-xs font-semibold">Cek Ongkir</span>
                        </div>
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="lg:col-span-9 space-y-8">
                    @if ($products->isEmpty())
                        <div
                            class="flex flex-col items-center justify-center py-24 text-center border border-dashed rounded-xl bg-muted/20">
                            <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                                <i class="bi bi-search text-2xl text-muted-foreground"></i>
                            </div>
                            <h3 class="text-lg font-medium">Produk tidak ditemukan</h3>
                            <p class="text-sm text-muted-foreground max-w-xs mt-1">Kami tidak dapat menemukan produk
                                yang sesuai dengan filter atau pencarian Anda.</p>
                            <a href="{{ route('customer.products') }}"
                                class="mt-6 text-sm font-semibold text-primary hover:underline">Hapus semua filter</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div
                                    class="group relative flex flex-col rounded-xl border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/20">
                                    <!-- Image -->
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
                                                    class="inline-flex items-center rounded-full border bg-background/80 backdrop-blur px-2.5 py-0.5 text-[10px] font-semibold">
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

                                    <a href="#" class="absolute inset-0 z-0">
                                        <span class="sr-only">Detail: {{ $product->name }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="pt-8 border-t">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')
</body>

</html>

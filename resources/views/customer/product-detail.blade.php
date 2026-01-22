<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Garuda Fiber</title>

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
        <!-- Breadcrumbs -->
        <div class="border-b bg-muted/20">
            <div class="container mx-auto px-4 py-4">
                <nav class="flex items-center gap-2 text-xs font-medium text-muted-foreground">
                    <a href="{{ route('homepage') }}" class="hover:text-primary transition-colors">Beranda</a>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <a href="{{ route('customer.products') }}" class="hover:text-primary transition-colors">Katalog</a>
                    <i class="bi bi-chevron-right text-[10px]"></i>
                    <span class="text-foreground truncate">{{ $product->name }}</span>
                </nav>
            </div>
        </div>

        <section class="py-12 md:py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">

                    <!-- Product Image Gallery -->
                    <div class="space-y-4">
                        <div
                            class="aspect-square relative overflow-hidden rounded-2xl border bg-muted/50 flex items-center justify-center p-12">
                            @if ($product->image)
                                <img src="{{ asset('uploads/product/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="object-contain w-full h-full">
                            @else
                                <i class="bi bi-image text-8xl text-muted-foreground/20"></i>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="space-y-8">
                        <div>
                            <div class="flex items-center gap-4 mb-4">
                                <span
                                    class="inline-flex items-center rounded-full border bg-primary/10 px-2.5 py-0.5 text-xs font-semibold text-primary">
                                    {{ optional($product->category)->category_name ?? 'Hardware' }}
                                </span>
                                @if ($product->sku)
                                    <span class="text-xs font-medium text-muted-foreground uppercase tracking-widest">
                                        SKU: {{ $product->sku }}
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">
                                {{ $product->name }}
                            </h1>
                            <div class="flex items-center gap-4">
                                <span class="text-3xl font-bold text-primary">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <span class="text-sm text-muted-foreground">/
                                    {{ $product->uom->name ?? 'Unit' }}</span>
                            </div>
                        </div>

                        <div class="space-y-4 pt-6 border-t">
                            <div class="flex items-center gap-4">
                                <label for="quantity" class="text-sm font-medium">Jumlah:</label>
                                <div class="flex items-center border rounded-md">
                                    <button type="button" class="px-3 py-1 hover:bg-accent transition-colors border-r"
                                        id="btn-minus">-</button>
                                    <input type="number" id="quantity" value="1" min="1"
                                        class="w-12 text-center bg-transparent focus:outline-none text-sm font-medium">
                                    <button type="button" class="px-3 py-1 hover:bg-accent transition-colors border-l"
                                        id="btn-plus">+</button>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-4">
                                <button onclick="addToCartDetailed('{{ $product->id }}')"
                                    class="flex-1 inline-flex items-center justify-center rounded-md bg-primary h-12 px-8 text-sm font-semibold text-primary-foreground shadow transition-colors hover:bg-primary/90">
                                    <i class="bi bi-cart-plus me-2 text-lg"></i> Tambah ke Keranjang
                                </button>
                                <button
                                    class="h-12 w-12 inline-flex items-center justify-center rounded-md border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground transition-colors group">
                                    <i class="bi bi-heart group-hover:scale-110 transition-transform"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Product Description & Specs -->
                        <div class="space-y-8 pt-8 border-t">
                            <div class="space-y-3">
                                <h3 class="text-sm font-bold uppercase tracking-wider text-muted-foreground">Deskripsi
                                    Produk</h3>
                                <div class="text-muted-foreground leading-relaxed prose prose-sm max-w-none">
                                    {!! nl2br(e($product->description ?? 'Tidak ada deskripsi untuk produk ini.')) !!}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 py-6 px-6 bg-muted/30 rounded-xl">
                                @if ($product->merk)
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-muted-foreground">
                                            Merk</p>
                                        <p class="text-sm font-medium">{{ $product->merk }}</p>
                                    </div>
                                @endif
                                @if ($product->dimensi)
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-muted-foreground">
                                            Dimensi</p>
                                        <p class="text-sm font-medium">{{ $product->dimensi }}</p>
                                    </div>
                                @endif
                                @if ($product->berat)
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-muted-foreground">
                                            Berat</p>
                                        <p class="text-sm font-medium">{{ $product->berat }} Kg</p>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider font-bold text-muted-foreground">Unit
                                    </p>
                                    <p class="text-sm font-medium">{{ $product->uom->name ?? 'Pcs' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        @if (!$relatedProducts->isEmpty())
            <section class="py-16 border-t">
                <div class="container mx-auto px-4">
                    <h2 class="text-2xl font-bold mb-8">Produk Terkait</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach ($relatedProducts as $rel)
                            <div
                                class="group relative flex flex-col rounded-xl border bg-card text-card-foreground shadow-sm transition-all hover:shadow-md hover:border-primary/20 p-4">
                                <div
                                    class="aspect-square relative overflow-hidden rounded-lg bg-muted/50 flex items-center justify-center p-6 mb-4">
                                    @if ($rel->image)
                                        <img src="{{ asset('uploads/product/' . $rel->image) }}"
                                            alt="{{ $rel->name }}"
                                            class="object-contain w-full h-full transition-transform group-hover:scale-105">
                                    @else
                                        <i class="bi bi-image text-2xl text-muted-foreground/30"></i>
                                    @endif
                                </div>
                                <h4
                                    class="font-bold text-sm line-clamp-2 mb-2 group-hover:text-primary transition-colors">
                                    {{ $rel->name }}</h4>
                                <p class="text-sm font-bold text-primary">Rp
                                    {{ number_format($rel->price, 0, ',', '.') }}</p>
                                <a href="{{ route('customer.products.detail', $rel->id) }}"
                                    class="absolute inset-0 z-0">
                                    <span class="sr-only">Lihat {{ $rel->name }}</span>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.getElementById('quantity');
            const btnPlus = document.getElementById('btn-plus');
            const btnMinus = document.getElementById('btn-minus');

            btnPlus.addEventListener('click', () => {
                quantityInput.value = parseInt(quantityInput.value) + 1;
            });

            btnMinus.addEventListener('click', () => {
                if (parseInt(quantityInput.value) > 1) {
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                }
            });
        });

        function addToCartDetailed(productId) {
            const qty = parseInt(document.getElementById('quantity').value) || 1;
            if (typeof addToCart === 'function') {
                addToCart(productId, event, qty);
            }
        }
    </script>
</body>

</html>

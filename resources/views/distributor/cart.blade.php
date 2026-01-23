<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Garuda Fiber</title>

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
        <div class="container mx-auto px-4 py-12">
            <h1 class="text-3xl font-bold tracking-tight mb-8">Keranjang Belanja</h1>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                <!-- Cart Items -->
                <div class="lg:col-span-8 space-y-6">
                    @if ($cartItems->isEmpty())
                        <div
                            class="flex flex-col items-center justify-center py-24 text-center border border-dashed rounded-xl bg-muted/20">
                            <div class="h-16 w-16 rounded-full bg-muted flex items-center justify-center mb-4">
                                <i class="bi bi-cart-x text-2xl text-muted-foreground"></i>
                            </div>
                            <h3 class="text-lg font-medium">Keranjang Anda kosong</h3>
                            <p class="text-sm text-muted-foreground max-w-xs mt-1">Sepertinya Anda belum menambahkan
                                produk apapun ke keranjang.</p>
                            <a href="{{ route('distributor.products') }}"
                                class="mt-6 inline-flex items-center justify-center rounded-md bg-primary h-10 px-6 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90">
                                Mulai Belanja
                            </a>
                        </div>
                    @else
                        <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-muted/50 border-b">
                                        <tr>
                                            <th class="px-6 py-4 text-left font-medium">Produk</th>
                                            <th class="px-6 py-4 text-center font-medium">Kuantitas</th>
                                            <th class="px-6 py-4 text-right font-medium">Harga</th>
                                            <th class="px-6 py-4 text-center font-medium">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-border">
                                        @foreach ($cartItems as $item)
                                            <tr>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-4">
                                                        <div
                                                            class="h-16 w-16 flex-shrink-0 bg-muted rounded-md overflow-hidden p-2">
                                                            @if ($item->product->image)
                                                                <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                                                    alt="{{ $item->product->name }}"
                                                                    class="h-full w-full object-contain">
                                                            @else
                                                                <div
                                                                    class="h-full w-full flex items-center justify-center text-muted-foreground/40">
                                                                    <i class="bi bi-image"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-base">{{ $item->product->name }}
                                                            </div>
                                                            <div
                                                                class="text-xs text-muted-foreground uppercase tracking-wider">
                                                                {{ $item->product->merk }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <form action="{{ route('distributor.cart.update', $item->id) }}"
                                                        method="POST" class="flex items-center justify-center gap-2">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="number" name="quantity"
                                                            value="{{ $item->quantity }}" min="1"
                                                            class="h-8 w-16 rounded-md border border-input bg-background px-2 py-1 text-center text-xs focus:outline-none focus:ring-1 focus:ring-ring">
                                                        <button type="submit"
                                                            class="h-8 w-8 inline-flex items-center justify-center rounded-md border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground text-xs"><i
                                                                class="bi bi-check-lg"></i></button>
                                                    </form>
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="font-bold">Rp
                                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-[10px] text-muted-foreground">Rp
                                                        {{ number_format($item->product->price, 0, ',', '.') }} /
                                                        {{ $item->product->uom->name ?? 'unit' }}</div>
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    <form action="{{ route('distributor.cart.remove', $item->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-destructive hover:scale-110 transition-transform"><i
                                                                class="bi bi-trash"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4">
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm sticky top-24">
                        <div class="p-6 border-b">
                            <h3 class="font-semibold text-lg">Ringkasan Pesanan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            @php
                                $subtotal = $cartItems->sum(function ($item) {
                                    return $item->product->price * $item->quantity;
                                });
                            @endphp
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Pajak (11%)</span>
                                <span>Rp {{ number_format($subtotal * 0.11, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-4 flex justify-between font-bold text-lg">
                                <span>Total</span>
                                <span class="text-primary">Rp {{ number_format($subtotal * 1.11, 0, ',', '.') }}</span>
                            </div>

                            <form action="{{ route('distributor.checkout') }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="shipping_address" class="block text-sm font-medium mb-1">Alamat
                                        Pengiriman</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-ring"
                                        placeholder="Masukkan alamat lengkap..." required>{{ Auth::user()->alamat }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="notes" class="block text-sm font-medium mb-1">Catatan Tambahan
                                        (Opsional)</label>
                                    <textarea name="notes" id="notes" rows="2"
                                        class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-ring"
                                        placeholder="Informasi khusus untuk pengiriman..."></textarea>
                                </div>
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center rounded-md bg-primary h-11 px-8 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 mt-2 {{ $cartItems->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $cartItems->isEmpty() ? 'disabled' : '' }}>
                                    Lanjut ke Checkout
                                </button>
                            </form>
                            <a href="{{ route('distributor.products') }}"
                                class="w-full inline-flex items-center justify-center rounded-md border border-input bg-background h-10 px-8 text-sm font-medium hover:bg-accent hover:text-accent-foreground transition-colors">
                                Kembali Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast('{{ session('success') }}', 'success');
                }
            });
        </script>
    @endif
</body>

</html>

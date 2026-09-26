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
                                                        method="POST" class="flex items-center justify-center">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="inline-flex items-center rounded-md border border-input shadow-sm">
                                                            <button type="button" onclick="decrementQty(this)"
                                                                class="h-8 w-8 flex items-center justify-center bg-muted hover:bg-accent text-foreground transition-colors rounded-l-md font-bold text-sm">
                                                                <i class="bi bi-dash"></i>
                                                            </button>
                                                            <input type="number" name="quantity"
                                                                value="{{ $item->quantity }}" min="1"
                                                                onchange="this.form.submit()"
                                                                class="h-8 w-14 border-x border-input bg-background px-1 text-center text-xs font-semibold focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                            <button type="button" onclick="incrementQty(this)"
                                                                class="h-8 w-8 flex items-center justify-center bg-muted hover:bg-accent text-foreground transition-colors rounded-r-md font-bold text-sm">
                                                                <i class="bi bi-plus"></i>
                                                            </button>
                                                        </div>
                                                        <button type="submit" title="Simpan Perubahan"
                                                            class="h-8 w-8 ml-2 inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-sm hover:bg-primary/90 text-xs transition-colors">
                                                            <i class="bi bi-check-lg"></i>
                                                        </button>
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

                            <div class="mt-6">
                                <a href="{{ route('distributor.confirmation') }}"
                                    class="w-full inline-flex items-center justify-center rounded-md bg-primary h-11 px-8 text-sm font-medium text-primary-foreground shadow transition-colors hover:bg-primary/90 {{ $cartItems->isEmpty() ? 'pointer-events-none opacity-50' : '' }}">
                                    Lanjut ke Konfirmasi Pesanan
                                </a>
                            </div>
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

    <script>
        function decrementQty(btn) {
            const input = btn.form.querySelector('input[name="quantity"]');
            let val = parseInt(input.value) || 1;
            if (val > 1) {
                input.value = val - 1;
                btn.form.submit();
            }
        }

        function incrementQty(btn) {
            const input = btn.form.querySelector('input[name="quantity"]');
            let val = parseInt(input.value) || 1;
            input.value = val + 1;
            btn.form.submit();
        }
    </script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast('{{ session('success') }}', 'success');
                }
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast('{{ session('error') }}', 'error');
                }
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof showToast === 'function') {
                    showToast('{{ $errors->first() }}', 'error');
                }
            });
        </script>
    @endif
</body>

</html>

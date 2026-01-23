<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Garuda Fiber</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-background font-sans text-foreground antialiased min-h-screen flex flex-col">
    @include('include.navbar-client')

    <main class="flex-1 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">
                <a href="{{ route('distributor.order.index') }}"
                    class="text-sm text-muted-foreground hover:text-primary mb-6 inline-block">
                    <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                </a>

                <h1 class="text-2xl font-bold tracking-tight mb-2">Detail Pesanan</h1>
                <p class="text-muted-foreground mb-8">#{{ $order->order_number }}</p>

                <div class="rounded-xl border bg-card text-card-foreground shadow-sm overflow-hidden mb-8">
                    <div class="p-6 border-b bg-muted/20">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-muted-foreground">Status Pesanan</p>
                                <p class="font-medium capitalize">{{ $order->status }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-muted-foreground">Tanggal Pemesanan</p>
                                <p class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-semibold mb-4">Informasi Pengiriman</h3>
                        <p class="text-sm text-muted-foreground mb-6">
                            {{ $order->shipping_address }}
                            <br>
                            Catatan: {{ $order->notes ?? '-' }}
                        </p>

                        <h3 class="font-semibold mb-4">Item Pesanan</h3>
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-16 bg-muted rounded-md overflow-hidden flex-shrink-0">
                                        @if ($item->product->image)
                                            <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <div
                                                class="h-full w-full flex items-center justify-center text-muted-foreground">
                                                <i class="bi bi-image"></i></div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium">{{ $item->product->name }}</h4>
                                        <p class="text-xs text-muted-foreground">{{ $item->quantity }} x Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-sm font-medium">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t mt-6 pt-6 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Subtotal</span>
                                <span>Rp {{ number_format($order->total_price / 1.11, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Pajak (11%)</span>
                                <span>Rp {{ number_format(($order->total_price / 1.11) * 0.11, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2">
                                <span>Total</span>
                                <span class="text-primary">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('include.footer-client')
</body>

</html>

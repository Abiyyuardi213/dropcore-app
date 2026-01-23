<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan - Garuda Fiber</title>
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
            <h1 class="text-3xl font-bold tracking-tight mb-8">Riwayat Pesanan</h1>

            <div class="space-y-6">
                @forelse($orders as $order)
                    <div class="rounded-xl border bg-card text-card-foreground shadow-sm p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <p class="text-sm font-bold text-primary">{{ $order->order_number }}</p>
                                <p class="text-xs text-muted-foreground">{{ $order->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2
                            {{ $order->status == 'completed'
                                ? 'border-transparent bg-green-500/10 text-green-500'
                                : ($order->status == 'pending'
                                    ? 'border-transparent bg-yellow-500/10 text-yellow-500'
                                    : ($order->status == 'cancelled'
                                        ? 'border-transparent bg-red-500/10 text-red-500'
                                        : 'border-transparent bg-blue-500/10 text-blue-500')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="text-sm">Total: <span class="font-bold">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                            <p class="text-xs text-muted-foreground">{{ $order->items->count() }} Produk</p>
                        </div>

                        <a href="{{ route('distributor.order.show', $order->id) }}"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background h-9 px-4 hover:bg-accent hover:text-accent-foreground">
                            Lihat Detail
                        </a>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-muted-foreground">Belum ada pesanan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    @include('include.footer-client')
</body>

</html>

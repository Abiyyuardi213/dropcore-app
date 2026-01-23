<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - Garuda Fiber</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-background font-sans text-foreground antialiased min-h-screen flex flex-col items-center justify-center">

    <div class="w-full max-w-md p-6 text-center">
        <div class="h-24 w-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-check-lg text-5xl"></i>
        </div>

        <h1 class="text-3xl font-bold tracking-tight mb-2">Pesanan Diterima!</h1>
        <p class="text-muted-foreground mb-8">
            Terima kasih telah berbelanja di Garuda Fiber. Nomor pesanan Anda adalah <span
                class="font-bold text-foreground">{{ $order->order_number }}</span>.
        </p>

        <div class="space-y-3">
            <a href="{{ route('distributor.order.show', $order->id) }}"
                class="block w-full rounded-md bg-primary py-3 text-sm font-bold text-primary-foreground shadow hover:bg-primary/90">
                Lihat Pesanan Saya
            </a>
            <a href="{{ route('homepage') }}"
                class="block w-full rounded-md border border-input bg-background py-3 text-sm font-medium hover:bg-accent hover:text-accent-foreground">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>

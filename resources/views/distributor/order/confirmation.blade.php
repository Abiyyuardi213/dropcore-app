<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Garuda Fiber</title>

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

    <main class="flex-1 py-12 pb-32 lg:pb-12">
        <div class="container mx-auto px-6 md:px-12 lg:px-16 max-w-7xl">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-foreground">Konfirmasi Pesanan</h1>
                    <p class="text-muted-foreground mt-1">Periksa kembali detail pesanan Anda sebelum melanjutkan.</p>
                </div>
                <div class="hidden md:block">
                    <span
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-muted text-foreground text-sm font-medium border border-border">
                        <i class="bi bi-shield-check"></i> Pembayaran Aman
                    </span>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-destructive/30 bg-destructive/5 p-4 text-sm text-destructive" role="alert">
                    <ul class="list-disc pl-4">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            <form action="{{ route('distributor.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                    <!-- Left Column: Details -->
                    <div class="lg:col-span-8 space-y-6">

                        <!-- 1. Informasi Penerima & Alamat -->
                        <div class="bg-card p-5 lg:p-6 rounded-xl shadow-sm border border-border">
                            <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold shadow-sm">1</span>
                                Alamat Pengiriman
                            </h2>

                            <div class="space-y-4 ml-0 lg:ml-11">
                                <div
                                    class="bg-muted/30 rounded-lg p-4 border border-border flex items-start sm:items-center gap-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-card flex items-center justify-center text-muted-foreground shadow-sm border border-border flex-shrink-0">
                                        <i class="bi bi-person-fill text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-foreground">{{ Auth::user()->name }}</p>
                                        <p class="text-sm text-muted-foreground">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>

                                <div>
                                    <label for="shipping_address"
                                        class="block text-sm font-bold text-foreground mb-2">Alamat Lengkap</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" required
                                        class="w-full rounded-md border border-input bg-card shadow-sm focus:border-ring focus:ring-ring text-sm p-3.5 transition-colors placeholder:text-muted-foreground"
                                        placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02, Kec. Tebet, Jakarta Selatan">{{ old('shipping_address', Auth::user()->alamat) }}</textarea>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-bold text-foreground mb-2">Catatan
                                        (Opsional)</label>
                                    <textarea name="notes" id="notes" rows="2"
                                        class="w-full rounded-md border border-input bg-card shadow-sm focus:border-ring focus:ring-ring text-sm p-3.5 transition-colors placeholder:text-muted-foreground"
                                        placeholder="Pesan khusus untuk penjual atau kurir...">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Produk -->
                        <div class="bg-card p-5 lg:p-6 rounded-xl shadow-sm border border-border">
                            <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold shadow-sm">2</span>
                                Produk Dipesan
                            </h2>

                            <div class="ml-0 lg:ml-11 divide-y divide-border">
                                @foreach ($cartItems as $item)
                                    <div class="py-4 first:pt-0 last:pb-0 flex gap-4">
                                        <div
                                            class="h-20 w-20 flex-shrink-0 bg-muted/30 rounded-lg overflow-hidden border border-border relative group">
                                            @if ($item->product->image)
                                                <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                            @else
                                                <div
                                                    class="h-full w-full flex items-center justify-center text-muted-foreground bg-muted">
                                                    <i class="bi bi-image text-2xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                                            <h3 class="font-bold text-foreground truncate text-base">
                                                {{ $item->product->name }}</h3>
                                            <p class="text-sm text-muted-foreground mb-2">{{ $item->product->merk }}</p>

                                            <div class="flex items-center justify-between">
                                                <div
                                                    class="text-xs font-medium text-muted-foreground bg-muted/60 px-2.5 py-1 rounded-md inline-block border border-border">
                                                    {{ $item->quantity }} x Rp
                                                    {{ number_format($item->product->price, 0, ',', '.') }}
                                                </div>
                                                <div class="text-right block lg:hidden">
                                                    <p class="font-bold text-foreground">Rp
                                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right hidden lg:block flex-shrink-0">
                                            <p class="font-bold text-foreground text-lg">Rp
                                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-muted-foreground mt-1 font-medium">Berat:
                                                {{ ($item->product->weight ?? 0) * $item->quantity }} kg</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div
                                class="ml-0 lg:ml-11 mt-5 pt-4 border-t border-border flex justify-between items-center text-sm bg-muted/50 p-3 rounded-lg">
                                <span class="text-muted-foreground font-medium">Total Berat Pesanan</span>
                                <span class="font-bold text-foreground"
                                    id="displayTotalWeight">{{ number_format($totalWeight, 2, ',', '.') }} Kg</span>
                            </div>
                        </div>

                        <!-- 3. Metode Pengiriman & Pembayaran -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Pengiriman -->
                            <div
                                class="bg-card p-5 lg:p-6 rounded-xl shadow-sm border border-border h-full flex flex-col">
                                <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold shadow-sm">3</span>
                                    Kurir
                                </h2>
                                <div class="space-y-3 lg:ml-2 flex-1">
                                    @foreach ($shippingServices as $service)
                                        <label class="group relative block cursor-pointer">
                                            <input type="radio" name="shipping_service_id"
                                                value="{{ $service->id }}" @checked(old('shipping_service_id') == $service->id) class="peer sr-only" required=""
                                                data-price="{{ $service->biaya_dasar }}" onchange="calculateTotal()">

                                            <div
                                                class="rounded-lg border border-solid border-border bg-card p-4 transition-all duration-200 hover:border-primary/40 hover:bg-muted/30 peer-checked:border-solid peer-checked:border-primary peer-checked:bg-muted/50 peer-checked:shadow-sm">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-10 h-10 rounded-lg bg-muted text-primary flex items-center justify-center shrink-0">
                                                            <i class="bi bi-truck text-lg"></i>
                                                        </div>
                                                        <div>
                                                            <p class="font-bold text-foreground text-sm">
                                                                {{ $service->nama }}</p>
                                                            <p class="text-xs text-muted-foreground font-medium tracking-wide">
                                                                {{ $service->kode ?? 'REG' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-3 pt-3 border-t border-border flex justify-between items-center">
                                                    <span class="text-xs text-muted-foreground">Biaya per kg</span>
                                                    <p class="font-bold text-foreground text-sm">Rp
                                                        {{ number_format($service->biaya_dasar, 0, ',', '.') }}</p>
                                                </div>

                                                <!-- Checkmark icon -->
                                                <div
                                                    class="absolute top-0 right-0 -mt-2 -mr-2 hidden peer-checked:flex h-6 w-6 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-sm ring-2 ring-background scale-0 peer-checked:scale-100 transition-transform">
                                                    <i class="bi bi-check text-sm font-bold"></i>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Pembayaran -->
                            <div
                                class="bg-card p-5 lg:p-6 rounded-xl shadow-sm border border-border h-full flex flex-col">
                                <h2 class="text-lg font-bold mb-4 flex items-center gap-3">
                                    <span
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-primary text-primary-foreground text-sm font-bold shadow-sm">4</span>
                                    Pembayaran
                                </h2>
                                <div class="space-y-3 lg:ml-2 flex-1">
                                    @forelse ($paymentMethods as $method)
                                        <label class="group relative block cursor-pointer">
                                            <input type="radio" name="payment_method_id"
                                                value="{{ $method->id }}" @checked(old('payment_method_id') == $method->id) class="peer sr-only" required="">

                                            <div
                                                class="rounded-lg border border-solid border-border bg-card p-4 transition-all duration-200 hover:border-primary/40 hover:bg-muted/30 peer-checked:border-solid peer-checked:border-primary peer-checked:bg-muted/50 peer-checked:shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="h-10 w-10 flex-shrink-0 rounded-lg bg-muted text-primary flex items-center justify-center border border-border">
                                                        <i class="bi bi-bank text-lg"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="font-bold text-foreground text-sm truncate">
                                                            {{ $method->nama_sumber }}</p>
                                                        <p class="text-xs text-muted-foreground font-mono mt-0.5 truncate">
                                                            {{ $method->nomor_rekening }}</p>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-xs text-muted-foreground pl-[3.25rem]">
                                                    a.n {{ $method->atas_nama }}
                                                </div>
                                                <!-- Checkmark icon -->
                                                <div
                                                    class="absolute top-0 right-0 -mt-2 -mr-2 hidden peer-checked:flex h-6 w-6 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-sm ring-2 ring-background scale-0 peer-checked:scale-100 transition-transform">
                                                    <i class="bi bi-check text-sm font-bold"></i>
                                                </div>
                                            </div>
                                        </label>
                                    @empty<p class="text-sm text-muted-foreground" role="status">Belum ada rekening bank aktif untuk pembayaran. Silakan hubungi admin.</p>@endforelse
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column: Summary (Sticky) -->
                    <div class="lg:col-span-4">
                        <div class="sticky top-24 space-y-6">

                            <div
                                class="bg-card p-6 rounded-xl shadow-sm border border-border overflow-hidden relative">

                                <h2 class="text-lg font-bold mb-6 text-foreground">Ringkasan Pembayaran</h2>

                                <div class="space-y-4">
                                    <div class="flex justify-between text-sm text-muted-foreground">
                                        <span>Total Harga Produk</span>
                                        <span class="font-semibold text-foreground">Rp
                                            {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-muted-foreground">
                                        <span>Pajak (PPN 11%)</span>
                                        <span class="font-semibold text-foreground">Rp
                                            {{ number_format($subtotal * 0.11, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm text-muted-foreground">
                                        <span>Ongkos Kirim (<span id="summaryWeight">{{ ceil($totalWeight) }}</span>
                                            kg)</span>
                                        <span class="font-semibold text-foreground" id="summaryShipping">Rp 0</span>
                                    </div>

                                    <div class="border-t border-solid border-border my-4"></div>

                                    <div class="flex justify-between items-end">
                                        <div>
                                            <span class="block text-sm font-medium text-muted-foreground mb-1">Total
                                                Tagihan</span>
                                            <span class="text-2xl font-bold text-primary leading-none tracking-tight"
                                                id="finalTotal">
                                                Rp {{ number_format($subtotal * 1.11, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                    <p
                                        class="text-xs text-muted-foreground text-right mt-1 font-medium bg-muted/30 inline-block px-2 py-1 rounded ml-auto float-right clear-both">
                                        *Sudah termasuk Pajak & Ongkir</p>
                                </div>

                                <!-- Desktop Button -->
                                <button type="submit" @disabled($paymentMethods->isEmpty())
                                    class="hidden lg:block w-full mt-8 bg-primary hover:bg-primary/90 text-primary-foreground py-4 px-4 rounded-lg font-bold shadow-sm transition-all focus:ring-4 focus:ring-ring/30 text-sm tracking-wide">
                                    BUAT PESANAN SEKARANG
                                </button>

                                <a href="{{ route('distributor.cart') }}"
                                    class="hidden lg:block text-center mt-4 text-sm font-semibold text-muted-foreground hover:text-foreground transition-colors py-2">
                                    Kembali ke Keranjang
                                </a>
                            </div>

                            <!-- Security Badge -->
                            <div
                                class="bg-card p-4 rounded-xl border border-border flex items-center gap-3 shadow-sm">
                                <div
                                    class="w-10 h-10 rounded-full bg-muted text-primary flex items-center justify-center shrink-0">
                                    <i class="bi bi-shield-lock-fill text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-foreground text-xs uppercase tracking-wider">Garansi
                                        Keamanan</h4>
                                    <p class="text-muted-foreground text-xs mt-0.5">Transaksi Anda 100% aman dan terenkripsi.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </form>
        </div>
    </main>

    <!-- Mobile Sticky Floating Action Bar -->
    <div
        class="fixed bottom-0 left-0 right-0 bg-card border-t border-border p-4 z-50 lg:hidden shadow-[0_-4px_20px_rgba(0,0,0,0.08)] pb-safe animation-slide-up">
        <div class="flex items-center justify-between gap-4 max-w-7xl mx-auto">
            <div class="flex flex-col">
                <span class="text-[10px] text-muted-foreground font-bold uppercase tracking-wider">Total Pembayaran</span>
                <span class="text-lg font-bold text-primary leading-tight" id="mobileFinalTotal">Rp
                    {{ number_format($subtotal * 1.11, 0, ',', '.') }}</span>
            </div>
            <button type="submit" form="checkoutForm" @disabled($paymentMethods->isEmpty())
                class="flex-1 max-w-[180px] bg-primary text-primary-foreground px-4 py-3 rounded-lg font-bold text-sm shadow-sm active:scale-95 transition-all">
                Bayar Sekarang
            </button>
        </div>
    </div>

    <!-- Safe area padding for mobile -->
    <div class="h-24 lg:hidden"></div>

    @include('include.footer-client')
    @include('include.cart-scripts')

    <script>
        const productSubtotal = {{ $subtotal }};
        const taxAmount = {{ $subtotal * 0.11 }};
        const rawTotalWeight = {{ $totalWeight }};
        // Round up weight to nearest int, min 1
        const billableWeight = Math.max(1, Math.ceil(rawTotalWeight));

        function formatRupiah(amount) {
            return 'Rp ' + amount.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function calculateTotal() {
            // Get selected shipping price
            const selectedShipping = document.querySelector('input[name="shipping_service_id"]:checked');
            let shippingCost = 0;

            if (selectedShipping) {
                const pricePerKg = parseFloat(selectedShipping.getAttribute('data-price'));
                shippingCost = pricePerKg * billableWeight;
            }

            // Update UI
            document.getElementById('summaryShipping').innerText = formatRupiah(shippingCost);

            const total = productSubtotal + taxAmount + shippingCost;
            const formattedTotal = formatRupiah(total);

            // Update both desktop and mobile total displays
            const finalTotalEl = document.getElementById('finalTotal');
            if (finalTotalEl) finalTotalEl.innerText = formattedTotal;

            const mobileTotalEl = document.getElementById('mobileFinalTotal');
            if (mobileTotalEl) mobileTotalEl.innerText = formattedTotal;
        }

        // Initialize display
        const displayWeightEl = document.getElementById('summaryWeight');
        if (displayWeightEl) displayWeightEl.innerText = billableWeight;

        calculateTotal();

        // Mobile visual tweaks
        // Ensure the input focus doesn't break fixed footer overlap (sometimes happens on mobile keyboards)
        // Simple listener to hide footer on focus if needed, but sticky usually works fine.
    </script>
</body>

</html>

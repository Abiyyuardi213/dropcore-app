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
                        <!-- Order Status Header -->
                        <div class="grid grid-cols-2 gap-4 text-sm mb-8">
                            <div>
                                <p class="text-muted-foreground">Status Pesanan</p>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $order->status == 'completed'
                                        ? 'bg-green-100 text-green-800'
                                        : ($order->status == 'cancelled'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucwords(str_replace('_', ' ', $order->status)) }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-muted-foreground">Tanggal Pemesanan</p>
                                <p class="font-medium">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Order Tracker -->
                        @php
                            $currentStatus = $order->status;
                            $steps = [
                                1 => [
                                    'label' => 'Pesanan Diproses',
                                    'date' => $order->created_at,
                                    'status' => [
                                        'pending',
                                        'waiting_payment',
                                        'waiting_confirmation',
                                        'processing',
                                        'shipped',
                                        'completed',
                                    ],
                                ],
                                2 => [
                                    'label' => 'Pesanan Dikirim',
                                    'date' => $order->shipped_at,
                                    'status' => ['shipped', 'completed'],
                                ],
                                3 => [
                                    'label' => 'Pesanan Diantar',
                                    'date' => null,
                                    'status' => ['shipped', 'completed'],
                                ], // Simulated step for "On Delivery"
                                4 => ['label' => 'Pesanan Diterima', 'date' => null, 'status' => ['completed']],
                                5 => [
                                    'label' => 'Pesanan Selesai',
                                    'date' => $order->status == 'completed' ? $order->updated_at : null,
                                    'status' => ['completed'],
                                ],
                            ];

                            // Determine active step index based on current status
                            $activeStep = 0;
                            if (in_array($currentStatus, ['pending', 'waiting_payment', 'waiting_confirmation'])) {
                                $activeStep = 1;
                            } elseif ($currentStatus == 'processing') {
                                $activeStep = 1;
                            } elseif ($currentStatus == 'shipped') {
                                $activeStep = 3;
                            }
                            // Jump to step 3 (Diantar) visually when shipped
                            elseif ($currentStatus == 'completed') {
                                $activeStep = 5;
                            } elseif ($currentStatus == 'cancelled') {
                                $activeStep = 0;
                            } // Or handle differently
                        @endphp

                        <div class="relative mt-2">
                            @php
                                // Calculate Progress Percentage for the bar
                                $totalSteps = 5;
                                $percent = 0;
                                if ($activeStep > 1) {
                                    // Percentage of the "active" path relative to total segments (4 segments for 5 steps)
                                    $percent = min(100, round((($activeStep - 1) / ($totalSteps - 1)) * 100));
                                }
                            @endphp

                            <!-- Connector Lines Container -->
                            <div class="absolute top-4 left-[10%] right-[10%] h-0.5 bg-gray-200 -z-0"
                                aria-hidden="true">
                                <!-- Colored Progress Line (Black to match Past nodes) -->
                                <div class="h-full bg-gray-900 transition-all duration-500"
                                    style="width: {{ $percent }}%"></div>
                            </div>

                            <!-- Steps Nodes -->
                            <div class="grid grid-cols-5 relative z-10">
                                @foreach ($steps as $index => $step)
                                    @php
                                        // Reset flags
                                        $isPast = false;
                                        $isCurrent = false;

                                        // Current active step gets the special color (Green/Primary)
                                        if ($activeStep == $index) {
                                            $isCurrent = true;
                                        }
                                        // Steps before the active one are "Past" (Black/Dark)
                                        elseif ($activeStep > $index) {
                                            $isPast = true;
                                        }

                                        // Special visual logic for "Shipped" state covering step 2 & 3
                                        if ($currentStatus == 'shipped') {
                                            if ($index == 2) {
                                                $isPast = true;
                                                $isCurrent = false;
                                            } // Dikirim is past
                                            if ($index == 3) {
                                                $isCurrent = true;
                                                $isPast = false;
                                            } // Diantar is current
                                        }
                                    @endphp
                                    <div class="flex flex-col items-center group">
                                        <div
                                            class="relative flex h-8 w-8 items-center justify-center rounded-full border-2 
                                            {{ $isCurrent
                                                ? 'bg-green-600 border-green-600 text-white shadow-lg shadow-green-200'
                                                : ($isPast
                                                    ? 'bg-gray-900 border-gray-900 text-white'
                                                    : 'bg-white border-gray-300 text-gray-400') }} transition-colors duration-300">
                                            @if ($isPast || $isCurrent)
                                                <i class="bi bi-check-lg text-sm"></i>
                                            @else
                                                <span class="text-xs font-medium">{{ $index }}</span>
                                            @endif
                                        </div>
                                        <div class="mt-2 text-center px-1">
                                            <span
                                                class="block text-[10px] sm:text-xs font-medium leading-tight {{ $isCurrent ? 'text-green-700 font-bold' : ($isPast ? 'text-gray-900' : 'text-gray-400') }}">
                                                {{ $step['label'] }}
                                            </span>
                                            @if ($step['date'] && ($isPast || $isCurrent))
                                                <p class="text-[9px] sm:text-[10px] text-muted-foreground mt-1">
                                                    {{ $step['date']->format('d M H:i') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="p-6">
                        @if ($order->shipping_provider)
                            <div class="mb-8">
                                <h3 class="font-semibold mb-4 flex items-center gap-2">
                                    <i class="bi bi-truck"></i> Update Pengiriman
                                </h3>
                                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div>
                                            <p
                                                class="text-xs text-muted-foreground uppercase tracking-wider font-semibold">
                                                Jasa Pengiriman</p>
                                            <p class="font-bold text-sm mt-1 text-gray-900">
                                                {{ $order->shipping_provider }}</p>
                                        </div>
                                        <div>
                                            <p
                                                class="text-xs text-muted-foreground uppercase tracking-wider font-semibold">
                                                Nomor Resi</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <p class="font-mono font-bold text-sm text-gray-900">
                                                    {{ $order->tracking_number ?? '-' }}</p>
                                                @if ($order->tracking_number)
                                                    <button onclick="copyResi('{{ $order->tracking_number }}')"
                                                        class="text-blue-600 hover:text-blue-700 transition-colors"
                                                        title="Salin Resi">
                                                        <i class="bi bi-copy text-xs"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            <p
                                                class="text-xs text-muted-foreground uppercase tracking-wider font-semibold">
                                                Waktu Pengiriman</p>
                                            <p class="font-bold text-sm mt-1 text-gray-900">
                                                {{ $order->shipped_at ? $order->shipped_at->format('d M Y, H:i') : 'Menunggu pick-up' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <i class="bi bi-geo-alt"></i> Alamat Pengiriman
                        </h3>
                        <div class="text-sm text-gray-600 mb-8 bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <p class="font-bold text-gray-900 mb-1">{{ Auth::user()->name }}</p>
                            <p class="mb-2">{{ $order->user->no_telepon ?? '-' }}</p>
                            <p class="mb-0 leading-relaxed">
                                {{ $order->shipping_address }}
                            </p>
                            @if ($order->notes)
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <span class="font-semibold text-gray-900">Catatan:</span> {{ $order->notes }}
                                </div>
                            @endif
                        </div>

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
                                                <i class="bi bi-image"></i>
                                            </div>
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

    <!-- Custom Toast Notification -->
    <div id="copyToast"
        class="fixed bottom-5 right-5 bg-gray-900 text-white px-4 py-3 rounded-lg shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 flex items-center gap-2 z-50">
        <i class="bi bi-check-circle-fill text-green-400"></i>
        <span>Nomor Resi berhasil disalin!</span>
    </div>

    <script>
        function copyResi(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('copyToast');
                // Show toast
                toast.classList.remove('translate-y-20', 'opacity-0');

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.add('translate-y-20', 'opacity-0');
                }, 3000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
</body>

</html>

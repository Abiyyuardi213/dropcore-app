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

                        <!-- Product Images Preview -->
                        <div class="mb-4 flex gap-3 overflow-x-auto pb-2 scrollbar-none">
                            @foreach ($order->items->take(4) as $item)
                                <div
                                    class="relative h-16 w-16 flex-shrink-0 rounded-md border bg-gray-50 overflow-hidden">
                                    @if ($item->product->image)
                                        <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                                            alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center text-gray-300">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif
                                    <span
                                        class="absolute bottom-0 right-0 rounded-tl-md bg-black/60 px-1 text-[10px] font-medium text-white">
                                        x{{ $item->quantity }}
                                    </span>
                                </div>
                            @endforeach
                            @if ($order->items->count() > 4)
                                <div
                                    class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-md border bg-gray-50 text-xs font-medium text-gray-500">
                                    +{{ $order->items->count() - 4 }}
                                </div>
                            @endif
                        </div>

                        <div class="mb-4 pt-4 border-t border-dashed">
                            <p class="text-sm">Total: <span class="font-bold">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</span></p>
                            <p class="text-xs text-muted-foreground">{{ $order->items->count() }} Produk</p>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('distributor.order.show', $order->id) }}"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-input bg-background h-9 px-4 hover:bg-accent hover:text-accent-foreground">
                                Lihat Detail
                            </a>

                            @if (in_array($order->status, ['pending', 'processing', 'confirmed']))
                                <button type="button" onclick="openCancelModal('{{ $order->id }}')"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium border border-red-200 bg-red-50 text-red-600 h-9 px-4 hover:bg-red-100">
                                    Batalkan
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <p class="text-muted-foreground">Belum ada pesanan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Cancel Modal -->
    <div id="cancelModal"
        class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 space-y-4">
            <h3 class="text-lg font-bold">Ajukan Pembatalan Pesanan</h3>
            <p class="text-sm text-gray-500">Mohon sebutkan alasan pembatalan Anda. Permintaan ini perlu konfirmasi
                admin.</p>

            <form id="cancelForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="space-y-3">
                    <label class="block">
                        <span class="text-sm font-medium">Alasan Pembatalan</span>
                        <select name="cancellation_reason"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 p-2 border"
                            required onchange="checkReason(this)">
                            <option value="">Pilih Alasan...</option>
                            <option value="Ingin mengubah alamat">Ingin mengubah alamat</option>
                            <option value="Ingin mengubah pesanan">Ingin mengubah pesanan (tambah/kurang produk)
                            </option>
                            <option value="Menemukan harga lebih murah">Menemukan harga lebih murah</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </label>

                    <div id="otherReasonDiv" class="hidden">
                        <label class="block">
                            <span class="text-sm font-medium">Detail Alasan</span>
                            <textarea name="cancellation_note" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary/20 p-2 border"></textarea>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeCancelModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Konfirmasi Pembatalan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCancelModal(orderId) {
            const modal = document.getElementById('cancelModal');
            const form = document.getElementById('cancelForm');
            form.action = "{{ url('pesanan') }}/" + orderId + "/cancel";
            modal.classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        function checkReason(select) {
            const otherDiv = document.getElementById('otherReasonDiv');
            if (select.value === 'Lainnya') {
                otherDiv.classList.remove('hidden');
                otherDiv.querySelector('textarea').required = true;
            } else {
                otherDiv.classList.add('hidden');
                otherDiv.querySelector('textarea').required = false;
            }
        }
    </script>

    @include('include.footer-client')
</body>

</html>

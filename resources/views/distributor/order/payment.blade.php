<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pesanan - Garuda Fiber</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex flex-col"
    style="font-family: 'Inter', sans-serif;">

    @include('include.navbar-client')

    <main class="flex-1 py-10">
        <div class="container mx-auto px-4 max-w-2xl">

            <div class="mb-8 text-center">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                    <i class="bi bi-wallet2 text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Selesaikan Pembayaran</h1>
                <p class="text-gray-500 mt-2">Pesanan #{{ $order->order_number }} berhasil dibuat. Silakan lakukan
                    pembayaran untuk melanjutkan.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Summary Section -->
                <div class="p-6 bg-blue-50/50 border-b border-gray-100">
                    <div class="flex flex-col items-center">
                        <span class="text-sm font-medium text-gray-500 mb-1">Total Tagihan</span>
                        <span class="text-3xl font-bold text-blue-600">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="p-6 space-y-6">

                    <!-- Bank Info -->
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="bi bi-bank text-blue-600"></i> Informasi Rekening Tujuan
                        </h3>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                            <p class="text-sm text-gray-500 mb-1">Transfer ke:</p>
                            <p class="text-lg font-bold text-gray-900">{{ $order->payment_method }}</p>
                            <div
                                class="mt-2 text-xs text-blue-600 bg-blue-50 px-2 py-1 rounded inline-block border border-blue-100">
                                <i class="bi bi-info-circle mr-1"></i> Pastikan nominal transfer sesuai hingga 3 digit
                                terakhir.
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <form action="{{ route('distributor.order.process-payment', $order->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label class="block font-bold text-gray-900 mb-2">Upload Bukti Transfer</label>

                            <div class="relative group">
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 hover:bg-blue-50/20 transition-colors cursor-pointer"
                                    id="dropzone">
                                    <input type="file" name="payment_proof" id="payment_proof"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*"
                                        required onchange="previewImage(this)">

                                    <div class="space-y-2" id="placeholder">
                                        <div
                                            class="w-12 h-12 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto group-hover:bg-blue-100 group-hover:text-blue-500 transition-colors">
                                            <i class="bi bi-cloud-arrow-up text-2xl"></i>
                                        </div>
                                        <p class="text-sm font-medium text-gray-600">Klik atau geser foto ke sini</p>
                                        <p class="text-xs text-gray-400">Format: JPG, PNG (Max. 2MB)</p>
                                    </div>

                                    <div id="preview-container" class="hidden">
                                        <img id="preview" src="#" alt="Preview Bukti"
                                            class="max-h-48 mx-auto rounded-lg shadow-sm">
                                        <p
                                            class="mt-2 text-sm text-blue-600 font-medium cursor-pointer hover:underline">
                                            Ganti Foto</p>
                                    </div>
                                </div>
                            </div>
                            @error('payment_proof')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-500/20 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                            <i class="bi bi-send"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>

                </div>
            </div>

            <div class="mt-6 text-center">
                <a href="{{ route('distributor.order.index') }}"
                    class="text-sm font-medium text-gray-500 hover:text-gray-900">
                    Lewati & Bayar Nanti (Cek Riwayat Pesanan)
                </a>
            </div>

        </div>
    </main>

    @include('include.footer-client')

    <script>
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('placeholder').classList.add('hidden');
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>

</html>

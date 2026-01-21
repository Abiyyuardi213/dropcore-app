<footer class="bg-zinc-950 py-12 text-zinc-300">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Logo -->
            <div>
                <img src="{{ asset('image/gfi-putih.png') }}" alt="Logo" class="mb-6 h-12 w-auto">
                <p class="text-sm leading-relaxed text-zinc-400">
                    Penyedia perangkat dan solusi jaringan terpercaya <br>untuk kebutuhan Anda.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h5 class="mb-6 text-lg font-bold text-white">Navigasi</h5>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="{{ url('/') }}" class="flex items-center gap-2 hover:text-white transition-colors">
                            <i class="bi bi-house-door"></i> Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#produk" class="flex items-center gap-2 hover:text-white transition-colors">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/tentang') }}"
                            class="flex items-center gap-2 hover:text-white transition-colors">
                            <i class="bi bi-info-circle"></i> Tentang Kami
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/berita') }}"
                            class="flex items-center gap-2 hover:text-white transition-colors">
                            <i class="bi bi-newspaper"></i> Berita
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h5 class="mb-6 text-lg font-bold text-white">Kontak</h5>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2">
                        <i class="bi bi-envelope"></i> info@garudafiber.id
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-telephone"></i> +62 812-3456-7890
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-geo-alt"></i> Jakarta, Indonesia
                    </li>
                </ul>
            </div>
        </div>

        <div class="my-8 border-t border-zinc-800"></div>

        <div class="text-center text-xs text-zinc-500">
            &copy; {{ date('Y') }} Garuda Fiber Indonesia. All rights reserved.
        </div>
    </div>
</footer>

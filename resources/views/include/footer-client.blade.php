<footer class="py-4" style="background-color: #0d47a1;">
    <div class="container">
        <div class="row align-items-start text-white">

            <!-- Logo -->
            <div class="col-lg-4 mb-4">
                <img src="{{ asset('image/gfi-putih.png') }}" alt="Logo" height="55" class="mb-3">

                <p class="text-white-200 mb-0">
                    Solusi internet cepat dan terpercaya untuk kebutuhan Anda.
                </p>
            </div>

            <!-- Navigasi -->
            <div class="col-lg-4 mb-4">
                <h5 class="mb-3 fw-bold">Navigasi</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="{{ url('/') }}" class="text-white text-decoration-none">
                            <i class="bi bi-house-door me-2"></i> Beranda
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/produk') }}" class="text-white text-decoration-none">
                            <i class="bi bi-box-seam me-2"></i> Produk
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/tentang') }}" class="text-white text-decoration-none">
                            <i class="bi bi-info-circle me-2"></i> Tentang Kami
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="{{ url('/berita') }}" class="text-white text-decoration-none">
                            <i class="bi bi-newspaper me-2"></i> Berita
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kontak -->
            <div class="col-lg-4 mb-4">
                <h5 class="mb-3 fw-bold">Kontak</h5>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope me-2"></i> info@garudafiber.id
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone me-2"></i> +62 812-3456-7890
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-geo-alt me-2"></i> Jakarta, Indonesia
                    </li>
                </ul>
            </div>

        </div>

        <hr class="border-light opacity-25">

        <div class="text-center text-white-200 small">
            © {{ date('Y') }} Garuda Fiber Indonesia. All rights reserved.
        </div>
    </div>
</footer>

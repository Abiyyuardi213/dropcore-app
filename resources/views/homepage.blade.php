<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PT Garuda Fiber</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .hero {
            padding: 120px 20px;
            background: linear-gradient(135deg, #0d47a1, #1976d2);
            color: white;
            text-align: center;
        }

        .feature-icon {
            font-size: 40px;
            color: #0d47a1;
        }

        .upgrade-card {
            background: #0d47a1;
            color: white;
            border-radius: 15px;
        }

        /* footer {
            background: #0d47a1;
            color: white;
            padding: 40px 20px;
            margin-top: 60px;
        } */
    </style>
</head>

<body>
    @include('include.navbar-client')

    <section class="hero">
        <div class="container">
            <h1 class="fw-bold mb-3">Internet Superfast untuk Semua</h1>
            <p class="mb-4">
                Rasakan kecepatan internet fiber optik terbaik dengan layanan customer service 24/7.
                Koneksi stabil, cepat, dan terpercaya untuk kebutuhan Anda.
            </p>
            <a href="#penawaran" class="btn btn-warning btn-lg me-2">Lihat Penawaran</a>
            <a href="#kontak" class="btn btn-outline-light btn-lg">Hubungi Kami</a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center fw-bold mb-4">Navigasi Utama</h2>
            <div class="row g-4">
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Produk</div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Tentang Kami</div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Berita</div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Temukan Kami</div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Gabung Distributor</div>
                </div>
                <div class="col-md-4 col-lg-2">
                    <div class="card p-3 text-center">Dukungan</div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Fitur Unggulan Garuda Fiber</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Kecepatan Ultra Tinggi</h5>
                        <p>Nikmati kecepatan hingga 300 Mbps dengan teknologi fiber optik terkini.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Support 24/7</h5>
                        <p>Tim customer service kami siap membantu kapan saja.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Instalasi Mudah</h5>
                        <p>Instalasi cepat dan mudah tanpa ribet.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Koneksi Stabil</h5>
                        <p>Uptime 99.9% untuk koneksi tanpa terputus.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Harga Kompetitif</h5>
                        <p>Paket internet berkualitas dengan harga terjangkau.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4">
                        <h5>Trusted by Thousands</h5>
                        <p>Dipercaya ribuan pelanggan seluruh Indonesia.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="upgrade-card p-5 text-center">

                @if (Auth::check())
                    {{-- Jika user login --}}
                    <h2 class="fw-bold mb-3">Explore Produk Kami</h2>
                    <p class="mb-4">Lihat berbagai paket internet terbaik yang sesuai dengan kebutuhan Anda.</p>
                    <a href="{{ url('/produk') }}" class="btn btn-warning btn-lg">Lihat Produk</a>
                @else
                    {{-- Jika user belum login --}}
                    <h2 class="fw-bold mb-3">Siap Upgrade Internet Anda?</h2>
                    <p class="mb-4">Bergabunglah dengan ribuan pelanggan yang telah merasakan kecepatan internet fiber
                        optik terbaik.</p>
                    <a href="#daftar" class="btn btn-warning btn-lg">Daftar Sekarang</a>
                @endif

            </div>
        </div>
    </section>

    @include('include.footer-client')
    @include('services.ToastModal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

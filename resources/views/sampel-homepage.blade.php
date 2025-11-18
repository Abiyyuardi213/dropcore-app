<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garuda Fiber - Internet Provider Terbaik Indonesia</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: #008BAA;
            --color-primary-dark: #006B89;
            --color-accent: #00D9FF;
            --color-bg: #FFFFFF;
            --color-bg-light: #F8FAFB;
            --color-text: #1A1A1A;
            --color-text-muted: #666666;
            --color-border: #E5E7EB;
            --color-shadow: rgba(0, 0, 0, 0.08);
        }
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: var(--color-text);
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95) !important;
            border-bottom: 1px solid var(--color-border);
            z-index: 1000;
            backdrop-filter: blur(10px);
            height: 80px;
            padding: 0 20px;
        }
        .navbar-brand {
            color: var(--color-primary) !important;
            font-weight: 800;
            font-size: 20px;
            gap: 12px;
            display: flex;
            align-items: center;
        }
        .profile-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: var(--color-primary);
            color: white !important;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .profile-btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px var(--color-shadow);
        }
        main {
            margin-top: 80px;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-bg-light) 100%);
            position: relative;
            overflow: hidden;
            padding: 60px 20px;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, var(--color-accent) 0%, transparent 70%);
            opacity: 0.08;
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--color-primary) 0%, transparent 70%);
            opacity: 0.05;
            border-radius: 50%;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 800px;
        }
        .hero-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2.5rem, 8vw, 4.5rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: clamp(1rem, 2.5vw, 1.25rem);
            color: var(--color-text-muted);
            margin-bottom: 40px;
            line-height: 1.8;
        }
        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 60px;
        }
        .btn-primary-custom {
            padding: 14px 32px;
            background: var(--color-primary);
            color: white !important;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            background: var(--color-primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 139, 170, 0.3);
        }
        .btn-secondary-custom {
            padding: 14px 32px;
            background: transparent;
            color: var(--color-primary);
            border: 2px solid var(--color-primary);
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary-custom:hover {
            background: var(--color-primary);
            color: white;
            transform: translateY(-3px);
        }
        .menu-section {
            padding: 100px 20px;
            background: var(--color-bg-light);
        }
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(2rem, 5vw, 3rem);
            font-weight: 700;
            text-align: center;
            margin-bottom: 60px;
            color: var(--color-text);
        }
        .menu-card {
            background: var(--color-bg);
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--color-border);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            height: 100%;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--color-primary) 0%, var(--color-accent) 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .menu-card:hover {
            border-color: var(--color-primary);
            box-shadow: 0 16px 32px var(--color-shadow);
            transform: translateY(-8px);
        }
        .menu-card:hover::before {
            transform: scaleX(1);
        }
        .menu-icon {
            font-size: 48px;
            color: var(--color-primary);
        }
        .menu-card-title {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--color-text);
        }
        .menu-card-desc {
            font-size: 14px;
            color: var(--color-text-muted);
            line-height: 1.6;
        }
        .features-section {
            padding: 100px 20px;
            background: var(--color-bg);
        }
        .feature-item {
            display: flex;
            gap: 20px;
        }
        .feature-icon {
            flex-shrink: 0;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
        }
        .feature-content h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--color-text);
        }
        .feature-content p {
            color: var(--color-text-muted);
            font-size: 14px;
            line-height: 1.6;
        }
        .cta-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-radius: 20px;
            text-align: center;
            color: white;
            margin: 100px 20px;
        }
        .cta-title {
            font-family: 'Poppins', sans-serif;
            font-size: clamp(1.5rem, 4vw, 2.5rem);
            font-weight: 700;
            margin-bottom: 20px;
        }
        .cta-text {
            font-size: 16px;
            margin-bottom: 30px;
            opacity: 0.95;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-cta {
            background: white;
            color: var(--color-primary) !important;
            padding: 14px 32px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }
        footer {
            background: var(--color-text);
            color: white;
            padding: 60px 20px 30px;
        }
        .footer-section h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .footer-section ul {
            list-style: none;
            padding: 0;
        }
        .footer-section ul li {
            margin-bottom: 10px;
        }
        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
        }
        .footer-section ul li a:hover {
            color: white;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 30px;
            text-align: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
        }

        @media (max-width: 768px) {
            .navbar {
                height: 70px;
                padding: 0 15px;
            }
            .navbar-brand {
                font-size: 18px;
            }
            .profile-btn {
                padding: 8px 12px;
                font-size: 12px;
            }
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                max-width: 300px;
            }
            .menu-card {
                padding: 30px 20px;
            }
            .hero::before,
            .hero::after {
                width: 300px;
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <span>🦅 GARUDA FIBER</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <a href="#profile" class="profile-btn">
                    <i class="fas fa-user-circle"></i>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <h1 class="hero-title">Internet Superfast untuk Semua</h1>
                <p class="hero-subtitle">Rasakan kecepatan internet fiber optik terbaik dengan layanan customer service 24/7. Koneksi stabil, cepat, dan terpercaya untuk kebutuhan Anda.</p>
                <div class="hero-buttons">
                    <a href="#tentang" class="btn-primary-custom">Lihat Penawaran</a>
                    <a href="#kontak" class="btn-secondary-custom">Hubungi Kami</a>
                </div>
            </div>
        </section>

        <!-- Menu Section -->
        <section class="menu-section" id="tentang">
            <div class="container">
                <h2 class="section-title">Layanan Kami</h2>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <a href="#produk" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <h3 class="menu-card-title">Produk</h3>
                            <p class="menu-card-desc">Paket internet fiber optik berkecepatan tinggi dengan harga terjangkau</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="#tentang-kami" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="menu-card-title">Tentang Kami</h3>
                            <p class="menu-card-desc">Pelajari lebih lanjut tentang misi dan visi Garuda Fiber</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="#berita" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <h3 class="menu-card-title">Berita</h3>
                            <p class="menu-card-desc">Update terbaru tentang perkembangan layanan kami</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="#temukan" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="menu-card-title">Temukan Kami</h3>
                            <p class="menu-card-desc">Cari lokasi terdekat dan area jangkauan kami</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="#gabung" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <h3 class="menu-card-title">Gabung Distributor</h3>
                            <p class="menu-card-desc">Peluang bisnis menguntungkan bersama Garuda Fiber</p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <a href="#support" class="menu-card">
                            <div class="menu-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3 class="menu-card-title">Dukungan</h3>
                            <p class="menu-card-desc">Layanan customer service responsif 24/7</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title">Keunggulan Garuda Fiber</h2>
                <div class="row g-5 mt-4">
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Kecepatan Ultra Tinggi</h3>
                                <p>Nikmati kecepatan internet hingga 300 Mbps dengan teknologi fiber optik terkini</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Koneksi Stabil</h3>
                                <p>Uptime 99.9% menjamin layanan internet Anda tidak pernah terputus</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Support 24/7</h3>
                                <p>Tim customer service kami siap membantu Anda kapan saja</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Harga Kompetitif</h3>
                                <p>Dapatkan paket internet berkualitas dengan harga yang terjangkau</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Instalasi Mudah</h3>
                                <p>Proses instalasi cepat dan mudah tanpa ribet</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="feature-content">
                                <h3>Trusted by Thousands</h3>
                                <p>Dipercaya oleh ribuan pelanggan di seluruh Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="container-fluid px-0">
            <div class="container">
                <div class="cta-section">
                    <h2 class="cta-title">Siap Upgrade Internet Anda?</h2>
                    <p class="cta-text">Bergabunglah dengan ribuan pelanggan yang telah merasakan kecepatan internet fiber optik terbaik</p>
                    <a href="#kontak" class="btn-cta">Daftar Sekarang</a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-4">
                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>🦅 Garuda Fiber</h4>
                    <p style="color: rgba(255, 255, 255, 0.7); font-size: 14px;">Internet fiber optik terpercaya untuk kebutuhan rumah dan bisnis Anda.</p>
                </div>
                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#tentang">Tentang Kami</a></li>
                        <li><a href="#produk">Produk</a></li>
                        <li><a href="#berita">Berita</a></li>
                        <li><a href="#temukan">Temukan Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Layanan</h4>
                    <ul>
                        <li><a href="#support">Dukungan Pelanggan</a></li>
                        <li><a href="#faq">FAQ</a></li>
                        <li><a href="#status">Status Layanan</a></li>
                        <li><a href="#kontak">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-section">
                    <h4>Kontak</h4>
                    <ul>
                        <li><a href="tel:+62123456789"><i class="fas fa-phone"></i> +62 123 456 789</a></li>
                        <li><a href="mailto:info@garudafiber.com"><i class="fas fa-envelope"></i> info@garudafiber.com</a></li>
                        <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 Garuda Fiber. Semua hak dilindungi. | <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Kebijakan Privasi</a> | <a href="#" style="color: rgba(255, 255, 255, 0.7); text-decoration: none;">Syarat Penggunaan</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth scroll behavior for links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Active link highlighting
        window.addEventListener('scroll', () => {
            let current = '';
            const sections = document.querySelectorAll('section');
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });
        });
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - Garuda Fiber</title>

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

    <main class="flex-1">
        <!-- Hero Section -->
        <section class="relative py-20 bg-muted/30 overflow-hidden border-b">
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-3xl">
                    <h1 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">Membangun Masa Depan Konektivitas
                        Digital</h1>
                    <p class="text-lg text-muted-foreground leading-relaxed">
                        Garuda Fiber hadir sebagai mitra terpercaya bagi perusahaan dan instansi dalam menyediakan
                        solusi infrastruktur jaringan yang handal, cepat, dan aman.
                    </p>
                </div>
            </div>
        </section>

        <!-- Our Story -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                    <div class="space-y-6">
                        <div
                            class="inline-flex items-center rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary">
                            Kisah Kami</div>
                        <h2 class="text-3xl font-bold">Inovasi Tanpa Henti Sejak 2018</h2>
                        <p class="text-muted-foreground">
                            Berawal dari sebuah tim kecil ahli IT di Jakarta, Garuda Fiber tumbuh menjadi salah satu
                            penyedia perangkat jaringan fiber optic terkemuka di Indonesia. Kami memahami bahwa di dunia
                            digital saat ini, koneksi bukan sekadar kebutuhan, melainkan tulang punggung dari
                            produktivitas.
                        </p>
                        <p class="text-muted-foreground">
                            Dengan dedikasi pada kualitas dan kepuasan pelanggan, kami telah melayani ribuan klien mulai
                            dari UMKM hingga perusahaan multinasional dalam membangun infrastruktur IT yang kokoh.
                        </p>
                        <div class="grid grid-cols-2 gap-8 pt-6">
                            <div>
                                <h3 class="text-2xl font-bold text-primary">500+</h3>
                                <p class="text-xs text-muted-foreground uppercase tracking-widest font-semibold">Proyek
                                    Selesai</p>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-primary">15+</h3>
                                <p class="text-xs text-muted-foreground uppercase tracking-widest font-semibold">
                                    Provinsi Terjangkau</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="aspect-video rounded-2xl bg-muted overflow-hidden border shadow-xl">
                            <img src="{{ asset('image/server-room.jpg') }}" alt="Server Room"
                                class="object-cover w-full h-full">
                        </div>
                        <div
                            class="absolute -bottom-6 -left-6 bg-primary text-primary-foreground p-8 rounded-2xl shadow-2xl hidden md:block">
                            <p class="text-3xl font-bold mb-1">99.9%</p>
                            <p class="text-xs font-medium opacity-80 uppercase tracking-widest">Uptime Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values -->
        <section class="py-20 bg-muted/20 border-y">
            <div class="container mx-auto px-4">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <h2 class="text-3xl font-bold mb-4">Nilai-Nilai Kami</h2>
                    <p class="text-muted-foreground">Prinsip yang membimbing kami dalam setiap proyek dan interaksi
                        dengan pelanggan.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-card p-8 rounded-2xl border shadow-sm space-y-4">
                        <div
                            class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="text-xl font-bold">Kualitas Premium</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">
                            Kami hanya menyediakan perangkat yang telah teruji dan memenuhi standar internasional untuk
                            memastikan durabilitas tinggi.
                        </p>
                    </div>
                    <div class="bg-card p-8 rounded-2xl border shadow-sm space-y-4">
                        <div
                            class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="text-xl font-bold">Orientasi Pelanggan</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">
                            Bagi kami, kesuksesan pelanggan adalah prioritas. Kami memberikan solusi yang personal
                            sesuai kebutuhan unik Anda.
                        </p>
                    </div>
                    <div class="bg-card p-8 rounded-2xl border shadow-sm space-y-4">
                        <div
                            class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-2xl">
                            <i class="bi bi-lightning"></i>
                        </div>
                        <h3 class="text-xl font-bold">Respon Cepat</h3>
                        <p class="text-sm text-muted-foreground leading-relaxed">
                            Di dunia yang bergerak cepat, kami berkomitmen memberikan layanan pengiriman dan support
                            teknis yang sigap.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-20">
            <div class="container mx-auto px-4">
                <div class="bg-zinc-950 rounded-3xl p-8 md:p-16 text-center text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 h-80 w-80 bg-primary/20 rounded-full blur-3xl">
                    </div>
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Mengakselerasi Bisnis Anda?</h2>
                        <p class="text-zinc-400 max-w-2xl mx-auto mb-10 text-lg">
                            Hubungi tim ahli kami untuk konsultasi infrastruktur jaringan atau jelajahi katalog produk
                            unggulan kami.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <a href="{{ route('distributor.products') }}"
                                class="bg-primary text-primary-foreground px-8 py-3 rounded-full font-bold hover:bg-primary/90 transition-colors">Lihat
                                Produk</a>
                            <a href="#"
                                class="bg-white text-zinc-950 px-8 py-3 rounded-full font-bold hover:bg-zinc-200 transition-colors">Hubungi
                                Kami</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('include.footer-client')
    @include('include.cart-scripts')
</body>

</html>

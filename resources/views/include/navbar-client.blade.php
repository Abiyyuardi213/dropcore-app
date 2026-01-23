<nav class="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 sticky top-0 z-50">
    <div class="container mx-auto px-4 flex h-16 items-center justify-between">
        <!-- Logo -->
        <a class="flex items-center gap-2 font-bold text-xl tracking-tight" href="{{ url('/homepage') }}">
            <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo" class="h-8 w-auto">
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-muted-foreground">
            <a href="{{ url('/homepage') }}" class="transition-colors hover:text-primary">Beranda</a>
            <a href="{{ route('distributor.products') }}" class="transition-colors hover:text-primary">Produk</a>
            <a href="{{ route('distributor.about') }}" class="transition-colors hover:text-primary">Tentang Kami</a>
            <a href="{{ route('distributor.news') }}" class="transition-colors hover:text-primary">Berita</a>
        </div>


        <!-- Right Side (Auth & Cart) -->
        <div class="flex items-center gap-4">
            @auth
                <div class="relative group">
                    <a href="{{ route('distributor.cart') }}" id="cart-icon-wrapper"
                        class="relative flex items-center justify-center text-muted-foreground hover:text-foreground transition-all duration-300 p-2 rounded-full hover:bg-accent">
                        <i class="bi bi-cart3 text-xl"></i>
                        @php
                            $cartItems = \App\Models\Cart::where('user_id', Auth::id())
                                ->with('product')
                                ->latest()
                                ->take(5)
                                ->get();
                            $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count();
                        @endphp
                        @if ($cartCount > 0)
                            <span id="cart-count-badge"
                                class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground border-2 border-background">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Mini Cart Dropdown -->
                    <div
                        class="absolute right-0 top-full mt-2 w-80 rounded-xl border border-border bg-popover/95 backdrop-blur-sm text-popover-foreground shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50 transform translate-y-2 group-hover:translate-y-0">
                        <div class="p-4 border-b">
                            <h3 class="font-bold text-sm">Keranjang Belanja</h3>
                        </div>
                        <div class="max-h-[300px] overflow-y-auto p-4 space-y-4" id="mini-cart-items">
                            @include('include.mini-cart-list')
                        </div>
                        @if ($cartCount > 5)
                            <div class="px-4 py-2 bg-muted/30 text-center border-t">
                                <p class="text-[10px] text-muted-foreground font-medium">+ {{ $cartCount - 5 }} produk
                                    lainnya</p>
                            </div>
                        @endif
                        <div class="p-4 border-t bg-muted/20">
                            <a href="{{ route('distributor.cart') }}"
                                class="flex items-center justify-center w-full rounded-md bg-primary h-9 px-4 text-xs font-bold text-primary-foreground shadow transition-all hover:bg-primary/90">
                                Lihat Semua Keranjang
                            </a>
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <button class="flex items-center gap-2 text-sm font-medium focus:outline-none">
                        <span class="hidden sm:inline-block">{{ Auth::user()->username }}</span>
                        @if (Auth::user()->profile_picture)
                            <img src="{{ asset('uploads/profile/' . Auth::user()->profile_picture) }}" alt="Profile"
                                class="h-8 w-8 rounded-full object-cover border border-border">
                        @else
                            <div
                                class="h-8 w-8 rounded-full bg-muted flex items-center justify-center border border-border">
                                <i class="bi bi-person text-muted-foreground"></i>
                            </div>
                        @endif
                        <i
                            class="bi bi-chevron-down text-xs text-muted-foreground group-hover:text-foreground transition-colors"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute right-0 top-full mt-2 w-56 rounded-md border border-border bg-popover text-popover-foreground shadow-md opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="p-1">
                            <a href="{{ route('distributor.profil') }}"
                                class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">
                                <i class="bi bi-person mr-2 h-4 w-4"></i>
                                <span>Profil</span>
                            </a>
                            <a href="{{ route('distributor.order.index') }}"
                                class="relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground">
                                <i class="bi bi-bag mr-2 h-4 w-4"></i>
                                <span>Order</span>
                            </a>
                            <div class="h-px bg-border my-1"></div>
                            <button type="button" onclick="openLogoutModal()"
                                class="relative flex w-full cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-destructive hover:text-destructive-foreground transition-colors">
                                <i class="bi bi-box-arrow-right mr-2 h-4 w-4"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login.distributor') }}"
                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-9 px-4 py-2">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</nav>

@auth
    <!-- Logout Confirmation Modal (Moved outside <nav> to prevent clipping) -->
    <div id="logout-modal" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-background/80 backdrop-blur-sm transition-opacity" onclick="closeLogoutModal()">
        </div>

        <!-- Dialog Content -->
        <div
            class="fixed left-[50%] top-[50%] z-[101] grid w-full max-w-lg translate-x-[-50%] translate-y-[-50%] gap-4 border bg-background p-6 shadow-lg duration-200 sm:rounded-lg md:w-full animate-in fade-in zoom-in-95 slide-in-from-bottom-2">
            <div class="flex flex-col space-y-1.5 text-center sm:text-left">
                <h2 class="text-lg font-semibold leading-none tracking-tight">Konfirmasi Logout</h2>
                <p class="text-sm text-muted-foreground">Apakah Anda yakin ingin keluar dari akun Anda?</p>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end sm:space-x-2 gap-2 sm:gap-0 mt-4">
                <button type="button" onclick="closeLogoutModal()"
                    class="inline-flex h-10 items-center justify-center rounded-md border border-input bg-background px-4 py-2 text-sm font-medium ring-offset-background transition-colors hover:bg-accent hover:text-accent-foreground">
                    Batal
                </button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="inline-flex h-10 w-full sm:w-auto items-center justify-center rounded-md bg-destructive px-4 py-2 text-sm font-medium text-destructive-foreground ring-offset-background transition-colors hover:bg-destructive/90">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logout-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLogoutModal();
        });
    </script>
@endauth

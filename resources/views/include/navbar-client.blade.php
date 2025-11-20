<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/homepage') }}">
            <img src="{{ asset('image/garuda-fiber.png') }}" alt="Logo" height="40" class="me-2">
        </a>

        <div class="ms-auto d-flex align-items-center">

            @auth
                {{-- Dropdown --}}
                <div class="dropdown">
                    <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
                       href="#"
                       role="button"
                       id="profileDropdown"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">

                        {{-- Username --}}
                        <span class="me-2 fw-semibold">
                            {{ Auth::user()->username }}
                        </span>

                        {{-- Foto Profil --}}
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('uploads/profile/' . Auth::user()->profile_picture) }}"
                                alt="Profile"
                                height="40"
                                width="40"
                                class="rounded-circle"
                                style="object-fit: cover;">
                        @else
                            <img src="{{ asset('images/default.png') }}"
                                alt="Default Profile"
                                height="40"
                                width="40"
                                class="rounded-circle"
                                style="object-fit: cover;">
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">

                        {{-- Menu Profile --}}
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.profil') }}">
                                <i class="bi bi-person me-2"></i> Profil
                            </a>
                        </li>

                        {{-- Menu Order --}}
                        <li>
                            <a class="dropdown-item" href="{{ url('/order') }}">
                                <i class="bi bi-bag me-2"></i> Order
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        {{-- Logout --}}
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item text-danger" type="submit">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>

                    </ul>
                </div>

            @else
                <a href="{{ route('login.customer') }}" class="btn btn-primary">Login</a>
            @endauth
        </div>
    </div>
</nav>

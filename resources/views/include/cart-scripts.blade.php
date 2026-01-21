<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function addToCart(productId, event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        // Find the product image for the animation
        let productCard = event ? event.target.closest('.group') : null;
        let productImage = productCard ? productCard.querySelector('img') : null;

        fetch("{{ route('customer.cart.add') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = "{{ route('login.customer') }}";
                    return;
                }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    if (productImage) {
                        animateToCart(productImage, data.cart_count);
                    } else {
                        showSuccessToast(data.success);
                        updateCartCount(data.cart_count);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function animateToCart(imageElement, newCount) {
        const cartIcon = document.getElementById('cart-icon-wrapper');
        if (!cartIcon || !imageElement) return;

        const imgRect = imageElement.getBoundingClientRect();
        const cartRect = cartIcon.getBoundingClientRect();

        const flyImg = imageElement.cloneNode(true);
        flyImg.classList.add('cart-fly');
        flyImg.style.top = imgRect.top + 'px';
        flyImg.style.left = imgRect.left + 'px';
        flyImg.style.width = imgRect.width + 'px';
        flyImg.style.height = imgRect.height + 'px';

        document.body.appendChild(flyImg);

        // Required for transition/animation to trigger correctly
        requestAnimationFrame(() => {
            flyImg.style.top = cartRect.top + 'px';
            flyImg.style.left = cartRect.left + 'px';
            flyImg.style.width = '20px';
            flyImg.style.height = '20px';
            flyImg.style.opacity = '0.5';
            flyImg.style.transform = 'scale(0.2)';
        });

        setTimeout(() => {
            flyImg.remove();
            updateCartCount(newCount);

            showSuccessToast('Produk ditambahkan ke keranjang');

            // Pulse effect on cart icon
            cartIcon.classList.add('scale-110', 'text-primary');
            setTimeout(() => {
                cartIcon.classList.remove('scale-110', 'text-primary');
            }, 3000);
        }, 800);
    }

    function updateCartCount(newCount) {
        if (newCount === undefined) return;

        const cartIcon = document.getElementById('cart-icon-wrapper');
        let badge = document.getElementById('cart-count-badge');

        if (newCount > 0) {
            if (!badge) {
                badge = document.createElement('span');
                badge.id = 'cart-count-badge';
                badge.className =
                    'absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-primary-foreground';
                cartIcon.appendChild(badge);
            }
            badge.innerText = newCount;
        } else if (badge) {
            badge.remove();
        }
    }

    function showSuccessToast(message) {
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
</script>

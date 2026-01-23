@forelse($cartItems as $item)
    <div class="flex items-center gap-3 group/item">
        <div class="h-12 w-12 rounded-lg bg-muted flex-shrink-0 overflow-hidden border">
            @if ($item->product->image)
                <img src="{{ asset('uploads/product/' . $item->product->image) }}"
                    class="h-full w-full object-contain p-1">
            @else
                <div class="h-full w-full flex items-center justify-center bg-muted text-muted-foreground">
                    <i class="bi bi-image text-xs"></i>
                </div>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-semibold truncate">{{ $item->product->name }}</p>
            <p class="text-[10px] text-muted-foreground">{{ $item->quantity }} x Rp
                {{ number_format($item->product->price, 0, ',', '.') }}</p>
        </div>
    </div>
@empty
    <div class="py-4 text-center">
        <i class="bi bi-cart-x text-2xl text-muted-foreground/30 mb-2 block"></i>
        <p class="text-xs text-muted-foreground">Keranjang Anda kosong</p>
    </div>
@endforelse

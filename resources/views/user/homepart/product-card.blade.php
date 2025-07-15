<div class="col-6 col-md-4 col-lg-2"> <!-- Now shows 6 cards in a row on desktop -->
    <div class="card text-center shadow-sm border-0 h-100 product-card" style="width: 160px; margin: auto;">
        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}"
             class="card-img-top"
             style="height: 100px; object-fit: contain; background: #f9f9f9;"
             alt="{{ $product->name }}"
             loading="lazy">

        <div class="card-body p-2">
            <h6 class="card-title mb-1 text-truncate" style="font-size: 0.85rem;">{{ $product->name }}</h6>

            <p class="mb-1 small text-muted" style="font-size: 0.75rem;">
                {{ $product->category->name ?? 'Uncategorized' }}
            </p>

            @if ($product->compare_price && $product->compare_price > $product->price)
                <div class="small text-muted">
                    <del>PKR {{ number_format($product->compare_price) }}</del>
                </div>
            @endif

            <div class="fw-bold text-success mb-2 small">
                PKR {{ number_format($product->price) }}
            </div>

            <a href="{{ url('/product/' . $product->id) }}"
               class="btn btn-outline-primary btn-sm w-100 rounded-pill" style="font-size: 0.75rem;">
                View
            </a>
        </div>
    </div>
</div>

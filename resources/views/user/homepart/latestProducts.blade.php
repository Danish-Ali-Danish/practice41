
<!-- Latest Products -->
<section class="my-5">
    <div class="container">
        <h4 class="fw-bold mb-4">Latest Products</h4>
        <div class="row g-3">
            @foreach ($products as $product)
                <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                    <div class="card h-100 shadow-sm text-center">
                        <div class="overflow-hidden">
                            <img src="{{ asset('storage/' . ($product->image ?? 'images/default-product.png')) }}"
                                 class="card-img-top img-fluid rounded product-hover"
                                 style="height: 120px; object-fit: cover; transition: transform 0.3s;" loading="lazy">
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1">{{ Str::limit($product->name, 20) }}</h6>
                            <p class="text-muted small mb-1">PKR {{ number_format($product->price) }}</p>
                            <a href="{{ url('/product/' . $product->id) }}" class="btn btn-outline-primary btn-sm w-100">View</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

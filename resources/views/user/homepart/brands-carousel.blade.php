
<!-- Brands Carousel -->
<section class="my-5">
    <div class="container">
        <!-- Heading + View All -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Brand</h2>
            <a href="{{ route('allbrands') }}" class="btn btn-outline-primary btn-sm">View All Brands</a>
        </div>

        <!-- Carousel Scroll Row -->
        <div class="overflow-auto d-flex flex-nowrap px-2" id="brandCarousel" style="scroll-behavior: smooth;">
            @foreach ($brands as $brand)
                <div class="card me-3 text-center shadow-sm flex-shrink-0" style="width: 160px;">
                    <img src="{{ $brand->file_path ? asset('storage/' . $brand->file_path) : asset('images/default-brand.png') }}"
                         class="card-img-top img-fluid rounded" style="height: 100px; object-fit: cover;" alt="{{ $brand->name }}" loading="lazy">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">{{ $brand->name }}</h6>
                        <a href="{{ route('allproducts') }}" class="btn btn-outline-primary btn-sm w-100">Explore</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Arrow Buttons Row -->
        <div class="d-flex justify-content-center mt-3 gap-3">
            <button class="btn btn-outline-primary" id="brandPrev">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </button>
            <button class="btn btn-outline-primary" id="brandNext">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>
</section>

<!-- Swiper Brands Carousel -->
<section class="my-5">
    <div class="container">
        <!-- Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Brand</h2>
            <a href="{{ route('allbrands') }}" class="btn btn-outline-primary btn-sm">View All Brands</a>
        </div>

        <!-- Swiper Container -->
        <div class="swiper brand-swiper">
            <div class="swiper-wrapper">
                @foreach ($brands as $brand)
                    <div class="swiper-slide">
                        <div class="card text-center shadow-sm border-0 mx-2" style="width: 160px;">
                            <img src="{{ $brand->file_path ? asset('storage/' . $brand->file_path) : asset('images/default-brand.png') }}"
                                 class="card-img-top img-fluid previewable-image"
                                 style="height: 100px; object-fit: cover;"
                                 alt="{{ $brand->name }}"
                                 loading="lazy">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">{{ $brand->name }}</h6>
                                <a href="{{ route('allproducts', ['brand' => $brand->id]) }}"
                                   class="btn btn-outline-primary btn-sm w-100">Explore</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Arrows -->
            <div class="d-flex justify-content-center mt-4 gap-3">
                <button class="btn btn-outline-primary btn-sm rounded-circle shadow swiper-brand-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm rounded-circle shadow swiper-brand-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

@extends('user.layouts.master')

@section('title', 'Home - ShopNow')

@section('content')
@push('styles')
<style>
    .product-card {
        transition: 0.3s ease;
        border-radius: 1rem;
        padding: 0.5rem;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .card-img-top {
        height: 120px !important; /* was 160px */
        object-fit: contain;
        background-color: #f8f9fa;
        padding: 0.5rem;
        cursor: pointer;
    }

    .card-body {
        padding: 0.6rem 0.75rem;
    }

    .card-title {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .card-body p {
        font-size: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .card-body .btn {
        font-size: 0.75rem;
        padding: 6px 12px;
    }
    .swiper-category-prev,
.swiper-category-next {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.testimonial-swiper .card {
        min-height: 330px;
    }
</style>
@endpush



<!-- Hero Section -->
<section class="bg-light py-5 text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Welcome to ShopNow</h1>
        <p class="lead text-muted mb-4">Discover top-rated products with free shipping on orders over $50</p>
        
        <x-search-box />
        
        <div class="mt-4 d-flex justify-content-center gap-3 flex-wrap">
            <a href="{{ route('allproducts') }}" class="btn btn-primary px-4">Shop Now</a>
            <a href="{{ route('allproducts', ['filter' => 'featured']) }}" class="btn btn-outline-dark px-4">Featured Products</a>
            </div>

        <!-- Category Shortcuts -->
        <!-- <div class="mt-4 d-flex justify-content-center flex-wrap gap-2">
            @foreach($categories as $category)
                <a href="{{ route('allproducts', ['category' => $category->slug]) }}" class="btn btn-outline-secondary btn-sm rounded-pill">{{ $category->name }}</a>
            @endforeach
        </div> -->
    </div>
</section>

<!-- Featured Products -->
<!-- Featured Products -->
<section class="py-5 bg-white">
    <div class="container">
        <h4 class="fw-bold mb-4">Featured Products</h4>
        <div class="row g-4">
            @forelse($featured as $product)
                @include('user.homepart.product-card', ['product' => $product])
            @empty
                <div class="col-12 text-center text-muted">No featured products available.</div>
            @endforelse
        </div>
    </div>
</section>

<!-- Promo Banners -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            @foreach($promos->take(3) as $promo)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="{{ asset('storage/' . $promo->image) }}" class="card-img-top" style="height:150px; object-fit:cover;" alt="{{ $promo->title }}" loading="lazy">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $promo->title }}</h5>
                            @if($promo->button_text && $promo->button_link)
                                <a href="{{ $promo->button_link }}" class="btn btn-outline-primary btn-sm mt-2">{{ $promo->button_text }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Categories Carousel -->
@include('user.homepart.categories-carousel')

<!-- Brands Carousel -->
@include('user.homepart.brands-carousel')

<!-- Other Products -->
<section class="py-5 bg-white">
    <div class="container">
        <h4 class="fw-bold mb-4">Latest Products</h4>
        <div class="row g-4">
            @forelse($products as $product)
                @include('user.homepart.product-card', ['product' => $product])
            @empty
                <div class="col-12 text-center text-muted">No products found.</div>
            @endforelse
        </div>
    </div>
</section>


<!-- Testimonials -->
@include('user.homepart.homeTestimonials')

<!-- Blog Posts -->
@include('user.homepart.blog-preview')

<!-- Newsletter -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h4 class="fw-bold mb-2">Subscribe to Our Newsletter</h4>
        <p class="mb-4">Get the latest updates on new products and upcoming sales</p>
        <form id="newsletter-form" class="row justify-content-center g-2">
            <div class="col-md-6 col-sm-8">
                <div class="input-group input-group-lg">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button class="btn btn-dark px-4" type="submit">Subscribe</button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Features Section -->
@include('user.homepart.feature-section')

@endsection
@push('scripts')
<script>
$(document).ready(function () {
    $('#global-search').on('keyup', function () {
        let query = $(this).val().trim();

        if (query.length < 2) {
            $('#search-results').hide().html('');
            return;
        }

        $.ajax({
            url: "{{ route('search.suggestions') }}",
            type: "GET",
            data: { q: query },
            success: function (res) {
                let html = '';
                if (res.products.length || res.categories.length || res.brands.length) {
                    res.products.forEach(product => {
                        html += `
                            <a href="/product/${product.id}" class="dropdown-item">
                                <strong>${product.name}</strong>
                                <small class="text-muted d-block">Product</small>
                            </a>`;
                    });

                    res.categories.forEach(cat => {
                        html += `
                            <a href="/allproducts?category=${cat.id}" class="dropdown-item">
                                ${cat.name}
                                <small class="text-muted d-block">Category</small>
                            </a>`;
                    });

                    res.brands.forEach(brand => {
                        html += `
                            <a href="/allproducts?brand=${brand.id}" class="dropdown-item">
                                ${brand.name}
                                <small class="text-muted d-block">Brand</small>
                            </a>`;
                    });
                } else {
                    html = '<div class="dropdown-item text-muted">No results found</div>';
                }

                $('#search-results').html(html).show();
            }
        });
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#global-search, #search-results').length) {
            $('#search-results').hide();
        }
    });
});
</script>
@endpush
@push('scripts')
<script>
    const brandSwiper = new Swiper('.brand-swiper', {
        slidesPerView: 2,
        spaceBetween: 10,
        breakpoints: {
            576: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            992: { slidesPerView: 5 },
            1200: { slidesPerView: 6 },
        },
        navigation: {
            nextEl: '.swiper-brand-next',
            prevEl: '.swiper-brand-prev',
        },
    });
</script>
<script>
    const categorySwiper = new Swiper('.category-swiper', {
        slidesPerView: 2,
        spaceBetween: 10,
        breakpoints: {
            576: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            992: { slidesPerView: 5 },
            1200: { slidesPerView: 6 },
        },
        navigation: {
            nextEl: '.swiper-category-next',
            prevEl: '.swiper-category-prev',
        },
    });
</script>
<script>
    const testimonialSwiper = new Swiper('.testimonial-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.testimonial-next',
            prevEl: '.testimonial-prev',
        },
        breakpoints: {
            768: { slidesPerView: 1 },
            992: { slidesPerView: 2 },
        },
    });
</script>
@endpush


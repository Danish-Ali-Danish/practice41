@extends('user.layouts.master')

@section('title', 'Home - ShopNow')

@section('content')
<!-- Hero Section -->
<section class="hero-section position-relative text-white overflow-hidden bg-dark">
    <div class="container py-7 text-center">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <h1 class="display-4 fw-bold mb-4">Welcome to ShopNow</h1>
                <p class="lead mb-4">Discover top-rated products with free shipping on orders over $50</p>

                <!-- Global Search -->
                 <x-search-box />


                <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                    <a href="{{ route('allproducts') }}" class="btn btn-light btn-lg px-4 py-2 rounded-pill shadow">
                        Shop Now <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('allproducts') }}" class="btn btn-outline-light btn-lg px-4 py-2 rounded-pill">
                        Featured Products
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.homepart.categories-carousel')
@include('user.homepart.brands-carousel')
@include('user.homepart.latestProducts')
@include('user.homepart.homepromo')
@include('user.homepart.homeTestimonials')
@include('user.homepart.blog-preview')
@include('user.homepart.feature-section')

<!-- Newsletter -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Subscribe to Our Newsletter</h2>
        <p class="mb-4">Get the latest updates on new products and upcoming sales</p>
        <form id="newsletter-form" class="row g-2 justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="input-group input-group-lg">
                    <input type="email" class="form-control" placeholder="Your email address" required>
                    <button class="btn btn-dark px-4" type="submit">Subscribe</button>
                </div>
            </div>
        </form>
    </div>
</section>
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
                if (res.length > 0) {
                    res.forEach(item => {
                        html += `
                            <a href="${item.url}" class="dropdown-item">
                                <div class="fw-semibold">${item.name}</div>
                                <small class="text-muted">${item.type}</small>
                            </a>
                        `;
                    });
                } else {
                    html = '<div class="dropdown-item text-muted">No results found</div>';
                }
                $('#search-results').html(html).show();
            }
        });
    });

    // Hide when clicked outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#global-search, #search-results').length) {
            $('#search-results').hide();
        }
    });
});
</script>
@endpush

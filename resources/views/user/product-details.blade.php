@extends('user.layouts.master')

@section('title', $product->name . ' - ShopNow')

@section('content')
<!-- Custom Styles -->
<style>
    .product-thumb {
        transition: transform 0.3s ease;
        object-fit: cover;
        width: 100%;
        height: 100%;
    }

    .product-thumb:hover {
        transform: scale(1.1);
    }

    .hover-shadow:hover {
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        transition: 0.2s;
    }

    .ratio {
        display: block;
        position: relative;
        width: 100%;
        padding-top: 75%; /* 4:3 ratio */
    }

    .ratio img {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        object-fit: cover;
    }
</style>

<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Product Details</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/allproducts') }}">Products</a></li>
        <li class="breadcrumb-item active text-white">{{ $product->name }}</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Product Details Start -->
<div class="container
 py-5 mt-5">
    <div class="row g-4 mb-5">
        <div class="col-lg-8 col-xl-9">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="border rounded">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3">{{ $product->name }}</h2>
                    <p class="mb-3">Category: {{ $product->category->name }}</p>
                    <div class="d-flex mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star {{ $i <= $product->average_rating ? 'text-secondary' : 'text-muted' }}"></i>
                        @endfor
                        <span class="ms-2">({{ $product->reviews->count() }} reviews)</span>
                    </div>
                    <h4 class="text-primary fw-bold mb-3">PKR {{ number_format($product->price) }}</h4>
                    @if ($product->compare_price)
                        <h5 class="text-danger text-decoration-line-through mb-3">PKR {{ number_format($product->compare_price) }}</h5>
                    @endif
                    <p class="mb-4">{{ $product->short_description }}</p>
                </div>
            </div>

            <!-- Description and Reviews -->
            <div class="col-lg-12 mt-5">
                <nav>
                    <div class="nav nav-tabs mb-3">
                        <button class="nav-link active border-white border-bottom-0" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#description">Description</button>
                        <button class="nav-link border-white border-bottom-0" type="button" role="tab" data-bs-toggle="tab" data-bs-target="#reviews">Reviews</button>
                    </div>
                </nav>
                <div class="tab-content mb-5">
                    <div class="tab-pane active" id="description">
                        <p>{{ $product->description }}</p>
                    </div>
                    <div class="tab-pane" id="reviews">
                        @forelse ($product->reviews as $review)
                        <div class="d-flex mb-4">
                            <img src="{{ $review->user->avatar ?? asset('img/avatar.jpg') }}" class="img-fluid rounded-circle p-3" style="width: 80px; height: 80px;">
                            <div class="ms-3">
                                <p class="mb-2" style="font-size: 14px;">{{ $review->created_at->format('d M Y') }}</p>
                                <h5>{{ $review->user->name }}</h5>
                                <div class="d-flex mb-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $review->rating ? 'text-secondary' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <p>{{ $review->comment }}</p>
                            </div>
                        </div>
                        @empty
                        <p>No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4 col-xl-3">
            <div class="row g-4">
                <div class="col-12">
                    <div class="mb-4">
                        <h4>Categories</h4>
                        <ul class="list-unstyled fruite-categorie">
                            @foreach($categories as $category)
                                @if($category && $category instanceof \App\Models\Category)
                                <li>
                                    <div class="d-flex justify-content-between fruite-name">
                                        <a href="{{ url('/products?category=' . $category->slug) }}">
                                            <i class="fas fa-apple-alt me-2"></i>{{ $category->name }}
                                        </a>
                                        <span>({{ $category->products_count }})</span>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h4>Featured Products</h4>
                        @foreach($Products as $p)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ asset('storage/' . $p->image) }}" class="img-fluid rounded me-3" style="width: 80px; height: 80px;">
                            <div>
                                <h6>{{ $p->name }}</h6>
                                <p class="mb-1 text-primary">PKR {{ number_format($p->price) }}</p>
                                <a href="{{ url('/product/' . $p->slug) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div>
                        <img src="{{ asset('img/banner-fruits.jpg') }}" class="img-fluid rounded" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    <h3 class="fw-bold mb-4">Related Products</h3>
    <div class="row">
        @foreach($relatedProducts as $rp)
        <div class="col-md-4 col-lg-3 mb-4">
            <a href="{{ url('/product/' . $rp->slug) }}" class="text-decoration-none text-dark">
                <div class="card h-100 hover-shadow">
                    <div class="ratio ratio-4x3 overflow-hidden">
                        <img src="{{ asset('storage/' . $rp->image) }}" class="card-img-top product-thumb" alt="{{ $rp->name }}">
                    </div>
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title">{{ $rp->name }}</h5>
                        <p class="card-text">PKR {{ number_format($rp->price) }}</p>
                        <span class="btn btn-outline-primary btn-sm mt-auto">View Details</span>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<!-- Product Details End -->
@endsection

@extends('user.layouts.master')

@section('title', 'All Products - ShopNow')

@push('styles')
<style>
    .sticky-sidebar {
        position: sticky;
        top: 100px;
    }
    .scrollable-list {
        max-height: 200px;
        overflow-y: auto;
    }
    .product-card {
        transition: 0.3s ease;
        border-radius: 1rem;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .card-img-top {
        height: 160px;
        object-fit: contain;
        background-color: #f8f9fa;
        padding: 0.75rem;
    }

    .card-body {
        padding: 0.75rem 1rem;
    }

    .card-title {
        font-size: 0.95rem;
        margin-bottom: 0.25rem;
    }

    .card-body p {
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .card-body .btn {
        font-size: 0.8rem;
        padding: 6px 14px;
    }

    .badge {
        font-size: 0.75rem;
    }

    @media (max-width: 576px) {
        .card-img-top {
            height: 160px;
        }
    }
</style>
@endpush

@section('content')

<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Products', 'url' => '']
]" />

<div class="container-fluid py-5">
    <div class="container py-5">
        <form method="GET" action="{{ route('allproducts') }}">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3 mb-5 mb-lg-0">
                    <div class="sticky-sidebar">
                        <!-- Categories -->
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Categories</h5>
                                <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div id="categoryCollapse" class="collapse show">
                                <div class="card-body scrollable-list">
                                    @foreach ($categories as $category)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="category[]" value="{{ $category->id }}"
                                                {{ is_array(request('category')) && in_array($category->id, request('category')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Brands -->
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Brands</h5>
                                <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#brandCollapse">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div id="brandCollapse" class="collapse show">
                                <div class="card-body scrollable-list">
                                    @foreach ($brands as $brand)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="brand[]" value="{{ $brand->id }}"
                                                {{ is_array(request('brand')) && in_array($brand->id, request('brand')) ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label">{{ $brand->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Price Range</h5>
                                <div class="mb-2">
                                    <label for="min_price" class="form-label">Min Price</label>
                                    <input type="number" name="min_price" class="form-control" value="{{ request('min_price', 0) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="max_price" class="form-label">Max Price</label>
                                    <input type="number" name="max_price" class="form-control" value="{{ request('max_price', 10000) }}">
                                </div>
                                <button class="btn btn-outline-primary w-100" type="submit">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Listing -->
                <div class="col-lg-9">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                        <h2 class="fw-bold mb-0">All Products</h2>
                        <select class="form-select w-auto text-black" name="sort" onchange="this.form.submit()">
                            <option value="">Sort By</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                        </select>
                    </div>

                    <!-- Product Grid -->
                    <div class="row">
                        @forelse ($products as $product)
                            <div class="col-sm-6 col-md-4 col-lg-4 mb-4">
                                <div class="card product-card h-100 shadow-sm border-0">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/no-image.png') }}"
                                         class="card-img-top"
                                         alt="{{ $product->name }}">

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-semibold mb-1">{{ $product->name }}</h5>
                                        <p class="text-muted small mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>

                                        <p class="text-muted small mb-2">
                                            {{ Str::limit($product->short_description, 80) }}
                                        </p>

                                        <div class="mb-2">
                                            @if ($product->compare_price && $product->compare_price > $product->price)
                                                <span class="text-muted text-decoration-line-through">
                                                    PKR {{ number_format($product->compare_price) }}
                                                </span>
                                            @endif
                                            <span class="fw-bold text-success ms-2">
                                                PKR {{ number_format($product->price) }}
                                            </span>
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                                {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of Stock' }}
                                            </span>
                                        </div>

                                        <a href="{{ url('/product/' . $product->id) }}"
                                           class="btn btn-outline-primary btn-sm w-100 mt-auto rounded-pill">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted">
                                No products found.
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('productSearch');
    const suggestionsList = document.getElementById('suggestionsList');

    searchInput.addEventListener('keyup', function () {
        const query = this.value.trim();

        if (query.length < 2) {
            suggestionsList.innerHTML = '';
            return;
        }

        fetch(`{{ route('search.suggestions') }}?term=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                suggestionsList.innerHTML = '';

                if (data.length > 0) {
                    const regex = new RegExp(`(${query})`, 'gi');

                    data.forEach(product => {
                        const highlightedName = product.name.replace(regex, '<strong>$1</strong>');
                        const highlightedShortDesc = (product.short_description || '').replace(regex, '<strong>$1</strong>');

                        suggestionsList.innerHTML += `
                            <li class="list-group-item list-group-item-action">
                                <a href="/product/${product.id}" class="d-flex align-items-center text-decoration-none text-dark">
                                    <img src="/storage/${product.image}" class="me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        ${highlightedName}<br>
                                        <small class="text-muted">${highlightedShortDesc}</small><br>
                                        <small class="text-muted">PKR ${Number(product.price).toLocaleString()}</small>
                                    </div>
                                </a>
                            </li>
                        `;
                    });
                } else {
                    suggestionsList.innerHTML = `<li class="list-group-item text-muted">No suggestions</li>`;
                }
            });
    });

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !suggestionsList.contains(e.target)) {
            suggestionsList.innerHTML = '';
        }
    });
});
</script>
@endpush

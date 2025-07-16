@extends('user.layouts.master')

@section('title', 'All Products - ShopNow')

@push('styles')
<style>
    .product-card {
        transition: 0.3s ease;
        border-radius: 1rem;
        padding: 0.4rem;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
    }

    .card-img-top {
        height: 100px;
        object-fit: contain;
        background-color: #f8f9fa;
        padding: 0.4rem;
    }

    .card-body {
        padding: 0.6rem 0.75rem;
    }

    .card-title {
        font-size: 0.85rem;
        margin-bottom: 0.25rem;
    }

    .card-body p {
        font-size: 0.72rem;
        margin-bottom: 0.4rem;
        line-height: 1.2;
    }

    .card-body .btn {
        font-size: 0.72rem;
        padding: 4px 10px;
    }

    .badge {
        font-size: 0.7rem;
        padding: 4px 8px;
    }
    .card img {
    background-color: #f9f9f9;
}
.popular-brand-card.active {
    border: 2px solid var(--primary);
    background-color: #eef3ff;
    box-shadow: 0 0 6px rgba(0, 0, 0, 0.05);
}



    @media (max-width: 576px) {
        .card-img-top {
            height: 90px;
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
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-3 mb-5 mb-lg-0">
                <div class="sticky-sidebar">
                    @include('user.includes.sidebar-filters', ['categories' => $categories, 'brands' => $brands, 'brandMessage' => $brandMessage ?? null])
                </div>
            </div>

            <!-- Product Listing -->
            <div class="col-lg-9">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-2">
                    <div class="d-flex justify-content-center gap-3 mb-4">
                        <a href="{{ route('allproducts') }}" class="btn ajax-link {{ request('filter') ? 'btn-outline-secondary' : 'btn-primary' }}">
                            All Products
                        </a>
                        <a href="{{ route('allproducts', ['filter' => 'featured']) }}" class="btn ajax-link {{ request('filter') === 'featured' ? 'btn-primary' : 'btn-outline-secondary' }}">
                            Featured Products
                        </a>
                    </div>
                    <select class="form-select w-auto text-black" name="sort">
                        <option value="">Sort By</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z-A</option>
                    </select>
                </div>

                <!-- Product Grid -->
                <div id="productGrid" class="row">
                    @foreach ($products as $product)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 mb-3 m-lg-2 px-1">
                            @include('user.homepart.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div id="paginationLinks" class="mt-4">
                    {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

@if ($popularBrands->count())
    <div class="container mt-5">
        <h5 class="mb-4 text-center">Popular Brands</h5>
        <div class="row justify-content-center">
            @foreach ($popularBrands as $brand)
                <div class="col-4 col-sm-3 col-md-2 mb-3">
                    <div class="card text-center shadow-sm border-0 p-2 h-100 popular-brand-card"
                        style="cursor: pointer;"
                        data-brand-id="{{ $brand->id }}">
                        <img src="{{ asset('storage/' . $brand->file_path) }}"
                            alt="{{ $brand->name }}"
                            class="img-fluid"
                            style="max-height: 60px; object-fit: contain;">
                        <div class="mt-2 small">{{ $brand->name }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
@endif

@endsection

@push('scripts')
<script>
function loadProducts(url) {
    $.ajax({
        url: url,
        type: 'GET',
        beforeSend: function() {
            $('#productGrid').html('<div class="text-center w-100 py-5"><div class="spinner-border text-primary"></div></div>');
        },
        success: function(response) {
            const html = $(response);
            $('#productGrid').html(html.find('#productGrid').html());
            $('#paginationLinks').html(html.find('#paginationLinks').html());
            $('.ajax-link').removeClass('btn-primary').addClass('btn-outline-secondary');

            if (url.includes('filter=featured')) {
                $('a[href*="filter=featured"]').addClass('btn-primary').removeClass('btn-outline-secondary');
            } else {
                $('a[href="{{ route('allproducts') }}"]').addClass('btn-primary').removeClass('btn-outline-secondary');
            }

            window.history.pushState(null, '', url);
        },
        error: function() {
            alert('Failed to load products.');
        }
    });
}

function buildFilterUrl() {
    const params = new URLSearchParams();

    const selectedCategories = $('.filter-checkbox[name="category[]"]:checked');
    const isCategorySelected = selectedCategories.length > 0;

    selectedCategories.each(function () {
        params.append('category[]', $(this).val());
    });

    $('.filter-checkbox[name="brand[]"]:checked').each(function () {
        params.append('brand[]', $(this).val());
    });

    const minPrice = $('input[name="min_price"]').val();
    const maxPrice = $('input[name="max_price"]').val();
    if (minPrice) params.append('min_price', minPrice);
    if (maxPrice) params.append('max_price', maxPrice);

    const sort = $('select[name="sort"]').val();
    if (sort) params.append('sort', sort);

    const currentParams = new URLSearchParams(window.location.search);
    const isFeatured = currentParams.get('filter') === 'featured';

    if (!isFeatured || (isFeatured && !isCategorySelected)) {
        const filter = currentParams.get('filter');
        if (filter === 'featured') {
            // skip adding
        } else if (filter) {
            params.append('filter', filter);
        }
    }

    return `{{ route('allproducts') }}?${params.toString()}`;
}

function applyFilters() {
    const url = buildFilterUrl();
    const selectedCategories = $('.filter-checkbox[name="category[]"]:checked');
    const isFeaturedActive = window.location.search.includes('filter=featured');

    if (isFeaturedActive && selectedCategories.length > 0) {
        $('.ajax-link').removeClass('btn-primary').addClass('btn-outline-secondary');
        $('a[href="{{ route('allproducts') }}"]').addClass('btn-primary').removeClass('btn-outline-secondary');
    }

    loadProducts(url);
}

$(document).ready(function () {
    $(document).on('click', '.ajax-link', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');

        if (url.includes('filter=featured')) {
            $('.filter-checkbox').prop('checked', false);
            $('input[name="min_price"]').val(0);
            $('input[name="max_price"]').val(10000);
            $('select[name="sort"]').val('');
        }

        loadProducts(url);
    });

    $(document).on('change', '.filter-checkbox', applyFilters);
    $('#applyPriceFilter').on('click', applyFilters);
    $('select[name="sort"]').on('change', applyFilters);

    window.onpopstate = function() {
        loadProducts(location.href);
    };
});

// Handle single-select logic for popular brands
$(document).on('click', '.popular-brand-card', function () {
    const brandId = $(this).data('brand-id');

    // Uncheck all brand checkboxes first
    $('.filter-checkbox[name="brand[]"]').prop('checked', false);

    // Check only the clicked brand
    const brandCheckbox = $(`.filter-checkbox[name="brand[]"][value="${brandId}"]`);
    brandCheckbox.prop('checked', true);

    // Highlight only the selected card
    $('.popular-brand-card').removeClass('active');
    $(this).addClass('active');

    // Trigger AJAX to filter products
    applyFilters();
});
$(document).on('change', '.filter-checkbox[name="brand[]"]', function () {
    $('.popular-brand-card').removeClass('active');
});
$(document).on('change', '.filter-checkbox[name="category[]"]', function () {
    const selectedCategories = $('.filter-checkbox[name="category[]"]:checked')
        .map(function () {
            return $(this).val();
        }).get();

    $.ajax({
        url: '{{ route("allproducts") }}',
        method: 'GET',
        data: {
            category: selectedCategories,
            brand_only: 1
        },
        success: function (html) {
            $('#brandFilters').html(html); // replace brands dynamically
            applyFilters(); // also refresh products grid
        },
        error: function () {
            console.error('Failed to update brand filters.');
        }
    });
});


</script>
@endpush

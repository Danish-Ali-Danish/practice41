@extends('user.layouts.master')

@section('title', 'Wishlist - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Wishlist', 'url' => '']
]" />
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0">My Wishlist</h2>
    <div id="wishlist-count" class="badge bg-primary rounded-pill">0 items</div>
</div>

<div id="wishlist-loading" class="text-center py-5">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <p class="mt-2">Loading your wishlist...</p>
</div>

<div class="row" id="wishlist-container">
    <!-- Wishlist products will load here -->
</div>

<div id="wishlist-empty" class="text-center py-5 d-none">
    <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
    <h4 class="mt-3">Your wishlist is empty</h4>
    <p class="text-muted">Save your favorite items here for later</p>
    <a href="{{ route('products') }}" class="btn btn-primary mt-2">Browse Products</a>
</div>

@endsection



@push('scripts')
<script>
    $(document).ready(function () {
        function loadWishlist() {
            $('#wishlist-loading').removeClass('d-none');
            $('#wishlist-container').addClass('d-none');
            $('#wishlist-empty').addClass('d-none');
            
            $.ajax({
                url: '/api/wishlist',
                method: 'GET',
                success: function (response) {
                    $('#wishlist-loading').addClass('d-none');
                    
                    if (response.products && response.products.length > 0) {
                        renderWishlistProducts(response.products);
                        $('#wishlist-count').text(response.products.length + ' ' + (response.products.length === 1 ? 'item' : 'items'));
                    } else {
                        $('#wishlist-empty').removeClass('d-none');
                        $('#wishlist-count').text('0 items');
                    }
                },
                error: function (xhr) {
                    $('#wishlist-loading').addClass('d-none');
                    $('#wishlist-container').html(`
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2rem;"></i>
                            <h4 class="mt-3">Error loading wishlist</h4>
                            <p class="text-muted">Please try again later</p>
                            <button onclick="loadWishlist()" class="btn btn-outline-primary mt-2">
                                <i class="bi bi-arrow-repeat"></i> Retry
                            </button>
                        </div>
                    `).removeClass('d-none');
                }
            });
        }

        function renderWishlistProducts(products) {
            let html = '';
            
            products.forEach(product => {
                html += `
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4 wishlist-item" data-product-id="${product.id}">
                        <div class="card h-100 shadow-sm position-relative">
                            <div class="remove-wishlist" onclick="removeFromWishlist(${product.id}, this)">
                                <i class="bi bi-x-lg text-danger"></i>
                            </div>
                            <img src="${product.image || '/images/placeholder-product.png'}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: contain; background: #f8f9fa;"
                                 alt="${product.name}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fs-6 text-truncate">${product.name}</h5>
                                <p class="card-text text-muted small">${product.category || 'No category'}</p>
                                <p class="fw-bold mb-2">PKR ${product.price.toLocaleString()}</p>
                                ${product.stock > 0 ? 
                                    `<a href="/product/${product.id}" class="btn btn-primary btn-sm mt-auto">View Product</a>` : 
                                    `<button class="btn btn-outline-secondary btn-sm mt-auto" disabled>Out of Stock</button>`
                                }
                            </div>
                        </div>
                    </div>
                `;
            });
            
            $('#wishlist-container').html(html).removeClass('d-none');
        }

        function removeFromWishlist(productId, element) {
            $(element).html('<i class="bi bi-arrow-clockwise"></i>');
            
            $.ajax({
                url: `/api/wishlist/${productId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // Remove the product card from UI
                    $(element).closest('.wishlist-item').fadeOut(300, function() {
                        $(this).remove();
                        
                        // Update count
                        const remainingItems = $('.wishlist-item').length;
                        $('#wishlist-count').text(remainingItems + ' ' + (remainingItems === 1 ? 'item' : 'items'));
                        
                        // Show empty state if no items left
                        if (remainingItems === 0) {
                            $('#wishlist-container').addClass('d-none');
                            $('#wishlist-empty').removeClass('d-none');
                        }
                    });
                    
                    // Show success message
                    showToast('Removed from wishlist', 'success');
                },
                error: function() {
                    $(element).html('<i class="bi bi-x-lg text-danger"></i>');
                    showToast('Failed to remove item', 'error');
                }
            });
        }

        function showToast(message, type) {
            // Implement your toast notification here or use a library
            alert(message); // Simple fallback
        }

        // Initial load
        loadWishlist();
    });
</script>
@endpush
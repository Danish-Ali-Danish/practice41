
@extends('user.layouts.master')

@section('title', 'Your Cart - ShopNow')

@section('content')
<x-breadcrumbs :items="[
    ['label' => 'Home', 'url' => url('/')],
    ['label' => 'Cart', 'url' => '']
]" />

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold mb-0">Your Shopping Cart</h2>
                <span id="cart-count" class="badge bg-primary rounded-pill fs-6"></span>
            </div>

            <!-- Cart Items Container -->
            <div id="cart-container" class="bg-white rounded-3 shadow-sm p-4">
                <!-- Loading spinner -->
                <div id="cart-loading" class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading your cart...</p>
                </div>
            </div>

            <!-- Empty cart state (hidden by default) -->
            <div id="empty-cart" class="text-center py-5 d-none">
                <div class="empty-cart-icon mb-4">
                    <i class="fas fa-shopping-cart fa-4x text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">Your cart is empty</h4>
                <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet</p>
                <a href="{{ route('welcome') }}" class="btn btn-primary px-4 py-2">
                    <i class="fas fa-store-alt me-2"></i> Continue Shopping
                </a>
            </div>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <!-- Order Summary Card -->
            <div class="card border-0 shadow-sm h-100 sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="fw-bold mb-0 text-black">Order Summary</h5>
                </div>
                <div class="card-body pt-0">
                    <div id="order-summary-loading" class="text-center py-3">
                        <div class="spinner-border text-primary spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div id="order-summary-content" class="d-none">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span id="subtotal" class="fw-medium">PKR 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Shipping</span>
                            <span id="shipping" class="fw-medium">PKR 0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tax</span>
                            <span id="tax" class="fw-medium">PKR 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-bold">Total</span>
                            <span id="total" class="fw-bold fs-5">PKR 0</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkout') }}" id="checkout-btn" class="btn btn-primary py-2">
                                <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                            </a>
                            <a href="{{ route('welcome') }}" class="btn btn-outline-primary py-2">
                                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                    
                    <div id="empty-order-summary" class="text-center py-4 d-none">
                        <p class="text-muted">Your cart is empty</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initial cart load
        loadCart();
        updateOrderSummary();
        
        // Update cart count in navbar
        updateCartCount();
    });

    function loadCart() {
        $('#cart-loading').removeClass('d-none');
        $('#cart-container').find('> *:not(#cart-loading)').remove();
        
        $.ajax({
            url: '/api/cart',
            method: 'GET',
            success: function(response) {
                $('#cart-loading').addClass('d-none');
                
                if (response.items && response.items.length > 0) {
                    renderCartItems(response.items);
                    $('#empty-cart').addClass('d-none');
                } else {
                    $('#cart-container').addClass('d-none');
                    $('#empty-cart').removeClass('d-none');
                }
                
                updateOrderSummary();
                updateCartCount();
            },
            error: function(xhr) {
                $('#cart-loading').addClass('d-none');
                $('#cart-container').html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load cart. Please try again.
                    </div>
                `);
            }
        });
    }

    function renderCartItems(items) {
        let html = `
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="table-light">
                            <th style="width: 40%">Product</th>
                            <th style="width: 15%">Price</th>
                            <th style="width: 20%">Quantity</th>
                            <th style="width: 15%">Subtotal</th>
                            <th style="width: 10%"></th>
                        </tr>
                    </thead>
                    <tbody>
        `;

        items.forEach(item => {
            const subtotal = item.price * item.quantity;
            html += `
                <tr data-id="${item.id}" class="cart-item">
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="${item.image}" class="cart-item-img" alt="${item.name}">
                            <div>
                                <h6 class="mb-1">${item.name}</h6>
                                ${item.color || item.size ? `
                                    <small class="text-muted">
                                        ${item.color ? item.color : ''}
                                        ${item.size ? (item.color ? ' • ' : '') + item.size : ''}
                                    </small>
                                ` : ''}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-medium">PKR ${item.price.toLocaleString()}</div>
                        ${item.original_price > item.price ? `
                            <small class="text-danger text-decoration-line-through">PKR ${item.original_price.toLocaleString()}</small>
                        ` : ''}
                    </td>
                    <td>
                        <div class="input-group quantity-control">
                            <button class="btn btn-outline-secondary updateQty" data-action="decrease" ${item.quantity <= 1 ? 'disabled' : ''}>
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="text" class="form-control text-center quantityInput" 
                                   value="${item.quantity}" min="1" readonly>
                            <button class="btn btn-outline-secondary updateQty" data-action="increase">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="fw-medium">PKR ${subtotal.toLocaleString()}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-danger cart-item-remove removeItem">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        html += `
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('welcome') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                </a>
                <button id="update-cart-btn" class="btn btn-primary" disabled style="display: none;">
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Updating...
                </button>
            </div>
        `;

        $('#cart-container').html(html);
    }

    function updateOrderSummary() {
        $('#order-summary-loading').removeClass('d-none');
        $('#order-summary-content').addClass('d-none');
        $('#empty-order-summary').addClass('d-none');
        
        $.ajax({
            url: '/api/cart/summary',
            method: 'GET',
            success: function(response) {
                $('#order-summary-loading').addClass('d-none');
                
                if (response.items_count > 0) {
                    $('#order-summary-content').removeClass('d-none');
                    $('#subtotal').text('PKR ' + response.subtotal.toLocaleString());
                    $('#shipping').text(response.shipping > 0 ? 'PKR ' + response.shipping.toLocaleString() : 'Free');
                    $('#tax').text('PKR ' + response.tax.toLocaleString());
                    $('#total').text('PKR ' + response.total.toLocaleString());
                    
                    // Disable checkout button if cart is empty
                    $('#checkout-btn').prop('disabled', false);
                } else {
                    $('#empty-order-summary').removeClass('d-none');
                    $('#checkout-btn').prop('disabled', true);
                }
            },
            error: function() {
                $('#order-summary-loading').addClass('d-none');
                $('#order-summary-content').addClass('d-none');
                $('#empty-order-summary').html(`
                    <div class="alert alert-danger py-2">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Failed to load order summary
                    </div>
                `).removeClass('d-none');
            }
        });
    }

    function updateCartCount() {
        $.ajax({
            url: '/api/cart/count',
            method: 'GET',
            success: function(response) {
                $('#cart-count').text(response.count || 0);
            }
        });
    }

    // Update Quantity
    $(document).on('click', '.updateQty', function() {
        const row = $(this).closest('tr');
        const productId = row.data('id');
        const input = row.find('.quantityInput');
        let qty = parseInt(input.val());
        const action = $(this).data('action');
        
        if (action === 'increase') {
            qty++;
        } else if (action === 'decrease' && qty > 1) {
            qty--;
        }
        
        $('#update-cart-btn').removeAttr('disabled').show();
        
        $.ajax({
            url: '/api/cart/update',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: qty
            },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function() {
                loadCart();
                $('#update-cart-btn').attr('disabled', true).hide();
            },
            error: function(xhr) {
                showToast('error', 'Failed to update quantity. ' + (xhr.responseJSON?.message || ''));
                $('#update-cart-btn').attr('disabled', true).hide();
            }
        });
    });

    // Remove Item
    $(document).on('click', '.removeItem', function() {
        const row = $(this).closest('tr');
        const productId = row.data('id');
        const productName = row.find('h6').text();
        
        if (confirm(`Are you sure you want to remove "${productName}" from your cart?`)) {
            $(this).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '/api/cart/remove',
                method: 'POST',
                data: { product_id: productId },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function() {
                    showToast('success', 'Item removed from cart');
                    loadCart();
                },
                error: function(xhr) {
                    showToast('error', 'Failed to remove item. ' + (xhr.responseJSON?.message || ''));
                    $(this).html('<i class="fas fa-trash-alt"></i>').prop('disabled', false);
                }
            });
        }
    });

    // Toast notification function
    function showToast(type, message) {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0 position-fixed bottom-0 end-0 m-3" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        const bsToast = new bootstrap.Toast(toast[0]);
        bsToast.show();
        
        toast.on('hidden.bs.toast', function() {
            $(this).remove();
        });
    }
</script>
@endpush
@extends('user.layouts.master')

@section('title', 'My Orders - ShopNow')

@section('content')
<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">My Orders</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
       
        <li class="breadcrumb-item active text-white">My Orders</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Orders Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Order #</th>
                        <th scope="col">Date</th>
                        <th scope="col">Items</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-container">
                    <!-- Orders will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Orders Page End -->
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $.ajax({
            url: '/api/orders',
            method: 'GET',
            success: function (orders) {
                if (orders.length === 0) {
                    $('#orders-container').html(`
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted">You have no orders yet.</p>
                                <a href="/shop" class="btn btn-primary">Start Shopping</a>
                            </td>
                        </tr>
                    `);
                    return;
                }

                let html = '';
                orders.forEach(order => {
                    let itemsHtml = '';
                    order.items.forEach(item => {
                        itemsHtml += `
                            <div class="d-flex align-items-center mb-2">
                                <img src="${item.image || '/img/placeholder-product.jpg'}" 
                                     class="img-fluid rounded-circle me-3" 
                                     style="width: 50px; height: 50px;" 
                                     alt="${item.name}">
                                <div>
                                    <p class="mb-0">${item.name}</p>
                                    <small class="text-muted">Qty: ${item.quantity} × PKR ${item.price}</small>
                                </div>
                            </div>
                        `;
                    });

                    html += `
                        <tr>
                            <td class="py-4">${order.order_number}</td>
                            <td class="py-4">${order.date}</td>
                            <td class="py-4">
                                <div class="order-items">
                                    ${itemsHtml}
                                </div>
                            </td>
                            <td class="py-4">PKR ${order.total_amount}</td>
                            <td class="py-4">
                                <span class="badge bg-${getStatusColor(order.status)} rounded-pill p-2">
                                    ${order.status}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="d-flex flex-column">
                                    <a href="/orders/${order.id}" class="btn btn-sm btn-outline-primary mb-2">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                    <a href="/orders/${order.id}/invoice" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-file-download me-1"></i> Invoice
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#orders-container').html(html);
            },
            error: function () {
                $('#orders-container').html(`
                    <tr>
                        <td colspan="6" class="text-center py-5 text-danger">
                            Failed to load orders. Please try again later.
                        </td>
                    </tr>
                `);
            }
        });

        function getStatusColor(status) {
            switch (status.toLowerCase()) {
                case 'pending': return 'warning';
                case 'completed': return 'success';
                case 'processing': return 'info';
                case 'shipped': return 'primary';
                case 'cancelled': return 'danger';
                case 'refunded': return 'secondary';
                default: return 'dark';
            }
        }
    });
</script>
@endpush
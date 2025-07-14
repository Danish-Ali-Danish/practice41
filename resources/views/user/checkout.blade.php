@extends('user.layouts.master')

@section('title', 'Checkout - ShopNow')

@section('content')
<!-- Single Page Header Start -->
<div class="container-fluid page-header py-5">
    <h1 class="text-center text-white display-6">Checkout</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Pages</a></li>
        <li class="breadcrumb-item active text-white">Checkout</li>
    </ol>
</div>
<!-- Single Page Header End -->

<!-- Checkout Page Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Billing details</h1>
        <form id="checkoutForm">
            @csrf
            <div class="row g-5">
                <div class="col-md-12 col-lg-6 col-xl-7">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">First Name<sup>*</sup></label>
                                <input type="text" class="form-control" name="first_name" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-6">
                            <div class="form-item w-100">
                                <label class="form-label my-3">Last Name<sup>*</sup></label>
                                <input type="text" class="form-control" name="last_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Company Name</label>
                        <input type="text" class="form-control" name="company">
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Address <sup>*</sup></label>
                        <input type="text" class="form-control" name="address" placeholder="House Number Street Name" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Town/City<sup>*</sup></label>
                        <input type="text" class="form-control" name="city" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Country<sup>*</sup></label>
                        <input type="text" class="form-control" name="country" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Postcode/Zip<sup>*</sup></label>
                        <input type="text" class="form-control" name="postcode" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Mobile<sup>*</sup></label>
                        <input type="tel" class="form-control" name="phone" required>
                    </div>
                    <div class="form-item">
                        <label class="form-label my-3">Email Address<sup>*</sup></label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-check my-3">
                        <input type="checkbox" class="form-check-input" id="Account-1" name="create_account" value="1">
                        <label class="form-check-label" for="Account-1">Create an account?</label>
                    </div>
                    <hr>
                    <div class="form-check my-3">
                        <input class="form-check-input" type="checkbox" id="Address-1" name="different_shipping" value="1">
                        <label class="form-check-label" for="Address-1">Ship to a different address?</label>
                    </div>
                    <div class="form-item">
                        <textarea name="notes" class="form-control" spellcheck="false" cols="30" rows="11" placeholder="Order Notes (Optional)"></textarea>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xl-5">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Products</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody id="order-summary">
                                <!-- Items will be injected via AJAX -->
                            </tbody>
                        </table>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                        <div class="col-12">
                            <div class="form-check text-start my-3">
                                <input type="checkbox" class="form-check-input bg-primary border-0" id="Transfer-1" name="payment_method" value="bank_transfer">
                                <label class="form-check-label" for="Transfer-1">Direct Bank Transfer</label>
                            </div>
                            <p class="text-start text-dark">Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.</p>
                        </div>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                        <div class="col-12">
                            <div class="form-check text-start my-3">
                                <input type="checkbox" class="form-check-input bg-primary border-0" id="Payments-1" name="payment_method" value="check">
                                <label class="form-check-label" for="Payments-1">Check Payments</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                        <div class="col-12">
                            <div class="form-check text-start my-3">
                                <input type="checkbox" class="form-check-input bg-primary border-0" id="Delivery-1" name="payment_method" value="cod">
                                <label class="form-check-label" for="Delivery-1">Cash On Delivery</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center border-bottom py-3">
                        <div class="col-12">
                            <div class="form-check text-start my-3">
                                <input type="checkbox" class="form-check-input bg-primary border-0" id="Paypal-1" name="payment_method" value="paypal">
                                <label class="form-check-label" for="Paypal-1">Paypal</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 text-center align-items-center justify-content-center pt-4">
                        <button type="submit" class="btn border-secondary py-3 px-4 text-uppercase w-100 text-primary">Place Order</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- Checkout Page End -->
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        // Load cart summary
        $.ajax({
            url: '/api/cart',
            method: 'GET',
            success: function (items) {
                let total = 0;
                let summaryHtml = '';

                if (items.length === 0) {
                    summaryHtml = '<tr><td colspan="5" class="text-center">Cart is empty.</td></tr>';
                    $('#checkoutForm').hide();
                } else {
                    items.forEach(item => {
                        const subtotal = item.price * item.quantity;
                        total += subtotal;

                        summaryHtml += `
                            <tr>
                                <th scope="row">
                                    <div class="d-flex align-items-center mt-2">
                                        <img src="${item.image || '/img/placeholder-product.jpg'}" class="img-fluid rounded-circle" style="width: 90px; height: 90px;" alt="${item.name}">
                                    </div>
                                </th>
                                <td class="py-5">${item.name}</td>
                                <td class="py-5">PKR ${item.price}</td>
                                <td class="py-5">${item.quantity}</td>
                                <td class="py-5">PKR ${subtotal}</td>
                            </tr>
                        `;
                    });

                    // Add subtotal row
                    summaryHtml += `
                        <tr>
                            <th scope="row"></th>
                            <td class="py-5"></td>
                            <td class="py-5"></td>
                            <td class="py-5">
                                <p class="mb-0 text-dark py-3">Subtotal</p>
                            </td>
                            <td class="py-5">
                                <div class="py-3 border-bottom border-top">
                                    <p class="mb-0 text-dark">PKR ${total}</p>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"></th>
                            <td class="py-5">
                                <p class="mb-0 text-dark text-uppercase py-3">TOTAL</p>
                            </td>
                            <td class="py-5"></td>
                            <td class="py-5"></td>
                            <td class="py-5">
                                <div class="py-3 border-bottom border-top">
                                    <p class="mb-0 text-dark">PKR ${total}</p>
                                </div>
                            </td>
                        </tr>
                    `;
                }

                $('#order-summary').html(summaryHtml);
            },
            error: function () {
                $('#order-summary').html('<tr><td colspan="5" class="text-center text-danger">Error loading cart.</td></tr>');
            }
        });

        // Place order
        $('#checkoutForm').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/api/orders/place',
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    showToast('Order placed successfully!');
                    window.location.href = '/orders';
                },
                error: function () {
                    showToast('Failed to place order.');
                }
            });
        });
    });
</script>
@endpush
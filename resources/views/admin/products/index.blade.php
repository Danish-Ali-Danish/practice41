@extends('admin.layout.app')

@section('content')

<div class="container dashboard-card">
    <h2>Product List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addProductBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Products
        </button>
    </div>
    <div class="table-responsive">
        <button id="bulkDeleteBtn" class="btn btn-danger mb-2 d-none">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>

        <table id="productTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllProducts"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('admin.products.edit')
@include('admin.products.delete')

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="Product Image">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

    const productModal = new bootstrap.Modal($('#productModal')[0]);
    const productForm = $('#productForm');
    const productIdInput = $('#productId');

    const productTable = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("products.index") }}',
        columns: [
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: id => `<input type="checkbox" class="product-checkbox" value="${id}">`
            },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'category.name', name: 'category.name' },
            { data: 'brand.name', name: 'brand.name' },
            { data: 'price', name: 'price' },
            {
                data: 'image',
                name: 'image',
                orderable: false,
                searchable: false,
                render: image => image ? `<img src="/storage/${image}" class="img-thumbnail file-preview" width="50" height="50" style="object-fit:cover;cursor:pointer" data-src="/storage/${image}">` : 'No Image'
            },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[1, 'desc']]
    });

    $(document).on('change', '#selectAllProducts', function () {
        $('.product-checkbox').prop('checked', this.checked);
        toggleBulkDeleteButton();
    });

    $(document).on('change', '.product-checkbox', toggleBulkDeleteButton);

    function toggleBulkDeleteButton() {
        const selectedCount = $('.product-checkbox:checked').length;
        $('#bulkDeleteBtn').toggleClass('d-none', selectedCount === 0);
    }

    $('#bulkDeleteBtn').on('click', function () {
        const ids = $('.product-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will delete selected products permanently.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete them!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("products.bulkDelete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids
                    },
                    success: () => {
                        showAlert('Selected products deleted successfully!');
                        productTable.ajax.reload();
                        $('#selectAllProducts').prop('checked', false);
                        $('#bulkDeleteBtn').addClass('d-none');
                    },
                    error: () => showAlert('Failed to delete selected products.', 'error')
                });
            }
        });
    });

    $('#addProductBtn').on('click', function () {
        productForm[0].reset();
        productIdInput.val('');
        $('#productModalLabel').text('Add New Product');
        productModal.show();
    });

    $('#saveProductBtn').on('click', function (e) {
        e.preventDefault();
        const id = $('#productId').val();
        const formData = new FormData(productForm[0]);
        const url = id ? `/products/${id}` : `{{ route('products.store') }}`;

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                showAlert(`Product ${id ? 'updated' : 'added'} successfully!`);
                productModal.hide();
                productForm[0].reset();
                productIdInput.val('');
                $('#productModalLabel').text('Add New Product');
                productTable.ajax.reload();
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errorHtml = '';
                    $.each(xhr.responseJSON.errors, function (key, messages) {
                        errorHtml += `<div>${messages.join('<br>')}</div>`;
                    });
                    showAlert(errorHtml, 'error');
                } else {
                    showAlert('An unexpected error occurred.', 'error');
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get(`/products/${id}`, function (product) {
            $('#productId').val(product.id);
            $('#productName').val(product.name);
            $('#productCategory').val(product.category_id);
            $('#productBrand').val(product.brand_id);
            $('#productPrice').val(product.price);
            $('#productComparePrice').val(product.compare_price);
            $('#productShortDescription').val(product.short_description);
            $('#productDescription').val(product.description);

            $('#productModalLabel').text('Edit Product');
            productModal.show();
        }).fail(() => showAlert('Failed to load product data.', 'error'));
    });

    $(document).on('click', '.delete-btn', function () {
        const productId = $(this).data('id');
        const productName = $(this).data('name');

        Swal.fire({
            title: `Delete "${productName}"?`,
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/products/${productId}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        showAlert('Product deleted successfully!', 'success');
                        productTable.ajax.reload();
                    },
                    error: function () {
                        showAlert('Failed to delete product.', 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.file-preview', function () {
        $('#previewImage').attr('src', $(this).data('src'));
        new bootstrap.Modal($('#filePreviewModal')).show();
    });

    function showAlert(message, type = 'success') {
        Swal.fire({
            icon: type,
            title: type.charAt(0).toUpperCase() + type.slice(1),
            html: message,
            timer: 4000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
        });
    }
});
</script>
@endsection
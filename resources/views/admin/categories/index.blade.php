@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Categories List</h2>

    <div id="alertContainer"></div>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addCategoryBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Category
        </button>
    </div>

    <div class="table-responsive">
        <table id="categoryTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>File</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('admin.categories.edit')

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="File Preview">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    const categoryModal = new bootstrap.Modal($('#categoryModal')[0]);
    const categoryForm = $('#categoryForm');
    const categoryIdInput = $('#categoryId');
    const categoryNameInput = $('#categoryName');
    const categoryModalLabel = $('#categoryModalLabel');
    const addCategoryBtn = $('#addCategoryBtn');
    const saveCategoryBtn = $('#saveCategoryBtn');

    const categoryTable = $('#categoryTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("categories.index") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'file', name: 'file', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
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

    function clearForm() {
        categoryIdInput.val('');
        categoryForm[0].reset();
        categoryModalLabel.text('Add New Category');
    }

    saveCategoryBtn.on('click', function () {
        const id = categoryIdInput.val();
        const name = categoryNameInput.val().trim();
        const file = $('#categoryFile')[0].files[0];

        if (!name) {
            showAlert('Category name is required.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        if (file) formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        if (id) formData.append('_method', 'PUT');

        const url = id ? `/categories/${id}` : '{{ route("categories.store") }}';

        $.ajax({
            url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                showAlert('Category ' + (id ? 'updated' : 'added') + ' successfully!', 'success');
                categoryModal.hide();
                clearForm();
                categoryTable.ajax.reload();
            },
            error: function (xhr) {
                const errorMessage = xhr.responseJSON?.errors?.name?.[0] || xhr.responseJSON?.message || xhr.statusText;
                showAlert('Error saving category: ' + errorMessage, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.ajax({
            url: `/categories/${id}`,
            method: 'GET',
            success: function (category) {
                categoryIdInput.val(category.id);
                categoryNameInput.val(category.name);
                categoryModalLabel.text('Edit Category');
                categoryModal.show();
            },
            error: function () {
                showAlert('Error fetching category for edit.', 'error');
            }
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: `Delete "${name}"?`,
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/categories/${id}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        showAlert('Category deleted successfully!', 'success');
                        categoryTable.ajax.reload();
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Failed to delete category.';
                        showAlert(msg, 'error');
                    }
                });
            }
        });
    });

    $(document).on('click', '.file-preview', function () {
        const src = $(this).data('src');
        $('#previewImage').attr('src', src);
        new bootstrap.Modal($('#filePreviewModal')).show();
    });

    addCategoryBtn.on('click', function () {
        clearForm();
        categoryModalLabel.text('Add New Category');
        categoryModal.show();
    });
});
</script>
@endsection

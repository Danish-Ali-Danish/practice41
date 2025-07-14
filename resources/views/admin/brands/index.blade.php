@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Brands List</h2>

    <div id="alertContainer"></div>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addBrandBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Brand
        </button>
    </div>

    <div class="table-responsive">
        <table id="brandTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('admin.brands.edit')

<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid" alt="Image Preview">
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
    const brandModal = new bootstrap.Modal($('#brandModal')[0]);
    const brandForm = $('#brandForm');
    const brandIdInput = $('#brandId');
    const brandNameInput = $('#brandName');
    const brandCategoryInput = $('#brandCategory');
    const saveBrandBtn = $('#saveBrandBtn');
    const addBrandBtn = $('#addBrandBtn');

    const brandTable = $('#brandTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: '{{ route("brands.index") }}',
        columns: [
    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
    { data: 'name', name: 'name' },
    { data: 'category.name', name: 'category.name' },
    { 
        data: 'file_path', 
        name: 'file_path', 
        orderable: false, 
        searchable: false 
        // ✅ Don't add any render() function here!
    },
    { 
        data: 'action', 
        name: 'action', 
        orderable: false, 
        searchable: false, 
        className: 'text-center' 
    }
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
        brandIdInput.val('');
        brandForm[0].reset();
        $('#brandModalLabel').text('Add New Brand');
    }

    saveBrandBtn.on('click', function (e) {
        e.preventDefault();
        const id = brandIdInput.val();
        const name = brandNameInput.val().trim();
        const category_id = brandCategoryInput.val();
        const file = $('#brandFile')[0]?.files[0];

        if (!name || !category_id) {
            showAlert('All fields are required.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('category_id', category_id);
        if (file) formData.append('file', file);
        formData.append('_token', '{{ csrf_token() }}');
        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: id ? `/brands/${id}` : '{{ route("brands.store") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: () => {
                showAlert(`Brand ${id ? 'updated' : 'added'} successfully!`);
                brandModal.hide();
                clearForm();
                brandTable.ajax.reload();
            },
            error: xhr => {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '';
                    $.each(errors, (key, messages) => {
                        errorHtml += `<div>${messages.join('<br>')}</div>`;
                    });
                    showAlert(errorHtml, 'error');
                } else {
                    showAlert('Failed to save brand.', 'error');
                }
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get(`/brands/${id}`, brand => {
            brandIdInput.val(brand.id);
            brandNameInput.val(brand.name);
            brandCategoryInput.val(brand.category_id);
            $('#brandModalLabel').text('Edit Brand');
            brandModal.show();
        }).fail(() => showAlert('Failed to fetch brand.', 'error'));
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/brands/${id}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: () => {
                        showAlert('Brand deleted successfully!');
                        brandTable.ajax.reload();
                    },
                    error: () => showAlert('Failed to delete brand.', 'error')
                });
            }
        });
    });

    addBrandBtn.on('click', function () {
        clearForm();
        brandModal.show();
    });

    $(document).on('click', '.file-preview', function () {
        $('#previewImage').attr('src', $(this).data('src'));
        new bootstrap.Modal($('#filePreviewModal')).show();
    });
});
</script>
@endsection

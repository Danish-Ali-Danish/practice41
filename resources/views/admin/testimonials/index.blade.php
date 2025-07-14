@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Testimonials List</h2>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addTestimonialBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Testimonial
        </button>
    </div>

    <div class="table-responsive">
        <button id="bulkDeleteBtn" class="btn btn-danger mb-2 d-none">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>
        <table id="testimonialTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllTestimonials"></th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Message</th>
                    <th>Image</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('admin.testimonials.modal')

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    const testimonialModal = new bootstrap.Modal($('#testimonialModal')[0]);
    const testimonialForm = $('#testimonialForm');
    const testimonialIdInput = $('#testimonialId');

    const table = $('#testimonialTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("testimonials.index") }}',
        columns: [
            { data: 'id', render: id => `<input type="checkbox" class="testimonial-checkbox" value="${id}">`, orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'name', name: 'name' },
            { data: 'designation', name: 'designation' },
            { data: 'message', name: 'message' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ]
    });

    function toggleBulkDeleteButton() {
        $('#bulkDeleteBtn').toggleClass('d-none', $('.testimonial-checkbox:checked').length === 0);
    }

    $('#selectAllTestimonials').on('change', function () {
        $('.testimonial-checkbox').prop('checked', this.checked);
        toggleBulkDeleteButton();
    });

    $(document).on('change', '.testimonial-checkbox', toggleBulkDeleteButton);

    $('#addTestimonialBtn').on('click', function () {
        testimonialForm[0].reset();
        testimonialIdInput.val('');
        $('#imagePreview').addClass('d-none').attr('src', '');
        $('#testimonialModalLabel').text('Add Testimonial');
        testimonialModal.show();
    });

    $('#testimonialImage').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    $('#saveTestimonialBtn').on('click', function (e) {
        e.preventDefault();
        const id = testimonialIdInput.val();
        const formData = new FormData(testimonialForm[0]);
        const url = id ? `/admin/testimonials/${id}` : `{{ route('testimonials.store') }}`;

        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                Swal.fire('Success!', 'Testimonial saved!', 'success');
                testimonialModal.hide();
                testimonialForm[0].reset();
                table.ajax.reload();
            },
            error: function (xhr) {
                let errorHtml = '';
                $.each(xhr.responseJSON.errors, (key, val) => errorHtml += `<div>${val}</div>`);
                Swal.fire('Validation Error', errorHtml, 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        $.get(`/admin/testimonials/${id}`, function (t) {
            $('#testimonialId').val(t.id);
            $('#testimonialName').val(t.name);
            $('#testimonialDesignation').val(t.designation);
            $('#testimonialMessage').val(t.message);
            if (t.image) {
                $('#imagePreview').attr('src', `/storage/${t.image}`).removeClass('d-none');
            } else {
                $('#imagePreview').addClass('d-none').attr('src', '');
            }
            $('#testimonialModalLabel').text('Edit Testimonial');
            testimonialModal.show();
        });
    });

    $(document).on('click', '.testimonial-preview', function () {
        const src = $(this).data('src');
        $('#previewImage').attr('src', src);
        new bootstrap.Modal($('#imagePreviewModal')).show();
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');

        Swal.fire({
            title: `Delete "${name}"?`,
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/testimonials/${id}`,
                    method: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function () {
                        Swal.fire('Deleted!', 'Testimonial deleted.', 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });

    $('#bulkDeleteBtn').on('click', function () {
        const selectedIds = $('.testimonial-checkbox:checked').map(function () {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) return;

        Swal.fire({
            title: `Delete ${selectedIds.length} testimonials?`,
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete all!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("testimonials.bulkDelete") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    },
                    success: function () {
                        Swal.fire('Deleted!', 'Selected testimonials deleted.', 'success');
                        table.ajax.reload();
                        $('#selectAllTestimonials').prop('checked', false);
                        toggleBulkDeleteButton();
                    }
                });
            }
        });
    });
});
</script>
@endsection
@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Features List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addFeatureBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Feature
        </button>
    </div>

    <div class="table-responsive">
        <button id="bulkDeleteBtn" class="btn btn-danger mb-2 d-none">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>

        <table id="featureTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllFeatures"></th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Icon</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

@include('admin.features.edit')
@include('admin.features.delete')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const featureModal = new bootstrap.Modal($('#featureModal')[0]);
    const featureForm = $('#featureForm');
    const featureIdInput = $('#featureId');

    const featureTable = $('#featureTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("features.index") }}',
        columns: [
            {
                data: 'id',
                render: id => `<input type="checkbox" class="feature-checkbox" value="${id}">`,
                orderable: false, searchable: false
            },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'icon', name: 'icon' },
            { data: 'description', name: 'description' },
            {
                data: 'action', name: 'action', className: 'text-center',
                orderable: false, searchable: false
            },
        ],
        order: [[1, 'desc']]
    });

    $('#selectAllFeatures').on('change', function () {
        $('.feature-checkbox').prop('checked', this.checked);
        toggleBulkBtn();
    });

    $(document).on('change', '.feature-checkbox', toggleBulkBtn);

    function toggleBulkBtn() {
        $('#bulkDeleteBtn').toggleClass('d-none', $('.feature-checkbox:checked').length === 0);
    }

    $('#bulkDeleteBtn').on('click', function () {
        const ids = $('.feature-checkbox:checked').map(function () { return this.value; }).get();

        if (ids.length) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete selected features permanently.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete them!'
            }).then(result => {
                if (result.isConfirmed) {
                    $.post('{{ route("features.bulkDelete") }}', { ids }, function () {
                        showAlert('Selected features deleted successfully!');
                        featureTable.ajax.reload();
                        $('#selectAllFeatures').prop('checked', false);
                        toggleBulkBtn();
                    }).fail(() => showAlert('Failed to delete selected features.', 'error'));
                }
            });
        }
    });

    $('#addFeatureBtn').on('click', function () {
        featureForm[0].reset();
        featureIdInput.val('');
        $('#featureModalLabel').text('Add Feature');
        featureModal.show();
    });

    $('#saveFeatureBtn').on('click', function (e) {
        e.preventDefault();
        const id = featureIdInput.val();
        const url = id ? `/admin/features/${id}` : '{{ route("features.store") }}';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url,
            method: 'POST',
            data: featureForm.serialize() + (id ? '&_method=PUT' : ''),
            success: () => {
                featureModal.hide();
                featureTable.ajax.reload();
                featureForm[0].reset();
                featureIdInput.val('');
                showAlert(`Feature ${id ? 'updated' : 'added'} successfully!`);
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
        $.get(`/admin/features/${id}`, function (feature) {
            featureIdInput.val(feature.id);
            $('#featureTitle').val(feature.title);
            $('#featureIcon').val(feature.icon);
            $('#featureDescription').val(feature.description);
            $('#featureModalLabel').text('Edit Feature');
            featureModal.show();
        }).fail(() => showAlert('Failed to load feature data.', 'error'));
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Delete this feature?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/features/${id}`,
                    method: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function () {
                        featureTable.ajax.reload();
                        showAlert('Feature deleted successfully!', 'success');
                    },
                    error: function () {
                        showAlert('Failed to delete feature.', 'error');
                    }
                });
            }
        });
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
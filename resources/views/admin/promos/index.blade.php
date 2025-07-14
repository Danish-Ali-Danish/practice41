@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Promo List</h2>

    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary me-2" id="addPromoBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Promo
        </button>
        <button id="bulkDeleteBtn" class="btn btn-danger d-none">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>
    </div>

    <div class="table-responsive">
        <table id="promoTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllPromos"></th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Button Text</th>
                    <th>Button Link</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Promo Modal -->
<div class="modal fade" id="promoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="promoForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="promoModalLabel">Add Promo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="promoId">
                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control">
                        <img id="previewImage" src="" alt="" class="mt-2" style="width: 100px; display:none;">
                    </div>
                    <div class="mb-3">
                        <label>Button Text</label>
                        <input type="text" name="button_text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Button Link</label>
                        <input type="url" name="button_link" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    // CSRF
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let table = $('#promoTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('promos.index') }}",
        columns: [
            { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'button_text', name: 'button_text' },
            { data: 'button_link', name: 'button_link' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#addPromoBtn').click(function () {
        $('#promoForm')[0].reset();
        $('#promoId').val('');
        $('#promoModalLabel').text('Add Promo');
        $('#previewImage').hide();
        $('#promoModal').modal('show');
    });

    $(document).on('submit', '#promoForm', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        let id = $('#promoId').val();
        let url = id ? "{{ url('admin/promos') }}/" + id : "{{ route('promos.store') }}";
        let type = id ? 'POST' : 'POST';

        if (id) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: type,
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                $('#promoModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Success', res.message, 'success');
            },
            error: function (err) {
                Swal.fire('Error', 'Please check your input', 'error');
            }
        });
    });

    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('id');
        $.get("{{ url('admin/promos') }}/" + id, function (promo) {
            $('#promoModal').modal('show');
            $('#promoId').val(promo.id);
            $('input[name="title"]').val(promo.title);
            $('input[name="button_text"]').val(promo.button_text);
            $('input[name="button_link"]').val(promo.button_link);
            if (promo.image) {
                $('#previewImage').attr('src', '/public/' + promo.image).show();
            } else {
                $('#previewImage').hide();
            }
            $('#promoModalLabel').text('Edit Promo');
        });
    });

    $(document).on('click', '.delete-btn', function () {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the promo!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/promos') }}/" + id,
                    type: 'DELETE',
                    success: function (res) {
                        table.ajax.reload();
                        Swal.fire('Deleted!', res.message, 'success');
                    }
                });
            }
        });
    });

    $('#selectAllPromos').click(function () {
        $('.selectPromo').prop('checked', $(this).prop('checked'));
        toggleBulkDelete();
    });

    $(document).on('change', '.selectPromo', toggleBulkDelete);

    function toggleBulkDelete() {
        let checked = $('.selectPromo:checked').length;
        $('#bulkDeleteBtn').toggleClass('d-none', checked === 0);
    }

    $('#bulkDeleteBtn').click(function () {
        let ids = $('.selectPromo:checked').map(function () {
            return $(this).val();
        }).get();

        if (ids.length === 0) return;

        Swal.fire({
            title: 'Delete Selected?',
            text: 'Are you sure you want to delete selected promos?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete all!'
        }).then(result => {
            if (result.isConfirmed) {
                $.post("{{ route('promos.bulkDelete') }}", { ids: ids }, function (res) {
                    table.ajax.reload();
                    Swal.fire('Deleted!', res.message, 'success');
                });
            }
        });
    });
});
</script>
@endsection

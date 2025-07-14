@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Blog Posts List</h2>
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" id="addBlogBtn">
            <i class="fas fa-plus-circle me-1"></i> Add Blog Post
        </button>
    </div>
    <div class="table-responsive">
        <button id="bulkDeleteBtn" class="btn btn-danger mb-2 d-none">
            <i class="fas fa-trash-alt me-1"></i> Delete Selected
        </button>
        <table id="blogTable" class="table table-striped table-hover w-100">
            <thead class="table-dark">
                <tr>
                    <th><input type="checkbox" id="selectAllBlogs"></th>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@include('admin.blog-posts.modal')
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    const blogModal = new bootstrap.Modal($('#blogModal')[0]);
    const blogForm = $('#blogForm');
    const blogIdInput = $('#blogId');

    const table = $('#blogTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("blog-posts.index") }}',
        columns: [
            { data: 'id', orderable: false, searchable: false, render: id => `<input type="checkbox" class="blog-checkbox" value="${id}">` },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'title', name: 'title' },
            { data: 'author', name: 'author' },
            { data: 'date', name: 'date' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[1, 'desc']]
    });

    $(document).on('change', '#selectAllBlogs', function () {
        $('.blog-checkbox').prop('checked', this.checked);
        toggleBulkDeleteButton();
    });

    $(document).on('change', '.blog-checkbox', toggleBulkDeleteButton);

    function toggleBulkDeleteButton() {
        $('#bulkDeleteBtn').toggleClass('d-none', $('.blog-checkbox:checked').length === 0);
    }

    $('#addBlogBtn').on('click', function () {
        blogForm[0].reset();
        blogIdInput.val('');
        $('#blogModalLabel').text('Add Blog Post');
        blogModal.show();
    });

    $('#saveBlogBtn').on('click', function (e) {
        e.preventDefault();
        const id = blogIdInput.val();
        const formData = new FormData(blogForm[0]);
        const url = id ? `/admin/blog-posts/${id}` : `{{ route('blog-posts.store') }}`;

        if (id) formData.append('_method', 'PUT');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function () {
                Swal.fire('Success!', 'Blog post saved!', 'success');
                blogModal.hide();
                blogForm[0].reset();
                blogIdInput.val('');
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
        $.get(`/admin/blog-posts/${id}`, function (post) {
            $('#blogId').val(post.id);
            $('#blogTitle').val(post.title);
            $('#blogAuthor').val(post.author);
            $('#blogContent').val(post.content);
            $('#blogStatus').val(post.status);
            $('#blogModalLabel').text('Edit Blog Post');
            blogModal.show();
        });
    });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');
        const title = $(this).data('title');

        Swal.fire({
            title: `Delete "${title}"?`,
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/admin/blog-posts/${id}`,
                    method: 'POST',
                    data: { _method: 'DELETE', _token: '{{ csrf_token() }}' },
                    success: function () {
                        Swal.fire('Deleted!', 'Blog post deleted.', 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection

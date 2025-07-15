@extends('admin.layout.app')

@section('content')
<div class="container dashboard-card">
    <h2>Featured Products</h2>

    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '—' }}</td>
                    <td>{{ $product->brand->name ?? '—' }}</td>
                    <td>{{ $product->price }}</td>
                    <td>
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" style="object-fit:cover;">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm unfeature-btn" data-id="{{ $product->id }}">
                            <i class="fas fa-times-circle"></i> Remove
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">No featured products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on('click', '.unfeature-btn', function () {
    const productId = $(this).data('id');

    Swal.fire({
        title: 'Remove from featured?',
        text: "This product will move back to Products list.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('{{ route("products.unfeature") }}', {
                id: productId,
                _token: '{{ csrf_token() }}'
            }, function (res) {
                Swal.fire('Done!', res.message, 'success').then(() => {
                    location.reload();
                });
            }).fail(() => {
                Swal.fire('Oops!', 'Failed to remove product.', 'error');
            });
        }
    });
});
</script>
@endsection

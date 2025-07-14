@extends('user.layouts.master')

@section('title', 'All Categories - ShopNow')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center mb-4">All Categories</h2>
    <div class="row" id="all-categories-list">
        @forelse ($categories as $category)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 text-center shadow-sm">
                    @if ($category->file_path)
                        <img src="{{ asset('storage/' . $category->file_path) }}" 
                             class="card-img-top"
                             style="height: 150px; object-fit: cover; cursor: pointer;"
                             alt="{{ $category->name }}"
                             onclick="previewCategoryImage('{{ asset('storage/' . $category->file_path) }}')">
                    @else
                        <img src="{{ asset('images/default-category.png') }}" 
                             class="card-img-top" 
                             alt="Default Category">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <a href="{{ route('allproducts', $category->id) }}" class="btn btn-outline-primary btn-sm">Explore</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">No categories found.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Category Preview Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-white rounded border-0 shadow">
      <div class="modal-body text-center p-4">
        <img id="previewImage" src="" class="img-fluid rounded shadow-sm" alt="Category Preview">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    function previewCategoryImage(src) {
        $('#previewImage').attr('src', src);
        const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        modal.show();
    }
</script>
@endpush

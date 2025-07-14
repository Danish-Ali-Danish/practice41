@extends('user.layouts.master')

@section('title', 'All Brands - ShopNow')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center mb-4">All Brands</h2>
    <div class="row" id="all-brands-list">
        @forelse ($brands as $brand)
            <div class="col-6 col-md-4 col-lg-3 mb-4">
                <div class="card h-100 text-center shadow-sm">
                    @if ($brand->file_path)
                        <img src="{{ asset('storage/' . $brand->file_path) }}" 
                             class="card-img-top"
                             style="height: 150px; object-fit: cover; cursor: pointer;"
                             alt="{{ $brand->name }}"
                             onclick="previewBrandImage('{{ asset('storage/' . $brand->file_path) }}')">
                    @else
                        <img src="{{ asset('images/default-brand.png') }}" 
                             class="card-img-top" 
                             alt="Default Brand">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $brand->name }}</h5>
                        <a href="{{ route('allproducts', $brand->id) }}" class="btn btn-outline-primary btn-sm">Explore</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">No brands found.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Brand Preview Modal -->
<div class="modal fade" id="BrandModal" tabindex="-1" aria-labelledby="BrandModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-white rounded border-0 shadow">
      <div class="modal-body text-center p-4">
        <img id="previewBrandImage" src="" class="img-fluid rounded shadow-sm" alt="Brand Preview">
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    function previewBrandImage(src) {
        $('#previewBrandImage').attr('src', src);
        const modal = new bootstrap.Modal(document.getElementById('BrandModal'));
        modal.show();
    }
</script>
@endpush

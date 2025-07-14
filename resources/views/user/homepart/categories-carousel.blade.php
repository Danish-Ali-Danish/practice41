
<!-- Categories Carousel -->
<section class="my-5">
    <div class="container">
        <!-- Heading and View All Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Category</h2>
            <a href="{{ route('allcate') }}" class="btn btn-outline-primary btn-sm">View All Categories</a>
        </div>

        <!-- Carousel Scroll Row -->
        <div class="overflow-auto d-flex flex-nowrap px-2" id="categoryCarousel" style="scroll-behavior: smooth;">
            @foreach ($categories as $category)
                <div class="card me-3 text-center shadow-sm flex-shrink-0" style="width: 160px;">
                    <img src="{{ $category->file_path ? asset('storage/' . $category->file_path) : asset('images/default-category.png') }}"
                         class="card-img-top img-fluid rounded" style="height: 100px; object-fit: cover;" alt="{{ $category->name }}" loading="lazy">
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1">{{ $category->name }}</h6>
                        <a href="{{ route('allproducts') }}" class="btn btn-outline-primary btn-sm w-100">Explore</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Arrows in New Row (Always Visible) -->
        <div class="d-flex justify-content-center mt-3 gap-3">
            <button class="btn btn-outline-primary" id="categoryPrev">
                <i class="fas fa-chevron-left me-1"></i> Previous
            </button>
            <button class="btn btn-outline-primary" id="categoryNext">
                Next <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </div>
    </div>
</section>
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

<!-- Swiper Categories Carousel -->
<section class="my-5">
    <div class="container">
        <!-- Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0">Shop by Category</h2>
            <a href="{{ route('allcate') }}" class="btn btn-outline-primary btn-sm">View All Categories</a>
        </div>

        <!-- Swiper Container -->
        <div class="swiper category-swiper">
            <div class="swiper-wrapper">
                @foreach ($categories as $category)
                    <div class="swiper-slide">
                        <div class="card text-center shadow-sm border-0 mx-2" style="width: 160px;">
                            <img src="{{ $category->file_path ? asset('storage/' . $category->file_path) : asset('images/default-category.png') }}"
                                 class="card-img-top img-fluid previewable-image rounded"
                                 style="height: 100px; object-fit: cover;"
                                 alt="{{ $category->name }}"
                                 loading="lazy">
                            <div class="card-body p-2">
                                <h6 class="card-title mb-1">{{ $category->name }}</h6>
                                <a href="{{ route('allproducts', ['category' => $category->id]) }}"
                                   class="btn btn-outline-primary btn-sm w-100">Explore</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Custom Arrow Buttons -->
            <div class="d-flex justify-content-center mt-4 gap-3">
                <button class="btn btn-outline-primary btn-sm rounded-circle shadow swiper-category-prev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-outline-primary btn-sm rounded-circle shadow swiper-category-next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
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

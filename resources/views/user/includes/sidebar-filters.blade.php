<!-- Categories -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Categories</h5>
        <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#categoryCollapse">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
    <div id="categoryCollapse" class="collapse show">
        <div class="card-body scrollable-list">
        @if($brandMessage)
            <div class="alert alert-warning py-1 px-2 small mb-2">
                {{ $brandMessage }}
            </div>
        @endif

            @foreach ($categories as $category)
                <div class="form-check">
                    <input class="form-check-input filter-checkbox" type="checkbox" name="category[]" value="{{ $category->id }}"
                        {{ is_array(request('category')) && in_array($category->id, request('category')) ? 'checked' : '' }}>
                    <label class="form-check-label">{{ $category->name }}</label>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Brands -->
<div class="card mb-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Brands</h5>
        <button class="btn btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#brandCollapse">
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <div id="brandCollapse" class="collapse show">
        <div class="card-body scrollable-list" id="brandFilters">
            {{-- Brand checkboxes will be loaded here --}}
            @include('user.includes.partial-brand-filter', ['brands' => $brands, 'brandMessage' => $brandMessage ?? null])
        </div>
    </div>
</div>

<!-- Price Range -->
<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title">Price Range</h5>
        <div class="mb-2">
            <label for="min_price" class="form-label">Min Price</label>
            <input type="number" name="min_price" class="form-control filter-input" value="{{ request('min_price', 0) }}">
        </div>
        <div class="mb-3">
            <label for="max_price" class="form-label">Max Price</label>
            <input type="number" name="max_price" class="form-control filter-input" value="{{ request('max_price', 10000) }}">
        </div>
        <button class="btn btn-outline-primary w-100" type="button" id="applyPriceFilter">Apply</button>
    </div>
</div>

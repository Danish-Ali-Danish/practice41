
<!-- Features Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">
            @foreach($features as $feature)
                <div class="col-md-3 col-6 mb-4">
                    <i class="{{ $feature['icon'] }} fa-2x text-primary mb-2"></i>
                    <h6 class="fw-bold">{{ $feature['title'] }}</h6>
                    <p class="text-muted small">{{ $feature['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

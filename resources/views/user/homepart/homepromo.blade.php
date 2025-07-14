<div class="row">
    @foreach($promos as $promo)
    <div class="col-md-4 mb-3">
        <div class="card">
            <img src="{{ asset('storage/' . $promo->image) }}" class="card-img-top" alt="{{ $promo->title }}" loading="lazy">
            <div class="card-body">
                <h5 class="card-title">{{ $promo->title }}</h5>
                @if($promo->button_text && $promo->button_link)
                    <a href="{{ $promo->button_link }}" class="btn btn-outline-primary">{{ $promo->button_text }}</a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>


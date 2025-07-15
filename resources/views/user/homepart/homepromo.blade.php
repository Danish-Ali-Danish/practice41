<div class="card text-white border-0 shadow-sm position-relative overflow-hidden">
    <img src="{{ asset('storage/' . $promo->image) }}" 
         style="height: 200px; filter: brightness(0.65);" 
         alt="{{ $promo->title }}" 
         loading="lazy">

    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center p-3">
        <h5 class="fw-bold text-white text-center">{{ $promo->title }}</h5>

        @if($promo->button_text && $promo->button_link)
            <a href="{{ $promo->button_link }}" 
               class="btn btn-sm text-white border-white mt-2 px-3"
               style="background-color: rgba(255, 255, 255, 0.2); backdrop-filter: blur(2px);">
                {{ $promo->button_text }}
            </a>
        @endif
    </div>
</div>

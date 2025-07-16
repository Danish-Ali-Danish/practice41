
@if($brands->count())
    @php
        $heading = $brandMessage ? 'Popular Brands' : '';
    @endphp
    <h6 class="fw-bold mb-2">{{ $heading }}</h6>
    @foreach($brands as $brand)
        <div class="form-check">
            <input class="form-check-input filter-checkbox" type="checkbox" name="brand[]" value="{{ $brand->id }}" id="brand{{ $brand->id }}">
            <label class="form-check-label" for="brand{{ $brand->id }}">{{ $brand->name }}</label>
        </div>
    @endforeach
@else
    <p class="text-muted">No brands available.</p>
@endif

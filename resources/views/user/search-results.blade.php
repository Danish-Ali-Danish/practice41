@extends('user.layouts.master')
@section('title', 'Search Results')

@section('content')
<div class="container py-5">
    <h2>Results for "{{ $query }}"</h2>

    <h5 class="mt-4">Products</h5>
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h6>{{ $product->name }}</h6>
                        <p class="text-muted">${{ $product->price }}</p>
                        <a href="{{ route('product.details', $product->slug) }}" class="btn btn-sm btn-primary">View</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
    </div>

    <h5 class="mt-4">Categories</h5>
    <ul>
        @forelse($categories as $category)
            <li><a href="/products?category={{ $category->id }}">{{ $category->name }}</a></li>
        @empty
            <li>No matching categories.</li>
        @endforelse
    </ul>

    <h5 class="mt-4">Brands</h5>
    <ul>
        @forelse($brands as $brand)
            <li><a href="/products?brand={{ $brand->id }}">{{ $brand->name }}</a></li>
        @empty
            <li>No matching brands.</li>
        @endforelse
    </ul>
</div>
@endsection

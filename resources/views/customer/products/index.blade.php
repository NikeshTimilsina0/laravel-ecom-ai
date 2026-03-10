@extends('layouts.customer')

@section('title', 'Products')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Products</h1>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <form method="GET" action="{{ route('products.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">
                    <i class="bi bi-image" style="font-size: 3rem;"></i>
                </div>
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text text-muted small">{{ $product->category->name }}</p>
                    <p class="card-text">
                        <strong class="text-primary">${{ number_format($product->price, 2) }}</strong>
                        @if($product->stock < 1)
                        <span class="badge bg-danger">Out of Stock</span>
                        @elseif($product->stock < 5)
                        <span class="badge bg-warning">Only {{ $product->stock }} left</span>
                        @endif
                    </p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @auth
                            @if($product->stock > 0)
                            <form method="POST" action="{{ route('cart.add', $product->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Add to Cart</button>
                            </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Login to Buy</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">No products found.</div>
        </div>
        @endforelse
    </div>

    {{ $products->links() }}
</div>
@endsection

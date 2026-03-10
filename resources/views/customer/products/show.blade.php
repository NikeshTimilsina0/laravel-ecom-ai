@extends('layouts.customer')

@section('title', $product->name)

@section('content')
<div class="container">
    <a href="{{ route('products.index') }}" class="btn btn-link mb-3">&larr; Back to Products</a>

    <div class="row">
        <div class="col-md-5">
            @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            @else
            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                <i class="bi bi-image" style="font-size: 5rem;"></i>
            </div>
            @endif
        </div>
        <div class="col-md-7">
            <h2>{{ $product->name }}</h2>
            <p class="text-muted">
                Category: <a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a> | 
                Vendor: {{ $product->vendor->name }}
            </p>
            <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
            
            @if($product->stock < 1)
                <div class="alert alert-danger">This product is out of stock.</div>
            @elseif($product->stock < 5)
                <div class="alert alert-warning">Only {{ $product->stock }} items left in stock!</div>
            @else
                <p class="text-success"><i class="bi bi-check-circle"></i> In Stock ({{ $product->stock }} available)</p>
            @endif

            <div class="card mb-3">
                <div class="card-body">
                    <h5>Description</h5>
                    <p>{{ $product->description ?? 'No description available.' }}</p>
                </div>
            </div>

            @auth
                @if($product->stock > 0)
                <form method="POST" action="{{ route('cart.add', $product->id) }}" class="d-flex gap-2">
                    @csrf
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}" style="width: 80px;">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-cart-plus"></i> Add to Cart
                    </button>
                </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-cart-plus"></i> Login to Buy
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection

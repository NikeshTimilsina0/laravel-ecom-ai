@extends('layouts.customer')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h1 class="mb-4">Shopping Cart</h1>

    @if($cartItems->isEmpty())
    <div class="alert alert-info">
        Your cart is empty. <a href="{{ route('products.index') }}">Continue shopping!</a>
    </div>
    @else
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" style="width: 50px; height: 50px; object-fit: cover;" class="me-3">
                                        @else
                                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                            <i class="bi bi-image"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('products.show', $item->product->id) }}">{{ $item->product->name }}</a>
                                            <br><small class="text-muted">{{ $item->product->category->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->product->price, 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.update', $item->id) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" class="form-control form-control-sm" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" style="width: 60px;">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                    </form>
                                </td>
                                <td>${{ number_format($item->quantity * $item->product->price, 2) }}</td>
                                <td>
                                    <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total</strong>
                        <strong>${{ number_format($total, 2) }}</strong>
                    </div>
                    <form method="POST" action="{{ route('cart.checkout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 btn-lg">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

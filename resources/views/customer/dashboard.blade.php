@extends('layouts.customer')

@section('title', 'My Dashboard')

@section('content')
<div class="container">
    <h1 class="mb-4">Welcome, {{ auth()->user()->name }}!</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">My Orders</h5>
                    <h2>{{ $stats['totalOrders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Cart Items</h5>
                    <h2>{{ $stats['cartItems'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Browse Products</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-sm mt-2">Shop Now</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>My Recent Orders</h5>
            <a href="{{ route('customer.orders') }}" class="btn btn-sm btn-primary">View All</a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'secondary') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No orders yet. <a href="{{ route('products.index') }}">Start shopping!</a></td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.customer')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="container">
    <a href="{{ route('customer.orders') }}" class="btn btn-link mb-3">&larr; Back to Orders</a>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order #{{ $order->id }}</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Items</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Vendor</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->product->vendor->name }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Order Status</span>
                        <span class="badge bg-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'cancelled' ? 'danger' : 'warning') }}">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Payment Status</span>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'secondary') }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total</span>
                        <strong>${{ number_format($order->total_price, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Date</span>
                        <span>{{ $order->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

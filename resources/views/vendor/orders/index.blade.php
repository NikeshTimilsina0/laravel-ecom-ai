@extends('layouts.vendor')

@section('title', 'My Orders')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Orders</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                        <th>Order Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orderItems as $item)
                    <tr>
                        <td>#{{ $item->order_id }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->order->user->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                        <td>
                            <span class="badge bg-{{ $item->order->order_status == 'delivered' ? 'success' : ($item->order->order_status == 'cancelled' ? 'danger' : 'warning') }}">
                                {{ ucfirst($item->order->order_status) }}
                            </span>
                        </td>
                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orderItems->links() }}
        </div>
    </div>
</div>
@endsection

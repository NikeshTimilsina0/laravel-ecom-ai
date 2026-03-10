@extends('layouts.customer')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h1 class="mb-4">My Orders</h1>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->orderItems->count() }} item(s)</td>
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
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            No orders yet. <a href="{{ route('products.index') }}">Start shopping!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

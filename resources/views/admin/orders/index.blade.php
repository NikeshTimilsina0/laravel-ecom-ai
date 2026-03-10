@extends('layouts.admin')

@section('title', 'Order Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order Management</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Order Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select">
                        <option value="">All Payment Status</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Total Price</th>
                        <th>Order Status</th>
                        <th>Payment Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->user->name }}</td>
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
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $order->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $order->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Order Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Order Status</label>
                                            <select name="order_status" class="form-select">
                                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

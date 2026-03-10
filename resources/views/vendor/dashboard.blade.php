@extends('layouts.vendor')

@section('title', 'Vendor Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h2 mb-4">Vendor Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2>{{ $stats['totalProducts'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Active Products</h5>
                    <h2>{{ $stats['activeProducts'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending Products</h5>
                    <h2>{{ $stats['pendingProducts'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2>{{ $stats['totalOrders'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>My Products</h5>
                    <a href="{{ route('vendor.products.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'approved' ? 'success' : ($product->status == 'pending' ? 'warning' : 'danger') }}">
                                        {{ $product->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No products yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Recent Orders</h5>
                    <a href="{{ route('vendor.orders') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $item)
                            <tr>
                                <td>#{{ $item->order_id }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <span class="badge bg-{{ $item->order->order_status == 'delivered' ? 'success' : 'warning' }}">
                                        {{ $item->order->order_status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

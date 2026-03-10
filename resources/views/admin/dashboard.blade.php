@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h2 mb-4">Admin Dashboard</h1>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2>{{ $stats['totalUsers'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Vendors</h5>
                    <h2>{{ $stats['totalVendors'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <h2>{{ $stats['totalCustomers'] }}</h2>
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
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2>{{ $stats['totalProducts'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2>{{ $stats['totalOrders'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <h2>${{ number_format($stats['totalRevenue'], 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Orders</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>${{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->order_status == 'delivered' ? 'success' : 'warning' }}">
                                        {{ $order->order_status }}
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

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Products</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Vendor</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->vendor->name }}</td>
                                <td>${{ number_format($product->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $product->status == 'approved' ? 'success' : 'warning' }}">
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
    </div>
</div>
@endsection

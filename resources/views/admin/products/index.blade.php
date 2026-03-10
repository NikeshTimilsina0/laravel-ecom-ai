@extends('layouts.admin')

@section('title', 'Product Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Product Management</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                        <th>Image</th>
                        <th>Name</th>
                        <th>Vendor</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-image"></i>
                            </div>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->vendor->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <span class="badge bg-{{ $product->status == 'approved' ? 'success' : ($product->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @if($product->status == 'pending')
                                <form method="POST" action="{{ route('admin.products.approve', $product->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.reject', $product->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" title="Reject">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

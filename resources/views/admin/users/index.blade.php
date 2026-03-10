@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Management</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search users..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                        <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role->role_name == 'super_admin' ? 'danger' : ($user->role->role_name == 'vendor' ? 'warning' : 'info') }}">
                                {{ ucfirst($user->role->role_name) }}
                            </span>
                        </td>
                        <td>
                            @if(isset($user->is_approved))
                                <span class="badge bg-{{ $user->is_approved ? 'success' : 'secondary' }}">
                                    {{ $user->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $user->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($user->role->role_name == 'vendor' && !$user->is_approved)
                                <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Approve Vendor">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger" title="Reject Vendor">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @endif
                                @if($user->role->role_name != 'super_admin')
                                <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit User Role</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role_id" class="form-select">
                                                @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ ucfirst($role->role_name) }}
                                                </option>
                                                @endforeach
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
                        <td colspan="7" class="text-center">No users found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

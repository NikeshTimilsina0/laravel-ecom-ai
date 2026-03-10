@extends('auth.layout')

@section('title', 'Register')
@section('card-title', 'Register')

@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Register As</label>
        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">Select Role</option>
            <option value="customer" selected>Customer</option>
            <option value="vendor">Vendor</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Note: Vendor accounts require admin approval.</small>
    </div>

    <button type="submit" class="btn btn-primary w-100">Register</button>
</form>

<div class="text-center mt-3">
    <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
</div>
@endsection

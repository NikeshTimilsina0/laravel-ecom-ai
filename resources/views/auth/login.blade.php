@extends('auth.layout')

@section('title', 'Login')
@section('card-title', 'Login')

@section('content')
<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus>
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

    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" class="form-check-input" id="remember">
        <label class="form-check-label" for="remember">Remember Me</label>
    </div>

    <button type="submit" class="btn btn-primary w-100">Login</button>
</form>

<div class="text-center mt-3">
    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</div>

<div class="text-center mt-2">
    <small class="text-muted">
        Demo Login:<br>
        Admin: admin@example.com / password<br>
        Vendor: vendor1@example.com / password<br>
        Customer: customer@example.com / password
    </small>
</div>
@endsection

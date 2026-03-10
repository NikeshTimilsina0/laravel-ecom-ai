<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductApprovalController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VendorProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('products.index');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/products', [CustomerController::class, 'products'])->name('products.index');
Route::get('/products/{product}', [CustomerController::class, 'productDetail'])->name('products.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user()->load('role');
        
        if ($user->role->role_name === 'super_admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role->role_name === 'vendor') {
            return redirect()->route('vendor.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    })->name('dashboard');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{user}/approve', [UserManagementController::class, 'approveVendor'])->name('admin.users.approve');
    Route::post('/admin/users/{user}/reject', [UserManagementController::class, 'rejectVendor'])->name('admin.users.reject');

    Route::get('/admin/products', [ProductApprovalController::class, 'index'])->name('admin.products.index');
    Route::post('/admin/products/{product}/approve', [ProductApprovalController::class, 'approve'])->name('admin.products.approve');
    Route::post('/admin/products/{product}/reject', [ProductApprovalController::class, 'reject'])->name('admin.products.reject');
    Route::delete('/admin/products/{product}', [ProductApprovalController::class, 'destroy'])->name('admin.products.destroy');

    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/admin/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/admin/orders/{order}', [OrderController::class, 'update'])->name('admin.orders.update');
});

Route::middleware(['auth', 'vendor'])->group(function () {
    Route::get('/vendor/dashboard', [VendorProductController::class, 'dashboard'])->name('vendor.dashboard');

    Route::get('/vendor/products', [VendorProductController::class, 'index'])->name('vendor.products.index');
    Route::get('/vendor/products/create', [VendorProductController::class, 'create'])->name('vendor.products.create');
    Route::post('/vendor/products', [VendorProductController::class, 'store'])->name('vendor.products.store');
    Route::get('/vendor/products/{product}/edit', [VendorProductController::class, 'edit'])->name('vendor.products.edit');
    Route::put('/vendor/products/{product}', [VendorProductController::class, 'update'])->name('vendor.products.update');
    Route::delete('/vendor/products/{product}', [VendorProductController::class, 'destroy'])->name('vendor.products.destroy');

    Route::get('/vendor/orders', [VendorProductController::class, 'orders'])->name('vendor.orders');
});

Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])->name('customer.dashboard');

    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add/{product}', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{cartItem}', [CustomerController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{cartItem}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/checkout', [CustomerController::class, 'checkout'])->name('cart.checkout');

    Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/orders/{order}', [CustomerController::class, 'orderDetail'])->name('customer.orders.show');
});

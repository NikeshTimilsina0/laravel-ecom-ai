<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalVendors' => User::whereHas('role', function ($query) {
                $query->where('role_name', 'vendor');
            })->count(),
            'totalCustomers' => User::whereHas('role', function ($query) {
                $query->where('role_name', 'customer');
            })->count(),
            'totalProducts' => Product::count(),
            'pendingProducts' => Product::where('status', 'pending')->count(),
            'totalOrders' => Order::count(),
            'totalRevenue' => Order::where('payment_status', 'paid')->sum('total_price'),
        ];

        $recentOrders = Order::with('user')->latest()->take(5)->get();
        $recentProducts = Product::with(['vendor', 'category'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentProducts'));
    }
}

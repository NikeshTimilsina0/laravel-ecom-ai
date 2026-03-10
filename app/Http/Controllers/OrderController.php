<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($request->has('status') && $request->status !== '') {
            $query->where('order_status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status !== '') {
            $query->where('payment_status', $request->payment_status);
        }

        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($request->only('order_status'));

        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
    }
}

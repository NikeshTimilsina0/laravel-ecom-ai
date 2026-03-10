<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        
        $stats = [
            'totalOrders' => Order::where('user_id', $userId)->count(),
            'cartItems' => CartItem::whereHas('cart', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->sum('quantity'),
        ];

        $recentOrders = Order::where('user_id', $userId)->with('orderItems.product')->latest()->take(5)->get();

        return view('customer.dashboard', compact('stats', 'recentOrders'));
    }

    public function products(Request $request)
    {
        $query = Product::with(['vendor', 'category'])->where('status', 'approved');

        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function productDetail(Product $product)
    {
        if ($product->status !== 'approved') {
            abort(404);
        }
        
        $product->load(['vendor', 'category']);
        return view('customer.products.show', compact('product'));
    }

    public function cart()
    {
        $userId = Auth::id();
        
        $cart = Cart::with(['cartItems.product.category'])->where('user_id', $userId)->first();
        
        $cartItems = $cart ? $cart->cartItems : collect([]);
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function addToCart(Request $request, Product $product)
    {
        if ($product->status !== 'approved') {
            return back()->with('error', 'Product not available.');
        }

        if ($product->stock < 1) {
            return back()->with('error', 'Product out of stock.');
        }

        $userId = Auth::id();
        
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->input('quantity', 1);
            if ($newQuantity > $product->stock) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->input('quantity', 1),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function updateCart(Request $request, CartItem $cartItem)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $quantity = $request->input('quantity', 1);
        
        if ($quantity < 1) {
            $cartItem->delete();
        } else {
            if ($quantity > $cartItem->product->stock) {
                return back()->with('error', 'Not enough stock available.');
            }
            $cartItem->update(['quantity' => $quantity]);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function removeFromCart(CartItem $cartItem)
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cartItem->cart_id !== $cart->id) {
            abort(403);
        }

        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }

    public function orders()
    {
        $userId = Auth::id();
        $orders = Order::with('orderItems.product')->where('user_id', $userId)->latest()->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('orderItems.product');
        return view('customer.orders.show', compact('order'));
    }

    public function checkout(Request $request)
    {
        $userId = Auth::id();
        
        $cart = Cart::with('cartItems.product')->where('user_id', $userId)->first();
        
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        foreach ($cart->cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Not enough stock for ' . $item->product->name);
            }
        }

        $total = $cart->cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $total,
            'order_status' => 'pending',
            'payment_status' => 'pending',
        ]);

        foreach ($cart->cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'vendor_id' => $item->product->vendor_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);

            $item->product->decrement('stock', $item->quantity);
        }

        $cart->cartItems()->delete();

        return redirect()->route('customer.orders.show', $order->id)->with('success', 'Order placed successfully!');
    }
}

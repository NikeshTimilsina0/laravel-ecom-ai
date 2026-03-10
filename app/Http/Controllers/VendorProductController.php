<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VendorProductController extends Controller
{
    public function dashboard()
    {
        $vendor = auth()->user();
        
        $stats = [
            'totalProducts' => Product::where('vendor_id', $vendor->id)->count(),
            'activeProducts' => Product::where('vendor_id', $vendor->id)->where('status', 'approved')->count(),
            'pendingProducts' => Product::where('vendor_id', $vendor->id)->where('status', 'pending')->count(),
            'totalOrders' => OrderItem::where('vendor_id', $vendor->id)->count(),
        ];

        $recentProducts = Product::where('vendor_id', $vendor->id)->with('category')->latest()->take(5)->get();

        $recentOrders = OrderItem::with(['order.user', 'product'])
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->take(5)
            ->get();

        return view('vendor.dashboard', compact('stats', 'recentProducts', 'recentOrders'));
    }

    public function index(Request $request)
    {
        $vendorId = auth()->id();
        
        $query = Product::where('vendor_id', $vendorId)->with('category');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->latest()->paginate(10);

        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');
        $data['vendor_id'] = auth()->id();
        $data['status'] = 'pending';

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product created successfully. It will be reviewed by admin.');
    }

    public function edit(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this product.');
        }
        
        $categories = Category::all();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('vendor.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this product.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('vendor.products.index')->with('success', 'Product deleted successfully.');
    }

    public function orders()
    {
        $vendorId = auth()->id();
        
        $orderItems = OrderItem::with(['order.user', 'product'])
            ->where('vendor_id', $vendorId)
            ->latest()
            ->paginate(10);

        return view('vendor.orders.index', compact('orderItems'));
    }
}

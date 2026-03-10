<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['vendor', 'category']);

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

        return view('admin.products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['vendor', 'category']);
        return view('admin.products.show', compact('product'));
    }

    public function approve(Product $product)
    {
        $product->update(['status' => 'approved']);

        return redirect()->route('admin.products.index')->with('success', 'Product approved successfully.');
    }

    public function reject(Request $request, Product $product)
    {
        $request->validate([
            'reason' => 'nullable|string',
        ]);

        $product->update(['status' => 'rejected']);

        return redirect()->route('admin.products.index')->with('success', 'Product rejected.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}

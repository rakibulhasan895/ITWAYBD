<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('product.index', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $data['added_by'] = auth()->user()->id ?? null;

        $product = Product::create($data);

        $product->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully!',
            'product' => $product
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully!',
            'product' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'success' => true,
            'message' => 'Product moved to trash!',
        ]);
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->latest()->paginate(10);
        return view('product.trash', compact('products'));
    }

    public function restore($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return response()->json([
            'success' => true,
            'message' => 'Product restored successfully!',
            'product' => $product
        ]);
    }

    public function forceDelete($id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Product permanently deleted!',
        ]);
    }
}

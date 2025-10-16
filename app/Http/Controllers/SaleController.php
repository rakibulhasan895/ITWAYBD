<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SaleController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = Sale::with('user', 'items.product');
    //     if ($request->customer) {
    //         $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->customer}%"));
    //     }
    //     if ($request->product) {
    //         $query->whereHas('items.product', fn($q) => $q->where('name', 'like', "%{$request->product}%"));
    //     }
    //     if ($request->from && $request->to) {
    //         $query->whereBetween('sale_date', [$request->from, $request->to]);
    //     }
    //     $sales = $query->paginate(10);
    //     return view('sales.index', compact('sales'));
    // }
    public function index(Request $request)
    {
        // Unique cache key per filter + page
        $cacheKey = 'sales_'
            . ($request->customer ?? 'all') . '_'
            . ($request->product ?? 'all') . '_'
            . ($request->from ?? 'start') . '_'
            . ($request->to ?? 'end') . '_page_' . ($request->page ?? 1);

        $sales = Cache::tags('sales')->remember($cacheKey, now()->addMinutes(1), function () use ($request) {
            $query = Sale::with('user', 'items.product')->orderBy('created_at', 'desc');

            if ($request->customer) {
                $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->customer}%"));
            }

            if ($request->product) {
                $query->whereHas('items.product', fn($q) => $q->where('name', 'like', "%{$request->product}%"));
            }

            if ($request->from && $request->to) {
                $query->whereBetween('sale_date', [$request->from, $request->to]);
            }

            return $query->paginate(10);
        });

        return view('sales.index', compact('sales'));
    }
    public function create()
    {
        $customers = User::all();
        $products = Product::all();
        return view('sales.create', compact('customers', 'products'));
    }
    public function store(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
        ]);

        $sale = Sale::create([
            'user_id' => $validated['user_id'],
            'sale_date' => now(),
            'total_price' => array_reduce($validated['items'], fn($carry, $item) => $carry + ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0), 0),
        ]);

        foreach ($validated['items'] as $item) {
            $sale->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'discount' => $item['discount'] ?? 0,
                'subtotal' => ($item['price'] * $item['quantity']) - ($item['discount'] ?? 0),
            ]);
        }

        if (!empty(request('notes'))) {
            $sale->notes()->create([
                'content' => request('notes'),
                'notable_id' => $sale->id,
                'notable_type' => Sale::class,
            ]);
        }
        Cache::tags('sales')->flush();

        // return redirect()->route('sales.index')->with('success', 'Sale created successfully.');

        return response()->json(['success' => true, 'message' => 'Sale created successfully']);
    }

    public function trash()
    {
        $trashedSales = Sale::onlyTrashed()->with('user', 'items.product')->paginate(10);
        return view('sales.trash', compact('trashedSales'));
    }

    public function restore($id)
    {
        $sale = Sale::onlyTrashed()->findOrFail($id);
        $sale->restore();
        return redirect()->route('sales.trash')->with('success', 'Sale restored successfully.');
    }

    public function forceDelete($id)
    {
        $sale = Sale::onlyTrashed()->findOrFail($id);
        $sale->forceDelete();
        return redirect()->route('sales.trash')->with('success', 'Sale permanently deleted.');
    }
}

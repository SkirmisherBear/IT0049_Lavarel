<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['product', 'customer', 'staff'])
            ->when(auth()->user()->role === 'staff', function ($query) {
                $query->where('sold_by', auth()->id());
            })
            ->latest()
            ->get();

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock_quantity', '>', 0)->orderBy('name')->get();
        $customers = Customer::orderBy('full_name')->get();

        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'nullable|exists:customers,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock_quantity < $validated['quantity']) {
            return back()
                ->withInput()
                ->with('error', "Insufficient stock. Only {$product->stock_quantity} left for {$product->name}.");
        }

        DB::transaction(function () use ($validated, $product, $request) {
            Sale::create([
                'product_id' => $product->id,
                'customer_id' => $validated['customer_id'] ?? null,
                'sold_by' => $request->user()->id,
                'quantity' => $validated['quantity'],
                'total_price' => $product->price * $validated['quantity'],
            ]);

            $product->decrement('stock_quantity', $validated['quantity']);
        });

        return redirect()->route('pos.dashboard')->with('success', 'Transaction completed and stock updated!');
    }
}

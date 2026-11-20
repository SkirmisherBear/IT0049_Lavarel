<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = Sale::with(['product', 'customer', 'staff'])->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get();
        $customers = Customer::orderBy('full_name')->get();
        return view('sales.create', compact('products', 'customers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $transactionCode = (string) Str::uuid();

        DB::transaction(function () use ($validated, $transactionCode, $request): void {
            $productIds = collect($validated['items'])
                ->pluck('product_id')
                ->all();

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                if ($product->stock_quantity < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => "Insufficient stock for {$product->name}. Only {$product->stock_quantity} left.",
                    ]);
                }
            }

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                Sale::create([
                    'transaction_code' => $transactionCode,
                    'product_id' => $product->id,
                    'customer_id' => $validated['customer_id'] ?? null,
                    'sold_by' => $request->user()->id,
                    'quantity' => $item['quantity'],
                    'total_price' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
            }
        });

        return redirect()->route('pos.dashboard')->with('success', 'Checkout completed and stock updated!');
    }
}

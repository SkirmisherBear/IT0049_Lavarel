<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private const CATEGORIES = [
        'training_session',
        'day_pass',
        'month_pass',
        'year_pass',
        'supplement',
        'drink',
        'merch',
    ];

    public function index(): View
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create', ['categories' => self::CATEGORIES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product added successfully.');
    }

    public function edit(Product $product): View
    {
        return view('products.edit', ['product' => $product, 'categories' => self::CATEGORIES]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->sales()->exists() || $product->clientOrderItems()->exists()) {
            return back()->with('error', 'Cannot delete a product with recorded sales or client orders.');
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }
}

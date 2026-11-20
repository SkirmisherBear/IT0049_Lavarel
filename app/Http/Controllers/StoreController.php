<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\ClientOrderItem;
use App\Models\Membership;
use App\Models\Product;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    private const CATEGORIES = [
        'training_session' => 'Training Sessions',
        'day_pass' => 'Day Passes',
        'month_pass' => '1 Month Passes',
        'year_pass' => '1 Year Passes',
        'supplement' => 'Supplements',
        'drink' => 'Drinks',
        'merch' => 'Merchandise',
    ];

    public function index(Request $request): View
    {
        $products = Product::query()
            ->where('stock_quantity', '>', 0)
            ->orderBy('category')
            ->orderBy('name')
            ->get();
        $orders = ClientOrder::whereBelongsTo($request->user())
            ->with('items')
            ->latest()
            ->limit(5)
            ->get();

        return view('client.store', [
            'products' => $products,
            'orders' => $orders,
            'categoryNames' => self::CATEGORIES,
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $user = $request->user();

        DB::transaction(function () use ($validated, $user): void {
            $products = Product::query()
                ->whereIn('id', collect($validated['items'])->pluck('product_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $total = 0;

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);

                if (! $product || $product->stock_quantity < $item['quantity']) {
                    $name = $product?->name ?? 'Selected product';
                    $stock = $product?->stock_quantity ?? 0;

                    throw ValidationException::withMessages([
                        'items' => "Not enough stock for {$name}. Only {$stock} available.",
                    ]);
                }

                $total += (float) $product->price * $item['quantity'];
            }

            $order = ClientOrder::create([
                'user_id' => $user->id,
                'transaction_code' => (string) Str::uuid(),
                'total' => $total,
                'status' => 'placed',
            ]);

            foreach ($validated['items'] as $item) {
                $product = $products->get($item['product_id']);
                $lineTotal = (float) $product->price * $item['quantity'];
                $orderItem = ClientOrderItem::create([
                    'client_order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_category' => $product->category,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'line_total' => $lineTotal,
                ]);

                $product->decrement('stock_quantity', $item['quantity']);
                $this->activateMembershipIfNeeded($user, $product, $orderItem);
            }
        });

        return redirect()->route('client.store')->with('success', 'Your order has been placed.');
    }

    private function activateMembershipIfNeeded(User $user, Product $product, ClientOrderItem $orderItem): void
    {
        $lastExpiry = Membership::whereBelongsTo($user)
            ->where('expires_at', '>', now())
            ->max('expires_at');
        $startsAt = $lastExpiry ? \Illuminate\Support\Carbon::parse($lastExpiry) : now();
        $expiresAt = match ($product->category) {
            'day_pass' => $startsAt->copy()->addDay(),
            'month_pass' => $startsAt->copy()->addMonth(),
            'year_pass' => $startsAt->copy()->addYear(),
            default => null,
        };

        if ($expiresAt === null) {
            return;
        }

        Membership::create([
            'user_id' => $user->id,
            'client_order_item_id' => $orderItem->id,
            'pass_name' => $product->name,
            'starts_at' => $startsAt,
            'expires_at' => $expiresAt,
        ]);
    }
}

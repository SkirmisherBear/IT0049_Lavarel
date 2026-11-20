@extends('layouts.client')
@section('title', 'Store')
@section('heading', 'Overclock Store')

@section('content')
<div class="client-page-heading">
    <div>
        <p class="eyebrow">GEAR UP</p>
        <h1>Overclock Store</h1>
        <p class="muted">Gym passes, training sessions, supplements, drinks, and merchandise.</p>
    </div>
</div>

<div class="store-layout">
    <div class="store-products">
        @forelse ($products->groupBy('category') as $category => $categoryProducts)
            <section class="store-category">
                <div class="card-heading-row">
                    <h2>{{ $categoryNames[$category] ?? str($category)->replace('_', ' ')->title() }}</h2>
                    <span class="muted">{{ $categoryProducts->count() }} items</span>
                </div>
                <div class="store-grid">
                    @foreach ($categoryProducts as $product)
                        <article class="client-card store-product"
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $product->price }}"
                                 data-stock="{{ $product->stock_quantity }}">
                            @if ($product->image)
                                <img class="store-product-image" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                            @else
                                <div class="store-product-placeholder">⚡</div>
                            @endif
                            <div class="card-eyebrow">{{ $categoryNames[$category] ?? str($category)->replace('_', ' ')->title() }}</div>
                            <h3>{{ $product->name }}</h3>
                            <div class="store-product-bottom">
                                <strong>₱{{ number_format((float) $product->price, 2) }}</strong>
                                <span>{{ $product->stock_quantity }} available</span>
                            </div>
                            <div class="store-add-row">
                                <input type="number" class="client-input store-quantity" min="1" max="{{ $product->stock_quantity }}" value="1" aria-label="Quantity for {{ $product->name }}">
                                <button type="button" class="client-button add-store-item">Add</button>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @empty
            <article class="client-card empty-card">
                <h2>Store is getting ready</h2>
                <p class="muted">There are no in-stock products available right now.</p>
            </article>
        @endforelse
    </div>

    <aside class="client-card store-cart-card">
        <div class="card-eyebrow">YOUR ORDER</div>
        <h2>Cart <span class="cart-count" id="storeCartCount">0</span></h2>
        <div id="storeCartItems" class="store-cart-items">
            <p class="muted" id="emptyStoreCart">Your cart is empty.</p>
        </div>
        <div id="storeCartInputs"></div>
        <div class="store-cart-total"><span>Total</span><strong id="storeCartTotal">₱0.00</strong></div>
        <form method="POST" action="{{ route('client.store.checkout') }}" id="storeCheckoutForm">
            @csrf
            <div id="storeCheckoutInputs"></div>
            <button class="client-button wide-button" id="storeCheckoutButton" type="submit" disabled>Place Order</button>
        </form>
        <p class="store-demo-note">Demo checkout records the order and activates any gym pass immediately. No payment gateway is connected.</p>
    </aside>
</div>

@if ($orders->isNotEmpty())
    <section class="client-card order-history">
        <div class="card-heading-row"><div><div class="card-eyebrow">YOUR RECENT PURCHASES</div><h2>Order history</h2></div></div>
        @foreach ($orders as $order)
            <div class="client-order-row">
                <div>
                    <strong>Order #{{ strtoupper(substr($order->transaction_code, 0, 8)) }}</strong>
                    <span>{{ $order->items->pluck('product_name')->join(', ') }}</span>
                </div>
                <div class="order-total">
                    <strong>₱{{ number_format((float) $order->total, 2) }}</strong>
                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        @endforeach
    </section>
@endif
@endsection

@push('scripts')
<script>
    const storeCart = new Map();
    const currency = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', minimumFractionDigits: 2 });
    const productCards = [...document.querySelectorAll('.store-product')];
    const productData = new Map(productCards.map((card) => [Number(card.dataset.id), {
        id: Number(card.dataset.id),
        name: card.dataset.name,
        price: Number(card.dataset.price),
        stock: Number(card.dataset.stock),
    }]));
    const savedStoreItems = @json(old('items', []));

    function renderStoreCart() {
        const list = document.getElementById('storeCartItems');
        const inputs = document.getElementById('storeCheckoutInputs');
        list.replaceChildren();
        inputs.replaceChildren();
        let total = 0;
        let count = 0;

        if (storeCart.size === 0) {
            const empty = document.createElement('p');
            empty.className = 'muted';
            empty.textContent = 'Your cart is empty.';
            list.append(empty);
        }

        [...storeCart.values()].forEach((item, index) => {
            total += item.price * item.quantity;
            count += item.quantity;
            const row = document.createElement('div');
            row.className = 'store-cart-row';
            const details = document.createElement('div');
            const name = document.createElement('strong');
            name.textContent = item.name;
            const meta = document.createElement('span');
            meta.textContent = `${item.quantity} × ${currency.format(item.price)}`;
            details.append(name, meta);
            const remove = document.createElement('button');
            remove.type = 'button';
            remove.className = 'cart-remove';
            remove.textContent = '×';
            remove.setAttribute('aria-label', `Remove ${item.name}`);
            remove.addEventListener('click', () => {
                storeCart.delete(item.id);
                renderStoreCart();
            });
            row.append(details, remove);
            list.append(row);

            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = `items[${index}][product_id]`;
            productInput.value = item.id;
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.value = item.quantity;
            inputs.append(productInput, quantityInput);
        });

        document.getElementById('storeCartTotal').textContent = currency.format(total);
        document.getElementById('storeCartCount').textContent = count;
        document.getElementById('storeCheckoutButton').disabled = storeCart.size === 0;
    }

    document.querySelectorAll('.add-store-item').forEach((button) => {
        button.addEventListener('click', () => {
            const card = button.closest('.store-product');
            const id = Number(card.dataset.id);
            const product = productData.get(id);
            const quantity = Number.parseInt(card.querySelector('.store-quantity').value, 10);
            const nextQuantity = quantity + (storeCart.get(id)?.quantity || 0);

            if (!Number.isInteger(quantity) || quantity < 1 || nextQuantity > product.stock) {
                window.alert(`Choose a quantity from 1 to ${product.stock - (storeCart.get(id)?.quantity || 0)}.`);
                return;
            }

            storeCart.set(id, { ...product, quantity: nextQuantity });
            renderStoreCart();
        });
    });

    savedStoreItems.forEach((savedItem) => {
        const product = productData.get(Number(savedItem.product_id));
        const quantity = Number.parseInt(savedItem.quantity, 10);
        if (product && quantity > 0) {
            storeCart.set(product.id, { ...product, quantity });
        }
    });

    renderStoreCart();
</script>
@endpush

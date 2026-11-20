@extends('layouts.pos')
@section('title', 'New Sale')
@section('heading', 'Process New Sale')

@section('content')
<div class="page-heading">
    <div>
        <h1>New Sale</h1>
        <p class="muted">Add multiple items to the cart and check them out together.</p>
    </div>
    <a href="{{ route('pos.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
</div>

<div class="card form-card sale-form-card">
    <form method="POST" action="{{ route('sales.store') }}" id="saleForm">
        @csrf

        @if ($errors->any())
            <div class="alert alert-error" role="alert">
                <strong>Please review the sale:</strong>
                <ul class="sale-errors">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

        <div class="form-group">
            <label for="productSelect">Select Product</label>
            <div class="sale-product-picker">
                <select id="productSelect" class="form-control">
                    <option value="">-- Choose Overclock Item --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->stock_quantity }}"
                                @disabled($product->stock_quantity < 1)>
                            {{ $product->name }} — ₱{{ number_format((float) $product->price, 2) }} ({{ $product->stock_quantity }} left)
                        </option>
                    @endforeach
                </select>
                <div class="sale-add-controls">
                    <input type="number" id="qtyInput" class="form-control quantity-input"
                           min="1" step="1" value="1" aria-label="Quantity to add">
                    <button type="button" class="btn" id="addToCart">Add to Cart</button>
                </div>
            </div>
            <div class="stock-hint" id="stockHint">Choose an item and quantity to add it to the cart.</div>
        </div>

        <div class="form-group">
            <label for="customerSelect">Customer (Optional)</label>
            <select name="customer_id" id="customerSelect" class="form-control">
                <option value="">Walk-in Customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="sale-cart-section">
            <div class="sale-cart-heading">
                <div>
                    <h2>Cart</h2>
                    <p class="muted">Review item quantities before checkout.</p>
                </div>
                <span class="cart-count" id="cartCount">0 items</span>
            </div>

            <div class="table-wrap">
                <table class="sale-cart-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th><span class="visually-hidden">Remove</span></th>
                        </tr>
                    </thead>
                    <tbody id="cartItems">
                        <tr id="emptyCartRow">
                            <td colspan="5" class="empty-state">Your cart is empty. Add a product to begin.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="cartHiddenInputs"></div>
        </div>

        <div class="checkout-summary">
            <div>
                <div class="total-label">Total Amount Due</div>
                <div class="stock-hint">All cart items will be checked out together.</div>
            </div>
            <strong class="live-total" id="liveTotal">₱0.00</strong>
        </div>

        <button type="submit" class="btn complete-transaction" id="checkoutButton" disabled>
            ⚡ Complete Checkout
        </button>
    </form>
</div>

<script>
    const productSelect = document.getElementById('productSelect');
    const qtyInput = document.getElementById('qtyInput');
    const stockHint = document.getElementById('stockHint');
    const cartItems = document.getElementById('cartItems');
    const cartHiddenInputs = document.getElementById('cartHiddenInputs');
    const liveTotal = document.getElementById('liveTotal');
    const cartCount = document.getElementById('cartCount');
    const checkoutButton = document.getElementById('checkoutButton');
    const cart = new Map();

    const savedItems = @json(old('items', []));
    const currency = new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
        minimumFractionDigits: 2,
    });

    function selectedProduct() {
        const option = productSelect.selectedOptions[0];

        if (!option || !option.value) {
            return null;
        }

        return {
            id: Number(option.value),
            name: option.dataset.name,
            price: Number(option.dataset.price),
            stock: Number(option.dataset.stock),
        };
    }

    function updateStockHint() {
        const product = selectedProduct();
        stockHint.textContent = product
            ? `${product.stock} units available in stock.`
            : 'Choose an item and quantity to add it to the cart.';
    }

    function renderCart() {
        cartItems.replaceChildren();
        cartHiddenInputs.replaceChildren();

        let total = 0;
        let itemCount = 0;

        if (cart.size === 0) {
            const emptyRow = document.createElement('tr');
            const emptyCell = document.createElement('td');
            emptyCell.colSpan = 5;
            emptyCell.className = 'empty-state';
            emptyCell.textContent = 'Your cart is empty. Add a product to begin.';
            emptyRow.append(emptyCell);
            cartItems.append(emptyRow);
        }

        [...cart.values()].forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            itemCount += item.quantity;

            const row = document.createElement('tr');
            const nameCell = document.createElement('td');
            const priceCell = document.createElement('td');
            const quantityCell = document.createElement('td');
            const subtotalCell = document.createElement('td');
            const removeCell = document.createElement('td');

            nameCell.textContent = item.name;
            priceCell.textContent = currency.format(item.price);
            subtotalCell.textContent = currency.format(subtotal);

            const quantityControls = document.createElement('div');
            quantityControls.className = 'cart-quantity-controls';

            const decreaseButton = document.createElement('button');
            decreaseButton.type = 'button';
            decreaseButton.className = 'qty-btn cart-qty-btn';
            decreaseButton.textContent = '−';
            decreaseButton.setAttribute('aria-label', `Decrease ${item.name} quantity`);
            decreaseButton.addEventListener('click', () => changeQuantity(item.id, -1));

            const quantityLabel = document.createElement('span');
            quantityLabel.className = 'cart-quantity';
            quantityLabel.textContent = item.quantity;

            const increaseButton = document.createElement('button');
            increaseButton.type = 'button';
            increaseButton.className = 'qty-btn cart-qty-btn';
            increaseButton.textContent = '+';
            increaseButton.setAttribute('aria-label', `Increase ${item.name} quantity`);
            increaseButton.disabled = item.quantity >= item.stock;
            increaseButton.addEventListener('click', () => changeQuantity(item.id, 1));

            quantityControls.append(decreaseButton, quantityLabel, increaseButton);
            quantityCell.append(quantityControls);

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.className = 'btn btn-secondary cart-remove-btn';
            removeButton.textContent = 'Remove';
            removeButton.addEventListener('click', () => {
                cart.delete(item.id);
                renderCart();
            });
            removeCell.append(removeButton);

            row.append(nameCell, priceCell, quantityCell, subtotalCell, removeCell);
            cartItems.append(row);

            const productInput = document.createElement('input');
            productInput.type = 'hidden';
            productInput.name = `items[${index}][product_id]`;
            productInput.value = item.id;

            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.value = item.quantity;

            cartHiddenInputs.append(productInput, quantityInput);
        });

        liveTotal.textContent = currency.format(total);
        cartCount.textContent = `${itemCount} ${itemCount === 1 ? 'item' : 'items'}`;
        checkoutButton.disabled = cart.size === 0;
    }

    function addToCart() {
        const product = selectedProduct();
        const quantity = Number.parseInt(qtyInput.value, 10);

        if (!product) {
            stockHint.textContent = 'Choose a product first.';
            return;
        }

        if (!Number.isInteger(quantity) || quantity < 1) {
            stockHint.textContent = 'Enter a quantity of at least 1.';
            qtyInput.focus();
            return;
        }

        const currentItem = cart.get(product.id);
        const nextQuantity = quantity + (currentItem?.quantity || 0);

        if (nextQuantity > product.stock) {
            stockHint.textContent = `Only ${product.stock} units of ${product.name} are available.`;
            return;
        }

        cart.set(product.id, { ...product, quantity: nextQuantity });
        qtyInput.value = 1;
        productSelect.value = '';
        updateStockHint();
        renderCart();
    }

    function changeQuantity(productId, change) {
        const item = cart.get(productId);
        const nextQuantity = item.quantity + change;

        if (nextQuantity < 1) {
            cart.delete(productId);
        } else if (nextQuantity <= item.stock) {
            item.quantity = nextQuantity;
            cart.set(productId, item);
        }

        renderCart();
    }

    productSelect.addEventListener('change', updateStockHint);
    document.getElementById('addToCart').addEventListener('click', addToCart);
    qtyInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            addToCart();
        }
    });

    savedItems.forEach((savedItem) => {
        const option = [...productSelect.options].find((item) => item.value === String(savedItem.product_id));

        if (!option) {
            return;
        }

        const product = {
            id: Number(option.value),
            name: option.dataset.name,
            price: Number(option.dataset.price),
            stock: Number(option.dataset.stock),
        };
        const quantity = Number.parseInt(savedItem.quantity, 10);

        if (quantity > 0) {
            cart.set(product.id, { ...product, quantity });
        }
    });

    updateStockHint();
    renderCart();
</script>
@endsection

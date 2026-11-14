@extends('layouts.pos')
@section('title', 'New Sale')
@section('heading', 'Process New Sale')

@section('content')
<div class="page-heading">
    <div>
        <h1>New Sale</h1>
        <p class="muted">Select a gym pass, drink, or gear item to process checkout.</p>
    </div>
    <a href="{{ route('pos.dashboard') }}" class="btn btn-secondary">← Dashboard</a>
</div>

<div class="card form-card">
    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        <div class="form-group">
            <label>Select Product</label>
            <select name="product_id" id="productSelect" class="form-control" required>
                <option value="" data-price="0" data-stock="0">-- Choose Overclock Item --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}"
                            data-price="{{ $product->price }}"
                            data-stock="{{ $product->stock_quantity }}"
                            {{ old('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} — ₱{{ number_format((float)$product->price, 2) }} ({{ $product->stock_quantity }} left)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label>Customer (Optional)</label>
                <select name="customer_id" class="form-control">
                    <option value="">Walk-in Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Quantity</label>
                <div class="qty-stepper">
                    <button type="button" class="qty-btn" id="qtyMinus">−</button>
                    <input type="number" name="quantity" id="qtyInput" class="form-control" style="text-align:center;font-weight:700;" min="1" value="{{ old('quantity', 1) }}" required>
                    <button type="button" class="qty-btn" id="qtyPlus">+</button>
                </div>
            </div>
        </div>

        <div class="checkout-summary">
            <div>
                <div style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:700;">Total Amount Due</div>
                <div style="font-size:12px;color:var(--cyan);margin-top:2px;" id="stockHint">Select an item to calculate</div>
            </div>
            <strong style="font-size:28px;color:var(--text);" id="liveTotal">₱0.00</strong>
        </div>

        <button type="submit" class="btn" style="width:100%;padding:14px;font-size:14px;">⚡ Complete Transaction</button>
    </form>
</div>

<script>
    const productSelect = document.getElementById('productSelect');
    const qtyInput = document.getElementById('qtyInput');
    const liveTotal = document.getElementById('liveTotal');
    const stockHint = document.getElementById('stockHint');

    function updateTotal() {
        const opt = productSelect.selectedOptions[0];
        const price = parseFloat(opt?.dataset.price || 0);
        const stock = parseInt(opt?.dataset.stock || 0, 10);
        const qty = Math.max(1, parseInt(qtyInput.value || 1, 10));

        liveTotal.textContent = '₱' + (price * qty).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        stockHint.textContent = price > 0 ? `${stock} units available in stock` : 'Select an item to calculate';
    }

    document.getElementById('qtyMinus').addEventListener('click', () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value || 1, 10) - 1);
        updateTotal();
    });

    document.getElementById('qtyPlus').addEventListener('click', () => {
        qtyInput.value = parseInt(qtyInput.value || 1, 10) + 1;
        updateTotal();
    });

    productSelect.addEventListener('change', updateTotal);
    qtyInput.addEventListener('input', updateTotal);
    updateTotal();
</script>
@endsection
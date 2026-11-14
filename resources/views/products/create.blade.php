@extends('layouts.pos')
@section('title', 'Add Product')
@section('heading', 'Add New Product')

@section('content')
<div class="page-heading">
    <div>
        <h1>Add Product</h1>
        <p class="muted">Create a new item in your Overclock inventory.</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">← Back</a>
</div>

<div class="card form-card">
    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label>Price (₱)</label>
            <input type="number" step="0.01" min="0" name="price" class="form-control" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label>Stock Quantity</label>
            <input type="number" min="0" name="stock_quantity" class="form-control" value="{{ old('stock_quantity', 0) }}" required>
        </div>

        <div class="form-group">
            <label>Product Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn">Save Product</button>
    </form>
</div>
@endsection